<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (auth()->attempt($validated)) {
            $request->session()->regenerate();

            return response()->json(
                [
                    'user' => new UserResource(auth()->user())
                ],
                Response::HTTP_OK
            );
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed')
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([], Response::HTTP_OK);
    }
}
