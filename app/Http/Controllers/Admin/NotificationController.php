<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = NotificationLog::orderBy('created_at', 'desc');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('event', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('recipient', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20)->appends($request->query());
        return view('admin.notifications.index', compact('logs'));
    }

    public function clearLogs()
    {
        NotificationLog::truncate();
        return back()->with('success', 'Notification logs purged successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        NotificationLog::whereIn('id', $request->ids)->delete();
        return back()->with('success', count($request->ids) . ' notifications deleted successfully.');
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['success' => true]);
    }

    public function check()
    {
        $unread = auth()->user()->unreadNotifications()->take(10)->get()->map(function($n) {
            return [
                'id' => $n->id,
                'title' => $n->data['title'] ?? 'Notification',
                'message' => $n->data['message'] ?? '',
                'type' => $n->data['type'] ?? 'info',
                'link' => $n->data['link'] ?? '#',
                'time' => $n->created_at->diffForHumans()
            ];
        });

        return response()->json(['unread' => $unread]);
    }
}
