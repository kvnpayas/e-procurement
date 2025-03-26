<?php

namespace App\Livewire\EnvelopeMaintenance;

use Livewire\Component;
use App\Models\Financial;
use App\Helpers\SearchModel;
use App\Models\ClassProduct;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;

class FinancialMaintenance extends Component
{
  use WithPagination;

  protected $inventoryItems;
  public $inventoryId, $description, $class, $unitOfMeasure, $unitCost;
  public $editInventory;
  public $classIds;
  public $search, $orderBy = 'created_at', $sort = 'desc';
  public function mount()
  {
    $this->inventoryItems = $this->getInventories();
    $this->classIds = ClassProduct::all();
  }

  public function getInventories()
  {
    return Financial::where('scrap', false)->orderBy($this->orderBy, $this->sort);
  }

  public function getApiInventories()
  {
    $response = Http::withHeaders([
      'Accept' => 'Application/json',
      'Authorization' => apiTokenBC(),
    ])->get(BCUrl() . 'CustomizedBusinessCentral/GetAllBusCenItemList');
    // dd(collect($response->json())->where('type', 1));
    return $response->json();
  }
  public function syncFinancials()
  {

    $apiInventories = $this->getApiInventories();
    foreach ($apiInventories as $inventory) {
      Financial::updateOrInsert(
        ['inventory_id' => $inventory['no']],
        [
          'description' => $inventory['description'],
          'class_id' => $inventory['itemCategoryCode'],
          'uom' => $inventory['baseUnitOfMeasure'],
          'unit_price' => $inventory['unitPrice'],
          'unit_cost' => $inventory['unitCost'],
          'type' => $inventory['type'],
          'available_quantity' => 0,
          'quantity_on_hand' => 0,
        ]
      );

    }

    return redirect('financial-maintenance')->with('success', 'Financials is updated.');

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

    $model = $this->getInventories();
    if ($search) {
      $this->inventoryItems = SearchModel::search($model, $fields, $search);
    } else {
      $this->inventoryItems = $model;
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

  public function openCreateModal()
  {
    $this->dispatch('openModal');
  }
  public function createModalClose()
  {
    $this->resetValidation();
    $this->dispatch('closeModal');
  }
  public function createFinancial()
  {
    $this->validate([
      'inventoryId' => 'required|max:11|min:11|unique:financials,inventory_id',
      'description' => 'required',
      'class' => 'required',
      'unitOfMeasure' => 'required',
      'unitCost' => 'required|numeric',
    ]);

    $data = [
      'inventory_id' => strtoupper($this->inventoryId),
      'description' => $this->description,
      'class_id' => $this->class,
      'uom' => $this->unitOfMeasure,
      'unit_price' => 0,
      'scrap' => 0,
      'unit_cost' => $this->unitCost,
      'available_quantity' => 0,
      'quantity_on_hand' => 0,
      // 'crtd_user' => Auth::user()->id,
    ];
    Financial::create($data);
    // return redirect()
    //   ->route('financial-envelope')
    //   ->with('success', 'Financial has been successfully created!');
    $this->dispatch('closeModal');
    $this->dispatch('success-message', ['message' => 'Financial has been successfully created!']);

  }

  // Open and Close Edit Modal
  public function openEditModal($id)
  {
    $this->editInventory = $this->getInventories()->where('id', $id)->first()->makeHidden(['created_at', 'updated_at'])->toArray();
    $this->dispatch('openEditModal');
  }
  public function closeEditModal()
  {
    $this->resetValidation();
    $this->dispatch('closeEditModal');
  }

  // Update Financial
  public function updateFinancial()
  {
    $this->editInventory['uom'] = strtoupper($this->editInventory['uom']);
    $this->validate([
      'editInventory.inventory_id' => 'required|max:11|min:11|unique:financials,inventory_id,' . $this->editInventory['id'],
      'editInventory.description' => 'required',
      'editInventory.class_id' => 'required',
      'editInventory.uom' => 'required',
      'editInventory.unit_cost' => 'required|numeric',
    ], [
      'editInventory.description.required' => 'The description field is required.',
      'editInventory.class_id.required' => 'The class field is required.',
      'editInventory.uom.required' => 'The uom field is required.',
      'editInventory.unit_cost.required' => 'The unit cost field is required.',
    ]);

    Financial::where('id', $this->editInventory['id'])->update([
      'inventory_id' => $this->editInventory['inventory_id'],
      'description' => $this->editInventory['description'],
      'class_id' => $this->editInventory['class_id'],
      'uom' => $this->editInventory['uom'],
      'unit_cost' => $this->editInventory['unit_cost'],
    ]);

    $this->dispatch('closeEditModal');
    $this->dispatch('success-message', ['message' => 'Financial has been successfully updated!']);
    // return redirect()
    //   ->route('financial-envelope')
    //   ->with('success', 'Financial has been successfully updated!');
  }

  public function updatedInventoryId($value)
  {
    $this->validate([
      'inventoryId' => 'min:11|max:11',
    ]);
  }
  public function updatedUnitOfMeasure($value)
  {
    $this->unitOfMeasure = strtoupper($value);
  }

  public function render()
  {
    if (!$this->inventoryItems) {
      $this->inventoryItems = $this->getInventories();
    }
    return view('livewire.envelope-maintenance.financial-maintenance', [
      'inventories' => $this->inventoryItems->paginate(10),
    ]);
  }
}
