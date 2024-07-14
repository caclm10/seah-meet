<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AccountPasswordController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $user = AuthHelper::user();
        $validated = $request->validate([
            'old_password' => [
                'required',
                function (string $attribute, mixed $value, \Closure $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('auth.password')->translate();
                    }
                }
            ],
            'new_password' => ['required', 'min:6', 'max:20'],
        ]);

        $user->password = bcrypt($validated['new_password']);
        $user->save();

        return response()->json([], Response::HTTP_OK);
    }
}
