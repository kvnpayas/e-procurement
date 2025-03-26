<?php

namespace App\Livewire\Admin\Bidding\Envelope;

use Livewire\Component;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\Auth;

class TechnicalEnvelope extends Component
{
  use WithPagination;
  protected $technicalLists;
  public $envelopeRemarks, $envelopeRemarksInput, $technicalName, $technicalDesc, $technicalId, $remarks;
  public $projectbid, $groups, $search, $orderBy = 'id', $sort = 'asc', $editButton = true, $customWeight = [], $totalWeight, $technicalWeight, $alertMessage, $technicalRemarksVal;
  protected $listeners = ['updateBidTechnicals'];
  public $initTechnicals;

  public function mount($id)
  {
    $this->projectbid = ProjectBidding::findOrFail($id);
    $this->technicalWeight = $this->projectbid->weights()->where('envelope', 'technical')->first()->weight;
    $this->envelopeRemarks = $this->projectbid->envelopeRemarks->where('envelope', 'technical')->first();
    $this->envelopeRemarksInput = $this->envelopeRemarks ? $this->envelopeRemarks->remarks : '';

  }
  public function getProjectTechnicals()
  {
    return $this->projectbid->technicals();
  }

  public function updatedSearch($search)
  {

    $this->resetPage();
    $fields = [
      'name',
      'description',
      'question',
      'question_type',
      'technicals.id',
    ];

    $model = $this->getProjectTechnicals();
    if ($search) {
      $this->technicalLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->technicalLists = $model;
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
  public function editWeight()
  {

    $techncialCount = $this->getProjectTechnicals()->get()->count();
    foreach ($this->projectbid->technicals as $technical) {
      $this->customWeight[$technical->id] = number_format($technical->pivot->weight, 2);
    }
    $this->totalWeight = array_sum($this->customWeight);
    $this->editButton = false;

  }
  public function saveWeight()
  {

    $this->totalWeight = number_format(array_sum($this->customWeight));

    $this->validate([
      'customWeight.*' => 'required|numeric',
      'totalWeight' => 'in:' . $this->technicalWeight,
    ], [
      'customWeight.*.required' => 'Weight field is required ',
      'customWeight.*.numeric' => 'Weight field must be a number ',
      'totalWeight.in' => 'Total must be equal to technical weight percentage(' . $this->technicalWeight . '%)',
    ]);

    foreach ($this->customWeight as $id => $weight) {
      $this->projectbid->technicals()->updateExistingPivot($id, ['weight' => $weight]);

    }
    $this->editButton = true;

    $this->alertMessage = 'Weight Percentage saved.';
    $this->dispatch('alert-technical');

  }

  public function updatedCustomWeight($value, $field)
  {
    $this->totalWeight = array_sum($this->customWeight);

    if ($this->customWeight[$field] == '') {
      $this->addError('customWeight.' . $field, 'Weight field is required');
    } else {
      $this->resetValidation('customWeight.' . $field);
    }
    if (number_format($this->totalWeight) != $this->technicalWeight) {
      $this->addError('totalWeight', 'Total must be equal to technical weight percentage(' . $this->technicalWeight . '%)');
    } else {
      $this->resetValidation('totalWeight');
    }
  }

  // Open Technical Modal Emit
  public function openTechnicalModalLists($id)
  {
    $this->initTechnicals = $this->getProjectTechnicals()->get();
    $this->dispatch('openTechnicalModalLists', $id);
  }

  // Update Bid Technicals when add
  public function updateBidTechnicals()
  {
    $this->technicalLists = $this->getProjectTechnicals();
    
    $initArray = $this->initTechnicals->pluck('id')->toArray();
    $technicalArray = $this->technicalLists->get()->pluck('id')->toArray();
    $first = array_diff($technicalArray, $initArray);
    $second = array_diff($initArray, $technicalArray);
    if(count($first) != 0 || count($second) != 0){
      $vendors = $this->projectbid->vendors()->wherePivot('status', 'On Hold')->get();
      foreach($vendors as $vendor){
        $envelopeStatus = $vendor->envelopeStatus->where('bidding_id', $this->projectbid->id)->where('envelope', 'technical')->first();
        $bidStatus = $vendor->bidStatus->where('bidding_id', $this->projectbid->id)->first();

        $envelopeStatus->update(['status' => false]);
        $bidStatus->update(['complete' => false]);
      }
    }
  
  }

  // remarks for every technicals
  public function remarksModal($id)
  {

    $technicalRemarks = $this->getProjectTechnicals()->find($id);
    $this->technicalId = $technicalRemarks->id;
    $this->technicalDesc = $technicalRemarks->description;
    $this->technicalName = $technicalRemarks->name;
    $this->technicalRemarksVal = $technicalRemarks->pivot->remarks;

    $this->remarks = $technicalRemarks->pivot->remarks ? $technicalRemarks->pivot->remarks : '';

    $this->dispatch('openRemarksModal');
  }

  public function closeRemarksModal()
  {
    $this->dispatch('closeRemarksModal');
  }
  public function saveRemarks()
  {
    $technicalRemarks = $this->getProjectTechnicals()->find($this->technicalId);
    $technicalRemarks->pivot->remarks = $this->remarks;
    $technicalRemarks->pivot->save();
    $this->dispatch('closeRemarksModal');
    $this->dispatch('success-message', ['message' => 'Technical remarks saved!']);
  }

  // Remakrs for Financial Envelope
  public function remarksModalBid()
  {
    $this->dispatch('openTechnicalRemarksModal');
  }
  public function closeTechnicalRemarksModal()
  {
    $this->dispatch('closeTechnicalRemarksModal');
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
        'envelope' => 'technical',
        'remarks' => $this->envelopeRemarksInput,
        'crtd_user' => Auth::user()->id
      ]);
    }

    $this->envelopeRemarks = $this->projectbid->envelopeRemarks->where('envelope', 'technical')->first();
    $this->envelopeRemarksInput = $this->envelopeRemarks ? $this->envelopeRemarks->remarks : '';

    $this->dispatch('closeTechnicalRemarksModal');
    $this->dispatch('success-message', ['message' => 'Technical envelope remarks saved!']);
  }

  public function render()
  {
    if (!$this->technicalLists) {
      $this->technicalLists = $this->getProjectTechnicals();
    }
    return view('livewire.admin.bidding.envelope.technical-envelope', [
      'technicals' => $this->technicalLists->paginate(10)
    ]);
  }
}
