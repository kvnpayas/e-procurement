<?php

namespace App\Livewire\Envelopes;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ProjectBidding;
use App\Models\VendorEnvelopeFile;
use Illuminate\Support\Facades\Auth;

class TechnicalView extends Component
{
  use WithFileUploads;

  public $projectId, $bid;
  public $alertMessage;
  public $answers = [], $multiAnswers = [], $files = [], $initFiles = [], $hasFiles = [];
  public $technicalFileName, $fileAttachment, $vendorStatus, $removeId, $technicalId;

  public function mount($projectId)
  {
    $this->bid = ProjectBidding::where('id', $projectId)->first();
    $this->initAnswer();
    $this->vendorStatus = $this->bid->bidVendorStatus->where('vendor_id', Auth::user()->id)->first();
  }

  public function initAnswer()
  {
    foreach ($this->bid->technicals as $initAnswer) {
      $vendorAnswer = $this->bid->technicalVendors->where('vendor_id', Auth::user()->id)->where('technical_id', $initAnswer->id)->first();
      $tempAnswer = $vendorAnswer ? $vendorAnswer->answer : null;
      if ($initAnswer->question_type == 'checkbox') {
        $this->answers[$initAnswer->id] = $tempAnswer ? $tempAnswer : false;
      } elseif ($initAnswer->question_type == 'multi-option') {
        $explodeOptions = explode('&@!', $tempAnswer);
        $this->answers[$initAnswer->id] = $tempAnswer;
        foreach ($initAnswer->options as $option) {
          if (in_array($option->id, $explodeOptions)) {
            $this->multiAnswers[$initAnswer->id][$option->id] = true;
          } else {
            $this->multiAnswers[$initAnswer->id][$option->id] = false;
          }
        }
      } else {
        $this->answers[$initAnswer->id] = $tempAnswer;
      }

      // $this->files[$initAnswer->id] = [];
    }
    // dd($this->bid->technicals);
    foreach ($this->bid->technicals as $initFile) {
      $file = $initFile->vendorFiles ? $initFile->vendorFiles->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id) : '';
      // $this->initFiles[$initFile->id] = !$file->isEmpty() ? $file : null;
      if (!$file->isEmpty()) {
        $this->initFiles[$initFile->id] = $file;
      } else {
        $this->initFiles[$initFile->id] = [];
      }
    }
    $this->initFiles = array_filter($this->initFiles, function ($value) {
      return $value !== null;
    });

    $this->hasFiles = $this->initFiles;
    // dd($this->hasFiles);
  }

  public function getTechnicals()
  {
    return $this->bid->technicals();
  }

  public function saveForm()
  {
    // For Multi option answer change to string
    foreach ($this->getTechnicals()->get() as $technical) {
      if ($technical->question_type == 'multi-option') {
        $answer = [];
        if ($this->multiAnswers[$technical->id]) {
          // dd($this->answers[$technical->id]);
          foreach ($this->multiAnswers[$technical->id] as $key => $data) {
            if ($data) {
              $answer[] = $key;
            }
          }
          $this->answers[$technical->id] = $answer ? implode('&@!', $answer) : null;
        }
      }
    }
    // dd($this->initFiles, $this->hasFiles, $this->files);

    // validates answers
    $this->validate(
      [
        'answers.*' => 'required',
        'hasFiles.*' => 'required',
        // 'files' => 'required',
      ],
      [
        'answers.*.required' => 'This field is required.',
        'hasFiles.*.required' => 'The file attachment is required.',
      ],
    );

    // Update or create answers
    foreach ($this->answers as $id => $answer) {

      // Save Uploaded file
      $vendorFileExists = VendorEnvelopeFile::where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id)->where('envelope_id', $id)->where('envelope', 'technical')->get();

      $technicalExists = $this->bid->technicalVendors->where('vendor_id', Auth::user()->id)->where('technical_id', $id)->first();

      if ($technicalExists) {
        $technicalExists->update(['answer' => $answer]);
      } else {
        $this->bid->technicalVendors()->create([
          'vendor_id' => Auth::user()->id,
          'technical_id' => $id,
          'answer' => $answer,
        ]);
      }
    }
    // Update technical status accoridng to the vendors input status true = all input have response
    // $vendorResponse = $this->bid->technicalVendors->where('vendor_id', Auth::user()->id)->where('answer' ,'!=', null)->count();
    // $technicalCount = $this->bid->technicals->count();

    $this->checkTechnicalStatus();

    // $this->dispatch('closeSaveModal');

    // return redirect()->route('bid-lists.technical-envelope', $this->bid->id)
    //   ->with('success', 'Technical requirements successfully updated!');
    $this->files = [];
    // $this->initAnswer();
    $this->dispatch('closeSaveModal');
    $this->vendorStatus = $this->bid->bidVendorStatus->where('vendor_id', Auth::user()->id)->first();
    $this->dispatch('success-message', ['message' => 'Technical successfully updated!']);
  }

  public function checkTechnicalStatus()
  {
    $technicalStatus = $this->bid->bidEnvelopeStatus()->where('envelope', 'technical')->where('vendor_id', Auth::user()->id)->first();

    $vendorTechnicals = $this->bid->technicals->map(function ($technical) {
      $answer = $this->answers[$technical->id] !== null ? $this->answers[$technical->id] : null;
      $technicaFiles = $technical->vendorFiles->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id);
      $vendorFiles = $technicaFiles->isEmpty() ? false : true;
      $technical->vendorStatus = $answer !== null && $vendorFiles ? true : false;
      return $technical;
    });
    // $filteredAnswers = array_filter($this->answers, function ($value) {
    //   return $value !== false;
    // });
    $checkStatus = $vendorTechnicals->pluck('vendorStatus')->toArray();
    // dd($vendorTechnicals);
    if (in_array(false, $checkStatus, true)) {
      $technicalStatus->update(['status' => false]);
    } else {
      $technicalStatus->update(['status' => true]);
    }

    $this->vendorStatus = $this->bid->bidVendorStatus->where('vendor_id', Auth::user()->id)->first();
    if ($this->vendorStatus) {
      $this->vendorStatus->update(['complete' => false]);
      $this->dispatch('updateVendorStatus');
    }
  }

  // File Attachment FUnctions
  public function changeRemoveFile($id)
  {
    unset($this->hasFiles[$id]);
    $this->hasFiles[$id] = [];
  }

  public function updatedFiles($value, $index)
  {
    $this->validate(
      [
        'files.' . $index => 'mimes:pdf|max:10240',
      ],
      [
        'files.' . $index . '.mimes' => 'The attachment must be a file of type: pdf.',
        'files.' . $index . 'max' => 'The file may not be greater than 10MB.',
      ],
    );
    $vendorFileExists = VendorEnvelopeFile::where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id)->where('envelope_id', $index)->where('envelope', 'technical')->get();
    $key = $vendorFileExists ? $vendorFileExists->count() : 0;
    $attachName = strtolower('technical_' . ($key + 1) . '_' . str_replace(' ', '_', $this->bid->id) . '_' . time() . '.' . $this->files[$index]->extension());
    VendorEnvelopeFile::create([
      'bidding_id' => $this->bid->id,
      'vendor_id' => Auth::user()->id,
      'envelope_id' => (int) $index,
      'envelope' => 'technical',
      'file' => $attachName,
    ]);
    $this->files[$index]->storeAs('vendor-file/technical', $attachName, 'public');
    // $this->initAnswer();
    $technical = $this->bid->technicals->where('id', $index)->first();
    $this->hasFiles[$index] = $technical->vendorFiles->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id);
    // dd($this->hasFiles);
    $this->files[$index] = null;
    $this->checkTechnicalStatus();
  }
  public function removeFile($id, $technicalId)
  {
    $file = VendorEnvelopeFile::findOrFail($id);
    if (file_exists(storage_path('app/public/vendor-file/technical/' . $file->file))) {
      unlink(storage_path('app/public/vendor-file/technical/' . $file->file));
    }
    $file->delete();
    $technical = $this->bid->technicals->where('id', $technicalId)->first();
    $this->hasFiles[$technicalId] = $technical->vendorFiles->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id);
    $this->checkTechnicalStatus();
    // unset($this->hasFiles[$technicalId][$id]);
    // dd($this->hasFiles);
    // $this->eligibilityFiles = $this->eligibility->vendorFiles->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id);
  }

  public function openSaveModalFromRemove($id, $technicalId)
  {
    $this->removeId = $id;
    $this->technicalId = $technicalId;
    $this->dispatch('openSaveRemoveModal');
  }
  public function saveRemoveForm()
  {
    $file = VendorEnvelopeFile::findOrFail($this->removeId);
    if (file_exists(storage_path('app/public/vendor-file/technical/' . $file->file))) {
      unlink(storage_path('app/public/vendor-file/technical/' . $file->file));
    }
    $file->delete();
    $technical = $this->bid->technicals->where('id', $this->technicalId)->first();
    $this->hasFiles[$this->technicalId] = $technical->vendorFiles->where('bidding_id', $this->bid->id)->where('vendor_id', Auth::user()->id);
    $this->checkTechnicalStatus();
    $this->dispatch('closeSaveRemoveModal');
    $this->dispatch('success-message', ['message' => 'Technical successfully updated!']);

  }
  public function closeSaveRemoveModal()
  {
    $this->dispatch('closeSaveRemoveModal');

  }
  public function viewFile($file)
  {
    $this->fileAttachment = route('view-file', ['file' => $file, 'folder' => 'vendor-file\technical']);
    $this->technicalFileName = $file;
    $this->dispatch('openFileModal');
  }

  public function closeFileModal()
  {
    $this->dispatch('closeFileModal');
  }

  // save modal
  public function openSaveModal()
  {
    $this->dispatch('openSaveModal');
  }

  public function closeSaveModal()
  {
    $this->dispatch('closeSaveModal');
  }
  public function render()
  {
    return view('livewire.envelopes.technical-view', ['technicals' => $this->getTechnicals()->get()]);
  }
}
