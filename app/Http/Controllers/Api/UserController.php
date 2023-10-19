<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ImageTransformation;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserUpdateRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use function imageavif;

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
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getProfile()
    {
        try {
            $user = auth()->user();

            return response()->json(['success' => true, 'image' => $user->avatar, 'email' => $user->email]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
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
                // Destroy the old image if it exists
                if ($user->avatar) {
                    if (file_exists(storage_path('app/public/img/profile/'.$user->avatar))) {
                        unlink(storage_path('app/public/img/profile/'.$user->avatar));
                    }
                }

                $filename = Str::slug(Str::lower($user->name));
                // Définition du nom de l'image
                $user->avatar = $filename.'.avif';
                // Propriétés de l'image
                $imgProperties = getimagesize($request->image->path());
                // Selon le type de l'image
                switch ($request->image->extension()) {
                    case 'jpg':
                        $gdImage = imagecreatefromjpeg($request->image->path());
                        $resizeBigImg = ImageTransformation::image_resize_big($gdImage, $imgProperties[0], $imgProperties[1]);
                        imageavif($resizeBigImg, storage_path('app/public/img/profile/'.$user->avatar));
                        // Création d'une miniature
                        break;
                    case 'jpeg':
                        $gdImage = imagecreatefromjpeg($request->image->path());
                        $resizeBigImg = ImageTransformation::image_resize_big($gdImage, $imgProperties[0], $imgProperties[1]);
                        imageavif($resizeBigImg, storage_path('app/public/img/profile'.$user->avatar));
                        break;
                    case 'png':
                        $gdImage = imagecreatefrompng($request->image->path());
                        $resizeBigImg = ImageTransformation::image_resize_big($gdImage, $imgProperties[0], $imgProperties[1]);
                        imageavif($resizeBigImg, storage_path('app/public/img/profile'.$user->avatar));
                        break;
                    case 'avif':
                        $gdImage = imagecreatefromavif($request->image->path());
                        $resizeBigImg = ImageTransformation::image_resize_big($gdImage, imagesx($gdImage), imagesy($gdImage));
                        imageavif($resizeBigImg, storage_path('app/public/img/profile'.$user->avatar));
                        break;
                    default:
                        $gdImage = imagecreatefromjpeg($request->image->path());
                        $resizeBigImg = ImageTransformation::image_resize_big($gdImage, $imgProperties[0], $imgProperties[1]);
                        imageavif($resizeBigImg, storage_path('app/public/img/profile'.$user->avatar));
                        break;
                }
                imagedestroy($gdImage);
                imagedestroy($resizeBigImg);

            }

            $user->save();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
