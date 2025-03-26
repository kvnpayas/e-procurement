<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use App\Helpers\SearchModel;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
  protected $listeners = ['testEvent'];
  public $search, $menuLists = [], $menuToggle = true, $user, $menus;

  public function mount()
  {
    $this->user = Auth::user();
    $this->menus = [
      [
        'name' => 'Bid Invitation',
        'route_name' => 'bid-invitation',
      ],
      [
        'name' => 'Bid Lists',
        'route_name' => 'bid-lists',
      ],
      [
        'name' => 'Bid Results',
        'route_name' => 'bid-results',
      ]
    ];
  }
  public function testEvent($menuName)
  {
    $this->menuToggle = $menuName;
    //  dd($this->menuToggle);
  }

  public function updatedSearch($search)
  {
    $fields = [
      'name',
    ];

    $model = collect($this->menus);

    if ($search) {
      $this->menuLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->menuLists = [];
    }
  }
  public function render()
  {
    return view('livewire.layout.sidebar');
  }
}
