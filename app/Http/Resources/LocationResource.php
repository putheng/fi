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
            'phone' => $this->validPhone($this->phone_num),
            'email' => $this->validEmail($this->email),
            'type' => $this->type
        ];
    }

    public function validEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          return ""; 
        }

        return strtolower($email);
    }

    public function validPhone($phone)
    {
        if(strlen($phone) < 8){
            return '';
        }

        return $phone;
    }
}