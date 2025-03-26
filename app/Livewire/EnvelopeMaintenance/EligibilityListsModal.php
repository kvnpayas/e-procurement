<?php

namespace App\Livewire\EnvelopeMaintenance;

use Livewire\Component;
use App\Models\Eligibility;
use Livewire\Attributes\On;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use App\Models\EligibilityGroup;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EligibilityListsModal extends Component
{
  use WithPagination;
  protected $listeners = ['eligibilitiesModal'];
  protected $eligibilityLists;
  public $group, $groupId, $groupEligibilities, $checkEligibilities = [], $showSelected = false, $search;

  public function eligibilitiesModal($id)
  {
    $this->checkEligibilities = [];
    $this->resetPage();
    $this->groupId = $id;
    $this->group = EligibilityGroup::find($id);
    $this->groupEligibilities = $this->group->eligibilities;
    $groupEligibilityIds = $this->groupEligibilities->pluck('id')->toArray();
    foreach ($this->getEligibilities()->get() as $item) {
      if (in_array($item->id, $groupEligibilityIds)) {
        $this->checkEligibilities[$item->id] = true;
      } else {
        $this->checkEligibilities[$item->id] = null;
      }

    }
    // dd($this->checkEligibilities);
    $this->showSelected = false;
    $this->dispatch('openAddModal');
  }
  public function closeAddModal()
  {
    $this->dispatch('closeAddModal');
  }

  public function getEligibilities()
  {

    if ($this->showSelected) {
      return EligibilityGroup::find($this->groupId)->eligibilities()->where('status', 'Active');
    }

    if ($this->search) {
      $this->resetPage();
      $fields = [
        'id',
        'name',
        'description',
      ];

      $model = Eligibility::where('status', 'Active');

      return SearchModel::search($model, $fields, $this->search);

    } else {
      return Eligibility::where('status', 'Active');
    }
  }


  public function selectedEligibilities()
  {
    $checkItems = array_filter($this->checkEligibilities, function ($value) {
      return $value === true;
    });
    // dd($this->checkEligibilities);
    return array_keys($checkItems);
  }
  public function addEligibilities()
  {
    $ids = $this->selectedEligibilities();

    $this->group->eligibilities()->detach();
    $this->group->eligibilities()->attach($ids);

    // return redirect()
    //   ->route('eligibility-envelope')
    //   ->with('success', 'Eligibilities Successfully Added!')
    //   ->with('activeTab', 'eligibility-group');
    $this->dispatch('closeAddModal');
    $this->dispatch('UpdateGroupMaintenance');
    $this->dispatch('success-message', ['message' => 'Eligibilities Successfully Added!']);
    $this->dispatch('activeTab', ['message' => 'eligibility-group']);
  }

  protected function paginate($items, $perPage = 10, $page = null, $options = [])
  {
    $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    return new LengthAwarePaginator(
      $items->forPage($page, $perPage),
      $items->count(),
      $perPage,
      $page,
      $options
    );
  }
  public function render()
  {
    $this->eligibilityLists = $this->getEligibilities();

    return view('livewire.envelope-maintenance.eligibility-lists-modal', [
      'eligibilities' => $this->eligibilityLists->paginate(10)
    ]);
  }
}
