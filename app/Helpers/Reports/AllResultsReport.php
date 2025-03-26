<?php
namespace App\Helpers\Reports;

use App\Models\ProjectBidding;
use App\Helpers\Reports\FinancialIndividualReport;
use App\Helpers\Reports\TechnicalIndividualReport;
use App\Helpers\Reports\EligibilityIndividualReport;


class AllResultsReport
{
  protected $project, $vendors, $envelopes;
  public static function getAllResults($bidId, $vendors)
  {
    $project = ProjectBidding::findOrFail($bidId);
    $allEnvelopes = [
      'eligibility' => (bool) $project->eligibility,
      'technical' => (bool) $project->technical,
      'financial' => (bool) $project->financial,
    ];

    $envelopesFilter = array_filter($allEnvelopes, function ($value) {
      return $value === true;
    });

    foreach ($envelopesFilter as $envelope => $value) {
      $weight = $project->weights->where('envelope', $envelope)->first();
      $envelopesFilter[$envelope] = $weight ? $weight->weight : null;
    }
    $envelopes = $envelopesFilter;
    $finalData = [];
    foreach ($vendors as $vendor) {


      $checkPrevEnvelopes = true;
      foreach ($envelopes as $envelope => $value) {
        $model = $envelope . 'Result';
        $envelopeModel = $project->{$model}->where('vendor_id', $vendor->id)->first();
        $envelopeResult = $envelopeModel ? $envelopeModel->result : 0;
        $viewSummary = $checkPrevEnvelopes || $envelopeResult ? true : false;
        $checkPrevEnvelopes = $envelopeResult;
        if ($envelope == 'eligibility' && $viewSummary) {

          $finalData[$vendor->id][$envelope] = EligibilityIndividualReport::generateReport($project->id, $vendor->id);

        } else if ($envelope == 'technical' && $viewSummary) {

          $finalData[$vendor->id][$envelope] = TechnicalIndividualReport::generateReport($project->id, $vendor->id);

        } else if ($envelope == 'financial' && $viewSummary) {
          $finalData[$vendor->id][$envelope] = FinancialIndividualReport::generateReport($project->id, $vendor->id);

        } else {
          $finalData[$vendor->id][$envelope] = false;
        }

      }
      $finalResult = $project->finalResult->where('vendor_id', $vendor->id)->first();
      $finalData[$vendor->id]['name'] = $vendor->name;
      $finalData[$vendor->id]['email'] = $vendor->email;
      $finalData[$vendor->id]['address'] = $vendor->address;
      $finalData[$vendor->id]['number'] = $vendor->number;
      $finalData[$vendor->id]['result'] = $finalResult ? $finalResult->result : 0;
      $finalData[$vendor->id]['score'] = $finalResult ? $finalResult->score : null;
      $finalData[$vendor->id]['rank'] = $finalResult ? $finalResult->rank : null;
    }

    $rankData = array_values(collect($finalData)->sortBy('rank')->toArray());
    return $rankData;
  }
}