<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @method \Illuminate\Routing\Controller middleware($middleware, array $options = [])
 */
class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {

    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $result = $this->authService->authLogin($data['email'], $data['password']);

        return $this->successResponse($result);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $result = $this->authService->authRegister($data['email'], $data['password']);
        return $this->successResponse($result);
    }

    public function refreshToken(Request $request)
    {
        $this->authService->refreshToken($request);
    }
}
