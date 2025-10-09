<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Admin giriş formunu gösterir.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        // Debug - view'ın varlığını kontrol et
        $viewPath = 'admin.auth.login';

        if (!view()->exists($viewPath)) {
            dd([
                'error' => 'View bulunamadı',
                'aranan_view' => $viewPath,
                'views_path' => resource_path('views'),
                'dosya_var_mi' => file_exists(resource_path('views/admin/auth/login.blade.php'))
            ]);
        }

        return view($viewPath);
    }

    /**
     * Giriş işlemini gerçekleştirir.
     */
    public function login(Request $request)
    {
        // Validasyon
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email adresi gereklidir.',
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'password.required' => 'Şifre gereklidir.',
        ]);

        // Giriş denemesi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Başarıyla giriş yaptınız!');
        }

        // Hatalı giriş
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email adresi veya şifre hatalı!');
    }

    /**
     * Çıkış işlemi
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Başarıyla çıkış yaptınız.');
    }
}
