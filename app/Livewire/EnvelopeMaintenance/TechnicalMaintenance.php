<?php

namespace App\Livewire\EnvelopeMaintenance;

use Livewire\Component;
use App\Models\Technical;
use App\Helpers\SearchModel;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class TechnicalMaintenance extends Component
{
  use WithPagination;
  protected $technicalsLists;
  public $technicalName, $technicalDescription;
  public $selectedQuestionType, $questionType;
  public $question, $technicalQuestion, $remarks, $attachment, $fileSwitch = false;
  public $numericFrom, $numericTo, $numericFromPer, $numericToPer, $option, $options = [], $weights = [];
  public $technical, $editName, $editDescription, $editStatus, $switchStatus, $viewAttachment;
  public $search, $orderBy = 'id', $sort = 'desc';
  protected $listeners = ['updateTechnicalMaintenance'];

  public function getTechnicals()
  {
    return Technical::orderBy($this->orderBy, $this->sort);
  }

  public function updateTechnicalMaintenance()
  {
    $this->technicalsLists = $this->getTechnicals();
  }
  public function createModal()
  {
    $this->dispatch('openModal');
    $this->technicalName = '';
    $this->technicalDescription = '';
    $this->resetValidation();
  }
  public function editModal($id)
  {
    $this->dispatch('openEditModal');
    $this->resetValidation();
    $this->technical = $this->getTechnicals()->where('id', $id)->first();
    $this->editName = $this->technical->name;
    $this->editDescription = $this->technical->description;
    $this->switchStatus = $this->technical->status;
    $this->editStatus = $this->technical->status == 'Active' ? true : false;
  }

  public function questionModal($id)
  {
    $this->dispatch('questionData', id: $id);

  }
  public function questionEditModal($id)
  {
    $this->dispatch('questionEditData', id: $id);

  }
  public function createModalClose()
  {
    $this->dispatch('closeModal');
  }

  public function editModalClose()
  {
    $this->dispatch('closeEditModal');
  }

  public function updatedEditStatus()
  {
    if (!$this->technical->question) {
      $this->switchStatus = 'Inactive';
      $this->editStatus = false;
      $this->addError('editStatus', 'Add technical question to change the status to Active.');
    } else {
      $this->switchStatus = $this->editStatus ? 'Active' : 'Inactive';
    }
  }


  public function createTechnical()
  {
    $this->validate([
      'technicalName' => 'required',
      'technicalDescription' => 'required',
    ]);

    $data = [
      'name' => $this->technicalName,
      'description' => $this->technicalDescription,
      'status' => 'Inactive',
      'crtd_user' => Auth::user()->id,
    ];

    Technical::create($data);
    // return redirect()
    //   ->route('technical-envelope')
    //   ->with('success', 'Technical has been successfully created!');
    $this->dispatch('closeModal');
    $this->dispatch('success-message', ['message' => 'Technical has been successfully created!']);
  }
  public function updatedSearch($search)
  {
    $this->resetPage();
    $fields = [
      'name',
      'description',
      'id',
    ];

    $model = $this->getTechnicals();
    if ($search) {
      $this->technicalsLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->technicalsLists = $model;
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
  public function editTechnical()
  {
    $this->validate(
      [
        'editName' => 'required',
        'editDescription' => 'required',
      ],
      [
        'editName.required' => 'The technical name field is required.',
        'editDescription.required' => 'The technical description field is required.',
      ],
    );

    $data = [
      'name' => $this->editName,
      'description' => $this->editDescription,
      'status' => $this->switchStatus,
      'upd_user' => Auth::user()->id,
    ];

    if ($this->switchStatus == 'Inactive') {
      if (!$this->technical->groups->isEmpty()) {
        foreach ($this->technical->groups as $group) {
          $this->technical->groups()->detach($group->id);
        }
      }
      if (!$this->technical->biddings->where('status', 'Active')->isEmpty()) {
        foreach ($this->technical->biddings->where('status', 'Active') as $bidding) {
          $this->technical->biddings()->detach($bidding->id);
        }
      }
    }

    $this->technical->update($data);
    // return redirect()
    //   ->route('technical-envelope')
    //   ->with('success', 'Technical has been successfully updated!');
    $this->dispatch('closeEditModal');
    $this->dispatch('success-message', ['message' => 'Technical has been successfully updated!']);
  }

  public function viewFileModal($id)
  {
    $this->viewAttachment = $this->getTechnicals()->where('id', $id)->first()->attachment;
  }

  public function render()
  {
    if (!$this->technicalsLists) {
      $this->technicalsLists = $this->getTechnicals();
    }
    return view('livewire.envelope-maintenance.technical-maintenance', [
      'technicals' => $this->technicalsLists->paginate(10)
    ]);
  }
}
