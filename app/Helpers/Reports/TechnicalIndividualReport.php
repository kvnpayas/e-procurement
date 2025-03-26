<?php
namespace App\Helpers\Reports;

use App\Models\ProjectBidding;

class TechnicalIndividualReport
{
  protected $bidding, $vendor;
  public static function generateReport($bidId, $vendorId)
  {
    $technicalData = [];
    $bidding = ProjectBidding::findOrFail($bidId);
    $vendor = $bidding->vendors->where('id', $vendorId)->first();
    $vendorTotalRating = 0;
    foreach ($bidding->technicals as $technical) {
      $vendorResponse = $technical->technicalVendors->where('bidding_id', $bidding->id)->where('vendor_id', $vendor->id)->first();
      $answer = null;
      $adminAnswer = null;
      $from = null;
      $to = null;
      $score = null;
      $rating = null;
      $ratingScore = null;
      if ($vendorResponse) {
        // reformat answers
        $initAnswer = $vendorResponse ? $vendorResponse->admin_answer ? $vendorResponse->admin_answer : $vendorResponse->answer : false;
        if ($technical->question_type == 'checkbox') {
          $answer = $vendorResponse->admin_answer ? 'Yes' : ($vendorResponse->answer ? 'Yes' : 'No');
          // $answer = $vendorResponse->answer ? 'Yes' : 'No';
          $vendorScore = $vendorResponse->admin_answer ? 100 : ($vendorResponse->answer ? 100 : 0);
          // $answer = $initAnswer ? 'Yes' : 'No';
          if ($vendorResponse && $vendorResponse->admin_answer == null) {
            $adminAnswer == null;
          } else {
            $adminAnswer = $vendorResponse->admin_answer ? 'Yes' : 'No';
          }
          // $adminAnswer = $vendorResponse ? $vendorResponse->admin_answer ? 'Yes' : 'No' : null;

          $ratingScore = $vendorScore == 100 ? (float) $technical->pivot->weight : 0;
          if ($bidding->score_method == 'Cost') {
            $rating = ($vendorScore == 100) ? $vendorScore : 0;
          } else {
            $rating = $technical->pivot->weight ? ($ratingScore / $technical->pivot->weight) * 100 : 0;
          }
          $score = $vendorScore ? 'Fully Compliant' : 'Non-compliant';
        } elseif ($technical->question_type == 'single-option') {
          $optionId = $vendorResponse->admin_answer ? $vendorResponse->admin_answer : $vendorResponse->answer;
          $adminOption = $technical->options->where('id', $vendorResponse->admin_answer)->first();
          $option = $technical->options->where('id', $optionId)->first();
          $adminAnswer = $vendorResponse->admin_answer;
          $initScore = $adminOption ? $adminOption->score : ($option ? $option->score : 0);
          $answer = $option ? $option->name : null;
          $ratingScore = (($option->score / 100) * ($technical->pivot->weight * 0.01)) * 100;
          $rating = $technical->pivot->weight ? ($ratingScore / $technical->pivot->weight) * 100 : 0;
          // dd($rating, $ratingScore);
          if ($technical->pivot->weight) {
            $score = ($rating == 100) ? 'Fully Compliant' : (($rating > 0) ? 'Partially Compliant' : 'Non-compliant');
          } else {
            $dataScore = $initScore ? $initScore : 0;
            $score = ($dataScore == 100) ? 'Fully Compliant' : (($dataScore > 0) ? 'Partially Compliant' : 'Non-compliant');
          }
        } elseif ($technical->question_type == 'multi-option') {
          $stringAnswer = $vendorResponse->admin_answer ? $vendorResponse->admin_answer : $vendorResponse->answer;
          $adminAnswer = $vendorResponse->admin_answer;
          $arrayIds = explode('&@!', $stringAnswer);
          $options = $technical->options->whereIn('id', $arrayIds);
          $totalScore = $technical->options->sum('score');
          $ratingScore = (($options->sum('score') / $totalScore) * ($technical->pivot->weight * 0.01)) * 100;
          $rating = ($ratingScore / $technical->pivot->weight) * 100;
          $arrayAnswer = $options->pluck('name')->toArray();
          $answer = implode(', ', $arrayAnswer);
          $score = ($rating == 100) ? 'Fully Compliant' : (($rating > 0) ? 'Partially Compliant' : 'Non-compliant');
        } else {

          $answer = $initAnswer ? $initAnswer : null;
          $adminAnswer = $vendorResponse->admin_answer;
          $from = $technical->from;
          $to = $technical->to;

          if ($to) {
            $dataScore = $answer >= $technical->from && $answer <= $technical->to ? 100 : 0;
          } else {
            $dataScore = $answer
              >= $technical->from ? 100 : 0;
          }
          $ratingScore = $dataScore == 100 ? (float) $technical->pivot->weight : 0;
          $rating = $technical->pivot->weight ? ($ratingScore / $technical->pivot->weight) * 100 : 0;
          $score = $dataScore ? 'Fully Compliant' : 'Non-compliant';
        }

        // Retrieve Vendor Attachment 
        $vendorFiles = $technical->vendorFiles->where('bidding_id', $bidding->id)->where('vendor_id', $vendor->id);

        $vendorFilesArray = $vendorFiles && !$vendorFiles->isEmpty() ? $vendorFiles->pluck('file')->toArray() : [];

        $technicalData['data'][$technical->id] = [
          'id' => $technical->id,
          'question' => $technical->question,
          'type' => $technical->question_type,
          'answer' => $answer,
          'admin_answer' => $adminAnswer,
          'from' => $from,
          'to' => $to,
          'score' => $score,
          'rating' => $rating,
          'rating_score' => $ratingScore,
          'files' => $vendorFiles ? $vendorFiles->pluck('file')->toArray() : null,
          'result' => $answer !== null && $vendorFilesArray ? true : false,
          'remarks' => $technical->pivot->remarks,
        ];
      } else {
        $technicalData['data'][$technical->id] = [
          'id' => $technical->id,
          'question' => $technical->question,
          'type' => $technical->question_type,
          'answer' => null,
          'admin_answer' => null,
          'from' => null,
          'to' => null,
          'score' => null,
          'rating' => null,
          'rating_score' => null,
          'files' => null,
          'result' => false,
          'remarks' => $technical->pivot->remarks,
        ];
      }

      $vendorTotalRating += $ratingScore;
    }

    $vendorRemarks = $bidding->bidEnvelopeStatus->where('vendor_id', $vendor->id)->where('envelope', 'technical')->first();
    $result = $bidding->technicalResult->where('vendor_id', $vendor->id)->first();
    $technicalWeight = $bidding->weights()->where('envelope', 'technical')->first() ? $bidding->weights()->where('envelope', 'technical')->first()->weight : 0;
    $technicalData['name'] = $vendor->name;
    $technicalData['email'] = $vendor->email;
    $technicalData['address'] = $vendor->address;
    $technicalData['number'] = $vendor->number;
    $technicalData['result'] = $result ? $result->result : 0;
    $technicalData['vendor_total_rating'] = $vendorTotalRating;
    $technicalData['vendor_remarks'] = $vendorRemarks ? $vendorRemarks->remarks : '';
    $technicalData['total_rating'] = (int) $technicalWeight;
    return $technicalData;
  }
}

