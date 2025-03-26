<?php

namespace App\Livewire\Layout;

use App\Helpers\LogsActivity;
use Livewire\Component;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Nav extends Component
{
  public $toggleDropDown = false;
  public function logout(Logout $logout): void
  {

    LogsActivity::loggedOut();
    $logout();

    $this->redirect('/', navigate: true);
  }
  public function dropdownMenu()
  {
    $this->toggleDropDown = !$this->toggleDropDown;
  }
  public function render()
  {
    return view('livewire.layout.nav');
  }
}
