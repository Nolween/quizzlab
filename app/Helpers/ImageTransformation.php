<?php

namespace App\Helpers;

class ImageTransformation
{
    /**
     * Redimensionnement au format fiche d'une image
     */
    public static function image_resize_big($source, int $width, int $height)
    {
        $new_width = $width > $height ? 1080 : 1080 * ($width / $height);
        $new_height = $height > $width ? 1080 : 1080 * ($height / $width);
        $thumbImg = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($thumbImg, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        return $thumbImg;
    }

    /**
     * Redimensionnement au format vignette d'une image
     */
    public static function image_resize_small($source, int $width, int $height)
    {
        $new_width = $width > $height ? 240 : 240 * ($width / $height);
        $new_height = $height > $width ? 240 : 240 * ($height / $width);
        $thumbImg = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($thumbImg, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        return $thumbImg;
    }
}
