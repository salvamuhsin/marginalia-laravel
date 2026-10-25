<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Order, Product};

class OrderController extends Controller {
    public const STATUSES = ['pending', 'awaiting_payment', 'confirmed', 'shipped', 'delivered', 'cancelled'];

    public function index() {
        $orders = Order::when(request('status'), fn ($q, $s) => $q->where('status', $s))->latest()->paginate(15)->withQueryString();
        return view('admin.orders.index', ['orders' => $orders, 'statuses' => self::STATUSES]);
    }
    public function show(Order $order) {
        return view('admin.orders.show', ['order' => $order->load('items.product'), 'statuses' => self::STATUSES]);
    }
    public function update(Order $order) {
        $d = request()->validate(['status' => 'required|in:' . implode(',', self::STATUSES), 'payment_status' => 'required|in:unpaid,paid']);
        if ($order->status === 'cancelled') return back()->with('status', 'Cancelled orders cannot be changed.');
        if ($d['status'] === 'cancelled') {
            foreach ($order->items as $i) Product::whereKey($i->product_id)->increment('stock', $i->quantity);
        }
        $order->update($d);
        return back()->with('status', 'Order updated.');
    }
}
