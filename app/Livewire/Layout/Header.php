<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use Illuminate\Support\Facades\Route;

class Header extends Component
{
  public $routes = [];
  public $currentRoutes;
  public $routestTest = [];
  public function mount()
  {
    $this->routes = explode('.', Route::currentRouteName());
    $this->currentRoutes = end($this->routes);
  }

  public function render()
  {
    return view('livewire.layout.header');
  }
}
