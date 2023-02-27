<?php

namespace App\Http\Controllers\Admin\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Wrong credentials'], 401);
        }

        return $this->respondWithToken($token)->withCookie(cookie('isAdmin', '1', 3600 * 100));
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    // TODO: могут быть ошибки
    protected function respondWithToken($token)
    {
        $userModel = auth()->user();

        return response()->json(
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'lang_id' => $userModel->lang_id,
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        );
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        $userModel = auth()->user();

        $user = $userModel->toArray();

        $user['roles'] = $userModel->getRoleNames();
        $user['role'] = (isset($userModel->getRoleNames()[0])) ? $userModel->getRoleNames()[0] : null;

        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
