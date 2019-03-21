<?php

namespace App\Imports;

use App\Models\Clinic;
use Maatwebsite\Excel\Concerns\ToModel;

class ClinicImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd( $row[30]);
        return new Clinic([
            'id'            => $this->ifNull($row[0]),
            'code'          => $this->ifNull($row[1]),
            'name'          => $this->ifNull($row[2]),
            'name_lang2'    => $this->ifNull($row[3]),
            'site_id'       => $this->ifNull($row[4]),
            'chain_id'      => $this->ifNull($row[5]),
            'location_desc'         => $this->ifNull($row[6]),
            'location_desc_lang2'   => $this->ifNull($row[7]),
            'address_desc'          => $this->ifNull($row[8]),
            'address_desc_lang2'    => $this->ifNull($row[9]),
            'directions_desc'       => $this->ifNull($row[10]),
            'directions_desc_lang2' => $this->ifNull($row[11]),
            'gps_lat'      => $this->ifNull($row[12]),
            'gps_long'      => $this->ifNull($row[13]),
            'email'         => $this->ifNull($row[14]),
            'phone_num'     => $this->ifNull($row[15]),
            'website_url'   => $this->ifNull($row[16]),
            'video_url'     => $this->ifNull($row[17]),
            'logo_url'      => $this->ifNull($row[18]),
            'forward_url'   => $this->ifNull($row[19]),
            'res_notes'     => $this->ifNull($row[20]),
            'res_notes_lang2'       => $this->ifNull($row[21]),
            'res_time_slot_length'  => $this->ifNull($row[22]),
            'sort_index'        => $this->ifNull($row[23]),
            'is_enabled'        => 0,
            'type'              => $this->ifNull($row[25]),
            'res_options'       => $this->ifNull($row[26]),
            'res_options_text'  => $this->ifNull($row[27]),
            'res_options_text_lang2'    => $this->ifNull($row[28]),
            'days_visible'  => $this->ifNull($row[29]),
            'created_at'    => $this->ifNull($row[30]),
            'updated_at'    => $this->ifNull($row[31]),
        ]);
    }

    public function ifNull($value)
    {
        return $value === null ? 0 : $value;
    }
}
