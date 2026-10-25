<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{ContactMessage, Subscriber};

class InboxController extends Controller {
    public function index() {
        return view('admin.inbox', ['messages' => ContactMessage::latest()->limit(50)->get(), 'subscribers' => Subscriber::latest()->get()]);
    }
    public function destroy(ContactMessage $message) { $message->delete(); return back()->with('status', 'Message deleted.'); }
}
