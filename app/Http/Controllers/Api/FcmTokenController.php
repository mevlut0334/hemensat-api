<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserFcmToken;
use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fcm_token'   => 'required|string',
            'device_type' => 'in:android,ios',
        ]);

        UserFcmToken::updateOrCreate(
            [
                'user_id'   => auth()->id(),
                'fcm_token' => $request->fcm_token,
            ],
            [
                'device_type' => $request->device_type ?? 'android',
            ]
        );

        return response()->json(['message' => 'Token kaydedildi.']);
    }
}
