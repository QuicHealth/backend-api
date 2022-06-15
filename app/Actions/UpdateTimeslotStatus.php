<?php

namespace App\Actions;

use App\Models\Schedule;
use App\Models\Timeslot;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTimeslotStatus
{
    use AsAction;

    public function handle($doctor_unique_id, $day_id, $time_slots)
    {
        $schedule = $this->getScheduleID($doctor_unique_id, $day_id);

        Timeslot::where('schedule_id', $schedule->id)
            ->where('start', $time_slots['start'])
            ->where('end', $time_slots['end'])
            ->update(['selected' => true]);
    }

    public function getScheduleID($doctor_unique_id, $day_id)
    {
        $getSchedule = Schedule::where('doctor_unique_id', $doctor_unique_id)
            ->where('day_id', $day_id)
            ->first();

        if ($getSchedule) {

            return $getSchedule;
        }
    }
}