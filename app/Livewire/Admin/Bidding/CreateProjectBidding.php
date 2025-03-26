<?php

namespace App\Livewire\Admin\Bidding;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ProjectBidding;
use App\Models\ProjectBidFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateProjectBidding extends Component
{
  use WithFileUploads;

  public $projectTitle, $biddingType, $sales, $budget, $projectType, $reservedPrice, $switchReservedPrice, $reflectPrice, $deadlineDate, $dateTime, $time,
  $eligibility, $technical, $financial, $technicalWeight, $financialWeight, $totalWeight, $instructionDetails, $attachments, $fileExist, $fileSwitch = false, $scoreMethod = true, $projectCode;
  public $disabledPrice = false, $disableTechnicalWeight = false, $disableFinancialWeight = false, $tmpAttachments = [];
  public $fileAttachment;
  public $inputedData;
  public function mount()
  {
    $this->biddingType = 'bid';
    $this->sales = false;
    $this->switchReservedPrice = true;
    $this->reflectPrice = false;
    $this->time = "17:00";
    $this->eligibility = true;
    $this->technical = true;
    $this->financial = true;
    $this->technicalWeight = 50;
    $this->financialWeight = 50;
    $this->checksWeight();
    $this->deadlineDate = date('Y-m-d');
  }

  // Sales Switch
  public function updatedSales()
  {
    $this->technical = !$this->sales;
    $this->scoreMethod = !$this->sales;
    $this->switchReservedPrice = !$this->sales;
    if ($this->switchReservedPrice) {
      $this->disabledPrice = false;
    } else {
      $this->reservedPrice = '';
      $this->disabledPrice = true;
      $this->resetValidation('reservedPrice');
    }
    $this->disableTechnicalWeight = $this->sales;
    $this->checksWeight();
  }
  // Reserved Price Switch
  public function updatedSwitchReservedPrice()
  {
    if ($this->sales) {
      $this->switchReservedPrice = false;
    } else {
      if ($this->switchReservedPrice) {
        $this->disabledPrice = false;
      } else {
        $this->reservedPrice = '';
        $this->disabledPrice = true;
        $this->resetValidation('reservedPrice');
      }
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

  // Function to check envelopes and weights
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
    $this->disableFinancialWeight = !$this->scoreMethod;
    $this->disableTechnicalWeight = !$this->scoreMethod;
  }
  public function updatedFileSwitch()
  {
    if (!$this->fileSwitch) {
      $this->attachments = null;
    }
    $this->resetValidation('attachments');
  }
  public function createForm()
  {
    $this->projectCode = $this->generateProjectCode();
    $this->dateTime = $this->dateTime = $this->deadlineDate . ' ' . $this->time;
    $this->validate([
      'projectCode' => 'unique:project_biddings,project_id',
      'projectTitle' => 'required',
      'biddingType' => 'required',
      'reservedPrice' => $this->switchReservedPrice ? 'required' : '',
      // 'dateTime' => 'required|date|after:' . date('Y-m-d H:i'),
      'dateTime' => 'required|date',
      'time' => 'required',
      'totalWeight' => $this->technical || $this->financial ? 'required|in:100' : '',
    ], [
      'deadlineDate.after' => 'The deadline date field must be a date tommorow onwards.',
      'totalWeight.in' => 'The total weight must be equal to 100.'
    ]);

    $this->inputedData = [
      'project_id' => $this->projectCode,
      'budget_id' => $this->budget,
      'icss_project_id' => $this->projectType,
      'title' => $this->projectTitle,
      'status' => 'Active',
      'type' => $this->biddingType,
      'instruction_details' => $this->instructionDetails,
      // 'attachments' => $attach,
      'eligibility' => $this->eligibility,
      'technical' => $this->technical,
      'financial' => $this->financial,
      'deadline_date' => $this->deadlineDate . ' ' . $this->time,
      'reserved_price' => $this->switchReservedPrice ? $this->reservedPrice : '',
      'reflect_price' => $this->reflectPrice,
      'reserved_price_switch' => $this->switchReservedPrice,
      'scrap' => $this->sales,
      'score_method' => $this->scoreMethod ? 'Rating' : 'Cost',
      'crtd_user' => Auth::user()->id,
    ];
    $this->dispatch('openConfirmModal');
    // ProjectBidding::create($data);

  }
  public function generateProjectCode()
  {
    $latestProject = ProjectBidding::latest()->first();
    $nextNumber = $latestProject ? intval(substr($latestProject->project_id, -3)) + 1 : 1;
    $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    return 'TEI-ePROC-' . date('Y') . '-' . $formattedNumber;
  }
  public function createBid()
  {
    // if ($this->fileSwitch) {
    //   $this->attachments->storeAs('project_bid/', $this->inputedData['attachments'], 'public');
    // }

    $bid = ProjectBidding::create($this->inputedData);
    if ($this->technical) {
      $bid->weights()->create([
        'envelope' => 'technical',
        'weight' => $this->technicalWeight,
        'crtd_user' => Auth::user()->id,
      ]);
    }
    if ($this->financial) {
      $bid->weights()->create([
        'envelope' => 'financial',
        'weight' => $this->financialWeight,
        'crtd_user' => Auth::user()->id,
      ]);
    }

    foreach ($this->tmpAttachments as $index => $tmpAttachment) {

      $extension = pathinfo($tmpAttachment, PATHINFO_EXTENSION);

      $attach = $bid->project_id . '_' . $index . '_' . time() . '.' . $extension;

      $newPath = 'project_bid/' . $attach;

      Storage::disk('public')->move('temp/' . $tmpAttachment, $newPath);
      ProjectBidFile::create([
        'project_id' => $bid->id,
        'file_name' => $attach,
      ]);

    }
    // if ($this->fileSwitch) {
    //   $index = 1;
    //   foreach ($this->attachments as $attachment) {
    //     $attach = $bid->project_id . '_' . $index . '_' . time() . '.' . $attachment->extension();
    //     $attachment->storeAs('project_bid/', $attach, 'public');

    //     ProjectBidFile::create([
    //       'project_id' => $bid->id,
    //       'file_name' => $attach,
    //     ]);
    //     $index++;
    //   }
    // }

    return redirect()
      ->route('project-bidding')
      ->with('success', 'Project bid has been created!');
  }

  public function updatedAttachments()
  {
    // dd($this->attachments);
    $this->validate([
      'attachments' => 'nullable|mimes:pdf'
    ], [
      'attachments' => 'The attachments must be a file of type: pdf.'
    ]);
    $file = $this->attachments->store('temp', 'public');
    $this->tmpAttachments[] = basename($file);
  }
  public function removeFile($index)
  {
    if (file_exists(storage_path('app/public/temp/' . $this->tmpAttachments[$index]))) {
      unlink(storage_path('app/public/temp/' . $this->tmpAttachments[$index]));
    }
    unset($this->tmpAttachments[$index]);
    $this->tmpAttachments = array_values($this->tmpAttachments);
  }

  public function viewFile($file)
  {
    $folder = 'temp\\';
    $this->fileAttachment = route('view-file', ['file' => $file, 'folder' => $folder]);
    $this->fileName = $file;
    $this->dispatch('openFileModal');
  }
  public function closeFileModal()
  {
    $this->dispatch('closeFileModal');
  }

  public function closeModal()
  {
    $this->dispatch('closeConfirmModal');
  }

  public function render()
  {
    return view('livewire.admin.bidding.create-project-bidding');
  }
}
