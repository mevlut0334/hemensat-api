<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminSubscriptionViewController extends Controller
{
    /**
     * Kullanıcı listesi sayfası
     */
    public function index(Request $request)
    {
        $query = User::query()->orderBy('created_at', 'desc');

        // Email filtresi
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Abonelik durumu filtresi
        if ($request->filled('subscription_status')) {
            $query->where('is_subscribed', $request->subscription_status);
        }

        // Sadece gerekli kolonları çek (tarih kolonları yok)
        $users = $query->select([
            'id',
            'name',
            'email',
            'is_subscribed',
            'created_at'
        ])->paginate(15);

        return view('admin.subscriptions.index', compact('users'));
    }

    /**
     * Abonelik durumunu değiştir (AJAX)
     */
    public function toggle(Request $request, User $user)
    {
        $request->validate([
            'is_subscribed' => 'required|boolean'
        ]);

        $user->is_subscribed = $request->is_subscribed;
        $user->save();

        $status = $request->is_subscribed ? 'Abone' : 'Standart Kullanıcı';

        return response()->json([
            'success' => true,
            'message' => "Kullanıcı başarıyla {$status} yapıldı!"
        ]);
    }
}
