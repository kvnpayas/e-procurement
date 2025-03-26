<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EligibilityReport implements FromView
{
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
    // dd($this->data);
  }

  public function view(): View
  {
    return view('reports.newExcel.eligibility-report', [
      'data' => $this->data,
    ]);
  }

}
