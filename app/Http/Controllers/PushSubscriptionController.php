<?php
// app/Http/Controllers/PushSubscriptionController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'nullable|string',
            'keys.auth'   => 'nullable|string',
            'contentEncoding' => 'nullable|string',
        ]);

        $request->user()->updatePushSubscription(
            $request->input('endpoint'),
            $request->input('keys.p256dh'),
            $request->input('keys.auth'),
            $request->input('contentEncoding')
        );

        return response()->noContent();
    }

    public function destroy(Request $request)
    {
        $request->validate(['endpoint' => 'required|string']);
        $request->user()->deletePushSubscription($request->input('endpoint'));
        return response()->noContent();
    }
}
