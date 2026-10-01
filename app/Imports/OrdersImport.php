<?php

namespace App\Imports;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model; 
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrdersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return Model|array|null
     */
    public function model(array $row): Model|array|null 
    {
        return new Order([
            'order_date'   => $this->transformDate($row['data_prikaza'] ?? null),
            'within_year'  => (int)($row['v_ramkah_goda'] ?? 0),
            'order_number' => $row['nomer_prikaza'] ?? null,
            'updated_date' => $this->transformDate($row['data_izmeneniya'] ?? null),
            'updated_by'   => $row['izmenil'] ?? null,
        ]);
    }

    private function transformDate($value)
    {
        if (blank($value)) {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
            }
            
            return Carbon::createFromFormat('d.m.Y', trim($value))->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}

