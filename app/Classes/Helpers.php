<?php

namespace App\Classes;

use Illuminate\Database\Eloquent\Model;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class Helpers
{
    /**
     * This method uploads an image to cloudary
     * CloudinaryLabs\\CloudinaryLaravel\\CloudinaryEngine
     * @param $image_name
     * @param string $folder
     * @param array $transformation
     * @return  CloudinaryEngine

     */
    public static function uploadImageToCloudinary($image_name, string $folder = "", array $transformation = []): CloudinaryEngine
    {
        return Cloudinary::upload($image_name, [
            'folder' => $folder,
            'public_id' => uniqid('QUH_'),
            'transformation' => $transformation
        ]);
    }


    /**
     * This method upload single image to cloudinary
     * this method makes use of the cloudinary upload method
     *
     * @param $image
     * @param string $folder
     * @param array $transformation
     * @return string
     */
    public static function UploadImage($image, string $folder = "", array $transformation = [])
    {
        $image = self::uploadImageToCloudinary($image, $folder, $transformation);

        return $image->getSecurePath();
    }

    public static function getField(Model $model, $id, $field)
    {
        return $model->find($id)->$field;
    }
}