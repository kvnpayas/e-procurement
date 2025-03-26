<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FinancialEnvelopeInventory implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
      return [
        'inventory_id' => $collection['inventory_id'],
        'description' => $collection['description'],
        'quantity' => $collection['quantity'],
        'cost' => $collection['cost'],
      ];
    }
}
