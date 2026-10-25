<?php
namespace App\Http\Controllers;
use App\Models\{Order, Product};
use App\Support\Cart;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller {
    public function show() {
        if (Cart::count() === 0) return redirect()->route('cart.index');
        return view('checkout', ['lines' => Cart::lines(), 'total' => Cart::total()]);
    }

    public function store() {
        $data = request()->validate([
            'name' => 'required|max:120', 'email' => 'required|email', 'phone' => 'required|max:30',
            'address' => 'required|max:500', 'payment' => 'required|in:cod,card',
        ]);
        if ($data['payment'] === 'card' && ! config('shop.stripe_secret')) {
            return back()->withInput()->withErrors(['payment' => 'Card payment is not set up yet. Please choose cash on delivery.']);
        }
        $lines = Cart::lines();
        if ($lines->isEmpty()) return redirect()->route('cart.index');

        $order = DB::transaction(function () use ($data, $lines) {
            $order = Order::create([
                'user_id' => auth()->id(), 'name' => $data['name'], 'email' => $data['email'],
                'phone' => $data['phone'], 'address' => $data['address'], 'total' => $lines->sum('total'),
                'payment_method' => $data['payment'],
                'status' => $data['payment'] === 'card' ? 'awaiting_payment' : 'pending',
            ]);
            foreach ($lines as $l) {
                $p = Product::lockForUpdate()->find($l->product->id);
                abort_if($p->stock < $l->qty, 422, "Not enough stock for {$p->title}.");
                $p->decrement('stock', $l->qty);
                $order->items()->create(['product_id' => $p->id, 'quantity' => $l->qty, 'price' => $p->price]);
            }
            return $order;
        });
        session(['last_order' => $order->id]);

        if ($data['payment'] === 'card') {
            \Stripe\Stripe::setApiKey(config('shop.stripe_secret'));
            $session = \Stripe\Checkout\Session::create([
                'mode' => 'payment',
                'customer_email' => $order->email,
                'line_items' => $order->items()->with('product')->get()->map(fn ($i) => [
                    'quantity' => $i->quantity,
                    'price_data' => ['currency' => config('shop.stripe_currency'), 'unit_amount' => $i->price,
                                     'product_data' => ['name' => $i->product->title]],
                ])->all(),
                'success_url' => route('checkout.paid', $order) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel', $order),
            ]);
            $order->update(['stripe_session' => $session->id]);
            return redirect()->away($session->url);
        }
        session()->forget('cart');
        return redirect()->route('checkout.done', $order);
    }

    public function paid(Order $order) {
        abort_unless(session('last_order') == $order->id && $order->stripe_session, 403);
        if ($order->payment_status !== 'paid') {
            \Stripe\Stripe::setApiKey(config('shop.stripe_secret'));
            $s = \Stripe\Checkout\Session::retrieve($order->stripe_session);
            if ($s->payment_status === 'paid') $order->update(['payment_status' => 'paid', 'status' => 'confirmed']);
        }
        if ($order->payment_status === 'paid') {
            session()->forget('cart');
            return redirect()->route('checkout.done', $order);
        }
        return redirect()->route('cart.index')->with('status', 'Payment was not completed.');
    }

    public function cancel(Order $order) {
        abort_unless(session('last_order') == $order->id && $order->status === 'awaiting_payment', 403);
        DB::transaction(function () use ($order) {
            foreach ($order->items as $i) Product::whereKey($i->product_id)->increment('stock', $i->quantity);
            $order->update(['status' => 'cancelled']);
        });
        return redirect()->route('cart.index')->with('status', 'Payment cancelled. Your cart is still here.');
    }

    public function done(Order $order) {
        abort_unless(session('last_order') == $order->id || (auth()->check() && auth()->id() == $order->user_id), 403);
        return view('order-done', compact('order'));
    }
}
