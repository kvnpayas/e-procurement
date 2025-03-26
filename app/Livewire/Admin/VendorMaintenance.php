<?php

namespace App\Livewire\Admin;

use Auth;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use App\Models\VendorClass;
use Illuminate\Support\Str;
use App\Helpers\SearchModel;
use App\Models\ClassProduct;
use App\Models\VendorNature;
use Livewire\WithPagination;
use App\Mail\UserResetPassword;
use App\Mail\VendorRegistration;
use Illuminate\Support\Facades\Mail;

class VendorMaintenance extends Component
{
  use WithPagination;
  protected $vendorLists;
  public $roles, $role, $addVendorMessage, $vendorsEmail;
  public $editVendor, $editStatus, $switchStatus, $warningInactive;
  public $search, $showAll = false;
  public $userId, $user, $name, $address, $number;
  public $natureBusiness = [], $inputHidden = true, $othersInput,
  $classes = [],
  $nature = [],
  $selectedClasses = [];

  public function mount()
  {
    // $this->vendors = $this->getVendors();
    $this->roles = $this->getRoles();
    $this->role = 2;
    $this->natureBusiness = [
      'manufacturer' => false,
      'trading' => false,
      'service provider' => false,
    ];

    $getAllClass = $this->getClass();
    foreach ($getAllClass as $class) {
      $this->classes[$class['code']] = [
        'description' => $class['description'],
        'select' => false,
      ];
    }
    $this->classes();
  }
  public function getVendors()
  {
    if ($this->showAll) {
      return User::where('role_id', 2)->orderBy('name', 'asc');
    } else {
      return User::where('role_id', 2)->where('active', true)->orderBy('name', 'asc');
    }
  }
  public function getRoles()
  {
    return Role::all();
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
  public function registrationModal()
  {
    $this->dispatch('addModal');
  }
  public function vendorRegistration()
  {
    $nob = [];
    $nob = $this->natureBusiness;
    if (!$this->inputHidden && $this->othersInput != null) {
      $nob[$this->othersInput] = true;
    }
    $this->nature = array_keys(array_filter($nob));

    $this->validate([
      'vendorsEmail' => 'required|email|unique:users,email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
      'role' => 'required',
      'name' => 'required',
      'address' => 'required',
      'number' => 'required',
      'nature' => 'required',
      'selectedClasses' => 'required',
    ], [
      'nature.required' => 'The nature of business is required.',
      // 'dti_sec_no.required' => 'The dti no or sec no is required.',
      // 'selectedClasses.required' => 'The product/services is required.',
    ]);

    $token = Str::random(40);

    $data = [
      'name' => $this->name,
      'email' => $this->vendorsEmail,
      'address' => $this->address,
      'number' => $this->number,
      'role_id' => 2,
      'token' => $token,
      'active' => true,
      'crtd_user' => Auth::user()->id,
    ];

    $vendor = User::create($data);

    // Vendor Nature of Business
    foreach ($this->nature as $data) {
      if ($data == 'manufacturer' || $data == 'trading' || $data == 'service provider') {
        VendorNature::create([
          'vendor_id' => $vendor->id,
          'name' => $data,
        ]);
      } else {
        VendorNature::create([
          'vendor_id' => $vendor->id,
          'others' => $data,
        ]);
      }
    }

    // Vendor Products/Services
    foreach ($this->selectedClasses as $index => $classData) {
      VendorClass::create([
        'vendor_id' => $vendor->id,
        'code' => $index,
        'description' => $classData['description'],
      ]);
    }

    Mail::to($vendor->email)->send(new VendorRegistration($vendor));

    $this->dispatch('closeModal');
    $this->dispatch('success-message', ['message' => 'Success! The email invitation has already been sent to ' . $vendor->email . '.']);
  }

  // Create Vendor Functions
  public function selectedBusiness($value)
  {
    $this->natureBusiness[$value] = !$this->natureBusiness[$value];
  }
  public function selectedOther()
  {
    $this->inputHidden = !$this->inputHidden;
  }

  public function getClass()
  {
    return ClassProduct::all();
  }
  public function classes()
  {
    $this->selectedClasses = collect($this->classes)
      ->where('select', true)
      ->toArray();
  }
  public function selectClass($code)
  {
    $this->classes[$code]['select'] = !$this->classes[$code]['select'];
    $this->classes();
  }
  public function resetClass()
  {
    foreach ($this->classes as $index => $class) {
      $this->classes[$index] = [
        'description' => $class['description'],
        'select' => false,
      ];
    }
    $this->classes();
  }
  // Create Vendor Functions

  public function viewVendor($id)
  {
    return redirect()->route('vendor-maintenance.vendor-details', $id);
  }
  public function editUser($id)
  {
    $this->addVendorMessage = null;
    $this->warningInactive = null;
    $this->editVendor = $this->getVendors()->where('id', $id)->first();
    $this->editStatus = (bool) $this->editVendor->active;
    $this->switchStatus = $this->editVendor->active ? 'Active' : 'Inactive';
    $this->dispatch('openEditModal');
  }
  public function updatedEditStatus()
  {
    $bids = $this->editVendor->biddings->where('status', 'Active');
    $this->switchStatus = $this->editStatus ? 'Active' : 'Inactive';
    if (!$this->editStatus) {
      if (!$bids->isEmpty()) {
        $this->warningInactive = 'Warning! This vendor has been selected to participate in bids(Active). If marked inactive, the vendor will be removed from all participating bid lists.';
      }
    } else {
      $this->warningInactive = null;
    }
  }
  public function closeEditModal()
  {
    $this->warningInactive = null;
    $this->dispatch('closeEditModal');
  }
  public function eidtUserInfo()
  {
    sleep(2);
    $this->addVendorMessage = null;

    if (!$this->editStatus) {
      $this->editVendor->biddings()->detach();
    }
    $this->editVendor->update([
      'active' => $this->editStatus
    ]);

    $this->dispatch('closeEditModal');
    $this->dispatch('success-message', ['message' => 'Changes saved successfully!']);
  }
  public function resetPassword()
  {
    $this->dispatch('openConfirmationModal');
    $this->dispatch('closeEditModal');
  }
  public function closeConfirmationModal()
  {
    $this->addVendorMessage = null;
    $this->dispatch('closeConfirmationModal');
  }
  public function resetUserPassword()
  {
    $token = Str::random(40);
    $this->editVendor->update([
      'password' => null,
      'token' => $token
    ]);

    Mail::to($this->editVendor->email)->send(new UserResetPassword($this->editVendor, $token));

    // $this->dispatch('closeConfirmationModal');
    // $this->addVendorMessage = 'Reset password success! An email was sent to ' . $this->editVendor->email . '.';
    // $this->dispatch('add-vendor-message');
    $this->vendorLists = $this->getVendors();
    $this->dispatch('closeConfirmationModal');
    $this->dispatch('success-message', ['message' => 'Reset password success! An email was sent to ' . $this->editVendor->email . '.']);

  }

  public function closeAddModal()
  {
    $this->dispatch('closeModal');
  }

  public function openClassModal()
  {
    $this->dispatch('openClassModal');
  }
  public function closeClassModal()
  {
    $this->dispatch('closeClassModal');
  }
  public function render()
  {
    if (!$this->vendorLists) {
      $this->vendorLists = $this->getVendors();
    }
    return view('livewire.admin.vendor-maintenance', [
      'vendors' => $this->vendorLists->paginate(10)
    ]);
  }
}
