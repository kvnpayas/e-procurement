<?php

namespace App\Exports;

use App\Exports\ProjectDetails;
use App\Exports\SummaryFinalReport;
use App\Exports\FinancialFinalReport;
use App\Exports\EligibilityFinalReport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FinalReport implements WithMultipleSheets
{
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
    // dd($this->data);
  }

  public function sheets(): array
  {
    $allEnvelopes = [
      'eligibility' => (bool) $this->data['projectbid']->eligibility,
      'technical' => (bool) $this->data['projectbid']->technical,
      'financial' => (bool) $this->data['projectbid']->financial,
    ];
    $envelopes = array_filter($allEnvelopes, function ($value) {
      return $value === true;
    });

    $reports[] = new ProjectDetails($this->data['projectbid']);
    $reports[] = new SummaryFinalReport($this->data);

    if (isset($envelopes['eligibility'])) {
      $reports[] = new EligibilityFinalReport($this->data);
    }

    if (isset($envelopes['technical'])) {
      $reports[] = new TechnicalFinalReport($this->data);
    }

    if (isset($envelopes['financial'])) {
      $reports[] = new FinancialFinalReport($this->data);
    }

    return $reports;
  }
}
