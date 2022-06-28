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
                'unique_id' => $this->resource->unique_id,
                'name' => $this->resource->name,
                'email' => $this->resource->email,
                'phone' => $this->resource->phone,
                'image' => $this->resource->image,
                'featured' => $this->resource->featured,
                'status' => $this->resource->status,
                'latitude' => $this->resource->latitude,
                'city' => $this->resource->city,
                'state' => $this->resource->state,
                'country' => $this->resource->country,
                'address' => $this->resource->address,
                'description' => $this->resource->description,
                'doctors' => DoctorResource::collection($this->whenLoaded('doctors')),
            ];
    }
}