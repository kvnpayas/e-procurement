<?php

namespace App\Livewire\Admin\Bidding\Vendor;

use App\Models\User;
use Livewire\Component;
use App\Helpers\SearchModel;
use App\Models\ProjectBidding;
use Livewire\WithPagination;

class BiddingVendor extends Component
{
  use WithPagination;
  protected $vendorLists;
  public $projectbid, $orderBy = 'id', $sort = 'desc', $search;
  protected $listeners = ['updateBidVendors'];

  public function mount($id)
  {
    $this->projectbid = ProjectBidding::findOrFail($id);
  }

  public function getVendors()
  {
    return $this->projectbid->vendors()->orderBy($this->orderBy, $this->sort);
  }

  public function updatedSearch($search)
  {

    $this->resetPage();
    $fields = [
      'users.id',
      'name',
      'email',
      'address',
      'number',
    ];

    $model = $this->getVendors();
    if ($search) {
      $this->vendorLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->vendorLists = $model;
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
  // Open vendor lists emit
  public function openVendorModalLists($id)
  {
    $this->dispatch('openVendorModalLists', $id);
  }

  // Update vendor lists from emit
  public function updateBidVendors()
  {
    $this->vendorLists = $this->getVendors();
  }
  public function render()
  {
    if (!$this->vendorLists) {
      $this->vendorLists = $this->getVendors();
    }
    return view('livewire.admin.bidding.vendor.bidding-vendor', [
      'vendors' => $this->vendorLists->paginate(10)
    ]);
  }
}
