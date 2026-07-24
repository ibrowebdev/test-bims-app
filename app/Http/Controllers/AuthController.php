<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Redirect the user to the BIMS OAuth authorization page.
     */
    public function redirect()
    {
        $config = Config::get('services.bims');

        $clientId = $config['client_id'];
        $redirectUri = $config['redirect_uri'] ?: route('auth.callback');
        $bimsHost = rtrim($config['host'], '/');

        $url = "{$bimsHost}/oauth/authorize?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&state=dkdk&prompt=consent";

        return redirect($url);
    }

    /**
     * Handle the OAuth callback from BIMS.
     */
    public function callback(Request $request)
    {
        $code = $request->query('code');

        if (! $code) {
            return redirect(route('home'))->with('error', 'Authorization code was not provided.');
        }

        $config = Config::get('services.bims');
        $bimsHost = rtrim($config['host'], '/');
        $redirectUri = $config['redirect_uri'] ?: route('auth.callback');


        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post("{$bimsHost}/oauth/token", [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'redirect_uri' => $redirectUri,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ]);

        if ($response->failed()) {
            Log::error('BIMS OAuth token exchange failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return redirect(route('home'))->with('error', 'Failed to authenticate with BIMS service.');
        }

        $responseData = $response->json();

        return $this->loginUser($responseData);
    }

    /**
     * Authenticate or register user from BIMS payload and log them in.
     */
    protected function loginUser(array $responseData)
    {
        $userPayload = $responseData['user'] ?? $responseData;

        if (empty($userPayload['email'])) {
            Log::error('BIMS OAuth user payload missing email', ['payload' => $responseData]);
            return redirect(route('home'))->with('error', 'Invalid user details received from BIMS.');
        }

        $firstName = $userPayload['first_name'] ?? '';
        $lastName = $userPayload['last_name'] ?? '';
        $name = trim("{$firstName} {$lastName}") ?: ($userPayload['name'] ?? 'BIMS User');

        $user = User::updateOrCreate(
            [
                'email' => $userPayload['email'],
            ],
            [
                'name' => $name,
                'password' => bcrypt('password'),
            ]
        );

        Auth::login($user);

        return redirect()->intended(route('dashboard'));
    }
}
