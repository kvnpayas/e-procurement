<?php

namespace App\Livewire\Admin\Bidding\Envelope;

use Livewire\Component;
use App\Models\Eligibility;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use App\Models\ProjectBidding;
use App\Models\EligibilityGroup;
use Illuminate\Support\Facades\Auth;

class EligibilityListsModal extends Component
{
  use WithPagination;

  protected $listeners = ['eligibilityModal'];
  protected $eligibilityLists;
  public $projectEligibilities, $projectbid, $checkEligibilities = [], $groups, $selectedGroup, $search, $clickOrder = [];
  public $currentSearch = '';

  public function mount($id)
  {
    $this->projectbid = ProjectBidding::findOrFail($id);
    $this->projectEligibilities = $this->projectbid->eligibilities;
    $eligibilityIds = $this->projectEligibilities->pluck('id')->toArray();
    foreach ($eligibilityIds as $id) {
      $this->checkEligibilities[$id] = true;
    }

    $this->groups = $this->getEligibilityGroups()->get();
  }
  public function getEligibilities()
  {
    if ($this->selectedGroup) {
      $groups = $this->getEligibilityGroups()->where('id', $this->selectedGroup)->first()->eligibilities();
      return $groups->where('status', 'Active');
    } else {
      return Eligibility::where('status', 'Active');
    }
  }

  public function getEligibilityGroups()
  {
    return new EligibilityGroup;
  }


  public function updatedSearch($search)
  {
    $this->currentSearch = $search;
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


  public function updatedSelectedGroup()
  {
    $this->checkEligibilities = [];
    if ($this->selectedGroup) {
      $this->checkEligibilities = [];
      $groups = $this->getEligibilityGroups()->where('id', $this->selectedGroup)->first()->eligibilities();
      foreach ($groups->where('status', 'Active')->get() as $id) {
        $this->checkEligibilities[$id->id] = true;
      }
    } else {
      $eligibilityIds = $this->projectEligibilities->pluck('id')->toArray();
      foreach ($eligibilityIds as $id) {
        $this->checkEligibilities[$id] = true;
      }
    }
    $this->applySearch();

  }

  public function updatedCheckEligibilities($value, $key)
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
  public function selectedEligibilities()
  {
    // Filter checked items
    $checkItems = array_filter($this->checkEligibilities, function ($value) {
      return $value === true;
    });

    // Ensure clickOrder is an array
    if (!is_array($this->clickOrder)) {
      $this->clickOrder = [];
    }

    // Merge initial data with clickOrder to preserve initial data
    $initialKeys = array_keys($this->checkEligibilities);
    $mergedKeys = array_unique(array_merge($this->clickOrder, $initialKeys));

    // Get sorted keys based on merged keys and checked items
    $sortedKeys = array_intersect($mergedKeys, array_keys($checkItems));

    // Create sorted eligibilities array
    $sortedEligibilities = array_fill_keys($sortedKeys, true);

    return array_keys($sortedEligibilities);
  }

  public function addEligibilities()
  {
    $ids = $this->selectedEligibilities();
    // Prepare the data for sync
    $syncData = [];
    foreach ($ids as $id) {
      $syncData[$id] = [
        'crtd_user' => Auth::user()->id,
        // 'upd_user' => Auth::user()->id
      ];
    }
    // Sync eligibilities to pivot table with additional attributes
    $this->projectbid->eligibilities()->sync($syncData);
    // Update the upd_user field for existing records
    foreach ($ids as $id) {
      $dataExists = $this->projectbid->eligibilities()->where('eligibility_id', $id)->first();
      if ($dataExists) {
        $this->projectbid->eligibilities()->updateExistingPivot($id, ['upd_user' => Auth::user()->id]);
      }
    }
    $this->projectbid->load('eligibilities');
    $this->search = "";
    $this->currentSearch = "";
    $this->dispatch('updateBidEligibilities');
    $this->dispatch('closeEligibilityModal');
    $this->dispatch('success-message', ['message' => 'Eligibility Envelope has been successfully updated!']);
    // return redirect()
    //   ->route('project-bidding.eligibility-envelopes', $this->projectbid->id)
    //   ->with('success', 'Eligibility Envelope has been successfully updated!');
  }

  public function eligibilityModal()
  {
    $this->dispatch('openEligibilityModal');
  }

  public function closeEligibilityModal()
  {
    $this->dispatch('closeEligibilityModal');
  }

  private function applySearch()
  {
    if ($this->currentSearch) {
      $fields = [
        'name',
        'description',
        'eligibilities.id',
      ];

      $model = $this->getEligibilities();
      $this->eligibilityLists = SearchModel::search($model, $fields, $this->currentSearch);
    } else {
      $this->eligibilityLists = $this->getEligibilities();
    }
  }
  public function render()
  {
    if (!$this->eligibilityLists) {
      $this->eligibilityLists = $this->getEligibilities();
    }
    return view('livewire.admin.bidding.envelope.eligibility-lists-modal', [
      'eligibilities' => $this->eligibilityLists->paginate(10, ['*'], 'eligibilityLists')
    ]);
  }
}
