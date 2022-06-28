<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
                'unique_id' => $this->unique_id,
                'name' => $this->name,
                'email' => $this->email,
                'gender' => $this->gender,
                'phone' => $this->phone,
                'image' => $this->image,
                'featured' => $this->featured,
                'status' => $this->status,
                'address' => $this->address,
                'specialty' => $this->specialty,
                'hospital' => $this->whenLoaded('hospital'),
                // 'hospital' =>  HospitalResource::collection($this->whenLoaded('hospital')),
                'appointments' => AppointmentResource::collection($this->whenLoaded('appointments')),
                'availablity' => ScheduleResource::collection($this->whenLoaded('schedule')),

            ];
    }
}
