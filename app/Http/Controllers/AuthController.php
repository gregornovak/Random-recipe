<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Lumen\Exceptions;
use Tymon\JWTAuth\{JWTAuth, Exceptions\TokenExpiredException, Exceptions\TokenInvalidException, Exceptions\JWTException};

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

    /**
     * Authenticate the user.
     * 
     * @return User
     */
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);
        try {
            if (! $token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json(['error' => ['text' => 'This user does not exist', 'err_num' => self::USER_DOES_NOT_EXIST]], 401);
            }

        } catch (TokenExpiredException $e) {
            return response()->json(['error' => ['text' => 'Token has expired', 'err_num' => self::USER_TOKEN_EXPIRED]], 500);

        } catch (TokenInvalidException $e) {
            return response()->json(['error' => ['text' => 'Token is invalid', 'err_num' => self::USER_TOKEN_INVALID]], 500);

        } catch (JWTException $e) {
            return response()->json(['error' => ['text' => $e->getMessage(), 'err_num' => self::USER_NO_TOKEN_PROVIDED]], 500);

        }

        $user = $this->jwt->user();

        return response()->json(compact('token', 'user'));
    }

    /**
     * Register a new user.
     * 
     * @return json
     */
    public function register(Request $request) 
    {
        $this->validate($request, [
            'nickname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        try {
            $user = User::create([
                'nickname' => $request->nickname,
                'email' => $request->email,
                'password' => app('hash')->make($request->password)
            ]);

        } catch(Exception $e) {
            return response()->json(['error' => 'User could not be created'], 500);
        }
        
        return response()->json(['success' => 'User has been successfully created'], 200);
    }

    public function protected() 
    {
        return ['sjeos' => 'Success'];
    }
}