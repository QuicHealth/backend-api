<?php

namespace App\Http\Resources;

use App\Models\Timeslot;
use Illuminate\Http\Resources\Json\JsonResource;


class ScheduleResource extends JsonResource
{

    public function slot($schedule_id)
    {
        $timeslot = Timeslot::where('schedule_id', $schedule_id)->get();
        return $timeslot;
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            // 'day_id' => $this->day_id,
            'date' => $this->date,
            'doctor' => $this->whenLoaded('doctor'),
            'timeslot' => $this->slot($this->id),
        ];
    }
}