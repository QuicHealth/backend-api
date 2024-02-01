<?php

namespace App\Classes;

use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Support\Carbon;
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

    public static function saveNotification($authUserId, $user_type, $category, $message, $title, $userEmail = '')
    {
        // create notification for the user

        $notification = new Notification();
        $notification->user_id = $authUserId;
        $notification->user_type = $user_type; // eg. patient, or doctor or hospital
        $notification->category = $category;
        $notification->title = $title;
        $notification->message = $message;
        $notification->read_reciept = false;
        $newNotification =  $notification->save();


        // send email
        // $payment->notify(new PaymentSuccessfulNotification($payment));


        // send sms
        if ($newNotification) {
            return [
                'status' => "success",
                'message' => 'Notification sent successfully'

            ];
        }

        return [
            'status' => "error",
            'message' => 'Notification not sent'

        ];
    }

    public static function updateAppointment($created_at)
    {
        $appointmentCreatedTime = Carbon::parse($created_at);

        $currentTime = Carbon::now();
    }

    public static function createOtp($length)
    {
        $digits = '';
        $numbers = range(0, 9);
        shuffle($numbers);
        for ($i = 0; $i < $length; $i++) {
            $digits;
            $digits .= $numbers[$i];
        }
        return $digits;
    }

    public static function CurrencyFormatter($number)
    {
        $abbrevs = array(12 => "T", 9 => "B", 6 => "M", 3 => "K", 0 => "");
        foreach ($abbrevs as $exponent => $abbrev) {
            if ($number >= pow(10, $exponent)) {
                $display_num = $number / pow(10, $exponent);
                $decimals = ($exponent >= 3 && round($display_num) < 100) ? 1 : 0;
                return  env('CURRENCY_NAIRA') . number_format($display_num, $decimals) . $abbrev;
            }
        }
    }

    public static function filter_user_identity($identity)
    {
        return filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
    }

    public static function formatPhoneNumber($phoneNumber)
    {
        if (Str::startsWith($phoneNumber, '0')) {
            return Str::replaceFirst('0', '234', $phoneNumber);
        }
        return $phoneNumber;
    }
}
