<?php

namespace App\Livewire\Admin\Bidding\Vendor;

use App\Models\ClassProduct;
use App\Models\User;
use Livewire\Component;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\Auth;

class VendorLists extends Component
{
  use WithPagination;
  protected $vendorLists;
  public $projectbid, $projectVendors, $checkVendors = [], $search;
  protected $listeners = ['openVendorModalLists'];
  public $classes = [], $classesArray = [], $showAll = false;

  public function openVendorModalLists($id)
  {
    $this->projectbid = ProjectBidding::findOrFail($id);
    $this->projectVendors = $this->projectbid->vendors;

    if ($this->projectbid->financial) {
      $classId = $this->projectbid->financials->groupBy('class_id')->map(function ($group, $classId) {
        return $classId;
      })->values()->toArray();
      $this->showAll = false;

    } else {
      $classId = [];
      $this->showAll = true;
    }

    $this->classesArray = $classId;

    $this->classes = ClassProduct::whereIn('code', $classId)->get();

    $vendorIds = $this->projectVendors->pluck('id')->toArray();
    foreach ($vendorIds as $id) {
      $this->checkVendors[$id] = true;
    }

    $this->dispatch('openVendorModal');
  }


  public function getVendors()
  {
    if (!$this->showAll) {
      $vendors = User::where('role_id', 2)
        ->where('token', null)
        ->where('active', 1)
        ->whereHas('vendorClasses', function ($query) {
          $query->whereIn('code', $this->classesArray);
        });
    } else {
      $vendors = User::where('role_id', 2)
        ->where('token', null)
        ->where('active', 1);
    }

    return $vendors;

  }

  public function updatedSearch($search)
  {

    $this->resetPage();
    $fields = [
      'id',
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

  public function selectedVendors()
  {
    $checkItems = array_filter($this->checkVendors, function ($value) {
      return $value === true;
    });
    // dd($this->checkEligibilities);
    return array_keys($checkItems);
  }
  public function addVendors()
  {
    $ids = $this->selectedVendors();

    // Delete existing vendors when remove on selected
    foreach ($this->projectVendors as $vendor) {
      if (!in_array($vendor->id, $ids)) {
        $this->projectbid->vendors()->detach($vendor->id);
      }
    }

    // Add vendor to pivot table
    foreach ($ids as $id) {
      $dataExists = $this->projectbid->vendors()->where('vendor_id', $id)->first();
      if ($dataExists) {
        $this->projectbid->vendors()->updateExistingPivot($id, ['upd_user' => Auth::user()->id]);
      } else {
        $this->projectbid->vendors()->attach($id, ['crtd_user' => Auth::user()->id]);
      }
    }

    $this->projectbid->update(['invited_vendor' => $this->projectbid->vendors->count()]);

    $this->dispatch('updateBidVendors');
    $this->dispatch('closeVendorModal');
    $this->dispatch('success-message', ['message' => 'Bidding vendors have successfully updated!']);

    // return redirect()
    // ->route('project-bidding.vendor-lists', $this->projectbid->id)
    // ->with('success', 'Bidding vendors have successfully updated!');
  }

  // Close Modal
  public function closeVendorModal()
  {
    $this->dispatch('closeVendorModal');
  }
  public function render()
  {
    if (!$this->vendorLists) {
      $this->vendorLists = $this->getVendors();
    }
    return view('livewire.admin.bidding.vendor.vendor-lists', [
      'vendors' => $this->vendorLists->paginate(10)
    ]);
  }
}
