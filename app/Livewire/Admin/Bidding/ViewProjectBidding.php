<?php

namespace App\Livewire\Admin\Bidding;

use Livewire\Component;
use App\Models\ProjectBidding;

class ViewProjectBidding extends Component
{
  public $projectBid, $attachment;

  public function mount($id)
  {
    $this->projectBid = ProjectBidding::find($id);
    $this->attachment = $this->projectBid->attachment ? route('view-file', ['file' => $this->projectBid->attachment, 'folder' => 'project_bid']) : '';
  }

  public function back()
  {
    return redirect()->route('project-bidding');
  }

  // Open and Close File Modal
  public function openFileModal($file)
  {
    $this->attachment = route('view-file', ['file' => $file, 'folder' => 'project_bid']);
    $this->dispatch('openFileModal');
  }
  public function closeFileModal()
  {
    $this->dispatch('closeFileModal');
  }
  
  public function render()
  {
    return view('livewire.admin.bidding.view-project-bidding');
  }
}
