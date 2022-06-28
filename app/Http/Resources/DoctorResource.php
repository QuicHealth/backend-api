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
                'unique_id' => $this->resource->unique_id,
                'name' => $this->resource->name,
                'email' => $this->resource->email,
                'gender' => $this->resource->gender,
                'phone' => $this->resource->phone,
                'image' => $this->resource->image,
                'featured' => $this->resource->featured,
                'status' => $this->resource->status,
                'address' => $this->resource->address,
                'specialty' => $this->resource->specialty,
                'hospital' => new HospitalResource($this->whenLoaded('hospital')),
                'appointments' => AppointmentResource::collection($this->whenLoaded('appointments')),
                'availablity' => $this->whenLoaded('schedule'),
            ];
    }
}