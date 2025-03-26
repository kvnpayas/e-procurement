<?php

namespace App\Livewire\Admin\Modal;

use Livewire\Component;
use App\Models\ProjectBidding;
use App\Helpers\Reports\FinancialIndividualReport;
use App\Helpers\Reports\TechnicalIndividualReport;
use App\Helpers\Reports\EligibilityIndividualReport;

class VendorReview extends Component
{
  protected $listeners = ['reviewModal', 'closeReviewModalDispatch', 'openReviewModalDispatch'];
  public $selectedVendor, $project, $vendor;
  public $finalEnvelopes;
  public $fileName, $fileAttachment;
  public function reviewModal($project, $vendorId)
  {
    $this->project = ProjectBidding::findOrFail($project);
    // dd($this->project->vendors);
    $this->selectedVendor = $this->project->vendors()->where('users.id', $vendorId)->first();

    $allEnvelopes = [
      'eligibility' => (bool) $this->project->eligibility,
      'technical' => (bool) $this->project->technical,
      'financial' => (bool) $this->project->financial,
    ];
    $firstEnvelope = array_search(true, $allEnvelopes, true);

    $envelopes = array_filter($allEnvelopes, function ($value) {
      return $value === true;
    });

    foreach ($envelopes as $envelope => $value) {
      $weight = $this->project->weights->where('envelope', $envelope)->first();
      $envelopes[$envelope] = $weight ? $weight->weight : null;
    }
    $this->envelopes = $envelopes;

    $this->finalEnvelopes = [];
    $checkPrevEnvelopes = true;
    foreach ($this->envelopes as $envelope => $value) {
      $model = $envelope . 'Result';
      $envelopeModel = $this->project->{$model}->where('vendor_id', $vendorId)->first();
      $envelopeResult = $envelopeModel ? $envelopeModel->result : 0;

      $viewSummary = $checkPrevEnvelopes || $envelopeResult ? true : false;
      $checkPrevEnvelopes = $envelopeResult;

      if ($envelope == 'eligibility' && $viewSummary) {
        $this->finalEnvelopes[$envelope] = EligibilityIndividualReport::generateReport($this->project->id, $vendorId);

        $this->finalEnvelopes[$envelope]['status'] = $this->project->eligibilityResult->where('vendor_id', $vendorId)->first()->result;
      } else if ($envelope == 'technical' && $viewSummary) {
        $this->finalEnvelopes[$envelope] = TechnicalIndividualReport::generateReport($this->project->id, $vendorId);

      } else if ($envelope == 'financial' && $viewSummary) {
        $this->finalEnvelopes[$envelope] = FinancialIndividualReport::generateReport($this->project->id, $vendorId);

      } else {
        $this->finalEnvelopes[$envelope] = false;
      }
    }
    // dd($this->finalEnvelopes);
    $this->dispatch('openReviewVendorModal');
  }

  public function closeReviewVendorModal()
  {
    $this->dispatch('closeReviewVendorModal');
  }
  public function viewFile($file, $envelope)
  {
    $folder = 'vendor-file\\' . $envelope;
    $this->fileAttachment = route('view-file', ['file' => $file, 'folder' => $folder]);
    $this->fileName = $file;
    $this->dispatch('openFileModal');
    $this->dispatch('closeReviewVendorModal');
  }
  public function closeFileModal()
  {
    $this->dispatch('closeFileModal');
  }

  public function getHistoryModal($projectId, $vendorId, $inventoryId)
  {
    $this->dispatch('getHistory', $projectId, $vendorId, $inventoryId);
  }
  public function closeReviewModalDispatch()
  {
    $this->dispatch('closeReviewVendorModal');
  }
  public function openReviewModalDispatch($projectId, $vendorId)
  {
    $this->reviewModal($projectId, $vendorId);
  }
  public function render()
  {
    return view('livewire.admin.modal.vendor-review');
  }
}
