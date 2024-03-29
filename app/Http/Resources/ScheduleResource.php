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
            'id' => $this->id,
            'day' => $this->day,
            'recursion' => $this->recursion,
            'doctor_id' => $this->doctor_id,
            'availablity' => $this->availablity,
            'doctor' => new DoctorResource($this->whenLoaded('doctor')),
            'timeslot' =>  TimeslotResource::collection($this->timeslot),
            // 'timeslot' =>  TimeslotResource::collection($this->whenLoaded('timeslot')),
        ];
    }
}
