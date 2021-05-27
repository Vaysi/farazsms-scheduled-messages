<?php

namespace App\Exports;

use App\Number;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;

class NumbersExport implements FromQuery
{
    use Exportable;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Number::query()->where('contact_id',$this->id)->select(['number']);
    }
}
