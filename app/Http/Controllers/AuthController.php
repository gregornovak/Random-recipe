<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    protected const USER_DOES_NOT_EXIST = -1;
    protected const USER_TOKEN_EXPIRED = -2;
    protected const USER_TOKEN_INVALID = -3;
    protected const USER_NO_TOKEN_PROVIDED = -4;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'nickname' => 'required|min:3',
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);
        try {
            if (! $token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json(['error' => ['text' => 'This user does not exist', 'err_num' => self::USER_DOES_NOT_EXIST]], 401);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => ['text' => 'Token has expired', 'err_num' => self::USER_TOKEN_EXPIRED]], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => ['text' => 'Token is invalid', 'err_num' => self::USER_TOKEN_INVALID]], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => ['text' => $e->getMessage(), 'err_num' => self::USER_NO_TOKEN_PROVIDED]], 500);

        }

        return response()->json(compact('token'));
    }

    public function register(Request $request) 
    {

    }
}