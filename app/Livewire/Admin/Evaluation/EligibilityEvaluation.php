<?php

namespace App\Livewire\Admin\Evaluation;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ProjectBidding;
use Illuminate\Support\Carbon;
use App\Models\VendorEnvelopeFile;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Reports\EligibilityIndividualReport;

class EligibilityEvaluation extends Component
{
  use WithFileUploads;
  public $bidding, $vendors, $eligibilities;
  public $arrowRotated, $fileAttachment, $eligibilityName;
  public $eligibilityReview, $eligibilityResponse, $eligibilityResult, $results;
  public $vendorResponse, $adminResponse, $eligibilityDetails, $adminVendor, $fileInputs, $adminFiles, $hasFile;
  public $fileStatus = [];
  public $vendorRemarks;

  public function mount($biddingId)
  {
    $this->bidding = ProjectBidding::findOrFail($biddingId);

    $this->vendors = $this->getVendors();
    // dd($this->vendors->pluck('vendorStatus.submission_date', 'name')->toArray());
    $this->eligibilities = $this->getEligibilities()->get();

    $this->setVendorResults();
  }

  public function setVendorResults()
  {

    $eligibilityResult = [];
    foreach ($this->vendors as $vendor) {
      // $eligibilityResult[$vendor->id]
      foreach ($this->getEligibilities()->get() as $eligibility) {
        $count = $eligibility->eligibilityVendors->where('bidding_id', $this->bidding->id)->where('vendor_id', $vendor->id)->count();
        $files = $eligibility->vendorFiles->where('bidding_id', $this->bidding->id)->where('vendor_id', $vendor->id)->where('file', '!=', '');
        $filesAdmin = $eligibility->vendorFiles->where('bidding_id', $this->bidding->id)->where('vendor_id', $vendor->id)->where('admin_file', '!=', NULL);
        $this->eligibilityResult[$vendor->id][$eligibility->id] = $eligibility->details->count() == $count && (!$files->isEmpty() || !$filesAdmin->isEmpty()) ? true : false;

        foreach ($files as $file) {
          $fileInitStatus = $this->bidding->fileAttachmentsStatus('eligibility')->where('file_id', $file->id)->first();
          $this->fileStatus[$file->id] = [
            'file_id' => $file->id,
            'status' => $fileInitStatus ? true : false,
          ];
        }
      }
      $vendorResult = $this->bidding->eligibilityResult->where('vendor_id', $vendor->id)->first();

      $data = [
        'vendor_id' => $vendor->id,
        'result' => in_array(false, $this->eligibilityResult[$vendor->id], false) ? false : true,
      ];
      if ($vendorResult) {
        $vendorResult->update($data);
        $this->results[] = $vendorResult;
      } else {
        $this->results[] = $this->bidding->eligibilityResult()->create($data);
      }

      $remarks = $this->bidding->bidEnvelopeStatus->where('vendor_id', $vendor->id)->where('envelope', 'eligibility')->first();
      $this->vendorRemarks[$vendor->id] = $remarks ? $remarks->remarks : null;

    }
    // dd($this->results);
  }

  public function printReport()
  {
    $eligibilityData = [];
    foreach ($this->vendors as $vendor) {
      $eligibilityData[$vendor->id] = EligibilityIndividualReport::generateReport($this->bidding->id, $vendor->id);
      // dd($eligibityData);
      // foreach ($this->getEligibilities()->get() as $eligibilities) {
      //   $vendorFiles = $eligibilities->vendorFiles->where('bidding_id', $this->bidding->id)->where('vendor_id', $vendor->id)->where('envelope_id', $eligibilities->id)->where('envelope', 'eligibility');
      //   foreach ($eligibilities->details as $detail) {
      //     $response = $eligibilities->eligibilityVendors->where('bidding_id', $this->bidding->id)->where('vendor_id', $vendor->id)->where('eligibility_detail_id', $detail->id)->first();

      //     $details = [
      //       'response' => $response ? $response->response ? true : false : false,
      //       'admin_response' => $response ? $response->admin_response ? true : false : false,
      //     ];
      //   }
      //   if (!$vendorFiles->isEmpty()) {
      //     $adminFile = $vendorFiles->where('admin_file', '!=', null)->toArray();
      //   } else {
      //     $adminFile = null;
      //   }
      //   $adminResponse = collect($details)->where('admin_response', true)->toArray();
      //   $detailsResponse = collect($details)->filter(function ($item) {
      //     return $item;
      //   })->toArray();

      //   $eligibilityData[$vendor->id]['eligibilities'][$eligibilities->id] = [
      //     'name' => $eligibilities->name,
      //     'description' => $eligibilities->description,
      //   ];
      //   $eligibilityData[$vendor->id]['eligibilities'][$eligibilities->id]['results'] = [
      //     'result' => $detailsResponse || !$vendorFiles->isEmpty() ? true : false,
      //     'admin' => $adminResponse || $adminFile ? true : false,
      //   ];
      // }

      // $result = $this->bidding->eligibilityResult->where('vendor_id', $vendor->id)->first();
      // $eligibilityData[$vendor->id]['name'] = $vendor->name;
      // $eligibilityData[$vendor->id]['email'] = $vendor->email;
      // $eligibilityData[$vendor->id]['address'] = $vendor->address;
      // $eligibilityData[$vendor->id]['number'] = $vendor->number;
      // $eligibilityData[$vendor->id]['result'] = $result ? $result->result : 0;
    }
    $sortedData = array_values($eligibilityData);
    $this->dispatch('openReportModal', $sortedData, 'eligibility', $this->bidding->id);
  }
  public function getVendors()
  {
    return $this->bidding->vendors()
      ->whereIn('status', ['Under Evaluation', 'Lost', 'Winning Bidder'])
      ->get()
      ->map(function ($vendor) {
        $vendor->vendorStatus = $vendor->vendorStatus->where('bidding_id', $this->bidding->id)->first();
        return $vendor;
      })
      ->sortBy(function ($vendor) {
        return $vendor->vendorStatus ? $vendor->vendorStatus->submission_date : null;
      })
      ->values();
  }

  public function getEligibilities()
  {
    return $this->bidding->eligibilities();
  }

  public function viewAttachFile($file, $eligibilityName, $fileId)
  {
    $this->fileAttachment = route('view-file', ['file' => $file, 'folder' => 'vendor-file\eligibility']);
    $this->eligibilityName = $eligibilityName;

    $file = VendorEnvelopeFile::findOrFail($fileId);
    // dd($file);
    // change status
    $fileStatusExists = $this->bidding->fileAttachmentsStatus('eligibility')->where('file_id', $fileId)->first();
    if (!$fileStatusExists) {
      $this->bidding->fileAttachmentsStatus('eligibility')->create([
        'bidding_id' => $this->bidding->id,
        'file_id' => $file->id,
        'vendor_id' => $file->vendor_id,
        'validated_by' => Auth::user()->id,
        'envelope' => 'eligibility',
        'validated_date' => Carbon::now(),
      ]);
    }
    $this->setVendorResults();
    $this->dispatch('openFileModal');
    $this->dispatch('checkFileAttachmentStatus');
  }
  public function closeFileModal()
  {
    $this->dispatch('closeFileModal');
  }
  public function reviewModal($id, $vendorId)
  {
    $this->eligibilityReview = $this->getEligibilities()->where('eligibility_id', $id)->first();
    $this->eligibilityResponse = $this->eligibilityReview->eligibilityVendors->where('bidding_id', $this->bidding->id)->where('vendor_id', $vendorId);
    $this->dispatch('openReviewModal');
  }
  public function closeReviewModal()
  {
    $this->dispatch('closeReviewModal');
  }
  public function adminModal($id, $vendorId)
  {
    $this->adminVendor = $vendorId;
    $this->adminResponse = [];
    $this->resetValidation();
    $this->eligibilityDetails = $this->getEligibilities()->where('eligibility_id', $id)->first();
    $this->vendorResponse = $this->eligibilityDetails->eligibilityVendors->where('bidding_id', $this->bidding->id)->where('vendor_id', $vendorId);
    foreach ($this->eligibilityDetails->details as $detail) {
      $responseExist = $this->eligibilityDetails->eligibilityVendors->where('bidding_id', $this->bidding->id)->where('vendor_id', $this->adminVendor)->where('eligibility_detail_id', $detail->id)->first();

      $this->adminResponse[$detail->id] = $responseExist ? $responseExist->admin_response : NULL;
    }
    $this->adminFiles = $this->eligibilityDetails->vendorFiles->where('bidding_id', $this->bidding->id)->where('vendor_id', $this->adminVendor)->where('admin_file', '!=', NULL);
    $this->hasFile = $this->adminFiles->isEmpty() ? false : true;
    // dd($this->adminResponse);
    $this->dispatch('openAdminModal');
  }

  public function updatedFileInputs()
  {
    sleep(2);
  }
  public function submitAdminResponse()
  {
    $this->validate([
      'adminResponse.*' => 'required',
      'fileInputs.*' => $this->hasFile ? '' : 'required|file|mimes:pdf',
      'fileInputs' => $this->hasFile ? '' : 'required',
    ], [
      'adminResponse.*.required' => 'Admin response field is required.'
    ]);

    foreach ($this->eligibilityDetails->details as $detail) {
      $responseExist = $this->eligibilityDetails->eligibilityVendors->where('bidding_id', $this->bidding->id)->where('vendor_id', $this->adminVendor)->where('eligibility_detail_id', $detail->id)->first();
      if ($responseExist) {
        $responseExist->update(['admin_response' => $this->adminResponse[$detail->id]]);
      } else {
        $this->eligibilityDetails->eligibilityVendors()->create([
          'bidding_id' => $this->bidding->id,
          'vendor_id' => $this->adminVendor,
          'eligibility_detail_id' => $detail->id,
          'admin_response' => $this->adminResponse[$detail->id],
        ]);
      }
    }
    // dd($this->fileInputs);
    // Vendor Files
    $fileExists = $this->eligibilityDetails->vendorFiles->where('bidding_id', $this->bidding->id)->where('vendor_id', $this->adminVendor)->where('admin_file', '!=', NULL);
    if ($this->fileInputs) {

      foreach ($this->fileInputs as $key => $file) {
        $attachName = strtolower('eligibility_' . ($key + 1) . '_' . str_replace(' ', '_', $this->bidding->id) . '_' . time() . '.' . $file->extension());
        $files[] = $attachName;
        VendorEnvelopeFile::create([
          'bidding_id' => $this->bidding->id,
          'vendor_id' => $this->adminVendor,
          'envelope_id' => $this->eligibilityDetails->id,
          'envelope' => 'eligibility',
          'admin_file' => $attachName,
          'admin_user' => Auth::user()->id,
        ]);
        $file->storeAs('vendor-file/eligibility', $attachName, 'public');
      }

      if (!$fileExists->isEmpty()) {
        $ids = $fileExists->pluck('id')->toArray();
        foreach ($fileExists as $file) {
          $filePath = 'app/public/vendor-file/eligibility/' . $file->admin_file;
          if (file_exists(storage_path($filePath))) {
            unlink(storage_path($filePath));
          }
        }

        VendorEnvelopeFile::destroy($ids);
      }
    }
    $this->dispatch('closeAdminModal');
    // return redirect()
    //   ->route('project-bidding.evaluation', $this->bidding->id)
    //   ->with('success', 'Eligibility updated!');
    $this->dispatch('closeAdminModal');
    $this->dispatch('success-message', ['message' => 'Eligibility updated!']);
  }
  public function uploadFile()
  {
    $this->hasFile = false;
  }

  public function updatedVendorRemarks($value, $id)
  {
    $remarks = $this->bidding->bidEnvelopeStatus->where('vendor_id', $id)->where('envelope', 'eligibility')->first();

    $remarks->remarks = $value;
    $remarks->save();
  }

  public function closeAdminModal()
  {
    $this->dispatch('closeAdminModal');
  }
  public function render()
  {
    return view('livewire.admin.evaluation.eligibility-evaluation');
  }
}
