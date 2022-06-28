<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ScheduleCollection extends ResourceCollection
{
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
            'day_id' => $this->day_id,
            'date' => $this->date,
            'doctor' => $this->whenLoaded('doctor'),
            'timeslot' => $this->whenLoaded('timeslot'),
        ];
    }
}