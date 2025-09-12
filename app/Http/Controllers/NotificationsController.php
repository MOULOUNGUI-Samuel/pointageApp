<?php

// app/Http/Controllers/NotificationsController.php
namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->latest()->paginate(15);
        $unreadCount = $user->unreadNotifications()->count();

        if(session()->has('module_id')){

        }else{
            $modules = Module::orderBy('created_at', 'asc')->first();
            session()->put('module_id', $modules->id);
        }
        return view('notifications.index', compact('notifications','unreadCount'));
    }

    public function markAsRead(Request $request, string $id)
    {
        $n = $request->user()->notifications()->whereKey($id)->firstOrFail();
        if ($n->read_at === null) $n->markAsRead();
        return back();
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return back();
    }
}
