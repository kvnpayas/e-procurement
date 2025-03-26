<?php

namespace App\Livewire\Envelopes;

use App\Mail\VendorSbumitBid;
use App\Models\Role;
use Livewire\Component;
use App\Models\ProjectBidding;
use Illuminate\Support\Carbon;
use App\Mail\AdminCompleteVendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\ProjectBidVendorStatus;

class SummaryView extends Component
{
  public $project;
  public $results = [], $eligibilities, $enableSubmit, $vendorStatus, $fileAttachment, $fileName;

  public function mount($projectId)
  {
    $this->project = ProjectBidding::findOrFail($projectId);

    $this->getResults();
    $this->vendorStatus = $this->getStatus()->where('bidding_id', $this->project->id)->first();
  }

  public function getStatus()
  {
    return ProjectBidVendorStatus::where('vendor_id', Auth::user()->id)->get();
  }

  // Get all envelope result
  public function getResults()
  {
    $allEnvelopes = [
      'Eligibilities' => (bool) $this->project->eligibility,
      'Technicals' => (bool) $this->project->technical,
      'Financials' => (bool) $this->project->financial,
    ];

    $allEnvelopeStatus = $this->project->bidEnvelopeStatus->where('vendor_id', Auth::user()->id);
    $allEnvelopesStatus = $allEnvelopeStatus->pluck('status')->all();
    $this->enableSubmit = in_array(0, $allEnvelopesStatus, true) || in_array('0', $allEnvelopesStatus, true) ? false : true;

    $envelopes = array_filter($allEnvelopes, function ($value) {
      return $value === true;
    });

    foreach ($envelopes as $envelope => $value) {
      $methodName = 'get' . $envelope;
      if (method_exists($this, $methodName)) {
        $this->results[$envelope] = $this->$methodName();
      }
    }
    // dd($this->results);
  }

  public function getEligibilities()
  {
    $envelopeStatus = $this->project->bidEnvelopeStatus->where('vendor_id', Auth::user()->id)->where('envelope', 'eligibility')->first();
    foreach ($this->project->eligibilities as $eligibility) {

      // Status Pased or Fail
      $count = $eligibility->eligibilityVendors->where('bidding_id', $this->project->id)->where('vendor_id', Auth::user()->id)->count();
      $elFiles = $eligibility->vendorFiles->where('bidding_id', $this->project->id)->where('vendor_id', Auth::user()->id);
      $status = $eligibility->details->count() == $count && !$elFiles->isEmpty() ? true : false;
      // Check if there is expiration date with validatin
      $checkValidation = '';
      $vendorEligibilityFiles = [];

      $vendorEligibilityFiles = $elFiles->isEmpty() ? [] : $elFiles->pluck('file')->toArray();
      foreach ($eligibility->details as $detail) {
        if ($detail->validate_date) {
          $vendorResponse = $eligibility->eligibilityVendors->where('bidding_id', $this->project->id)->where('vendor_id', Auth::user()->id)->where('eligibility_detail_id', $detail->id)->first();
          $dateResponse = $vendorResponse ? ($vendorResponse->admin_response ? $vendorResponse->admin_response : $vendorResponse->response) : '';

          $warningExpiration = Carbon::now()->addMonths(3)->format('Y-m-d');

          if ($dateResponse && $dateResponse <= Carbon::now()) {
            $checkValidation = 'Expired';
            break;
          } elseif ($dateResponse && $dateResponse <= $warningExpiration) {
            $checkValidation = 'Warning';
            break;
          }
        }
      }

      $eligibilityData['eligibilityData'][$eligibility->id] = [
        'id' => $eligibility->id,
        'name' => $eligibility->name,
        'description' => $eligibility->description,
        'status' => $status,
        'files' => $vendorEligibilityFiles,
        'remarks' => $eligibility->pivot->remarks,
      ];
    }
    $eligibilityData['status'] = $envelopeStatus->status;

    return $eligibilityData;

  }

  public function getTechnicals()
  {
    // dd($this->project->technicals);
    $envelopeStatus = $this->project->bidEnvelopeStatus->where('vendor_id', Auth::user()->id)->where('envelope', 'technical')->first();
    if ($envelopeStatus) {
      foreach ($this->project->technicals as $technical) {
        $vendorInitResponse = $technical->technicalVendors->where('bidding_id', $this->project->id)->where('vendor_id', Auth::user()->id)->first();

        $files = $technical->vendorFiles->where('bidding_id', $this->project->id)->where('vendor_id', Auth::user()->id);

        $vendorFiles = $files && !$files->isEmpty() ? $files->pluck('file')->toArray() : [];
        if ($vendorInitResponse) {
          if ($technical->question_type == 'single-option' || $technical->question_type == 'multi-option') {
            $selectedOption = $technical->options->where('id', $vendorInitResponse->answer)->first();
            $vendorResponse = $selectedOption ? $selectedOption->name : null;
          } else {
            $vendorResponse = $vendorInitResponse !== null ? $vendorInitResponse->answer : null;
          }
        } else {
          $vendorResponse = null;
        }


        $technicalData['technicalData'][$technical->id] = [
          'id' => $technical->id,
          'question' => $technical->question,
          'type' => $technical->question_type,
          'answer' => $vendorResponse,
          'files' => $vendorFiles,
          'status' => $vendorResponse !== null && $vendorFiles ? true : false,
          'remarks' => $technical->pivot->remarks
        ];
      }
      $technicalData['status'] = $envelopeStatus->status;
      // dd($technicalData);
    } else {
      $technicalData = [
        'status' => false,
        'technicalData' => [],
      ];
    }


    return $technicalData;
  }

  public function getFinancials()
  {
    $envelopeStatus = $this->project->bidEnvelopeStatus->where('vendor_id', Auth::user()->id)->where('envelope', 'financial')->first();
    if ($envelopeStatus && $envelopeStatus->status) {
      foreach ($this->project->financials as $financial) {
        $vendorInitResponse = $financial->financialVendors->where('bidding_id', $this->project->id)->where('vendor_id', Auth::user()->id)->first();

        $vendorResponse = $vendorInitResponse ? $vendorInitResponse->answer : null;
        $price = $vendorInitResponse ? $vendorInitResponse->price : null;
        $fees = $vendorInitResponse ? $vendorInitResponse->other_fees : null;
        $financialData['financialData'][$financial->id] = [
          'id' => $financial->id,
          'inventory_id' => $financial->inventory_id,
          'description' => $financial->description,
          'uom' => $financial->uom,
          'quantity' => $financial->pivot->quantity,
          'price' => $price,
          'other_fees' => $fees,
          'total' => ($price + $fees) * $financial->pivot->quantity,
          'remarks' => $financial->pivot->remarks,
        ];
      }
      $finFiles = $this->project->financialFiles->where('vendor_id', Auth::user()->id);

      $financialData['status'] = true;
      $financialData['attachments'] = $finFiles->isEmpty() ? [] : $finFiles->pluck('file')->toArray();
    } else {
      $financialData = [
        'status' => false,
        'financialData' => [],
      ];
    }


    return $financialData;
  }

  public function openSubmitModal()
  {
    $this->dispatch('openSubmitModal');
  }
  public function closeSubmitModal()
  {
    $this->dispatch('closeSubmitModal');
  }

  public function submitBid()
  {
    sleep(2);
    // check if all status is complete
    $allEnvelopesStatus = $this->project->bidEnvelopeStatus->where('vendor_id', Auth::user()->id)->pluck('status')->all();
    if (in_array(0, $allEnvelopesStatus, true) || in_array('0', $allEnvelopesStatus, true)) {
      abort(403, 'Unprocessable Entity');
    }
    $statusExists = $this->getStatus()->where('bidding_id', $this->project->id)->first();
    if ($statusExists) {
      $statusExists->update([
        'vendor_id' => Auth::user()->id,
        'complete' => true,
        'submission_date' => now(),
      ]);
    }

    $approverEmails = Role::whereIn('id', [3, 4])->with('users')->get()->pluck('users.*.email')->flatten()->toArray();

    if ($approverEmails) {
      Mail::to($approverEmails)->send(new AdminCompleteVendor($this->project, Auth::user()));
    }

    Mail::to(Auth::user()->email)->send(new VendorSbumitBid($this->project, Auth::user()));
    $this->dispatch('closeSubmitModal');
    // $this->dispatch('success-message', ['message' => 'You successfully submit ' . $this->project->title . ' bid.']);
    // return redirect()->route('bid-lists.summary-and-submission', $this->project->id)
    //   ->with('success', 'You successfully submit ' . $this->project->title . ' bid.');
    // $this->vendorStatus = $this->getStatus()->where('bidding_id', $this->project->id)->first();
    $this->enableSubmit = false;
    $this->dispatch('updateVendorStatus');
    $this->dispatch('success-message', ['message' => 'You successfully submit ' . $this->project->title . ' bid.']);
  }

  //  View attachment modal
  public function viewFile($file, $envelope)
  {
    $folder = 'vendor-file\\' . $envelope;
    $this->fileAttachment = route('view-file', ['file' => $file, 'folder' => $folder]);
    $this->fileName = $file;
    $this->dispatch('openFileModal');
  }

  public function closeFileModal()
  {
    $this->dispatch('closeFileModal');
  }
  public function render()
  {
    return view('livewire.envelopes.summary-view');
  }
}
