<?php

namespace App\Livewire\Admin\Bidding\Envelope;

use Livewire\Component;
use App\Models\Technical;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use App\Models\ProjectBidding;
use App\Models\TechnicalGroup;
use Illuminate\Support\Facades\Auth;

class TechnicalLists extends Component
{
  use WithPagination;
  public $groups = [], $bidding, $selectedGroup, $bidingTechnicals, $checkTechnicals = [], $search, $clickOrder;
  protected $technicalLists;
  protected $listeners = ['openTechnicalModalLists'];
  public $currentSearch;

  public function openTechnicalModalLists($id)
  {
    $this->bidding = ProjectBidding::findOrFail($id);
    $this->groups = $this->getTechnicalGroups()->get();
    $this->bidingTechnicals = $this->bidding->technicals;
    $technicalIds = $this->bidingTechnicals->pluck('id')->toArray();
    foreach ($technicalIds as $id) {
      $this->checkTechnicals[$id] = true;
    }

    $this->dispatch('openTechnicalModal');

  }

  public function getTechnicals()
  {
    if ($this->selectedGroup) {
      $groups = $this->getTechnicalGroups()->where('id', $this->selectedGroup)->first()->technicals();
      return $groups->where('status', 'Active');
    } else {
      return Technical::where('status', 'Active');
    }
  }
  public function getTechnicalGroups()
  {
    return new TechnicalGroup;
  }

  public function updatedSearch($search)
  {
    $this->currentSearch = $search;
    $this->resetPage();
    $fields = [
      'name',
      'description',
      'question',
      'question_type',
      'technicals.id',
    ];

    $model = $this->getTechnicals();
    if ($search) {
      $this->technicalLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->technicalLists = $model;
    }
  }

  public function updatedSelectedGroup()
  {
    $this->checkTechnicals = [];
    if ($this->selectedGroup) {
      $this->checkTechnicals = [];
      $groups = $this->getTechnicalGroups()->where('id', $this->selectedGroup)->first()->technicals();
      foreach ($groups->where('status', 'Active')->get() as $id) {
        $this->checkTechnicals[$id->id] = true;
      }
    } else {
      $technicalIds = $this->bidingTechnicals->pluck('id')->toArray();
      foreach ($technicalIds as $id) {
        $this->checkTechnicals[$id] = true;
      }
    }

    $this->applySearch();

  }

  public function updatedCheckTechnicals($value, $key)
  {
    if (!is_array($this->clickOrder)) {
      $this->clickOrder = [];
    }

    if ($value) {
      $this->clickOrder[] = $key;
    } else {
      $this->clickOrder = array_diff($this->clickOrder, [$key]);
    }

    $this->applySearch();
  }

  public function selectedTechnicals()
  {
    $checkItems = array_filter($this->checkTechnicals, function ($value) {
      return $value === true;
    });
    // dd($this->checkEligibilities);
    // return array_keys($checkItems);

    // Ensure clickOrder is an array
    if (!is_array($this->clickOrder)) {
      $this->clickOrder = [];
    }

    // Merge initial data with clickOrder to preserve initial data
    $initialKeys = array_keys($this->checkTechnicals);
    $mergedKeys = array_unique(array_merge($this->clickOrder, $initialKeys));

    // Get sorted keys based on merged keys and checked items
    $sortedKeys = array_intersect($mergedKeys, array_keys($checkItems));

    // Create sorted technicals array
    $sortedTechnicals = array_fill_keys($sortedKeys, true);

    return array_keys($sortedTechnicals);
  }

  public function addTechnicals()
  {
    $ids = $this->selectedTechnicals();

    // Compute weight percentage per item
    $technicalweight = $this->bidding->weights()->where('envelope', 'technical')->first()->weight;
    $technicalCount = count($ids);
    $weight = $technicalCount ? $technicalweight / $technicalCount : 0;

    // Prepare the data for sync
    $syncData = [];
    foreach ($ids as $id) {
      $syncData[$id] = [
        'crtd_user' => Auth::user()->id,
        // 'upd_user' => Auth::user()->id
        'weight' => $weight
      ];
    }

    // Sync technicals to pivot table with additional attributes
    $this->bidding->technicals()->sync($syncData);
    // Update the upd_user field for existing records
    foreach ($ids as $id) {
      $dataExists = $this->bidding->technicals()->where('technical_id', $id)->first();
      if ($dataExists) {
        $this->bidding->technicals()->updateExistingPivot($id, ['upd_user' => Auth::user()->id, 'weight' => $weight]);
      }
    }
    $this->bidding->load('technicals');

    $this->search = "";
    $this->currentSearch = "";

    $this->dispatch('updateBidTechnicals');
    $this->dispatch('closeTechnicalModal');
    $this->dispatch('success-message', ['message' => 'Technical Envelope has been successfully updated!']);
    // return redirect()
    //   ->route('project-bidding.technical-envelopes', $this->bidding->id)
    //   ->with('success', 'Technical Envelope has been successfully updated!');
  }

  // close add modal
  public function closeTechnicalModal()
  {
    $this->dispatch('closeTechnicalModal');
  }

  private function applySearch()
  {
    if ($this->currentSearch) {
      $fields = [
        'name',
        'description',
        'question',
        'question_type',
        'technicals.id',
      ];

      $model = $this->getTechnicals();
      $this->technicalLists = SearchModel::search($model, $fields, $this->currentSearch);
    } else {
      $this->technicalLists = $this->getTechnicals();
    }
  }
  public function render()
  {
    if (!$this->technicalLists) {
      $this->technicalLists = $this->getTechnicals();
    }
    return view('livewire.admin.bidding.envelope.technical-lists', [
      'technicals' => $this->technicalLists->paginate(10, ['*'], 'technicalLists')
    ]);
  }
}
