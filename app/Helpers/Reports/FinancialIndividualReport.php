<?php
namespace App\Helpers\Reports;

use App\Models\ProjectBidding;

class FinancialIndividualReport
{
  protected $bidding, $vendor;
  public static function generateReport($bidId, $vendorId)
  {
    $financialData = [];
    $bidding = ProjectBidding::findOrFail($bidId);
    $vendor = $bidding->vendors->where('id', $vendorId)->first();

    $grandTotalReserved = 0;
    $grandTotalVendor = 0;
    foreach ($bidding->financials as $financial) {
      $vendorResponse = $financial->financialVendors->where('bidding_id', $bidding->id)->where('vendor_id', $vendor->id)->first();
      $total_reserved = $financial->pivot->bid_price * $financial->pivot->quantity;
      if ($vendorResponse) {

        $price = $vendorResponse->admin_price ? $vendorResponse->admin_price : $vendorResponse->price;
        $fees = $vendorResponse->admin_fees ? $vendorResponse->admin_fees : $vendorResponse->other_fees;

        $amount = ($price + $fees) * $financial->pivot->quantity;
        $financialData['data'][$financial->id] = [
          'id' => $financial->id,
          'inventory_id' => $financial->inventory_id,
          'description' => $financial->description,
          'uom' => $financial->uom,
          'reserved_price' => $financial->pivot->bid_price,
          'quantity' => $financial->pivot->quantity,
          'price' => $price,
          'other_fees' => $fees,
          'admin_price' => $vendorResponse->admin_price,
          'admin_fees' => $vendorResponse->admin_fees,
          'amount' => $amount,
          'total_reserved_price' => $total_reserved,
          'remarks' => $financial->pivot->remarks,
        ];
      } else {
        $financialData['data'][$financial->id] = [
          'id' => $financial->id,
          'inventory_id' => $financial->inventory_id,
          'description' => $financial->description,
          'uom' => $financial->uom,
          'reserved_price' => $financial->pivot->bid_price,
          'quantity' => $financial->pivot->quantity,
          'price' => null,
          'other_fees' => null,
          'admin_price' => null,
          'admin_fees' => null,
          'amount' => null,
          'total_reserved_price' => $total_reserved,
          'remarks' => $financial->pivot->remarks,
        ];
      }

      $grandTotalVendor += $amount;
      $grandTotalReserved += $total_reserved;
    }

    // Retrieve vendor financial offer
    $vendorFiles = $bidding->financialFiles->where('vendor_id', $vendor->id);

    $vendorFinancialResult = $bidding->financialResult->where('vendor_id', $vendor->id)->first();
    $financialWeight = $bidding->weights()->where('envelope', 'financial')->first() ? $bidding->weights()->where('envelope', 'financial')->first()->weight : 0;
    // if ($diffPercent >= 20) {
    //   $vendorScore = 100 * $financialWeight * 0.01;
    // } elseif ($diffPercent <= 20 && $diffPercent >= 10) {
    //   $vendorScore = 95 * $financialWeight * 0.01;
    // } elseif ($diffPercent <= 10 && $diffPercent >= 5) {
    //   $vendorScore = 90 * $financialWeight * 0.01;
    // } elseif ($diffPercent <= 5 && $diffPercent >= 1) {
    //   $vendorScore = 85 * $financialWeight * 0.01;
    // } else {
    //   $vendorScore = 0 * $financialWeight * 0.01;
    // }
    $vendorFinancialResult = $bidding->financialResult->where('vendor_id', $vendor->id)->first();
    $vendorRemarks = $bidding->bidEnvelopeStatus->where('vendor_id', $vendor->id)->where('envelope', 'financial')->first();
    $result = $vendorFinancialResult ? $vendorFinancialResult->result : 0;
    $vendorScore = $vendorFinancialResult ? $vendorFinancialResult->score : null;
    $financialData['name'] = $vendor->name;
    $financialData['email'] = $vendor->email;
    $financialData['address'] = $vendor->address;
    $financialData['number'] = $vendor->number;
    $financialData['grand_total'] = $grandTotalVendor;
    $financialData['vendor_rating_score'] = $vendorScore;
    $financialData['total_rating_score'] = (int) $financialWeight;
    $financialData['files'] = $vendorFiles ? $vendorFiles->pluck('file')->toArray() : null;
    $financialData['vendor_remarks'] = $vendorRemarks ? $vendorRemarks->remarks : '';
    $financialData['result'] = $result;
    return $financialData;
  }
}

