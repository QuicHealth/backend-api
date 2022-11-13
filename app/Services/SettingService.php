<?php

namespace App\Services;

use App\Classes\Helpers;
use Termwind\Components\Dd;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;

class SettingService
{
    protected $userId;
    protected $image;
    protected $model;
    protected $settingsDB;

    /**
     * @param Model $model
     */
    public function __construct(Model $model, $id)
    {
        $this->model = $model;
        $this->userId = $id;
    }


    public function settings(): SettingService
    {
        $this->settingsDB =  $this->model->where('id', $this->userId)->first();

        // dd($this->settingsDB);

        return $this;
    }

    public function get(): array
    {
        return [
            "image" =>  $this->settingsDB->profile_pic_link,
            "email" =>  $this->settingsDB->email,
            "phone" =>  $this->settingsDB->phone,
            "address" =>  $this->settingsDB->address,
            "dob" =>  $this->settingsDB->dob,
            "city" =>  $this->settingsDB->city,
            "gender" =>  $this->settingsDB->gender,
            "emergency_number" =>  $this->settingsDB->emergency_number,
        ];
    }

    protected function uploadImageTocloudinary($file, $folder)
    {
        $transformation = [
            'gravity' => 'auto',
            'width' => 500,
            'height' => 500,
            'crop' => 'fill'
        ];

        $this->image = Helpers::UploadImage($file, $folder, $transformation);

        return $this->image;
    }

    public function uploadImage($imageFile, $folder)
    {
        $allowedImage = ['png', 'jpg', 'jpeg'];

        $extension =  $imageFile->getClientOriginalExtension();

        $check = in_array($extension, $allowedImage);

        if (!$check) {

            return response()->json([
                'status' => false,
                'message' => 'Invalid file type, only png, jpg and jpeg files are allowed',
            ]);
        }

        $image = $imageFile->getRealPath();

        dd($image);

        $uploadImage = $this->uploadImageTocloudinary($image, $folder);

        $this->settingsDB->profile_pic_link = $uploadImage;

        if ($this->settingsDB->save()) {
            return response([
                'status' => true,
                'message' => 'Image updated successfully'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Error uploading image, pls try again'
        ]);
    }

    /**
     * @param $data
     * @return Application|Response|ResponseFactory
     */
    public function saveUpdate($data, $folder = "")
    {
        // $updateData = [
        //     "email" => $data['email'] ?? $this->settingsDB->email,
        //     "phone" =>  $data['phone'] ?? $this->settingsDB->phone,
        //     "address" =>  $data['address'] ?? $this->settingsDB->address,
        //     "dob" =>  $data['dob'] ?? $this->settingsDB->dob,
        //     "city" =>  $data['city'] ?? $this->settingsDB->city,
        //     "gender" =>  $data['gender'] ?? $this->settingsDB->gender,
        //     "emergency_number" =>  $data['emergency_number'] ?? $this->settingsDB->emergency_number,
        // ];

        $this->settingsDB->email = $data['email'] ?? '';
        $this->settingsDB->phone = $data['phone'] ?? '';
        $this->settingsDB->address = $data['address'] ?? '';
        $this->settingsDB->dob = $data['dob'] ?? '';
        $this->settingsDB->city = $data['city'] ?? '';
        $this->settingsDB->gender = $data['gender'] ?? '';
        $this->settingsDB->emergency_number = $data['emergency_number'] ?? '';

        if (isset($data['image'])) {
            $uploadImage = $this->uploadImageTocloudinary($data['image'], $folder);
            // $updateData['profile_pic_link'] = $uploadImage;
            $this->settingsDB->profile_pic_link = $uploadImage;
        }


        // $update = $this->settingsDB->update($updateData);

        if ($this->settingsDB->save()) {
            return response([
                'status' => true,
                'message' => 'Settings updated successfully'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Error updating Settings'
        ]);
    }

    public function saveUpdatePassword($data)
    {

        if (!Hash::check($data['old_password'], $data['password'])) {
            return response([
                'status' => false,
                'message' => 'Old password is incorrect'
            ]);
        }

        $updateData = [
            "password" => Hash::make($data['password']),
        ];

        $update = $this->settingsDB->update($updateData);

        if ($update) {
            return response([
                'status' => true,
                'message' => 'Password updated successfully'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Error updating password'
        ]);
    }
}
