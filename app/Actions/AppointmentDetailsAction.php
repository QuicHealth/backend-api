<?php

namespace App\Actions;

use App\Models\Details;
use App\Models\Appointment;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\AppointmentDetailsRequest;

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
                    'length' => $data['length'],
                    'treatments' => $data['treatments'],
                    'others' => $data['others']
                ]
            );

            return $details;
        }
    }
}
