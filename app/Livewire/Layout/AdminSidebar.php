<?php

namespace App\Livewire\Layout;

use App\Models\Menu;
use Livewire\Component;
use App\Helpers\SearchModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminSidebar extends Component
{
  public $user, $modules, $menus, $currentRoute, $toggleMenu = false;
  public $search, $menuLists = [], $menuToggle = true;
  protected $listeners = ['testEvent'];
  public function mount()
  {
    $allModules = [
      [
        'name' => 'Maintenance',
        'img' => 'img/admin-maintenance.png',
        'classId' => 'maintenanceMenu',
        'classIdDiv' => 'main-maintenance',
      ],
      [
        'name' => 'Envelope Maintenance',
        'img' => 'img/envelope-maintenance.png',
        'classId' => 'envelopeMaintenanceMenu',
        'classIdDiv' => 'main-envelope-maintenance',
      ],
      [
        'name' => 'e-Procurement',
        'img' => 'img/admin-e-proc.png',
        'classId' => 'eProcurement',
        'classIdDiv' => 'main-procurement',
      ],
    ];

    $this->user = Auth::user();
    $this->menus = Menu::all();
    $userModules = $this->user->role->menus->unique('module')->pluck('module')->toArray();
    $this->modules = collect($allModules)->whereIn('name', $userModules)->toArray();
    $route = Route::currentRouteName();
    $this->currentRoute = explode('.',$route)[0];
    foreach($this->modules as $index => $module){
      $this->modules[$index]['menus'] = $this->user->role->menus->where('module', $module['name'])->pluck('route_name', 'name')->toArray();
      $this->modules[$index]['showMenu'] = in_array($this->currentRoute, $this->modules[$index]['menus']);
    }
    // dd($this->modules);
  }
  public function toggleModule($index)
  {
    $this->modules[$index]['showMenu'] = !$this->modules[$index]['showMenu'];
    // $this->dispatch('toggleModule', (string) $classId);
  }

  public function updatedSearch($search)
  {
    $fields = [
      'name',
    ];

    $model = $this->user->role->menus;
    if ($search) {
      $this->menuLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->menuLists = [];
    }
  }
  public function testEvent($menuName)
  {
   $this->menuToggle = $menuName;
  //  dd($this->menuToggle);
  }
  public function render()
  {
    return view('livewire.layout.admin-sidebar');
  }
}
