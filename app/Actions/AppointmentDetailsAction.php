<?php

namespace App\Actions;

use App\Models\Details;
use App\Models\Appointment;
use Lorisleiva\Actions\Concerns\AsAction;

class AppointmentDetailsAction
{
    use AsAction;

    public function handle($data)
    {
        $appointment = Appointment::find($data['appointment_id']);

        if ($appointment) {

            $details = Details::updateOrCreate(
                [
                    'appointment_id' => $data['appointment_id']
                ],

                [
                    'purpose' => $data['purpose'],
                    'symptoms' =>  $data['symptoms'],
                    'allergies' => $data['allergies'],
                    'medications' => $data['medications'],
                    'others' => $data['others']
                ]
            );

            return $details;
        }
    }
}
