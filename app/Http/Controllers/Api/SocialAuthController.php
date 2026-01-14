<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends BaseController
{
    /**
     * Redirect to social provider
     */
    public function redirectToProvider($provider)
    {
        $this->validateProvider($provider);

        try {
            // For stateless API, return the URL to frontend
            $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
            
            // But for browser redirect, do actual redirect
            if (request()->expectsJson()) {
                return $this->sendResponse(['url' => $url], 'OAuth authorization URL generated successfully');
            }
            
            // Direct browser redirect
            return redirect($url);
        } catch (\Exception $e) {
            return $this->sendError('OAuth redirect failed', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle callback from social provider
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            
            // Визначаємо поле ID в залежності від провайдера
            $providerIdField = $provider . '_id';
            
            // Шукаємо користувача за email
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                // Створюємо нового користувача
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(),
                    $providerIdField => $socialUser->getId(),
                ]);
            } else {
                // Оновлюємо social provider ID якщо не встановлено
                if (!$user->{$providerIdField}) {
                    $user->update([
                        $providerIdField => $socialUser->getId(),
                    ]);
                }
            }

            // Створюємо токен
            $token = $user->createToken('auth_token')->plainTextToken;

            // Перенаправляємо на frontend з токеном
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
            
            return Redirect::to("{$frontendUrl}/auth/callback/{$provider}?token={$token}");
            
        } catch (\Exception $e) {
            Log::error("OAuth {$provider} callback error: " . $e->getMessage());
            
            // Перенаправляємо на frontend з помилкою
            $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
            return Redirect::to("{$frontendUrl}/auth/callback/{$provider}?error=" . urlencode($e->getMessage()));
        }
    }

    /**
     * Validate social provider
     */
    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['google', 'github'])) {
            abort(404, 'Provider not supported');
        }
    }
}
