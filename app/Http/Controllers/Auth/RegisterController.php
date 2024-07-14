<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Rules\AlphaSpace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'max:255', new AlphaSpace],
            'email' => ['required', 'max:255', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:6', 'max:20']
        ]);

        $user = new User([
            ...$validated,
            'ulid' => Str::ulid()->toString(),
            'password' => bcrypt($validated['password'])
        ]);

        $user->save();

        auth()->login($user);

        $request->session()->regenerate();

        return response()->json(
            [
                "user" => new UserResource($user),
            ],
            Response::HTTP_CREATED
        );
    }
}
