<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthService
{
    public function __construct(protected AuthRepository $authRepository)
    {

    }

    public function authRegister(string $email, string $password) : User
    {
        $hashedPassword = Hash::make($password);
        $user = User::make(
            $email,
            $hashedPassword,
        );

        $this->authRepository->createUser($user);
        return $user;
    }

    /**
     * @throws ConnectionException
     */
    public function authLogin(string $email, string $password) : array
    {
        if (! (Auth::attempt(['email' => $email, 'password' => $password])))
        {
            return [];
        }

        // request lay access_token va refresh_token
        $response = Http::asForm()->post(url('oauth/token'), [
            'grant_type' => 'password',
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'username' => $email,
            'password' => $password,
            'scope' => '',
        ]);

        $authResponse = $response->json();
        if (!empty($authResponse)) {
            $authUser = Auth::user();
            return [
                'email' => $authUser->email,
                'access_token' => $authResponse['access_token'],
                'refresh_token' => $authResponse['refresh_token'],
                'token_type' => $authResponse['token_type'],
                'expires_in' => $authResponse['expires_in']
            ];
        }

        return [];
    }

    public function refreshToken($request): array
    {
        $response = Http::asForm()->post(url('oauth/token'), [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
            'scope' => '',
        ]);

        $authResponse = $response->json();

        if (!empty($authResponse)) {
            return [
                'token_type' => $authResponse['token_type'],
                'expires_in' => $authResponse['expires_in'],
                'token' => $authResponse['access_token'],
                'refresh_token' => $authResponse['refresh_token'],
            ];
        }

        return [];
    }

}
