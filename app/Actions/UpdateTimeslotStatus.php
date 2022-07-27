<?php

namespace App\Actions;

use App\Models\Schedule;
use App\Models\Timeslot;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTimeslotStatus
{
    use AsAction;

    public function handle($doctor_id, $date, $time_slots)
    {

        $schedule = $this->getScheduleID($doctor_id, $date);

        Timeslot::where('schedule_id', $schedule->id)
            ->where('start', $time_slots['start'])
            ->where('end', $time_slots['end'])
            ->update(['selected' => true]);

        // return $time;
    }

    public function getScheduleID($doctor_id, $date)
    {
        dd($doctor_id, $date);
        $getSchedule = Schedule::where('doctor_id', $doctor_id)
            ->where('date', $date)
            ->first();

        if ($getSchedule) {

            return $getSchedule;
        }
    }
}