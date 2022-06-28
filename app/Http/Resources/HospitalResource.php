<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HospitalResource extends JsonResource
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
                'phone' => $this->phone,
                'image' => $this->image,
                'featured' => $this->featured,
                'status' => $this->status,
                'latitude' => $this->latitude,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
                'address' => $this->address,
                'description' => $this->description,
                'doctors' => DoctorResource::collection($this->whenLoaded('doctors')),
            ];
    }
}
