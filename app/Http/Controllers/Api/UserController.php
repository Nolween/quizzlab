<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserUpdateRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{


    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Ajout du middleware nécessaire selon les actions
        $this->middleware('auth:sanctum');
    }

    /**
     * @param UserUpdateRequest $request
     * @return JsonResponse
     */

    public function updateUserProfile(UserUpdateRequest $request)
    {
        try {
            $user = auth()->user();

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            // Upload de l'image


            if ($request->has('image')) {
                $user->avatar = $request->image;
            }

            $user->save();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
