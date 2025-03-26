<?php

namespace App\Livewire\Admin\Bidding\Envelope;

use Livewire\Component;
use App\Models\Financial;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FinancialEnvelopeInventory;

class FinancialEnvelope extends Component
{
  use WithPagination;
  use WithFileUploads;
  protected $financialLists;
  public $projectbid, $editPriceButton = true, $editQuantityButton = true, $customPrice = [], $customQuantity = [], $envelopeRemarks, $envelopeRemarksInput;
  public $orderBy = 'inventory_id', $sort = 'asc', $search, $alertMessage;
  public $id, $inventoryId, $description, $remarks, $tempPriceTotal, $tempQuantityTotal;
  public $inventoryUpload, $hasFile = false, $financialsUpload = [], $grandTotal, $disabledUpload, $total, $financialTotal;
  public $selectedFinancial;
  protected $listeners = ['updateBidFinancials'];
  public $initFinancial;

  public function mount($id)
  {
    $this->projectbid = ProjectBidding::findOrFail($id);
    $this->envelopeRemarks = $this->projectbid->envelopeRemarks->where('envelope', 'financial')->first();
    $this->envelopeRemarksInput = $this->envelopeRemarks ? $this->envelopeRemarks->remarks : '';
    $this->tempPriceTotal = 0;

    foreach ($this->getFinancials()->get() as $total) {
      $this->tempPriceTotal += $total->pivot->bid_price * $total->pivot->quantity;
    }
    $this->financialTotal = $this->tempPriceTotal;
    // dd($this->financialTotal,  (int)$this->projectbid->reserved_price);
    if ($this->projectbid->reserved_price_switch) {
      if ($this->tempPriceTotal != $this->projectbid->reserved_price) {
        $this->addError('tempPriceTotal', 'The financial total must equal PHP ' . number_format($this->projectbid->reserved_price, 2) . ' reserved price.');
        // dd($this->tempPriceTotal);
      }
    }
  }

  public function getFinancials()
  {
    return $this->projectbid->financials()->where('scrap', $this->projectbid->scrap ? true : false);
  }

  public function updatedSearch($search)
  {

    $this->resetPage();
    $fields = [
      'inventory_id',
      'description',
      'class_id',
      'uom',
    ];

    $model = $this->getFinancials();

    if ($search) {
      $this->financialLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->financialLists = $model;
    }
  }


  // public function selectedFilters($params)
  // {

  //   if ($this->orderBy == $params) {
  //     $this->sort = $this->sort == 'asc' ? 'desc' : 'asc';
  //   } else {
  //     $this->orderBy = $params;
  //     $this->sort = 'desc';
  //   }
  // }

  // PRICE EDIT and SAVE
  public function editPrice()
  {
    $this->editPriceButton = false;
    $this->editQuantityButton = true;
    foreach ($this->getFinancials()->get() as $financial) {
      $this->customPrice[$financial->id] = $financial->pivot->bid_price;
    }

  }

  public function savePrice()
  {
    $this->validate([
      'customPrice.*' => 'required|gt:0',
    ], [
      'customPrice.*.required' => 'Unit cost is required.',
      'customPrice.*.gt' => 'Unit cost must greater than 0.',
    ]);

    // $this->tempPriceTotal = 0;
    // foreach ($this->projectbid->financials as $fin) {
    //   $this->tempPriceTotal += $this->customPrice[$fin->id] * $fin->pivot->quantity;
    // }

    // if ($this->projectbid->reserved_price_switch) {
    //   $this->validate([
    //     'tempPriceTotal' => $this->projectbid->reserved_price_switch ? 'lte:' . $this->projectbid->reserved_price : '',
    //   ], [
    //     'tempPriceTotal.lte' => $this->projectbid->reserved_price_switch ? 'The total price must be less than or equal to PHP ' . number_format($this->projectbid->reserved_price, 2) . '. Your total is PHP ' . number_format($this->tempPriceTotal) . '.' : '',
    //   ]);
    // }

    $this->financialGrandTotal();
    foreach ($this->customPrice as $id => $price) {
      $this->projectbid->financials()->updateExistingPivot($id, ['bid_price' => $price]);
    }

    $this->alertMessage = 'Unit costs saved.';
    $this->dispatch('alert-financial');
  }

  // PRICE EDIT and SAVE

  // Quantity EDIT and SAVE
  public function editQuantity()
  {
    $this->editQuantityButton = false;
    $this->editPriceButton = true;
    foreach ($this->getFinancials()->get() as $financial) {
      $this->customQuantity[$financial->id] = $financial->pivot->quantity;
    }

  }

  public function saveQuantity()
  {

    $this->validate([
      'customQuantity.*' => 'required|gt:0',
    ], [
      'customQuantity.*.required' => 'Quantity is required.',
      'customQuantity.*.gt' => 'Quantity must greater than 0.',
    ]);

    // $this->tempPriceTotal = 0;
    // foreach ($this->projectbid->financials as $fin) {
    //   $this->tempPriceTotal += $this->customQuantity[$fin->id] * $fin->pivot->bid_price;
    // }

    // if ($this->projectbid->reserved_price_switch) {
    //   $this->validate([
    //     'tempPriceTotal' => $this->projectbid->reserved_price_switch ? 'lte:' . $this->projectbid->reserved_price : '',
    //   ], [
    //     'tempPriceTotal.lte' => $this->projectbid->reserved_price_switch ? 'The total price must be less than or equal to PHP ' . number_format($this->projectbid->reserved_price, 2) . '. Your total is PHP ' . number_format($this->tempPriceTotal) . '.' : '',
    //   ]);
    // }

    foreach ($this->customQuantity as $id => $quantity) {
      $this->projectbid->financials()->updateExistingPivot($id, ['quantity' => $quantity]);

    }
    $this->financialGrandTotal();
    // $this->resetValidation('tempPriceTotal');

    $this->alertMessage = 'Quantities saved.';
    $this->dispatch('alert-financial');
  }

  // Quantity EDIT and SAVE

  // REMARKS
  public function remarksModal($id)
  {
    $this->inventoryId = null;
    $this->id = null;
    $this->description = null;
    $this->remarks = null;

    $remarkFinancial = $this->getFinancials()->find($id);
    $this->id = $remarkFinancial->id;
    $this->inventoryId = $remarkFinancial->inventory_id;
    $this->description = $remarkFinancial->description;

    $this->remarks = $remarkFinancial->pivot->remarks;

    $this->dispatch('openRemarksModal');
  }

  public function closeRemarksModal()
  {
    $this->dispatch('closeRemarksModal');
  }
  public function saveRemarks()
  {
    sleep(2);
    $remarkFinancial = $this->getFinancials()->find($this->id);
    $remarkFinancial->pivot->remarks = $this->remarks;
    $remarkFinancial->pivot->save();

    $this->alertMessage = 'Remarks Added!';
    $this->dispatch('closeRemarksModal');
    $this->dispatch('success-message', ['message' => 'Remarks Saved']);
  }
  // REMARKS

  // Upload Ivnetories
  public function updatedInventoryUpload()
  {
    $this->validate([
      'inventoryUpload' => 'mimes:xlsx|max:10240'
    ]);

    $financialsUpload = Excel::toArray(new FinancialEnvelopeInventory, $this->inventoryUpload);
    if ($financialsUpload) {
      foreach ($financialsUpload[0] as $item) {
        if (!isset($item['inventory_id']) || !isset($item['quantity']) || !isset($item['cost'])) {
          $this->addError('inventoryUpload', 'Invalid excel Format!');
          $hasError = true;
          break;
        } else {
          $hasError = false;
          break;
        }
      }
    }

    if ($hasError) {
      return;
    }

    $this->hasFile = true;

    foreach ($financialsUpload[0] as $index => $financial) {
      $duplicateExcel = collect($this->financialsUpload)->where('inventory_id', $financial['inventory_id'])->first();
      $duplicate = $this->getFinancials()->where('inventory_id', $financial['inventory_id'])->first();
      $inventory = Financial::where('inventory_id', $financial['inventory_id'])->first();
      $this->financialsUpload[$index] = [
        'inventory_id' => $financial['inventory_id'],
        'description' => $inventory ? $inventory->description : $financial['description'],
        'quantity' => $financial['quantity'],
        'reserved_price' => $financial['cost'],
        'total' => $financial['quantity'] * $financial['cost'],
        'reflect_price' => $this->projectbid->reflect_price,
        'notExist' => $inventory ? false : true,
        'duplicate' => $duplicate ? true : false,
        'duplicateExcel' => $duplicateExcel ? true : false,
      ];
    }
    $this->checkInvalid($this->financialsUpload);
  }

  public function removeUploaded()
  {
    $this->financialsUpload = [];
    $this->hasFile = false;
  }

  public function removeItem($index)
  {
    unset($this->financialsUpload[$index]);
    $this->checkInvalid($this->financialsUpload);
  }
  public function openUploadedModal()
  {
    $this->dispatch('openUploadedModal');
  }
  public function closeUploadedModal()
  {
    $this->dispatch('closeUploadedModal');
  }

  public function checkInvalid($financials)
  {
    $invalidData = collect($financials)->filter(function ($item) {
      return $item['notExist'] === true || $item['duplicate'] === true || $item['duplicateExcel'] === true;
    });

    if (count($invalidData) == 0) {
      $this->disabledUpload = false;
    } else {
      $this->disabledUpload = true;
    }

    $this->grandTotal = collect($financials)->sum('total');
  }
  public function uploadInventories()
  {

    $this->validate([
      'financialsUpload.*.inventory_id' => 'required',
      'financialsUpload.*.quantity' => 'required|numeric',
      'financialsUpload.*.reserved_price' => 'required|numeric|gt:0',
    ], [
      'financialsUpload.*.quantity.required' => 'Quantity field is required.',
      'financialsUpload.*.quantity.numeric' => 'Quantity field is must be numeric.',
      'financialsUpload.*.reserved_price.numeric' => 'Reserved price field is must be numeric.',
      'financialsUpload.*.reserved_price.required' => 'Unit Cost field must be grater than 0.',
      'financialsUpload.*.inventory_id.required' => 'Inventory Id field is required.',
    ]);
    foreach ($this->financialsUpload as $financialUpload) {
      if ($financialUpload['quantity'] && $financialUpload['reserved_price']) {
        $tempTotal = $financialUpload['quantity'] * $financialUpload['reserved_price'];
        $this->total = $this->projectbid->financials->sum('pivot.bid_price') + $tempTotal;
      }
      $this->grandTotal = $this->total;
      if ($this->projectbid->reserved_price_switch) {
        $this->validate([
          'total' => (bool) $this->projectbid->reserved_price_switch ? 'lte:' . $this->projectbid->reserved_price . ',' : '',
        ], [
          'total.lte' => 'The grand total must not exceed the reserved price of ' . number_format($this->projectbid->reserved_price, 2) . ', your grand total is ' . number_format($this->total, 2) . '.',
        ]);
      }


      $financialModel = Financial::where('inventory_id', $financialUpload['inventory_id'])->first();

      $this->projectbid->financials()->attach($financialModel->id, [
        'crtd_user' => Auth::user()->id,
        'bid_price' => $financialUpload['reserved_price'],
        'quantity' => $financialUpload['quantity'],
      ]);

      // Financial::create($data);
    }
    return redirect()
      ->route('project-bidding.financial-envelopes', $this->projectbid->id)
      ->with('success', 'Financial Envelope has been successfully updated!');
  }
  // Upload Ivnetories

  public function removeFinancialModal($index)
  {
    $this->selectedFinancial = $this->getFinancials()->where('financials.id', $index)->first();

    $this->dispatch('openRemoveModal');
  }
  public function closeRemoveModal()
  {
    $this->dispatch('closeRemoveModal');
  }
  public function removeFinancial()
  {
    $this->projectbid->financials()->detach($this->selectedFinancial->id);

    $this->financialLists = $this->getFinancials();
    $this->financialGrandTotal();
    $this->updateVendorStatus();
    $this->dispatch('closeRemoveModal');
    $this->dispatch('success-message', ['message' => 'Financial has been successfully removed from the project!']);

    // return redirect()
    //   ->route('project-bidding.financial-envelopes', $this->projectbid->id)
    //   ->with('success', 'Financial has been successfully removed from the project!');
  }

  public function addFinancial()
  {
    $this->initFinancial = $this->getFinancials()->get();
    $this->dispatch('openAddFinancialModal');
  }
  public function updateBidFinancials()
  {
    $this->financialLists = $this->getFinancials();
    $this->financialGrandTotal();
    $initArray = $this->initFinancial->pluck('id')->toArray();
    $financialArray = $this->financialLists->get()->pluck('id')->toArray();
    $first = array_diff($financialArray, $initArray);
    $second = array_diff($initArray, $financialArray);

    if (count($first) != 0 || count($second) != 0) {
      $this->updateVendorStatus();
    }
    // dd($this->financialTotal);
  }

  public function updateVendorStatus()
  {
    $vendors = $this->projectbid->vendors()->wherePivot('status', 'On Hold')->get();
    foreach ($vendors as $vendor) {
      $envelopeStatus = $vendor->envelopeStatus->where('bidding_id', $this->projectbid->id)->where('envelope', 'financial')->first();
      $bidStatus = $vendor->bidStatus->where('bidding_id', $this->projectbid->id)->first();

      $envelopeStatus->update(['status' => false]);
      $bidStatus->update(['complete' => false]);
    }
  }

  // Remakrs for Financial Envelope
  public function remarksModalBid()
  {
    $this->dispatch('openFinancialRemarksModal');
  }
  public function closeFinancialRemarksModal()
  {
    $this->dispatch('closeFinancialRemarksModal');
  }

  // Save Evnvelope Remarks
  public function saveEnvelopeRemarks()
  {
    if ($this->envelopeRemarks) {
      $this->envelopeRemarks->update([
        'remarks' => $this->envelopeRemarksInput,
        'upd_user' => Auth::user()->id
      ]);
    } else {
      $this->projectbid->envelopeRemarks()->create([
        'envelope' => 'financial',
        'remarks' => $this->envelopeRemarksInput,
        'crtd_user' => Auth::user()->id
      ]);
    }

    $this->envelopeRemarks = $this->projectbid->envelopeRemarks->where('envelope', 'financial')->first();
    $this->envelopeRemarksInput = $this->envelopeRemarks ? $this->envelopeRemarks->remarks : '';

    $this->dispatch('closeFinancialRemarksModal');
    $this->dispatch('success-message', ['message' => 'Financial envelope remarks saved!']);
  }

  // Compute Financial Total
  public function financialGrandTotal()
  {
    $this->tempPriceTotal = 0;
    foreach ($this->projectbid->financials as $fin) {
      if (!$this->editPriceButton) {
        $this->tempPriceTotal += $this->customPrice[$fin->id] * $fin->pivot->quantity;
        // $this->editPriceButton = true;
      } elseif (!$this->editQuantityButton) {
        $this->tempPriceTotal += $this->customQuantity[$fin->id] * $fin->pivot->bid_price;
        // $this->editQuantityButton = true;
      } else if ($this->editPriceButton && $this->editQuantityButton) {
        // dd($this->editPriceButton);
        $this->tempPriceTotal += $fin->pivot->bid_price * $fin->pivot->quantity;
      }
    }
    // dd($this->tempPriceTotal);
    $this->financialTotal = $this->tempPriceTotal;
    // dd($this->financialTotal,  (int)$this->projectbid->reserved_price);
    if ($this->projectbid->reserved_price_switch) {
      // dd(1);
      if ($this->tempPriceTotal != $this->projectbid->reserved_price) {
        $this->addError('tempPriceTotal', 'The financial total must equal PHP ' . number_format($this->projectbid->reserved_price, 2) . ' reserved price.');
        // dd($this->tempPriceTotal);
      } else {
        $this->resetValidation('tempPriceTotal');
      }
      // $this->editQuantityButton = true;
      // $this->editPriceButton = true;
    }
    $this->editQuantityButton = true;
    $this->editPriceButton = true;
    // $this->tempPriceTotal = 0;
    // foreach ($this->projectbid->financials as $fin) {
    //   $this->tempPriceTotal += $this->customQuantity[$fin->id] * $fin->pivot->bid_price;
    // }

    // if ($this->projectbid->reserved_price_switch) {
    //   $this->validate([
    //     'tempPriceTotal' => $this->projectbid->reserved_price_switch ? 'lte:' . $this->projectbid->reserved_price : '',
    //   ], [
    //     'tempPriceTotal.lte' => $this->projectbid->reserved_price_switch ? 'The total price must be less than or equal to PHP ' . number_format($this->projectbid->reserved_price, 2) . '. Your total is PHP ' . number_format($this->tempPriceTotal) . '.' : '',
    //   ]);
    // }

  }

  public function render()
  {
    if (!$this->financialLists) {
      $this->financialLists = $this->getFinancials();
    }


    return view('livewire.admin.bidding.envelope.financial-envelope', [
      'financials' => $this->financialLists->paginate(10)
    ]);
  }
}
