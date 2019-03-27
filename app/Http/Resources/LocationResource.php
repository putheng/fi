<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class LocationResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'lat' => $this->gps_lat,
            'lng' => $this->gps_long,
            'address' => $this->address_desc,
            'city' => $this->location_desc,
            'state' => 'PP',
            'zip' => '12855',
            'phone' => '(555) 555-5555',
            'type' => $this->type
        ];
    }
}