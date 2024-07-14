<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use App\Http\Resources\UserResource;
use App\Rules\AlphaSpace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'max:255', new AlphaSpace],
            'email' => ['required', 'max:255', 'email', Rule::unique('users', 'email')->ignore(auth()->id())],
        ]);

        $user = AuthHelper::user();
        $user->fill($validated);
        $user->save();

        return response()->json(
            [
                'user' => new UserResource($user)
            ],
            Response::HTTP_OK
        );
    }
}
