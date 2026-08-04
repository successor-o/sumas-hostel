<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $query = ContactMessage::query();

        if ($request->get('status') === 'unread') {
            $query->where('is_read', false);
        } elseif ($request->get('status') === 'read') {
            $query->where('is_read', true);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('matric_number', 'like', "%{$search}%");
            });
        }

        $messages = $query->latest()->paginate(10)->withQueryString();

        return view('admin.messages', [
            'messages' => $messages,
            'unreadCount' => ContactMessage::where('is_read', false)->count(),
            'totalCount' => ContactMessage::count(),
        ]);
    }

    public function markRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => true]);

        return back()->with('status', 'Message marked as read.');
    }

    public function markUnread(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => false]);

        return back()->with('status', 'Message marked as unread.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return back()->with('status', 'Message deleted.');
    }
}
