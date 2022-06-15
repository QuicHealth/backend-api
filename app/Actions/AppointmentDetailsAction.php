<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Http\Requests\AppointmentDetailsRequest;
use App\Models\Details;

class AppointmentDetailsAction
{
    use AsAction;

    public function handle(AppointmentDetailsRequest $request, $appointment_id)
    {
        $validated = $request->validated();

        $data = [
            'purpose' =>  $validated['purpose'],
            'length' =>  $validated['length'],
            'treatments' =>  $validated['treatments'],
            'others' =>  $validated['others']
        ];

        $details = Details::updateOrCreate(
            ['appointment_id' => $appointment_id],
            [$data]
        );

        return $details;
    }

    // public function addDetails($request, $appointment_id)
    // {
    //     $validated = $request->validated();

    //     $data = [
    //         'purpose' =>  $validated['purpose'],
    //         'length' =>  $validated['length'],
    //         'treatments' =>  $validated['treatments'],
    //         'others' =>  $validated['others']
    //     ];

    //     $details = Details::updateOrCreate(
    //         ['appointment_id' => $appointment_id],
    //         [$data]
    //     );

    //     return $details;
    // }
}
