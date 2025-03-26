<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class TechnicalReport implements FromView
{
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  public function view(): View
  {
    return view('reports.newExcel.technical-report', [
      'data' => $this->data,
    ]);
  }
}
