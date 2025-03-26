<?php

namespace App\Livewire\Admin\Modal;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Financial;
use App\Models\InventoryRecord;

class InventoryHistory extends Component
{

  protected $listeners = ['getHistory'];
  public $inventoryHistories, $inventory, $projectId, $vendorId;

  public function getHistory($projectId, $vendorId, $inventoryId)
  {
    $this->projectId = $projectId;
    $this->vendorId = $vendorId;
    $this->inventory = Financial::where('inventory_id', $inventoryId)->first();
    $inventories = InventoryRecord::where('inventory_id', $inventoryId)->get();
    $this->inventoryHistories = $inventories->isNotEmpty()
      ? $inventories->sortByDesc(function ($item) {
        return Carbon::createFromFormat('n/j/Y H:i', $item->trans_date);
      })
      : [];
    $this->dispatch('openHistoryModal');
    // $this->dispatch('closeReviewModalDispatch');
  }

  public function closeHistoryModal()
  {
    $this->dispatch('closeHistoryModal');
  }
  public function render()
  {
    return view('livewire.admin.modal.inventory-history');
  }
}
