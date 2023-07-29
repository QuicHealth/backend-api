<?php

namespace App\Actions;

use App\Models\Schedule;
use App\Models\Timeslot;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTimeslotStatus
{
    use AsAction;

    public function handle($doctor_id, $day, $time_slots, $selected = true)
    {

        $schedule = $this->getScheduleID($doctor_id, $day);

        Timeslot::where('schedule_id', $schedule->id)
            ->where('start', $time_slots['start'])
            ->where('end', $time_slots['end'])
            ->update(['selected' => $selected]);

        // return $time;
    }

    public function getScheduleID($doctor_id, $day)
    {

        $getSchedule = Schedule::where('doctor_id', $doctor_id)
            ->where('day', $day)
            ->first();

        if ($getSchedule) {

            return $getSchedule;
        }
    }
}
