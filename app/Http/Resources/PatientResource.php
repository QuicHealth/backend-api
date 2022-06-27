<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return
            [
                'unique_id' => $this->resource->unique_id,
                'name' => $this->resource->firstname . " " . $this->resource->lastname,
                'email' => $this->resource->email,
                'gender' => $this->resource->gender,
                'phone' => $this->resource->phone,
                'Date of birth' => $this->resource->dob,
                'Appointment' => new AppointmentResource($this->whenLoaded('appointments')),
                // 'Date of birth' => Carbon::parse($this->resource->dob)->toDateTimeString(),
            ];
    }
}
