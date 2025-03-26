<?php

namespace App\Livewire\Admin\Bidding;

use App\Mail\BidBulletinNotification;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BidBulletin extends Component
{
  use WithPagination;
  use WithFileUploads;
  public $bidding;
  public $title, $description, $file, $selectedFile, $selectedFileAttach, $deleteBulletin;
  public $editBulletin, $titleEdit, $descriptionEdit, $fileEdit, $hasFile, $typeImage;
  public $showFullText = [], $tempLists;
  protected $bulletinLists;

  public function mount($id)
  {
    $this->bidding = ProjectBidding::findOrFail($id);
    $this->showTextInit();
  }

  public function showTextInit()
  {
    foreach ($this->getBulletins()->get() as $bulletin) {
      $this->showFullText[$bulletin->id] = false;
    }
  }
  public function getBulletins()
  {
    return $this->bidding->bulletins();
  }

  public function createBulletinForm()
  {
    $this->dispatch('openCreateModal');
  }
  public function closeCreateModal()
  {
    $this->resetValidation();
    $this->file = null;
    $this->title = null;
    $this->description = null;
    $this->dispatch('closeCreateModal');
  }
  public function createBulletin()
  {
    $this->validate([
      'title' => 'required',
      'description' => 'required',
      'file' => 'required|mimes:pdf,jpeg,png',
    ]);

    if ($this->file) {
      $attach = strtolower(str_replace(' ', '_', $this->title)) . '_' . time() . '.' . $this->file->extension();
      $this->file->storeAs('bid-bulletin/', $attach, 'public');
    } else {
      $attach = null;
    }

    $data = [
      'title' => $this->title,
      'description' => $this->description,
      'attach_name' => $attach,
      'count_bulletin' => $this->bidding->bulletins->count() + 1,
      'crtd_user' => Auth::user()->id,
    ];

    $this->bidding->bulletins()->create($data);

    $vendors = $this->bidding->vendors()->wherePivot('status', 'Joined')->get();

    foreach ($vendors as $vendor) {
      Mail::to($vendor)->send(new BidBulletinNotification($data, $this->bidding, $vendor));
    }

    $this->bulletinLists = $this->getBulletins();
    $this->showTextInit();

    $this->dispatch('closeCreateModal');
    $this->dispatch('success-message', ['message' => 'Bulletin has been created!']);

    // return redirect()
    //   ->route('project-bidding.bid-bulletin', $this->bidding->id)
    //   ->with('success', 'Bulletin has been created!');
  }
  public function viewFile($id)
  {
    $imageExtension = ['png', 'jpeg', 'pdf'];
    $this->selectedFile = $this->getBulletins()->where('id', $id)->first();
    $extension = pathinfo($this->selectedFile->attach_name, PATHINFO_EXTENSION);
    if (in_array($extension, $imageExtension)) {
      $this->typeImage = true;
      $this->selectedFileAttach = route('view-file', ['file' => $this->selectedFile->attach_name, 'folder' => 'bid-bulletin']);
    }

    $this->dispatch('openFileModal');
  }

  public function closeFileModal()
  {
    $this->dispatch('closeFileModal');
  }

  public function editModal($id)
  {
    $this->editBulletin = $this->getBulletins()->where('id', $id)->first();
    $this->titleEdit = $this->editBulletin->title;
    $this->descriptionEdit = $this->editBulletin->description;
    $this->fileEdit = $this->editBulletin->attach_name;
    $this->hasFile = $this->fileEdit ? true : false;
    $this->dispatch('openEditModal');
  }

  public function uploadFile()
  {
    $this->hasFile = false;
    $this->fileEdit = null;
  }
  public function updateBulletin()
  {
    $this->validate([
      'titleEdit' => 'required',
      'descriptionEdit' => 'required',
      'fileEdit' => $this->hasFile ? '' : 'required|mimes:pdf,jpeg,png',
    ], [
      'titleEdit.required' => 'The title field is required.',
      'descriptionEdit.required' => 'The description field is required.',
      'fileEdit.required' => 'The file field is required.',
    ]);

    if (!$this->hasFile) {
      $attach = strtolower(str_replace(' ', '_', $this->titleEdit)) . '_' . time() . '.' . $this->fileEdit->extension();
      $this->fileEdit->storeAs('bid-bulletin/', $attach, 'public');
      unlink(storage_path('app/public/bid-bulletin/' . $this->editBulletin->attach_name));
    }

    $data = [
      'title' => $this->titleEdit,
      'description' => $this->descriptionEdit,
      'attach_name' => $this->hasFile ? $this->fileEdit : $attach,
      'upd_user' => Auth::user()->id,
    ];
    $this->editBulletin->update($data);

    $this->bulletinLists = $this->getBulletins();

    $this->dispatch('closeEditModal');
    $this->dispatch('success-message', ['message' => 'Bulletin has been updated!']);

    // return redirect()
    //   ->route('project-bidding.bid-bulletin', $this->bidding->id)
    //   ->with('success', 'Bulletin has been updated!');
  }

  public function closeEditModal()
  {
    $this->resetValidation();
    $this->dispatch('closeEditModal');
  }

  public function deleteBulletinModal($id)
  {
    $this->deleteBulletin = $this->getBulletins()->where('id', $id)->first();
    $this->dispatch('openDeleteModal');
  }
  public function closeDeleteModal()
  {
    $this->dispatch('closeDeleteModal');
  }
  public function deleteSelectedBulletin()
  {
    $this->deleteBulletin->delete();
    unlink(storage_path('app/public/bid-bulletin/' . $this->deleteBulletin->attach_name));

    $this->bulletinLists = $this->getBulletins();

    $this->dispatch('closeDeleteModal');
    $this->dispatch('success-message', ['message' => 'Bulletin has been deleted!']);
    // return redirect()
    //   ->route('project-bidding.bid-bulletin', $this->bidding->id)
    //   ->with('success', 'Bulletin has been deleted!');
  }

  public function toggleText($id)
  {
    $this->showFullText[$id] = !$this->showFullText[$id];
  }

  public function render()
  {
    if (!$this->bulletinLists) {
      $this->bulletinLists = $this->getBulletins();
    }
    return view('livewire.admin.bidding.bid-bulletin', [
      'bulletins' => $this->bulletinLists->paginate(10)
    ]);
  }
}
