<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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

        return [
            'id' => $this->id,
            'patient_id' => $this->user_id,
            'doctor_id' => $this->doctor_id,
            'day' => $this->day,
            // 'day_id' => $this->day_id,
            'status' => $this->status,
            'start' => $this->start,
            'end' => $this->end,
            'payment_status' => $this->payment_status,
            'payment_reference' => $this->payment_reference,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'doctor' => new DoctorResource($this->whenLoaded('doctor')),
        ];
    }
}
