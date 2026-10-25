<?php
namespace App\Http\Controllers;
use App\Models\{Category, ContactMessage, Product, Subscriber};

class PageController extends Controller {
    public function home() {
        return view('index', [
            'featured' => Product::with('category')->where('type', 'book')->where('stock', '>', 0)->inRandomOrder()->first(),
            'categories' => Category::withCount('products')->orderBy('type')->orderBy('name')->get(),
            'books' => Product::with('category')->where('type', 'book')->latest()->limit(4)->get(),
            'accessories' => Product::with('category')->where('type', 'accessory')->latest()->limit(4)->get(),
        ]);
    }
    public function about() { return view('about'); }
    public function contact() { return view('contact'); }

    public function contactSend() {
        ContactMessage::create(request()->validate([
            'name' => 'required|max:120', 'email' => 'required|email',
            'subject' => 'required|max:150', 'message' => 'required|max:3000',
        ]));
        return back()->with('status', 'Thanks! Your message has been sent. We will reply soon.');
    }
    public function subscribe() {
        $d = request()->validate(['email' => 'required|email']);
        Subscriber::firstOrCreate($d);
        return back()->with('status', 'You are subscribed to our newsletter.');
    }
}
