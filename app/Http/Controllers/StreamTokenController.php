<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Date;

class StreamTokenController extends Controller
{
    public function index(): JsonResponse
    {
        $user = AuthHelper::user();

        $secretKey = config('stream.secret_key');

        $now = Date::now();

        $token = JWT::encode([
            'server' => true,
            'exp' => $now->addHour()->timestamp,
            // 'iat' => $now->timestamp,
            'user_id' => $user->ulid
        ], $secretKey, 'HS256');


        return response()->json([
            'token' => $token
        ]);
    }
}
