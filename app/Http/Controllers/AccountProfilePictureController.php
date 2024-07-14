<?php

namespace App\Http\Controllers;

use App\Helpers\AuthHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class AccountProfilePictureController extends Controller
{
    public function update(Request $request): JsonResponse
    {
        $user = AuthHelper::user();

        $request->validate([
            'image' => ['file', 'image', 'max:2048']
        ]);

        $image = $request->file('image');

        $path = $image->store('images', 'public');

        $oldPath = $user->picture_path;

        $user->picture_path = $path;
        $user->save();

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return response()->json(
            [
                'picture_url' => asset(Storage::url($path))
            ],
            Response::HTTP_OK
        );
    }

    public function destroy(): JsonResponse
    {
        $user = AuthHelper::user();

        if ($user->picture_path) {
            $path = $user->picture_path;

            $user->picture_path = NULL;
            $user->save();

            Storage::disk('public')->delete($path);
        }

        return response()->json([], Response::HTTP_OK);
    }
}
