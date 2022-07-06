<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\Timeslot;
use Lorisleiva\Actions\Concerns\AsAction;

class SetAvailablityAction
{
    use AsAction;

    public function handle($validated)
    {
        $setSchedule = Schedule::updateOrCreate(
            ['doctor_id' => $validated['doctor_id']],
            ['date' =>  $validated['date']]
        );

        if ($setSchedule) {
            return  $this->saveTimeslots($validated, $setSchedule);
        }

        return response([
            'status' => false,
            'message' => "Can not set schedules",
        ], 402);
    }

    public function saveTimeslots($validated,  Schedule $schedule)
    {

        for ($i = 0; $i < count($validated['time_slots']); $i++) {
            Timeslot::updateOrCreate(
                ['schedule_id' => $schedule->id, 'start' => $validated['time_slots'][$i]['start'], 'end' => $validated['time_slots'][$i]['end']],
                ['selected' =>  $validated['time_slots'][$i]['selected'], 'status' =>  $validated['time_slots'][$i]['status']]
            );
        }

        return response([
            'status' => true,
            'message' => 'Schedule saved successfully'
        ]);
    }
}
