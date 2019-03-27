<?php

namespace App\Imports;

use App\Models\Clinic;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClinicImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row['code']);
        if(!empty($row['code'])){
            return new Clinic([
                'code'          => $this->ifNull($row['code']),
                'name'          => $this->ifNull($row['name']),
                'name_lang2'    => $this->ifNull($row['name_lang2']),
                'site_id'       => $this->ifNull($row['site_id']),
                'chain_id'      => $this->ifNull($row['chain_id']),
                'location_desc'         => $this->ifNull($row['location_desc']),
                'location_desc_lang2'   => $this->ifNull($row['location_desc_lang2']),
                'address_desc'          => $this->ifNull($row['address_desc']),
                'address_desc_lang2'    => $this->ifNull($row['address_desc_lang2']),
                'directions_desc'       => $this->ifNull($row['address_desc_lang2']),
                'directions_desc_lang2' => $this->ifNull($row['directions_desc_lang2']),
                'gps_lat'      => $this->ifNull($row['gps_lat']),
                'gps_long'      => $this->ifNull($row['gps_long']),
                'email'         => $this->ifNull($row['email']),
                'phone_num'     => $this->ifNull($row['phone_num']),
                'website_url'   => $this->ifNull($row['website_url']),
                'video_url'     => $this->ifNull($row['video_url']),
                'logo_url'      => $this->ifNull($row['logo_url']),
                'forward_url'   => $this->ifNull($row['forward_url']),
                'res_notes'     => $this->ifNull($row['res_notes']),
                'res_notes_lang2'       => $this->ifNull($row['res_notes_lang2']),
                'res_time_slot_length'  => $this->ifNull($row['res_time_slot_length']),
                'sort_index'        => $this->ifNull($row['sort_index']),
                'is_enabled'        => 0,
                'type'              => $this->ifNull($row['type']),
                'res_options'       => $this->ifNull($row['res_options']),
                'res_options_text'  => $this->ifNull($row['res_options_text']),
                'res_options_text_lang2'    => $this->ifNull($row['res_options_text_lang2']),
                'days_visible'  => $this->ifNull($row['days_visible']),
                'created_at'    => $this->ifNull($row['created_at']),
                'updated_at'    => $this->ifNull($row['updated_at']),
            ]);
        }
    }

    public function ifNull($value)
    {
        return $value === null ? 0 : $value;
    }
}
