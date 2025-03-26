<?php

namespace App\Livewire\Admin\Bidding;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ProjectBidding;
use App\Models\ProjectBidFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EditProjectBidding extends Component
{
  use WithFileUploads;

  public $projectTitle, $biddingType, $sales, $budget, $projectType, $reservedPrice, $switchReservedPrice, $reflectPrice, $deadlineDate, $extendDeadlineDate, $time, $extendTime,
  $eligibility, $technical, $financial, $technicalWeight, $financialWeight, $totalWeight, $instructionDetails, $attachments, $fileExist,
  $fileSwitch = false, $attachFiles, $attachFile, $scoreMethod;
  public $disabledPrice = false, $disableTechnicalWeight, $disableFinancialWeight;
  public $inputedData;
  public $projectBid, $createUserBid;
  public $fileAttachment, $fileName;

  public function mount($id)
  {
    $this->projectBid = ProjectBidding::find($id);
    // dd($this->projectBid->reserved_price_switch);
    $this->createUserBid = $this->projectBid->created_user->name;
    $this->projectTitle = $this->projectBid->title;
    $this->biddingType = $this->projectBid->type;
    $this->sales = (bool) $this->projectBid->scrap;
    $this->budget = $this->projectBid->budget_id;
    $this->projectType = $this->projectBid->icss_project_id;
    $this->reservedPrice = $this->projectBid->reserved_price;
    $this->switchReservedPrice = (bool) $this->projectBid->reserved_price_switch;
    $this->reflectPrice = (bool) $this->projectBid->reflect_price;

    $arrayDeadline = explode(' ', $this->projectBid->deadline_date);
    $this->deadlineDate = $arrayDeadline[0];
    $this->time = $arrayDeadline[1];
    if ($this->projectBid->extend_date) {
      $arrayExtendDeadline = explode(' ', $this->projectBid->extend_date);
      $this->extendDeadlineDate = $arrayExtendDeadline[0];
      $this->extendTime = $arrayExtendDeadline[1];
    }

    $this->scoreMethod = $this->projectBid->score_method == 'Rating' ? true : false;
    $this->eligibility = (bool) $this->projectBid->eligibility;
    $this->technical = (bool) $this->projectBid->technical;
    $this->financial = (bool) $this->projectBid->financial;

    $this->disableTechnicalWeight = !$this->technical;
    $this->disableFinancialWeight = !$this->financial;

    // Technical and Financial Weight
    $techWeight = $this->projectBid->weights()->where('envelope', 'technical')->first() ? $this->projectBid->weights()->where('envelope', 'technical')->first()->weight : null;
    $finWeight = $this->projectBid->weights()->where('envelope', 'financial')->first() ? $this->projectBid->weights()->where('envelope', 'financial')->first()->weight : null;
    $this->technicalWeight = $techWeight;
    $this->financialWeight = $finWeight;

    if ($this->technical || $this->financial) {
      $this->totalWeight = $this->technicalWeight + $this->financialWeight;
    } else {
      $this->totalWeight = 'N/A';
    }

    $this->instructionDetails = $this->projectBid->instruction_details;
    $this->attachFiles = $this->projectBid->projectBidFiles;
    // if (!$hasFiles->isEmpty()) {
    //   foreach ($hasFiles as $file) {
    //     $this->attachFiles[] = $file->file_name;
    //   }
    // }
    // $this->attachFile = $this->projectBid->attachment ? route('view-file', ['file' => $this->projectBid->attachment, 'folder' => 'project_bid']) : '';
    // dd(route('view-file', ['file' => $this->projectBid->attachment]));

  }

  public function checksWeight()
  {
    if ($this->technical || $this->financial) {
      if ($this->technical && $this->financial) {
        $this->technicalWeight = 50;
        $this->financialWeight = 50;
      } else {
        $this->financialWeight = $this->financial ? 100 : null;
        $this->technicalWeight = $this->technical ? 100 : null;
      }
      $this->totalWeight = $this->technicalWeight + $this->financialWeight;

    } else {
      $this->financialWeight = null;
      $this->technicalWeight = null;

      $this->totalWeight = 'N/A';
    }

    $this->validateWeight($this->totalWeight);
  }

  // Function to validate envelopes weight
  public function validateWeight($total)
  {
    // $this->totalWeight = $this->technicalWeight + $this->financialWeight;
    if ($total != 'N/A') {
      if ($total != 100) {
        $this->addError('totalWeight', 'The total weight must be equal to 100.');
      } else {
        $this->resetValidation('totalWeight');
      }
    } else {
      $this->resetValidation('totalWeight');
    }

  }

  // Reserved Technical Weight
  public function updatedTechnicalWeight()
  {
    if ($this->technicalWeight == null) {
      $this->technicalWeight = 0;
    }
    $this->totalWeight = $this->technicalWeight + $this->financialWeight;

    $this->validateWeight($this->totalWeight);
  }
  // Reserved Financial Weight
  public function updatedFinancialWeight()
  {
    if ($this->financialWeight == null) {
      $this->financialWeight = 0;
    }
    $this->totalWeight = $this->technicalWeight + $this->financialWeight;

    $this->validateWeight($this->totalWeight);
  }

  public function updatedTechnical()
  {
    $this->disableTechnicalWeight = !$this->technical;
    $this->sales = false;
    $this->scoreMethod = $this->technical;
    $this->checksWeight();

  }
  public function updatedFinancial()
  {
    $this->disableFinancialWeight = !$this->financial;

    $this->checksWeight();

  }
  public function updatedScoreMethod()
  {
    if (!$this->technical) {
      $this->scoreMethod = false;
    }
  }
  public function deleteFile($id)
  {
    $file = $this->projectBid->projectBidFiles->where('id', $id)->first();
    if (file_exists(storage_path('app/public/project_bid/' . $file->file_name))) {
      unlink(storage_path('app/public/project_bid/' . $file->file_name));
    }
    $file->delete();

    $this->projectBid->load('projectBidFiles');
    $this->attachFiles = $this->projectBid->projectBidFiles;

    $this->dispatch('closeConfirmationModal');
    $this->dispatch('success-message', ['message' => 'Attachments successfully deleted.']);
  }

  public function createForm()
  {
    // dd($this->attachments);
    $this->validate([
      'projectTitle' => 'required',
      'biddingType' => 'required',
      'reservedPrice' => $this->switchReservedPrice ? 'required' : '',
      // 'deadlineDate' => $this->extendDeadlineDate ? '' : 'required|date|after:' . date('Y-m-d'),
      // 'extendDeadlineDate' => $this->extendDeadlineDate ? 'required|date|after:' . date('Y-m-d') : '',
      'deadlineDate' => $this->extendDeadlineDate ? '' : 'required|date',
      'extendDeadlineDate' => $this->extendDeadlineDate ? 'required|date' : '',
      'time' => 'required',
      'totalWeight' => $this->technical || $this->financial ? 'required|in:100' : '',
      'attachments.*' => $this->fileSwitch ? 'required|file|mimes:pdf' : '',
      'attachments' => $this->fileSwitch ? 'required' : '',
    ], [
      'attachments.*.mimes' => 'The attachments field must be a file of type: pdf.',
      'deadlineDate.after' => 'The deadline date field must be a date tommorow onwards.',
      'totalWeight.in' => 'The total weight must be equal to 100.'
    ]);

    // check extend count
    $extend_count = $this->projectBid->extend_count ? $this->projectBid->extend_count : 0;

    $this->inputedData = [
      'budget_id' => $this->budget,
      'icss_project_id' => $this->projectType,
      'title' => $this->projectTitle,
      'status' => $this->projectBid->status == 'On Hold' ? 'On Hold' : 'Active',
      'type' => $this->biddingType,
      'instruction_details' => $this->instructionDetails,
      // 'attachment' => $attach,
      'eligibility' => $this->eligibility,
      'technical' => $this->technical,
      'financial' => $this->financial,
      'deadline_date' => $this->deadlineDate . ' ' . $this->time,
      'extend_date' => $this->extendDeadlineDate ? $this->extendDeadlineDate . ' ' . $this->extendTime : null,
      'reserved_price' => $this->switchReservedPrice ? $this->reservedPrice : '',
      'reflect_price' => $this->reflectPrice,
      'reserved_price_switch' => $this->switchReservedPrice,
      'scrap' => $this->sales,
      'extend_count' => $this->extendDeadlineDate ? $extend_count + 1 : $extend_count,
      'score_method' => $this->scoreMethod ? 'Rating' : 'Cost',
      'upd_user' => Auth::user()->id,
    ];

    $this->dispatch('openConfirmModal');
    // ProjectBidding::create($data);

  }

  public function createBid()
  {
    // Check if there is existing weights
    $bidTechnical = $this->projectBid->weights()->where('envelope', 'technical')->first();
    $bidFinancial = $this->projectBid->weights()->where('envelope', 'financial')->first();


    $this->projectBid->update($this->inputedData);
    if ($this->technical) {
      if ($bidTechnical) {
        $bidTechnical->update([
          'envelope' => 'technical',
          'weight' => $this->technicalWeight,
          'upd_user' => Auth::user()->id,
        ]);
      } else {
        $this->projectBid->weights()->create([
          'envelope' => 'technical',
          'weight' => $this->technicalWeight,
          'crtd_user' => Auth::user()->id,
        ]);
      }
    } else {
      if ($bidTechnical) {
        $bidTechnical->delete();
      }
    }

    if ($this->financial) {

      if ($bidFinancial) {
        $bidFinancial->update([
          'envelope' => 'financial',
          'weight' => $this->financialWeight,
          'upd_user' => Auth::user()->id,
        ]);
      } else {
        $this->projectBid->weights()->create([
          'envelope' => 'financial',
          'weight' => $this->financialWeight,
          'crtd_user' => Auth::user()->id,
        ]);
      }
    } else {
      if ($bidFinancial) {
        $bidFinancial->delete();
      }
    }

    if ($this->fileSwitch) {
      foreach ($this->attachments as $attachment) {
        $attachName = pathinfo($attachment->getClientOriginalName(), PATHINFO_FILENAME);
        $attach = str_replace(' ', '_', strtolower($attachName)) . '_' . $this->projectBid->project_id . '.' . $attachment->extension();
        $attachment->storeAs('project_bid/', $attach, 'public');

        ProjectBidFile::create([
          'project_id' => $this->projectBid->id,
          'file_name' => $attach,
        ]);
      }
    }

    return redirect()
      ->route('project-bidding')
      ->with('success', 'Project bid has been updated!');
  }

  public function updatedAttachments()
  {
    $this->validate([
      'attachments' => 'nullable|mimes:pdf'
    ], [
      'attachments' => 'The attachments must be a file of type: pdf.'
    ]);

    $count = $this->projectBid->projectBidfiles->count();
    $attach = $this->projectBid->project_id . '_' . $count + 1 . '_' . time() . '.' . $this->attachments->extension();
    $this->attachments->storeAs('project_bid/', $attach, 'public');

    ProjectBidFile::create([
      'project_id' => $this->projectBid->id,
      'file_name' => $attach,
    ]);

    $this->projectBid->load('projectBidFiles');
    $this->attachFiles = $this->projectBid->projectBidFiles;
  }
  public function viewFile($file)
  {
    $folder = 'project_bid\\';
    $this->fileAttachment = route('view-file', ['file' => $file, 'folder' => $folder]);
    $this->fileName = $file;
    $this->dispatch('openFileModal');
  }
  public function closeModal()
  {
    $this->dispatch('closeConfirmModal');
  }
  // Open and Close File Modal
  // public function openFileModal($attach)
  // {
  //   $this->attachFile = route('view-file', ['file' => $attach, 'folder' => 'project_bid']);
  //   $this->dispatch('openFileModal');
  // }
  public function closeFileModal()
  {
    $this->dispatch('closeFileModal');
  }
  // Open and Close Confirmation Modal
  public function openConfirmationModal()
  {
    $this->dispatch('openConfirmationModal');
  }
  public function closeConfirmationModal()
  {
    $this->dispatch('closeConfirmationModal');
  }
  public function render()
  {
    return view('livewire.admin.bidding.edit-project-bidding');
  }
}
