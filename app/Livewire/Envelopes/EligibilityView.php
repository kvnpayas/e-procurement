<?php

namespace App\Livewire\Envelopes;

use App\Models\EligibilityVendor;
use App\Models\VendorEnvelopeFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\Auth;

class EligibilityView extends Component
{
  use WithFileUploads;

  public $eligibilityName,
  $eligibilityRemarks,
  $eligibilityId,
  $eligibility,
  $projectId,
  $bid,
  $bidId,
  $vendorResponse,
  $fileInputs,
  $fileModal,
  $messageAction,
  $dateValidation,
  $removeId,
  $eligibilityFileName,
  $fileAttachment,
  $hasFile = [],
  $eligibilityFiles = [],
  $eligibilityDetails = [],
  $input = [];
  public $showModal = false, $vendorStatus;

  public function mount($projectId)
  {
    $this->bidId = $projectId;
    $this->bid = ProjectBidding::where('id', $this->bidId)->first();

    $this->vendorStatus = $this->bid->bidVendorStatus->where('vendor_id', Auth::user()->id)->first();
  }

  public function getEligibilities()
  {
    $projectBiddings = $this->bid->eligibilities->map(function ($eligibility) {
      $count = $eligibility->eligibilityVendors->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id)->count();
      $files = $eligibility->vendorFiles->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id);
      $eligibility->vendorStatus = $eligibility->details->count() == $count && !$files->isEmpty() ? true : false;
      return $eligibility;
    });

    return $projectBiddings;
  }

  public function eligibilityModal($id)
  {
    $this->resetValidation();
    $this->fileInputs = null;
    $this->input = [];
    $this->eligibility = $this->getEligibilities()
      ->where('id', $id)
      ->first();
    $this->eligibilityFiles = $this->eligibility->vendorFiles->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id);
    $this->eligibilityId = $this->eligibility->id;
    $this->eligibilityName = $this->eligibility->name;
    $this->eligibilityRemarks = $this->eligibility->pivot->remarks;
    $this->eligibilityDetails = $this->eligibility->details;
    
    $this->vendorResponse = $this->eligibility->eligibilityVendors->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id);
    // dd($this->eligibilityDetails);
    if ($this->vendorResponse->isEmpty()) {
      foreach ($this->eligibilityDetails as $detail) {
        $this->input[$detail->id] = null;
      }
    } else {
      foreach ($this->vendorResponse as $response) {
        $this->input[$response->eligibility_detail_id] = $response->response;
      }
    }
    // dd($this->input);
    $this->hasFile = !$this->eligibilityFiles->isEmpty() ? true : false;
    
    // check if has date validation
    foreach ($this->eligibilityDetails as $detail) {
      if ($detail->validate_date) {
        $threeMonths = date('Y-m-d', strtotime('+3 months'));
        $this->dateValidation[$detail->id]['date'] = date('Y-m-d', strtotime('+1 day'));
        if ($this->input[$detail->id]) {
          $this->dateValidation[$detail->id]['warning'] = $this->input[$detail->id] > $threeMonths ? false : true;
        } else {
          $this->dateValidation[$detail->id]['warning'] = false;
        }
      }
    }
    // dd($this->input);
    // dd($this->eligibilityDetails);
    $this->dispatch('openEligibilityModal');

  }
  public function closeModal()
  {
    $this->input = [];
    $this->fileInputs = null;
    $this->eligibilityDetails = [];
    $this->dispatch('closeEligibilityModal');
  }

  public function updatedInput($value, $index)
  {
    $detail = $this->eligibility->details->where('id', $index)->first();
    if ($detail && $detail->field_type && $detail->validate_date) {
      $threeMonths = date('Y-m-d', strtotime('+3 months'));
      $this->dateValidation[$detail->id]['warning'] = $this->input[$detail->id] > $threeMonths ? false : true;
    }

  }
  public function saveForm()
  {
    $vendorFileExists = VendorEnvelopeFile::where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id)->where('envelope_id', $this->eligibilityId)->where('envelope', 'eligibility')->get();
    $this->hasFile = !$vendorFileExists->isEmpty() ? $vendorFileExists : [];
    $this->validate(
      [
        'input.*' => 'required',
        'hasFile' => $this->hasFile ? '' : 'required',
      ],
      [
        'input.*.required' => 'This field is required.',
        'hasFile.required' => 'The file attachment is required.',
      ],
    );
    // dd($this->eligibility->details);
    // Create or Update vendor response
    foreach ($this->eligibility->details as $detail) {
      $responseExists = $this->vendorResponse->where('eligibility_detail_id', $detail->id)->first();
      if ($responseExists) {
        $responseExists->update(['response' => $this->input[$detail->id]]);
      } else {
        EligibilityVendor::create([
          'bidding_id' => $this->bid->id,
          'vendor_id' => Auth::user()->id,
          'eligibility_id' => $this->eligibilityId,
          'eligibility_detail_id' => $detail->id,
          'response' => $this->input[$detail->id],
        ]);
      }

    }

    // Update eligibility status accoridng to the vendors input status true = all input have response
    $this->checkEligibilityStatus();
    $this->vendorStatus = $this->bid->bidVendorStatus->where('vendor_id', Auth::user()->id)->first();
    $this->input = [];
    $this->dispatch('closeSaveModal');
    $this->dispatch('closeEligibilityModal');

    // return redirect()->route('bid-lists.eligibility-envelope', $this->bid->id)
    //   ->with('success', 'Eligibility requirements successfully updated!');
    $this->dispatch('success-message', ['message' => 'Eligibility successfully updated!']);

  }

  // Download uploaded file
  public function viewFile($fileName)
  {
    $this->fileAttachment = route('view-file', ['file' => $fileName, 'folder' => 'vendor-file\eligibility']);
    $this->eligibilityFileName = $fileName;
    $this->dispatch('openFileModal');
    $this->dispatch('closeEligibilityModal');
  }
  public function closeFileModal()
  {
    $this->eligibilityModal($this->eligibilityId);
    $this->dispatch('closeFileModal');
  }

  public function checkEligibilityStatus()
  {
    $vendorEligibilities = $this->bid->eligibilities->map(function ($eligibility) {
      $count = $eligibility->eligibilityVendors->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id)->count();
      $files = $eligibility->vendorFiles->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id);
      $eligibility->vendorStatus = $eligibility->details->count() == $count && !$files->isEmpty() ? true : false;
      return $eligibility;
    });
    $checkResponse = $vendorEligibilities->pluck('vendorStatus')->toArray();
    $eligbilityStatus = $this->bid->bidEnvelopeStatus()->where('envelope', 'eligibility')->where('vendor_id', Auth::user()->id)->first();

    if (in_array(false, $checkResponse, false)) {
      $eligbilityStatus->update(['status' => false]);
    } else {
      $eligbilityStatus->update(['status' => true]);
    }

    $this->vendorStatus = $this->bid->bidVendorStatus->where('vendor_id', Auth::user()->id)->first();
    if ($this->vendorStatus) {
      $this->vendorStatus->update(['complete' => false]);
      $this->dispatch('updateVendorStatus');
    }
  }

  public function updatedFileInputs()
  {
    // dd($this->fileInputs);
    $this->validate(
      [
        'fileInputs' => 'mimes:pdf|max:10240',
      ],
      [
        'fileInputs.mimes' => 'The attachment must be a file of type: pdf.',
        'fileInputs.max' => 'The file may not be greater than 10MB.',
      ],
    );
    $vendorFileExists = VendorEnvelopeFile::where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id)->where('envelope_id', $this->eligibilityId)->where('envelope', 'eligibility')->get();
    $key = $vendorFileExists ? $vendorFileExists->count() : 0;
    $attachName = strtolower('eligibility_' . ($key + 1) . '_' . str_replace(' ', '_', $this->projectId) . '_' . time() . '.' . $this->fileInputs->extension());
    VendorEnvelopeFile::create([
      'bidding_id' => $this->bid->id,
      'vendor_id' => Auth::user()->id,
      'envelope_id' => $this->eligibilityId,
      'envelope' => 'eligibility',
      'file' => $attachName,
    ]);
    $this->fileInputs->storeAs('vendor-file/eligibility', $attachName, 'public');
    $this->eligibilityFiles = $this->eligibility->vendorFiles->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id);
    $this->fileInputs = null;
    $this->checkEligibilityStatus();
  }

  public function uploadFile($id)
  {
    $file = VendorEnvelopeFile::findOrFail($id);
    if (file_exists(storage_path('app/public/vendor-file/eligibility/' . $file->file))) {
      unlink(storage_path('app/public/vendor-file/eligibility/' . $file->file));
    }
    $file->delete();
    $this->checkEligibilityStatus();
    $this->eligibilityFiles = $this->eligibility->vendorFiles->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id);
  }

  // save modal
  public function openSaveModal()
  {
    $this->dispatch('openSaveModal');
    $this->dispatch('closeEligibilityModal');
  }
  public function openSaveModalFromRemove($id)
  {
    $this->removeId = $id;
    $this->dispatch('openSaveRemoveModal');
    $this->dispatch('closeEligibilityModal');
  }
  public function closeSaveRemoveModal()
  {
    $this->dispatch('closeSaveRemoveModal');
    $this->eligibilityModal($this->eligibilityId);

  }
  public function saveRemoveForm()
  {
    $file = VendorEnvelopeFile::findOrFail($this->removeId);
    if (file_exists(storage_path('app/public/vendor-file/eligibility/' . $file->file))) {
      unlink(storage_path('app/public/vendor-file/eligibility/' . $file->file));
    }
    $file->delete();
    $this->checkEligibilityStatus();
    $this->eligibilityFiles = $this->eligibility->vendorFiles->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id);
    $this->dispatch('closeSaveRemoveModal');
    $this->eligibilityModal($this->eligibilityId);
    $this->dispatch('success-message', ['message' => 'Eligibility successfully updated!']);

  }

  public function closeSaveModal()
  {
    $this->dispatch('closeSaveModal');
  }
  public function render()
  {
    return view('livewire.envelopes.eligibility-view', ['eligibilities' => $this->getEligibilities()]);
  }
}