<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ClassProduct;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;

class ClassMaintenance extends Component
{
  use WithPagination;
  protected $classesList;
  public $alertMessage;

  public function mount()
  {
      $this->classesList = $this->getClass()->paginate(10);
  }
  public function getClass()
  {
      return new ClassProduct();
  }

  public function getApiClass()
  {
      $response = Http::withHeaders([
          'Accept' => 'Application/json',
          'Authorization' => 'Bearer ' . apiTokenBC(),
      ])->get(BCUrl() . 'BusCenCustomizeEndpoints/GetAllBCItemCategoryList');
      return $response->json();
  }

  public function syncClassess()
  {
      $apiClasses = collect($this->getApiClass());

      $localClassCodes = $this->getClass()->get()->pluck('code')->toArray();

      foreach ($apiClasses as $apiClass) {
          if (!in_array($apiClass['code'], $localClassCodes)) {
              ClassProduct::create([
                  'code' => $apiClass['code'],
                  'description' => $apiClass['description'],
              ]);
          } else {
              $classToUpdate = $this->getClass()->firstWhere('code', $apiClass['code']);
              $classToUpdate->update([
                  'description' => $apiClass['description'],
              ]);
          }
      }

      $this->alertMessage = 'Success!';
      $this->dispatch('alert-message');

      return redirect('class-maintenance')->with('success', 'Products/Services is updated.');
  }
  public function render()
  {
    if(!$this->classesList){
      $this->classesList = $this->getClass()->paginate(10);
    }
    return view('livewire.admin.class-maintenance', [
      'classes' => $this->classesList,
    ]);
  }
}
