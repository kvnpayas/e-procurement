<?php
namespace App\Helpers\Reports;

use App\Models\ProjectBidding;

class EligibilityIndividualReport
{
  protected $bidding, $vendor;
  public static function generateReport($bidId, $vendorId)
  {
    $eligibilityData = [];
    $bidding = ProjectBidding::findOrFail($bidId);
    $vendor = $bidding->vendors->where('id', $vendorId)->first();
    foreach ($bidding->eligibilities as $eligibility) {
      $vendorFiles = $eligibility->vendorFiles->where('bidding_id', $bidding->id)->where('vendor_id', $vendor->id)->where('envelope_id', $eligibility->id)->where('envelope', 'eligibility');
      foreach ($eligibility->details as $detail) {
        $response = $eligibility->eligibilityVendors->where('bidding_id', $bidding->id)->where('vendor_id', $vendor->id)->where('eligibility_detail_id', $detail->id)->first();

        $details = [
          'response' => $response ? $response->response ? true : false : false,
          'admin_response' => $response ? $response->admin_response ? true : false : false,
        ];
      }
      if (!$vendorFiles->isEmpty()) {
        $adminFile = $vendorFiles->where('admin_file', '!=', null);
      } else {
        $adminFile = null;
      }
      $adminResponse = collect($details)->where('admin_response', true)->toArray();
      $detailsResponse = collect($details)->filter(function ($item) {
        return $item;
      })->toArray();

      $eligibilityData['data'][$eligibility->id] = [
        'id' => $eligibility->id,
        'name' => $eligibility->name,
        'description' => $eligibility->description,
        'result' => $detailsResponse || !$vendorFiles->isEmpty() ? true : false,
        'admin' => $adminResponse || ($adminFile !== null && $adminFile->toArray()) ? true : false,
        'files' => $vendorFiles ? ($adminFile !== null && $adminFile->toArray() ? $adminFile->pluck('admin_file')->toArray() : $vendorFiles->pluck('file')->toArray()) : null,
        'remarks' => $eligibility->pivot->remarks
      ];
    }

    $vendorRemarks = $bidding->bidEnvelopeStatus->where('vendor_id', $vendor->id)->where('envelope', 'eligibility')->first();
    $result = $bidding->eligibilityResult->where('vendor_id', $vendor->id)->first();
    $eligibilityData['name'] = $vendor->name;
    $eligibilityData['email'] = $vendor->email;
    $eligibilityData['address'] = $vendor->address;
    $eligibilityData['number'] = $vendor->number;
    $eligibilityData['vendor_remarks'] = $vendorRemarks ? $vendorRemarks->remarks : '';
    $eligibilityData['result'] = $result ? $result->result : 0;

    return $eligibilityData;
  }
}

