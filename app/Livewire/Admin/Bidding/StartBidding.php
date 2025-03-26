<?php

namespace App\Livewire\Admin\Bidding;

use Livewire\Component;

class StartBidding extends Component
{

  public function closeBiddingModal()
  {
    $this->dispatch('closeBiddingModal');
  }
    public function render()
    {
        return view('livewire.admin.bidding.start-bidding');
    }
}
