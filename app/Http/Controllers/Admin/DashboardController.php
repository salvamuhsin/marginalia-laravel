<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Order, Product, User};

class DashboardController extends Controller {
    public function __invoke() {
        return view('admin.dashboard', [
            'stats' => [
                'Products' => Product::count(),
                'Orders' => Order::count(),
                'Pending orders' => Order::whereIn('status', ['pending', 'awaiting_payment'])->count(),
                'Customers' => User::where('is_admin', false)->count(),
            ],
            'revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'recent' => Order::latest()->limit(5)->get(),
            'low' => Product::where('stock', '<=', 5)->orderBy('stock')->limit(8)->get(),
        ]);
    }
}
