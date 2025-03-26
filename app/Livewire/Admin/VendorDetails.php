<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Models\ClassProduct;
use App\Models\VendorNature;

class VendorDetails extends Component
{
  public $vendor, $company_profile, $biddings;
  public $initNatureBusiness = [], $others = [], $allClass = [], $vendorNatures, $othersInput, $vendorClasses;
  public $fileAttachment;

  public function mount($id)
  {
    $this->vendor = User::findOrfail($id);

    $this->vendorNatures = $this->vendor->vendorNatures->pluck('name')->toArray();
    foreach ($this->vendor->vendorNatures as $nature) {
      if ($nature->others) {
        $this->others[] = $nature->name;
      }
    }

    $this->allClass = ClassProduct::all();
    // $this->vendorClasses = $this->vendor->vendorClasses->toArray();
    foreach ($this->vendor->vendorClasses as $class) {
      $this->vendorClasses[$class->code] = $class->description;
    }
  }
  public function addOthers()
  {
    if ($this->othersInput != null) {
      $this->vendorNatures[] = $this->othersInput;
      $this->others[] = $this->othersInput;
      $this->othersInput = '';
    }
  }
  public function removeOthers($index)
  {
    // unset($this->vendorNatures[$index]);
    // $this->vendorNatures = array_values($this->vendorNatures);
    unset($this->others[$index]);
    $this->others = array_values($this->others);
    foreach ($this->vendorNatures as $key => $value) {
      if (!in_array($value, ['manufacturer', 'trading', 'service provider']) && !in_array($value, $this->others)) {
        unset($this->vendorNatures[$key]);
      }
    }
  }

  public function vieCompanyProfile()
  {

    $this->company_profile = $this->vendor->company_profile;
    $folder = 'company-profile\\';
    $this->fileAttachment = route('view-file', ['file' =>  $this->company_profile , 'folder' => $folder]);
    $this->dispatch('openFileModal');
  }

  public function closeFileModal()
  {

    $this->dispatch('closeFileModal');
  }
  public function vendorNatureModal()
  {
    $this->initNatureBusiness = ['manufacturer', 'trading', 'service provider'];

  }
  public function selectedBusiness($value)
  {
    if (in_array($value, $this->vendorNatures)) {
      foreach ($this->vendorNatures as $key => $nature) {
        if ($value == $nature) {
          unset($this->vendorNatures[$key]);
        }
      }
    } else {
      $this->vendorNatures[] = $value;
    }

  }
  public function saveNature()
  {
    $this->validate([
      'vendorNatures' => 'required'
    ]);
    $this->vendor->vendorNatures()->delete();

    foreach ($this->vendorNatures as $nature) {
      $this->vendor->vendorNatures()->create([
        'name' => $nature,
        'others' => in_array($nature, $this->initNatureBusiness) ? null : true,
      ]);
    }

    return redirect()->route('vendor-maintenance.vendor-details', $this->vendor->id)
      ->with('success', 'Vendor profile updated!');
  }
  public function selectClass($code)
  {
    $class = $this->allClass->where('code', $code)->first();
    $codeExist = isset($this->vendorClasses[$code]);
    if($codeExist){
      unset($this->vendorClasses[$code]);
    }else{
      $this->vendorClasses[$code] = $class->description;
    }
  }

  public function saveClasses()
  {
    $this->validate([
      'vendorClasses' => 'required'
    ]);

    $this->vendor->vendorClasses()->delete();

    foreach ($this->vendorClasses as $code => $class) {
      $this->vendor->vendorClasses()->create([
        'code' => $code,
        'description' => $class,
      ]);
    }

    
    return redirect()->route('vendor-maintenance.vendor-details', $this->vendor->id)
      ->with('success', 'Vendor profile updated!');
  }

  public function render()
  {
    $this->biddings = $this->vendor->biddings;
    return view('livewire.admin.vendor-details');
  }
}
