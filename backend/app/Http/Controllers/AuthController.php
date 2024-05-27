<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    private function validateCorrectRegisterUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:255',
        ]);

        return $validator;
    }

    private function validateCorrectLoginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);

        return $validator;
    }

    public function register(Request $request)
    {
        $validator = $this->validateCorrectRegisterUser($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = $this->userService->createUser($request);

        $token = $this->userService->createUserToken($user);

        $policies = $this->userService->getPolicies($user);

        return response()->json(['access_token' => $token->plainTextToken, 'token_type' => 'bearer', 'policies' => $policies, 'expires_in' => $token->accessToken->expires_at]);
    }

    public function login(Request $request)
    {
        $validator = $this->validateCorrectLoginUser($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        if (! $this->userService->isCredentialsCorrect($request)) {
            return response()->json(['errors' => 'Bad credentials'], 401);
        }

        $user = $this->userService->findUserByEmail($request->email);

        $token = $this->userService->createUserToken($user);

        $policies = $this->userService->getPolicies($user);

        return response()->json(['access_token' => $token->plainTextToken, 'token_type' => 'bearer', 'policies' => $policies, 'expires_in' => $token->accessToken->expires_at]);
    }

    public function verify()
    {
        return response()->json(['message' => 'Successful verification']);
    }

    public function logout(Request $request)
    {
        $user = $this->userService->findUserByEmail(auth()->user()->email);

        $tokenId = explode('|', $request->bearerToken())[0];

        $this->userService->deleteUserTokenById($user, $tokenId);

        return response()->json(['message' => 'Successful logout']);
    }

    public function logoutall()
    {
        $user = $this->userService->findUserByEmail(auth()->user()->email);

        $this->userService->deleteAllUserTokens($user);

        return response()->json(['message' => 'Successful logout']);
    }
}
