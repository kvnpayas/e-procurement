<?php

namespace App\Livewire\Envelopes;

use Livewire\Component;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\Auth;

class Envelope extends Component
{
  public $project, $envelopes, $firstEnvelope, $vendorStatus;
  protected $listeners = ['updateVendorStatus'];

  public function mount($projectId)
  {
    $this->project = ProjectBidding::findOrFail($projectId);
    $allEnvelopes = [
      'eligibility' => (bool) $this->project->eligibility,
      'technical' => (bool) $this->project->technical,
      'financial' => (bool) $this->project->financial,
    ];
    $this->firstEnvelope = array_search(true, $allEnvelopes, true);

    $this->envelopes = array_filter($allEnvelopes, function ($value) {
      return $value === true;
    });

    $this->vendorStatus = $this->project->bidVendorStatus->where('vendor_id', Auth::user()->id)->first();
  }
  public function updateVendorStatus()
  {
    $this->vendorStatus = $this->project->bidVendorStatus->where('vendor_id', Auth::user()->id)->first();
  }
  public function render()
  {
    return view('livewire.envelopes.envelope');
  }
}
