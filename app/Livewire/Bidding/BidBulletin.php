<?php

namespace App\Livewire\Bidding;

use Livewire\Component;
use App\Models\ProjectBidding;

class BidBulletin extends Component
{
  public $bidding;
  public $title, $description, $file, $selectedFile, $selectedFileAttach, $deleteBulletin;
  protected $bulletinLists;
  public $showFullText = [], $tempLists;

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
  public function viewFile($id)
  {
    $this->selectedFile = $this->getBulletins()->where('id', $id)->first();
    $this->selectedFileAttach = route('view-file', ['file' => $this->selectedFile->attach_name, 'folder' => 'bid-bulletin']);
    $this->dispatch('openFileModal');
  }

  public function closeFileModal()
  {
    $this->dispatch('closeFileModal');
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
    return view('livewire.bidding.bid-bulletin', [
      'bulletins' => $this->bulletinLists->paginate(10)
    ]);
  }
}
