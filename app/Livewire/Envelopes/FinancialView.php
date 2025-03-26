<?php

namespace App\Livewire\Envelopes;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ProjectBidding;
use App\Models\VendorEnvelopeFile;
use Illuminate\Support\Facades\Auth;

class FinancialView extends Component
{
  use WithFileUploads;

  public $projectId, $grandTotal, $bid;
  public $alertMessage;
  public $fileAttachment, $financialFileName, $vendorFiles, $initFiles, $hasFile = [];
  public $vendorResponse = [];
  public $inventoryDesc, $inventoryRemarks, $vendorStatus, $removeId;

  public function mount($projectId)
  {
    $this->projectId = $projectId;
    $this->bid = ProjectBidding::where('id', $projectId)->first();
    $this->initResponse();
    $this->vendorStatus = $this->bid->bidVendorStatus->where('vendor_id', Auth::user()->id)->first();
  }

  public function getFinancials()
  {
    return $this->bid->financials();
  }

  public function initResponse()
  {
    foreach ($this->getFinancials()->get() as $financial) {
      $vendorPrice = $this->bid->FinancialVendors->where('vendor_id', Auth::user()->id)->where('financial_id', $financial->id)->first();
      $this->vendorResponse[$financial->id]['price'] = $vendorPrice ? number_format($vendorPrice->price, 2) : null;
      $this->vendorResponse[$financial->id]['fees'] = $vendorPrice ? number_format($vendorPrice->other_fees, 2) : null;
      $this->vendorResponse[$financial->id]['total'] = $vendorPrice ? number_format($financial->pivot->quantity * ($vendorPrice->price + $vendorPrice->other_fees), 2) : null;
      $this->vendorResponse[$financial->id]['totalNoFormat'] = $vendorPrice ? $financial->pivot->quantity * ($vendorPrice->price + $vendorPrice->other_fees) : null;
    }

    $total = array_column($this->vendorResponse, 'totalNoFormat');
    $this->grandTotal = array_sum($total);

    // Check if venor has existing file upload
    $this->hasFile  = $this->bid->financialFiles->where('vendor_id', Auth::user()->id);

  }

  public function saveForm()
  {
    // Remove commas from prices and fees to make them numeric
    foreach ($this->vendorResponse as $id => $response) {
      $this->vendorResponse[$id]['price'] = str_replace(',', '', $response['price']);
      $this->vendorResponse[$id]['fees'] = str_replace(',', '', $response['fees']);
    }

    // Validates vendor response
    $this->validate(
      [
        'vendorResponse.*.price' => 'required|gt:0|numeric',
        'vendorResponse.*.fees' => 'required|gte:0|numeric',
        'hasFile' => 'required',
      ],
      [
        'vendorResponse.*.price.required' => 'Price field is required.',
        'hasFile.required' => 'The attachment file is required.',
        'vendorResponse.*.price.gt' => 'Price cannot be less than 0.',
        'vendorResponse.*.fees.required' => 'Fees field is required.',
        'vendorResponse.*.fees.gte' => 'Fees cannot be less than 0.',
        'vendorFiles.*.mimes' => 'The files field must be a file of type: pdf.',
      ],
    );

    // Create or Update FinancialVendor
    foreach ($this->vendorResponse as $id => $response) {
      $financialExists = $this->bid->financialVendors->where('vendor_id', Auth::user()->id)->where('financial_id', $id)->first();

      if ($financialExists) {
        $financialExists->update([
          'price' => $response['price'],
          'other_fees' => $response['fees'],
        ]);
      } else {
        $this->bid->financialVendors()->create([
          'vendor_id' => Auth::user()->id,
          'financial_id' => $id,
          'price' => $response['price'],
          'other_fees' => $response['fees'],
        ]);
      }
    }

    $vendorFileExists = VendorEnvelopeFile::where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id)->where('envelope', 'financial')->get();

    // Update financial status accoridng to the vendors input status true = all input have response
    $vendorResponse = $this->bid->financialVendors->where('vendor_id', Auth::user()->id)->where('price', '!=', null)->count();
    $financialCount = $this->bid->financials->count();



    $this->dispatch('closeSaveModal');
    $this->initResponse();
    $this->checkFinancialStatus();
    // return redirect()->route('bid-lists.financial-envelope', $this->bid->id)
    //   ->with('success', 'Financial requirements successfully updated!');
    $this->dispatch('success-message', ['message' => 'Financial successfully updated!']);
  }

  public function checkFinancialStatus()
  {
    $financialStatus = $this->bid->bidEnvelopeStatus()->where('envelope', 'financial')->where('vendor_id', Auth::user()->id)->first();
    $bid = ProjectBidding::where('id', $this->bid->id)->first();
    $files = $bid->financialFiles->where('vendor_id', Auth::user()->id);
    // $vendorFinancial = $this->bid->financial->map(function ($technical) {
    //   $answer = $this->answers[$technical->id] !== null ? $this->answers[$technical->id] : null;
    //   $technicaFiles = $technical->vendorFiles->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id);
    //   $vendorFiles = $technicaFiles->isEmpty() ? false : true;
    //   $technical->vendorStatus = $answer !== null && $vendorFiles ? true : false;
    //   return $technical;
    // });
    $result = array_map(function ($item) {
      if ($item['price'] == null || $item['fees'] == null) {
        $data = false;
      } else {
        $data = true;
      }
      return $data;
    }, $this->vendorResponse);
    $vendorResult = !in_array(false, $result, false) && !$files->isEmpty() ? true : false;
    if ($vendorResult) {
      $financialStatus->update(['status' => true]);
    } else {
      $financialStatus->update(['status' => false]);
    }

    // $this->initResponse();
    // $this->dispatch('success-message', ['message' => 'Financial requirements successfully updated!']);
    $this->vendorStatus = $this->bid->bidVendorStatus->where('vendor_id', Auth::user()->id)->first();
    if ($this->vendorStatus) {
      $this->vendorStatus->update(['complete' => false]);
      $this->dispatch('updateVendorStatus');
    }
  }

  public function updatedVEndorFiles()
  {
    $this->validate(
      [
        'vendorFiles' => 'mimes:pdf|max:10240',
      ],
      [
        'vendorFiles.mimes' => 'The attachment must be a file of type: pdf.',
        'vendorFiles.max' => 'The file may not be greater than 10MB.',
      ],
    );
    $vendorFileExists = $this->bid->financialFiles->where('vendor_id', Auth::user()->id);

    $key = $vendorFileExists ? $vendorFileExists->count() : 0;
    $attachName = strtolower('financial_' . ($key + 1) . '_' . str_replace(' ', '_', $this->bid->id) . '_' . Auth::user()->id . '_' . time() . '.' . $this->vendorFiles->extension());
    VendorEnvelopeFile::create([
      'bidding_id' => $this->bid->id,
      'vendor_id' => Auth::user()->id,
      'envelope_id' => null,
      'envelope' => 'financial',
      'file' => $attachName,
    ]);
    $this->vendorFiles->storeAs('vendor-file/financial', $attachName, 'public');
    $bid = ProjectBidding::where('id', $this->bid->id)->first();
    $this->hasFile = $bid->financialFiles->where('vendor_id', Auth::user()->id);
    $this->checkFinancialStatus();


    // $this->vendorFiles = null;
    // $this->checkTechnicalStatus();
  }

  public function removeFile($id)
  {
    $file = VendorEnvelopeFile::findOrFail($id);
    // dd(Auth::user()->id);
    $filePath = storage_path("app/public/vendor-file/financial/{$file->file}");
    if (file_exists($filePath)) {
      unlink($filePath);
    }
    $file->delete();
    $bid = ProjectBidding::where('id', $this->bid->id)->first();
    $this->hasFile = $bid->financialFiles->where('vendor_id', Auth::user()->id)->values();
    $this->checkFinancialStatus();
    // dd($this->hasFile);

  }

  // public function showFile($id)
  // {
  //     $financial = collect($this->financials)
  //         ->where('id', $id)
  //         ->first();
  //     $this->fileAttahcment = $financial['attachment'];
  // }

  // Remarks Modal and function
  public function remarksModal($id)
  {
    $financialInventory = $this->getFinancials()->where('financial_id', $id)->first();
    $this->inventoryDesc = $financialInventory ? $financialInventory->description : '';
    $this->inventoryRemarks = $financialInventory ? $financialInventory->pivot->remarks : '';
    $this->dispatch('openRemarksModal');
  }

  public function changeRemoveFiles()
  {
    $this->hasFile = false;
  }

  public function closeRemarksModal()
  {
    $this->dispatch('closeRemarksModal');
  }

  public function openSaveModalFromRemove($id)
  {
    $this->removeId = $id;
    $this->dispatch('openSaveRemoveModal');
  }
  public function closeSaveRemoveModal()
  {
    $this->dispatch('closeSaveRemoveModal');

  }
  public function saveRemoveForm()
  {
    $file = VendorEnvelopeFile::findOrFail($this->removeId);
    if (file_exists(storage_path('app/public/vendor-file/financial/' . $file->file))) {
      unlink(storage_path('app/public/vendor-file/financial/' . $file->file));
    }
    $file->delete();

    $this->hasFile = $this->bid->financialFiles->where('vendor_id', Auth::user()->id);
    $this->checkFinancialStatus();
    $this->dispatch('closeSaveRemoveModal');
    $this->dispatch('success-message', ['message' => 'Financial successfully updated!']);

  }

  // view file
  public function viewFile($file)
  {
    $this->fileAttachment = route('view-file', ['file' => $file, 'folder' => 'vendor-file\financial']);
    $this->financialFileName = $file;
    $this->dispatch('openFileModal');
  }

  public function closeFileModal()
  {
    $this->dispatch('closeFileModal');
  }

  // save modal
  public function openSaveModal()
  {
    $this->dispatch('openSaveModal');
  }

  public function closeSaveModal()
  {
    $this->dispatch('closeSaveModal');
  }
  public function render()
  {
    return view('livewire.envelopes.financial-view', ['financials' => $this->getFinancials()->get()]);
  }
}
