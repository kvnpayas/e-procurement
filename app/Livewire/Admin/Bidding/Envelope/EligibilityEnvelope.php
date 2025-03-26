<?php

namespace App\Livewire\Admin\Bidding\Envelope;

use Livewire\Component;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\Auth;

class EligibilityEnvelope extends Component
{
  use WithPagination;
  protected $listeners = ['updateBidEligibilities'];
  protected $eligibilityLists;
  public $envelopeRemarks, $envelopeRemarksInput, $eligibilityRemarks, $eligibilityId, $eligibilityDesc, $eligibilityName, $remarks, $eligibilityRemakrsVal;
  public $projectbid, $search, $orderBy = 'created_at', $sort = 'asc';
  public $initEligbilities;

  public function mount($id)
  {
    $this->projectbid = ProjectBidding::findOrFail($id);
    $this->envelopeRemarks = $this->projectbid->envelopeRemarks->where('envelope', 'eligibility')->first();
    $this->envelopeRemarksInput = $this->envelopeRemarks ? $this->envelopeRemarks->remarks : '';

  }

  public function getEligibilities()
  {
    return $this->projectbid->eligibilities();
  }

  public function updatedSearch($search)
  {

    $this->resetPage();
    $fields = [
      'name',
      'description',
      'eligibilities.id',
    ];

    $model = $this->getEligibilities();
    if ($search) {
      $this->eligibilityLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->eligibilityLists = $model;
    }
  }

  // public function selectedFilters($params)
  // {

  //   if ($this->orderBy == $params) {
  //     $this->sort = $this->sort == 'asc' ? 'desc' : 'asc';
  //   } else {
  //     $this->orderBy = $params;
  //     $this->sort = 'desc';
  //   }
  // }

  public function eligibilityModal()
  {
    $this->initEligbilities = $this->getEligibilities()->get();
    $this->dispatch('eligibilityModal');
  }

  public function updateBidEligibilities()
  {
    $this->eligibilityLists = $this->getEligibilities();

    $initArray = $this->initEligbilities->pluck('id')->toArray();
    $eligibilityArray = $this->eligibilityLists->get()->pluck('id')->toArray();
    $first = array_diff($eligibilityArray, $initArray);
    $second = array_diff($initArray, $eligibilityArray);
    if(count($first) != 0 || count($second) != 0){
      $vendors = $this->projectbid->vendors()->wherePivot('status', 'On Hold')->get();
      foreach($vendors as $vendor){
        $envelopeStatus = $vendor->envelopeStatus->where('bidding_id', $this->projectbid->id)->where('envelope', 'eligibility')->first();
        $bidStatus = $vendor->bidStatus->where('bidding_id', $this->projectbid->id)->first();

        $envelopeStatus->update(['status' => false]);
        $bidStatus->update(['complete' => false]);
      }
    }
  }
  // remarks for every eligibilities
  public function remarksModal($id)
  {
    $this->inventoryId = null;
    $this->id = null;
    $this->description = null;
    $this->remarks = null;

    $this->eligibilityRemarks = $this->getEligibilities()->find($id);
    $this->eligibilityId = $this->eligibilityRemarks->id;
    $this->eligibilityDesc = $this->eligibilityRemarks->description;
    $this->eligibilityName = $this->eligibilityRemarks->name;
    $this->eligibilityRemakrsVal = $this->eligibilityRemarks->pivot->remarks;

    $this->remarks = $this->eligibilityRemarks->pivot->remarks ? $this->eligibilityRemarks->pivot->remarks : '';

    $this->dispatch('openRemarksModal');
  }

  public function closeRemarksModal()
  {
    $this->dispatch('closeRemarksModal');
  }
  public function saveRemarks()
  {
    $eligibilityRemarks = $this->getEligibilities()->find($this->eligibilityId);
    $eligibilityRemarks->pivot->remarks = $this->remarks;
    $eligibilityRemarks->pivot->save();
    $this->dispatch('closeRemarksModal');
    $this->dispatch('success-message', ['message' => 'Eligibility remarks saved!']);
  }


  // Remakrs for Financial Envelope
  public function remarksModalBid()
  {
    $this->dispatch('openEligibilityRemarksModal');
  }
  public function closeEligibilityRemarksModal()
  {
    $this->dispatch('closeEligibilityRemarksModal');
  }

  // Save Evnvelope Remarks
  public function saveEnvelopeRemarks()
  {
    if ($this->envelopeRemarks) {
      $this->envelopeRemarks->update([
        'remarks' => $this->envelopeRemarksInput,
        'upd_user' => Auth::user()->id
      ]);
    } else {
      $this->projectbid->envelopeRemarks()->create([
        'envelope' => 'eligibility',
        'remarks' => $this->envelopeRemarksInput,
        'crtd_user' => Auth::user()->id
      ]);
    }

    $this->envelopeRemarks = $this->projectbid->envelopeRemarks->where('envelope', 'eligibility')->first();
    $this->envelopeRemarksInput = $this->envelopeRemarks ? $this->envelopeRemarks->remarks : '';

    $this->dispatch('closeEligibilityRemarksModal');
    $this->dispatch('success-message', ['message' => 'Eligibility envelope remarks saved!']);
  }

  public function render()
  {
    if (!$this->eligibilityLists) {
      $this->eligibilityLists = $this->getEligibilities();
    }
    return view('livewire.admin.bidding.envelope.eligibility-envelope', [
      'eligibilities' => $this->eligibilityLists->paginate(10)
    ]);
  }
}
