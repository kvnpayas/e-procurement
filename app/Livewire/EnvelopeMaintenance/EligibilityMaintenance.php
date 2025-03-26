<?php

namespace App\Livewire\EnvelopeMaintenance;

use Livewire\Component;
use App\Models\Eligibility;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class EligibilityMaintenance extends Component
{
  use WithPagination;

  protected $eligibilityLists;
  public $eligibilityName, $eligibilityDescription;
  public $eligibility, $editName, $editDescription, $editStatus, $switchStatus;
  public $search, $orderBy = 'name', $sort = 'asc';
  public $alertMessage;
  protected $listeners = ['tooltipEdit'];

  public function mount()
  {
    $this->eligibilityLists = $this->getEligibilities();
  }
  public function getEligibilities()
  {
    return Eligibility::withCount('details')->orderBy($this->orderBy, $this->sort);
  }
  public function createModal()
  {
    $this->dispatch('openModal');
    $this->eligibilityName = '';
    $this->eligibilityDescription = '';
    $this->resetValidation();

  }
  public function createModalClose()
  {
    $this->dispatch('closeModal');
  }
  public function editModalClose()
  {
    $this->dispatch('closeEditModal');
  }
  public function editModal($id)
  {
    $this->dispatch('openEditModal');
    $this->resetValidation();
    $this->eligibility = $this->getEligibilities()->where('id', $id)->first();
    $this->editName = $this->eligibility->name;
    $this->editDescription = $this->eligibility->description;
    $this->switchStatus = $this->eligibility->status;
    $this->editStatus = $this->eligibility->status == 'Active' ? true : false;
  }
  public function updatedEditStatus()
  {
    if ($this->eligibility->details->where('status', 'Active')->count() == 0) {
      $this->switchStatus = 'Inactive';
      $this->editStatus = false;
      $this->addError('editStatus', 'Add at least one fields to change the status to Active.');
    } else {
      $this->switchStatus = $this->editStatus ? 'Active' : 'Inactive';
    }
  }
  public function createEligibility()
  {
    $this->validate([
      'eligibilityName' => 'required',
      'eligibilityDescription' => 'required',
    ]);

    $data = [
      'name' => $this->eligibilityName,
      'description' => $this->eligibilityDescription,
      'status' => 'Inactive',
      'crtd_user' => Auth::user()->id,
    ];

    Eligibility::create($data);
    // return redirect()
    //   ->route('eligibility-envelope')
    //   ->with('success', 'Eligibility has been successfully created!');
    $this->dispatch('closeModal');
    $this->dispatch('success-message', ['message' => 'Eligibility has been successfully created!']);
  }

  public function editEligibility()
  {
    $this->validate(
      [
        'editName' => 'required',
        'editDescription' => 'required',
      ],
      [
        'editName.required' => 'The eligibility name field is required.',
        'editDescription.required' => 'The eligibility description field is required.',
      ],
    );

    $data = [
      'name' => $this->editName,
      'description' => $this->editDescription,
      'status' => $this->switchStatus,
      'upd_user' => Auth::user()->id,
    ];
    // dd($this->eligibility->biddings->where('status', 'Active'));
    if ($this->switchStatus == 'Inactive') {
      if (!$this->eligibility->groups->isEmpty()) {
        foreach ($this->eligibility->groups as $group) {
          $this->eligibility->groups()->detach($group->id);
        }
      }
      if (!$this->eligibility->biddings->where('status', 'Active')->isEmpty()) {
        foreach ($this->eligibility->biddings->where('status', 'Active') as $bidding) {
          $this->eligibility->biddings()->detach($bidding->id);
        }
      }
    }
    $this->eligibility->update($data);
    // return redirect()
    //   ->route('eligibility-envelope')
    //   ->with('success', 'Eligibility has been successfully updated!');
    $this->dispatch('closeEditModal');
    $this->dispatch('success-message', ['message' => 'Eligibility has been successfully updated!']);
  }

  public function updatedSearch($search)
  {
    $this->resetPage();
    $fields = [
      'name',
      'description',
      'id',
    ];

    $model = $this->getEligibilities();
    if ($search) {
      $this->eligibilityLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->eligibilityLists = $model;
    }
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
  public function tooltipEdit($id)
  {
    dd($id);
  }
  public function render()
  {
    if (!$this->eligibilityLists) {
      $this->eligibilityLists = $this->getEligibilities();
    }
    return view('livewire.envelope-maintenance.eligibility-maintenance', [
      'eligibilities' => $this->eligibilityLists->paginate(10, ['*'], 'eligibility')
    ]);
  }
}
