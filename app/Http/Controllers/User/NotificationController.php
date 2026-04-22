<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        return view('profile.notifications', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        if ($request->has('ids') && is_array($request->ids)) {
            Auth::user()->notifications()->whereIn('id', $request->ids)->get()->each->markAsRead();
            return back()->with('success', 'Selected notifications marked as read.');
        } 
        
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy($id)
    {
        Auth::user()->notifications()->where('id', $id)->first()->delete();
        return back()->with('success', 'Notification deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        Auth::user()->notifications()->whereIn('id', $request->ids)->delete();
        return back()->with('success', 'Selected notifications deleted.');
    }
    
    public function clearAll()
    {
        Auth::user()->notifications()->delete();
        return back()->with('success', 'All notifications cleared.');
    }

    public function check()
    {
        $unread = Auth::user()->unreadNotifications()->take(5)->get()->map(function($n) {
            return [
                'id' => $n->id,
                'title' => $n->data['title'] ?? 'Notification',
                'message' => $n->data['message'] ?? '',
                'type' => $n->data['type'] ?? 'info',
                'created_at' => $n->created_at->diffForHumans()
            ];
        });

        return response()->json(['unread' => $unread]);
    }
}
