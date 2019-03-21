<?php

namespace App\Exports;

use App\Models\Clinic;
use Maatwebsite\Excel\Concerns\FromCollection;

class ClinicExport implements FromCollection
{
    public function collection()
    {
        return Clinic::all();
    }
}