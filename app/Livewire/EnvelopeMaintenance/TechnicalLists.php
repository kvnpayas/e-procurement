<?php

namespace App\Livewire\EnvelopeMaintenance;

use Livewire\Component;
use App\Models\Technical;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use App\Models\TechnicalGroup;

class TechnicalLists extends Component
{
  use WithPagination;

  protected $listeners = ['technicalsModal'], $technicalLists;
  public $group, $groupId, $groupTechnicals, $checkTechnicals = [], $showSelected = false, $search, $sample;

  public function technicalsModal($id)
  {
    $this->checkTechnicals = [];
    $this->resetPage();
    $this->groupId = $id;
    $this->group = TechnicalGroup::find($id);
    $this->groupTechnicals = $this->group->technicals;
    $groupTechnicalsIds = $this->groupTechnicals->pluck('id')->toArray();
    foreach ($this->getTechnicals()->get() as $item) {
      if (in_array($item->id, $groupTechnicalsIds)) {
        $this->checkTechnicals[$item->id] = true;
      } else {
        $this->checkTechnicals[$item->id] = null;
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
  public function getTechnicals()
  {

    if ($this->showSelected) {
      return TechnicalGroup::find($this->groupId)->technicals()->where('status', 'Active');
    }

    if ($this->search) {
      $this->resetPage();
      $fields = [
        'id',
        'name',
        'description',
      ];

      $model = Technical::where('status', 'Active');

      return SearchModel::search($model, $fields, $this->search);

    } else {
      return Technical::where('status', 'Active');
    }
  }

  public function selectedFinancials()
  {
    $checkItems = array_filter($this->checkTechnicals, function ($value) {
      return $value === true;
    });
    // dd($this->checkEligibilities);
    return array_keys($checkItems);
  }
  public function addTechnicals()
  {
    $ids = $this->selectedFinancials();

    $this->group->technicals()->detach();
    $this->group->technicals()->attach($ids);

    // return redirect()
    //   ->route('technical-envelope')
    //   ->with('success', 'Technicals Successfully Added!')
    //   ->with('activeTab', 'technical-group');
    $this->dispatch('closeAddModal');
    $this->dispatch('UpdateGroupMaintenance');
    $this->dispatch('success-message', ['message' => 'Technicals Successfully Added!']);
    $this->dispatch('activeTab', ['message' => 'technical-group']);
  }
  public function render()
  {
    $this->technicalLists = $this->getTechnicals();
    return view('livewire.envelope-maintenance.technical-lists', [
      'technicals' => $this->technicalLists->paginate(10)
    ]);
  }
}
