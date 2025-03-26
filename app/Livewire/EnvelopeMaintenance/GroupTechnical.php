<?php

namespace App\Livewire\EnvelopeMaintenance;

use Livewire\Component;
use App\Helpers\SearchModel;
use App\Models\TechnicalGroup;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class GroupTechnical extends Component
{
  use WithPagination;
  protected $groupLists;
  public $group, $groupName, $groupDescription;
  public $groupEditName, $groupEditDescription;
  public $search, $orderBy = 'name', $sort = 'desc';
  protected $listeners = ['UpdateGroupMaintenance'];
  public function getGroups()
  {
    return TechnicalGroup::withCount('technicals')->orderBy($this->orderBy, $this->sort);
  }
  public function updatedSearch($search)
  {
    $this->resetPage();
    $fields = [
      'name',
      'description',
    ];

    $model = $this->getGroups();
    if ($search) {
      $this->groupLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->groupLists = $model;
    }
  }
  public function UpdateGroupMaintenance()
  {
    $this->groupLists = $this->getGroups();
  }
  public function selectedFilters($params)
  {
    if ($this->orderBy == $params) {
      $this->sort = $this->sort == 'asc' ? 'desc' : 'asc';
    } else {
      $this->orderBy = $params;
      $this->sort = 'desc';
    }
  }

  public function createModal()
  {
    $this->dispatch('openModal');
    $this->groupName = '';
    $this->groupDescription = '';
    $this->resetValidation();
  }

  public function createModalClose()
  {
    $this->dispatch('closeModal');
  }


  public function editModal($id)
  {
    $this->dispatch('openEditModal');
    $this->group = $this->getGroups()->where('id', $id)->first();
    $this->groupEditName = $this->group->name;
    $this->groupEditDescription = $this->group->description;
    $this->resetValidation();
  }

  public function editModalClose()
  {
    $this->dispatch('closeEditModal');
  }

  public function createGroup()
  {
    $this->validate([
      'groupName' => 'required',
      'groupDescription' => 'required',
    ]);

    $data = [
      'name' => $this->groupName,
      'description' => $this->groupDescription,
      'crtd_user' => Auth::user()->id,
    ];

    TechnicalGroup::create($data);

    // return redirect()
    //   ->route('technical-envelope')
    //   ->with('success', 'Technical Group Create Successfully!')
    //   ->with('activeTab', 'technical-group');
    $this->dispatch('closeModal');
    $this->dispatch('success-message', ['message' => 'Technical Group Create Successfully!']);
    $this->dispatch('activeTab', ['message' => 'technical-group']);

  }

  public function editGroup()
  {
    $this->validate([
      'groupEditName' => 'required',
      'groupEditDescription' => 'required',
    ], [
      'groupEditName.required' => 'The group name field is required.',
      'groupEditDescription.required' => 'The group description field is required.',
    ]);

    $data = [
      'name' => $this->groupEditName,
      'description' => $this->groupEditDescription,
      'upd_user' => Auth::user()->id,
    ];

    $this->group->update($data);

    // return redirect()
    //   ->route('technical-envelope')
    //   ->with('success', 'Technical Group Update Successfully!')
    //   ->with('activeTab', 'technical-group');
      $this->dispatch('closeEditModal');
      $this->dispatch('success-message', ['message' => 'Technical Group Update Successfully!']);
      $this->dispatch('activeTab', ['message' => 'technical-group']);

  }


  public function render()
  {
    if (!$this->groupLists) {
      $this->groupLists = $this->getGroups();
    }
    return view('livewire.envelope-maintenance.group-technical', [
      'groups' => $this->groupLists->paginate(10, ['*'], 'group')
    ]);
  }
}
