<?php

namespace App\Actions;

use App\Models\Schedule;
use App\Models\Timeslot;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTimeslotStatus
{
    use AsAction;

    public function handle($doctor_id, $day_id, $time_slots)
    {
        $schedule = $this->getScheduleID($doctor_id, $day_id);

        Timeslot::where('schedule_id', $schedule->id)
            ->where('start', $time_slots['start'])
            ->where('end', $time_slots['end'])
            ->update(['selected' => true]);
    }

    public function getScheduleID($doctor_id, $date)
    {
        $getSchedule = Schedule::where('doctor_id', $doctor_id)
            ->where('date', $date)
            ->first();

        if ($getSchedule) {

            return $getSchedule;
        }
    }
}