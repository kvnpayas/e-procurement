<?php

namespace App\Livewire\EnvelopeMaintenance;

use Livewire\Component;
use App\Models\Technical;
use App\Models\TechnicalOption;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class TechnicalQuestion extends Component
{
  use WithFileUploads;
  protected $listeners = ['questionData', 'questionEditData'];
  public $technical;
  public $selectedQuestionType, $questionType;
  public $question, $technicalQuestion, $remarks, $attachment, $fileSwitch = false, $fileExist = false;
  public $numericFrom, $numericTo, $numericFromPer, $numericToPer, $option, $options = [], $weights = [];

  // edit question

  public function questionData($id)
  {
    $this->technical = Technical::find($id);
    // dd($this->technical);
    $this->resetValidation();

    $this->question = null;
    $this->remarks = null;
    $this->selectedQuestionType = null;
    $this->questionType = null;
    $this->numericFrom = null;
    $this->numericTo = null;

    $this->dispatch('openAddModal');
  }

  public function closeAddModal()
  {
    $this->dispatch('closeAddModal');
  }

  public function updatedSelectedQuestionType()
  {
    $this->resetValidation();
    $this->questionType = $this->selectedQuestionType;
  }

  public function addOption()
  {
    $this->validate([
      'option' => 'required',
    ]);

    $this->options[] = ['id' => null, 'name' => $this->option, 'score' => 100];
    $this->option = null;
  }
  public function removeOption($key)
  {
    unset($this->options[$key]);
    $this->options = array_values($this->options);

  }

  public function questionForm()
  {
    $this->validate([
      'question' => 'required',
      'attachment' => $this->fileSwitch ? 'required|file|mimes:pdf' : '',
      'selectedQuestionType' => 'required',
      // Numeric and Numeric Percentage question type
      'numericFrom' => $this->selectedQuestionType == 'numeric' || $this->selectedQuestionType == 'numeric-percent' ? 'required|gte:0|numeric' : '',
      'numericTo' => ($this->selectedQuestionType == 'numeric' && $this->numericTo) ? 'numeric|gte:' . $this->numericFrom : ($this->selectedQuestionType == 'numeric-percent'
        ? 'required|numeric|lte:100|gte:' . $this->numericFrom
        : ''),
      // Single and multi option question type
      'options' => $this->selectedQuestionType == 'single-option' || $this->selectedQuestionType == 'multi-option' ? 'required' : '',
      'options.*.score' => $this->selectedQuestionType == 'single-option' || $this->selectedQuestionType == 'multi-option' ? 'required|lte:100|gte:0' : '',
    ], [
      'selectedQuestionType.required' => 'You have to select question type.',
      'numericFrom.required' => 'The FROM field is required.',
      'numericFrom.gte' => 'The FROM field ust be greater than or equal to 0.',
      'numericTo.gte' => 'The TO field must be greater than or equal to FROM input.',
      'numericFromPer.required' => 'The FROM field is required.',
      'numericFromPer.gte' => 'The FROM field must be greater than or equal to 0.',
      'numericTo.required' => 'The TO field is required.',
      'numericTo.lte' => 'The TO field must be less than or equal to 100.',
      'options.required' => 'Options is required.',
      'options.*.score.required' => 'Weight field is required.',
      'options.*.score.lte' => 'The weight field must be less than or equal to 100.',
      'options.*.score.gte' => 'The weight field must be greater than or equal to 0.',
    ]);

    // File Attachment
    if ($this->fileSwitch) {
      $attachName = str_replace(' ', '_', $this->technical->name) . '_' . time() . '.' . $this->attachment->extension();
      $this->attachment->storeAs('envelope_maintenance/technical', $attachName, 'public');
    } else {
      if ($this->fileExist) {
        $attachName = $this->technical->attachment;
      } else {
        $attachName = NULL;
      }
    }
    // End File Attachment

    $data = [
      'question' => $this->question,
      'remarks' => $this->remarks,
      'question_type' => $this->selectedQuestionType,
      'from' => $this->numericFrom ? $this->numericFrom : null,
      'to' => $this->numericTo ? $this->numericTo : null,
      'status' => 'Active',
      'attachment' => $attachName,
      'upd_user' => Auth::user()->id,
    ];

    $this->technical->update($data);

    if ($this->selectedQuestionType == 'single-option' || $this->selectedQuestionType == 'multi-option') {

      $deletedeIds = array_diff($this->technical->options->pluck('id')->toArray(), collect($this->options)->pluck('id')->toArray());

      $this->technical->options()->whereIn('id', $deletedeIds)->delete();

      foreach ($this->options as $key => $dataOption) {
        $technicalOption = TechnicalOption::find($dataOption['id']);
        if ($technicalOption) {
          $dataOption['upd_user'] = Auth::user()->id;
          $technicalOption->update($dataOption);
        } else {
          $dataOption['crtd_user'] = Auth::user()->id;
          $dataOption['technical_id'] = $this->technical->id;
          unset($dataOption['id']);
          TechnicalOption::create($dataOption);
        }
      }
    }

    // return redirect()
    //   ->route('technical-envelope')
    //   ->with('success', 'Question has been updated!');
    $this->dispatch('closeAddModal');
    $this->dispatch('updateTechnicalMaintenance');
    $this->dispatch('success-message', ['message' => 'Question has been updated!']);
  }

  public function questionEditData($id)
  {
    $this->technical = Technical::find($id);
    $this->resetValidation();
    $this->options = [];

    if ($this->technical->question) {
      $this->question = $this->technical->question;
      $this->remarks = $this->technical->remarks;
      $this->selectedQuestionType = $this->technical->question_type;
      $this->questionType = $this->selectedQuestionType;
      $this->fileExist = $this->technical->attachment ? true : false;

      if ($this->technical->question_type == 'numeric' || $this->technical->question_type == 'numeric-percent') {
        $this->numericFrom = $this->technical->from;
        $this->numericTo = $this->technical->to;
      } elseif ($this->technical->question_type == 'single-option' || $this->technical->question_type == 'multi-option') {
        foreach ($this->technical->options as $option) {
          $this->options[] = [
            'id' => $option->id,
            'name' => $option->name,
            'score' => $option->score,
          ];
        }
      }
    }
    $this->dispatch('openAddModal');
  }
  public function questionEditClose()
  {
    $this->dispatch('closeEditQuestionModal');
  }
  public function changeAttach()
  {
    $this->fileExist = false;
  }
  public function render()
  {
    return view('livewire.envelope-maintenance.technical-question');
  }
}
