<?php

namespace App\Livewire\Admin\Bidding\Envelope;

use Livewire\Component;
use App\Models\Financial;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use App\Models\FinancialGroup;
use App\Models\ProjectBidding;
use Illuminate\Support\Facades\Auth;

class FinancialLists extends Component
{
  use WithPagination;
  public $groups, $bidding, $selectedGroup, $bidingFinancials, $checkFinancials = [], $search, $grandTotal, $clickOrder;
  protected $financialLists;
  protected $listeners = ['openAddFinancialModal'];
  public $currentSearch;

  public function mount($id)
  {
    $this->bidding = ProjectBidding::findOrFail($id);
    $this->financialLists = $this->getFinancials();
    $this->groups = $this->getFinancialGroups()->get();
    $this->bidingFinancials = $this->bidding->financials;
    $financialDatas = $this->bidingFinancials;
    foreach ($financialDatas as $data) {
      $this->checkFinancials[$data->id] = true;
    }
  }

  public function getFinancials()
  {
    if ($this->selectedGroup) {
      $groups = $this->getFinancialGroups()->where('id', $this->selectedGroup)->first()->financials()->where('scrap', $this->bidding->scrap ? true : false)->orderBy('inventory_id', 'asc');
      return $groups;
    } else {
      return Financial::where('scrap', $this->bidding->scrap ? true : false)->orderBy('inventory_id', 'asc');
      // return Financial::where('scrap', false)->orderBy('inventory_id', 'asc');
    }
  }
  public function getFinancialGroups()
  {
    return new FinancialGroup;
  }

  public function updatedSearch($search)
  {
    $this->currentSearch = $search;
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

  public function updatedSelectedGroup()
  {
    $this->checkFinancials = [];
    if ($this->selectedGroup) {
      $this->checkFinancials = [];
      $groups = $this->getFinancialGroups()->where('id', $this->selectedGroup)->first()->financials();
      foreach ($groups->get() as $group) {
        $this->checkFinancials[$group->id] = true;
      }
    } else {
      $financialDatas = $this->bidingFinancials->pluck('id')->toArray();
      foreach ($financialDatas as $data) {
        $this->checkFinancials[$data] = true;
      }
    }

    $this->applySearch();
  }


  public function updatedCheckFinancials($value, $key)
  {
    if (!is_array($this->clickOrder)) {
      $this->clickOrder = [];
    }

    if ($value) {
      $this->clickOrder[] = $key;
    } else {
      $this->clickOrder = array_diff($this->clickOrder, [$key]);
    }

    $this->applySearch();
  }

  public function selectedFinancials()
  {

    $checkItems = array_filter($this->checkFinancials, function ($value) {
      return $value === true;
    });
    // Ensure clickOrder is an array
    if (!is_array($this->clickOrder)) {
      $this->clickOrder = [];
    }

    // Merge initial data with clickOrder to preserve initial data
    $initialKeys = array_keys($this->checkFinancials);
    $mergedKeys = array_unique(array_merge($this->clickOrder, $initialKeys));

    // Get sorted keys based on merged keys and checked items
    $sortedKeys = array_intersect($mergedKeys, array_keys($checkItems));

    // Create sorted financials array
    $sortedFinancials = array_fill_keys($sortedKeys, true);

    return array_keys($sortedFinancials);
  }


  public function addFinancials()
  {
    $ids = $this->selectedFinancials();

    $this->grandTotal = 0;
    $currentFinancials = $this->bidding->financials;
    foreach ($ids as $id) {

      $fin = $this->bidding->financials()->where('financial_id', $id)->first();
      if ($fin) {
        $this->grandTotal += $fin->pivot->bid_price * $fin->pivot->quantity;
      } else {
        $price = $this->getFinancials()->where('financials.id', $id)->first()->unit_cost;
        // $this->bidding->financials()->attach($id, [
        //   'crtd_user' => Auth::user()->id,
        //   'bid_price' => $price == 0 ? 1 : $price,
        //   'quantity' => 1
        // ]);
        $this->grandTotal += ($price == 0 ? 1 : $price) * 1;
      }
    }

    if ($this->bidding->reserved_price_switch) {
      $this->validate([
        'grandTotal' => $this->bidding->reserved_price_switch ? 'lte:' . $this->bidding->reserved_price : '',
      ], [
        'grandTotal.lte' => 'The grand total must be less than or equal to PHP ' . number_format($this->bidding->reserved_price, 2) . '.',
      ]);
    }

    // Prepare the data for sync
    $syncData = [];
    $existingFinancialIds = $this->bidding->financials()->pluck('financials.id')->toArray();
    $detachIds = array_diff($existingFinancialIds, $ids);

    foreach ($ids as $id) {
      $retrieveData = $this->getFinancials()->where('financials.id', $id)->first();
      $price = $retrieveData ? $retrieveData->unit_cost : 0;
      $bidPrice = $price == 0 ? 1 : $price;

      $existingRecord = $this->bidding->financials()->wherePivot('financial_id', $id)->first();

      if ($existingRecord) {
        $this->bidding->financials()->updateExistingPivot($id, [
          'upd_user' => Auth::user()->id,
        ]);
      } else {
        $syncData[$id] = [
          'crtd_user' => Auth::user()->id,
          'bid_price' => $bidPrice,
          'quantity' => 1,
        ];
      }
    }

    if (!empty($syncData)) {
      $this->bidding->financials()->syncWithoutDetaching($syncData);
    }

    if (!empty($detachIds)) {
      $this->bidding->financials()->detach($detachIds);
    }
    $this->bidding->load('financials');

    $this->search = '';
    $this->currentSearch = '';

    $this->dispatch('updateBidFinancials');
    $this->dispatch('closeFinancialModal');
    $this->dispatch('success-message', ['message' => 'Financial Envelope has been successfully updated!']);
    // return redirect()
    //   ->route('project-bidding.financial-envelopes', $this->bidding->id)
    //   ->with('success', 'Financial Envelope has been successfully updated!');
  }
  public function openAddFinancialModal()
  {
    $this->checkFinancials = [];
    $this->bidingFinancials = $this->bidding->financials;
    $financialDatas = $this->bidingFinancials;
    foreach ($financialDatas as $data) {
      $this->checkFinancials[$data->id] = true;
    }
    $this->dispatch('openFinancialModal');
  }

  public function closeFinancialModal()
  {
    $this->dispatch('closeFinancialModal');
  }

  private function applySearch()
  {
    if ($this->currentSearch) {
      $fields = [
        'inventory_id',
        'description',
        'class_id',
        'uom',
      ];

      $model = $this->getFinancials();
      $this->financialLists = SearchModel::search($model, $fields, $this->currentSearch);
    } else {
      $this->financialLists = $this->getFinancials();
    }
  }
  public function render()
  {
    if (!$this->financialLists) {
      $this->financialLists = $this->getFinancials();
    }
    return view('livewire.admin.bidding.envelope.financial-lists', [
      'financials' => $this->financialLists->paginate(10, ['*'], 'financialLists')
    ]);
  }
}
