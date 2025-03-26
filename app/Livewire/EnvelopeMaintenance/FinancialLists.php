<?php

namespace App\Livewire\EnvelopeMaintenance;

use Livewire\Component;
use App\Models\Financial;
use App\Helpers\SearchModel;
use App\Models\FinancialGroup;
use Livewire\WithPagination;

class FinancialLists extends Component
{
  use WithPagination;

  protected $listeners = ['financialsModal'], $financialLists;
  public $group, $groupId, $groupFinancials, $checkFinancials = [], $showSelected = false, $search, $sample;

  public function financialsModal($id)
  {
    $this->checkFinancials = [];
    $this->resetPage();
    $this->groupId = $id;
    $this->group = FinancialGroup::find($id);
    $this->groupFinancials = $this->group->financials;
    $groupFinancialIds = $this->groupFinancials->pluck('id')->toArray();
    foreach ($this->getFinancials()->get() as $item) {
      if (in_array($item->id, $groupFinancialIds)) {
        $this->checkFinancials[$item->id] = true;
      } else {
        $this->checkFinancials[$item->id] = null;
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
  public function getFinancials()
  {

    if ($this->showSelected) {
      return FinancialGroup::find($this->groupId)->financials();
    }

    if ($this->search) {
      $this->resetPage();
      $fields = [
        'inventory_id',
        'description',
        'class_id',
        'uom',
      ];

      $model = Financial::where('scrap', false);

      return SearchModel::search($model, $fields, $this->search);

    } else {
      return Financial::where('scrap', false);
    }
  }

  public function selectedFinancials()
  {
    $checkItems = array_filter($this->checkFinancials, function ($value) {
      return $value === true;
    });
    // dd($this->checkEligibilities);
    return array_keys($checkItems);
  }
  public function addFinancials()
  {
    $ids = $this->selectedFinancials();

    $this->group->financials()->detach();
    $this->group->financials()->attach($ids);

    // return redirect()
    //   ->route('financial-envelope')
    //   ->with('success', 'Financials Successfully Added!')
    //   ->with('activeTab', 'financial-group');
    $this->dispatch('closeAddModal');
    $this->dispatch('UpdateGroupMaintenance');
    $this->dispatch('success-message', ['message' => 'Financials Successfully Added!']);
    $this->dispatch('activeTab', ['message' => 'financial-group']);
  }

  public function render()
  {
    $this->financialLists = $this->getFinancials();
    return view('livewire.envelope-maintenance.financial-lists', [
      'financials' => $this->financialLists->paginate(10)
    ]);
  }
}
