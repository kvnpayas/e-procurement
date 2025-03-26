<?php

namespace App\Livewire\Admin;

use App\Models\Menu;
use App\Models\Role;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AccessRights extends Component
{
  public $roles;
  public $roleData, $menus = [], $modules = [], $checkMenus = [], $roleAccess = [], $accessRights = [], $roleName;
  public function mount()
  {
    $this->roles = Role::whereNotIn('role_name', ['Admin', 'vendor'])->get();
  }
  public function menuModal($id)
  {
    $this->checkMenus = [];
    $this->accessRights = [];
    $this->roleData = $this->roles->where('id', $id)->first();
    $this->roleName = $this->roleData ? $this->roleData->role_name : '';
    $this->menus = Menu::all();
    $this->modules = $this->menus->unique('module')->pluck('module')->toArray();
    foreach ($this->roleData->menus as $menu) {
      $this->checkMenus[$menu->id] = true;
      $this->accessRights[$menu->id] = [
        'view' => (bool) $menu->pivot->view,
        'create' => (bool) $menu->pivot->create,
        'update' => (bool) $menu->pivot->update,
        'review' => (bool) $menu->pivot->review,
        'menu_check' => true,
      ];
    }
    // $this->roleAccess['view'] = (bool) $this->roleData->view;
    // $this->roleAccess['create'] = (bool) $this->roleData->create;
    // $this->roleAccess['update'] = (bool) $this->roleData->update;
    // $this->roleAccess['review'] = (bool) $this->roleData->review;

    $this->dispatch('openMenuModal');
  }

  public function checkMenusValue($id)
  {
    $newVal = $this->checkMenus[$id];
    if ($newVal) {
      $this->accessRights[$id] = [
        'view' => true,
        'create' => false,
        'update' => false,
        'review' => false,
        'menu_check' => true,
      ];
    } else {
      $this->accessRights[$id] = [
        'view' => false,
        'create' => false,
        'update' => false,
        'review' => false,
        'menu_check' => false,
      ];
    }

  }
  public function closeMenuModal()
  {
    $this->checkMenus = [];
    $this->dispatch('closeMenuModal');
  }
  public function updateMenus()
  {
    $checkItems = array_filter($this->checkMenus, function ($value) {
      return $value === true;
    });

    $selectedIds = array_keys($checkItems);

    // Update Role Acces rights
    // $this->roleData->update($this->roleAccess);

    // Delete existing eligbilities when remove on selcected
    foreach ($this->roleData->menus as $menu) {
      if (!in_array($menu->id, $selectedIds)) {
        $this->roleData->menus()->detach($menu->id);
      }
    }

    // Add eligbilities to pivot table
    foreach ($selectedIds as $id) {
      $dataExists = $this->roleData->menus()->where('menus.id', $id)->first();
      if ($dataExists) {
        $this->roleData->menus()->updateExistingPivot($id, [
          'upd_user' => Auth::user()->id,
          'view' => $this->accessRights[$id]['view'],
          'create' => $this->accessRights[$id]['create'],
          'update' => $this->accessRights[$id]['update'],
          'review' => $this->accessRights[$id]['review'],
        ]);
      } else {
        $this->roleData->menus()->attach($id, [
          'crtd_user' => Auth::user()->id,
          'view' => $this->accessRights[$id]['view'],
          'create' => $this->accessRights[$id]['create'],
          'update' => $this->accessRights[$id]['update'],
          'review' => $this->accessRights[$id]['review'],
        ]);
      }
    }

    // return redirect()
    //   ->route('access-rights')
    //   ->with('success', 'Access rights has been successfully updated!');
    $this->dispatch('closeMenuModal');
    $this->dispatch('success-message', ['message' => 'Access rights has been successfully updated!']);
  }
  public function render()
  {
    return view('livewire.admin.access-rights');
  }
}
