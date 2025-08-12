<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTFactory;

/**
 * @method \Illuminate\Routing\Controller middleware($middleware, array $options = [])
 */
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $refreshToken = $this->createRefreshToken();
        return $this->respondWithToken($token, $refreshToken);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->input('refresh_token');

        $payload = JWTAuth::setToken($refreshToken)->getPayload();

        $user = User::query()->findOrFail($payload->get('sub'));

        auth('api')->invalidate();

        $token = JWTAuth::fromUser($user);

        $refreshToken = $this->createRefreshToken();

        return $this->respondWithToken($token, $refreshToken);
    }

    public function profile()
    {
        return response()->json(auth('api')->user());
    }

    private function respondWithToken($token, $refreshToken)
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60
        ]);
    }

    private function createRefreshToken()
    {
        $data = [
            'sub' => auth('api')->user()->id,
            'random' => rand() . time(),
            'exp' => time() + (config('jwt.refresh_ttl') * 60),
        ];

        $payload = JWTFactory::class::customClaims($data)->make();
        return JWTAuth::manager()->encode($payload);
    }
}
