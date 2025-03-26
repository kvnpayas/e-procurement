<div class="overflow-x-auto shadow-md sm:rounded-lg ">
  <div wire:loading wire:target="syncFinancials">
    @include('partial.page-loader')
  </div>
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Financial Maintenance
      <p class="mt-1 text-sm font-normal text-gray-500 ">List of all financials.
      </p>
      <div class="flex justify-between pt-5">
        <div class="flex">
          <span
            class="inline-flex items-center px-3 text-sm tei-text-light tei-bg-primary border rounded-e-0 border-gray-300 border-e-0 rounded-s-md ">
            Search
          </span>
          <div class="relative">
            <input type="text" wire:model.live.debounce.500ms="search"
              class="block w-full text-xs tei-text-accent bg-transparent rounded-e-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 tei-focus-secondary peer"
              placeholder=" " />
            <label
              class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-0 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-orange-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Search
              here</label>
          </div>
        </div>
        <div>
          @if (roleAccessRights('create'))
            <button type="button" wire:click.prevent="openCreateModal" wire:loading.remove
              wire:target="openCreateModal"
              class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-2 hover:scale-110 transition-transform duration-300">
              Create Financials
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="openCreateModal">
              <div class="flex justify-center ">
                <div class="loading-small loading-main">
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
              </div>
            </div>
          @endif
          {{-- <button type="button"
            class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-2 hover:scale-110 transition-transform duration-300"
            data-modal-target="accept-modal" data-modal-toggle="accept-modal">
            Update Financials
          </button> --}}
        </div>
      </div>
    </caption>
    <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
      <tr>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Inventory ID</span>
            <button wire:click.prevent="selectedFilters('inventory_id')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'inventory_id' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'inventory_id' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Description</span>
            <button wire:click.prevent="selectedFilters('description')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'description' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'description' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Class ID</span>
            <button wire:click.prevent="selectedFilters('class_id')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'class_id' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'class_id' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Unit of Measure</span>
            <button wire:click.prevent="selectedFilters('uom')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'uom' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'uom' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Unit Cost</span>
            <button wire:click.prevent="selectedFilters('unit_cost')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'unit_cost' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'unit_cost' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        {{-- <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Available Quantity</span>
            <button wire:click.prevent="selectedFilters('available_quantity')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'available_quantity' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'available_quantity' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th> --}}
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>type</span>
            <button wire:click.prevent="selectedFilters('type')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'type' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'type' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <span>Action</span>
        </th>
      </tr>
    </thead>
    <tbody>
      @forelse ($inventories as $inventory)
        <tr class="bg-white border-b hover:shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)]">
          <th scope="row" class="px-6 font-medium tei-text-secondary whitespace-nowrap ">
            {{ $inventory->inventory_id }}
          </th>
          <td class="px-6">
            {{ $inventory->description }}
          </td>
          <td class="px-6">
            {{ $inventory->class_id }}
          </td>
          <td class="px-6">
            {{ $inventory->uom }}
          </td>
          <td class="px-6">
            PHP {{ number_format($inventory->unit_cost, 2) }}
          </td>
          {{-- <td class="px-6">
            {{ $inventory->available_quantity }}
          </td> --}}
          <td class="px-6">
            @php
              if ($inventory->type === 0) {
                  $type = 'Inventory';
              } elseif ($inventory->type == 1) {
                  $type = 'Services';
              } elseif ($inventory->type == 3) {
                  $type = 'Non-inventory';
              } else {
                  $type = null;
              }
            @endphp
            {{ $type }}
          </td>
          <td class="px-6">
            @if (roleAccessRights('create'))
              <button type="button" class="hover:scale-125 transition-transform duration-300"
                wire:click.prevent="openEditModal({{ $inventory->id }})" wire:loading.remove
                wire:target="openEditModal({{ $inventory->id }})">
                <i class="fa-solid fa-pen-to-square text-green-600 text-lg"></i>
              </button>
              <x-loading-spinner color="var(--secondary)" target="openEditModal({{ $inventory->id }})" />
            @endif
          </td>
        </tr>
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">No financials on records.</span>
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>
  {{-- <x-action-message class="me-3" on="alert-eligibility">
    {{ __($alertMessage) }}
  </x-action-message> --}}
  {{ $inventories->links('livewire.layout.pagination') }}
  {{-- @include('components.toast-message') --}}

  {{-- Accept Modal --}}
  <div id="accept-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          data-modal-hide="accept-modal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center">
          <svg class="mx-auto mb-4 tei-text-primary w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Update Financials?</h3>
          <button data-modal-hide="accept-modal" type="button"
            class="text-white bg-green-600 hover:bg-green-900  focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click= "syncFinancials">
            Confirm
          </button>
          <button data-modal-hide="accept-modal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10">
            Cancel</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Accept Modal --}}

  {{-- Create Financial --}}
  <div id="create-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-3xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Create Financial
          </h3>
          <button type="button" wire:click.prevent="createModalClose"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <form wire:submit="createFinancial">
          <div class="p-4 md:p-5 space-y-4">
            <p class="mt-1 text-xs font-extrabold tei-text-secondary italic"> Please review all the input details
              before
              creating. Thank you </p>
            <div class="mb-6">
              <div>
                <label for="inventoryId" class="block mb-2 text-sm font-extrabold tei-text-primary ">Inventory
                  Id</label>
                <input type="text" id="name" wire:model.live="inventoryId" placeholder="Inventory Id"
                  class="{{ $errors->has('inventoryId') ? 'border border-red-500 text-gray-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-xs rounded-lg block w-full sm:w-1/2 p-1.5" />
                @error('inventoryId')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div class="mt-5">
                <label for="description" class="block mb-2 text-sm font-extrabold tei-text-primary ">Financial
                  Description</label>
                <textarea id="description" rows="4"
                  class="{{ $errors->has('description') ? ' border border-red-500 text-gray-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-xs rounded-lg border"
                  placeholder="Financial Description" wire:model="description"></textarea>
                @error('description')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <div>
                    <label for="inventoryId" class="block mb-2 text-sm font-extrabold tei-text-primary ">Class</label>
                    <select wire:model="class"
                      class="{{ $errors->has('description') ? ' border border-red-500 text-gray-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-500 focus:ring-gray-500 focus:border-gray-500 ' }} block w-full p-1.5 text-xs rounded-lg border">
                      <option value="">Select Class</option>
                      @foreach ($classIds as $id)
                        <option value='{{ $id->code }}'>{{ $id->description }}</option>
                      @endforeach
                    </select>
                    @error('class')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                  <div class="mt-5">
                    <label for="unitOfMeasure" class="block mb-2 text-sm font-extrabold tei-text-primary ">Unit of
                      Measure</label>
                    <input type="text" id="name" wire:model.live="unitOfMeasure"
                      placeholder="Unit of measure"
                      class="{{ $errors->has('unitOfMeasure') ? 'border border-red-500 text-gray-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-xs rounded-lg block w-1/2 p-1.5" />
                    @error('unitOfMeasure')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                </div>
                <div>
                  <div>
                    <label for="unitCost" class="block mb-2 text-sm font-extrabold tei-text-primary ">Unit Cost
                      (PHP)</label>
                    <input type="text" id="name" wire:model="unitCost" placeholder="Unit cost"
                      class="{{ $errors->has('unitCost') ? 'border border-red-500 text-gray-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-xs rounded-lg block w-1/2 p-1.5" />
                    @error('unitCost')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                  {{-- <div class="mt-5">
                    <label for="uom" class="block mb-2 text-sm font-extrabold tei-text-primary ">Unit of Measure</label>
                    <input type="number" id="name" wire:model="uom" placeholder="Unit of measure"
                      class="{{ $errors->has('uom') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-xs rounded-lg block w-1/2 p-1.5" />
                    @error('uom')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  </div> --}}
                </div>
              </div>
            </div>
          </div>
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            <button type="submit" wire:loading.remove wire:target="createFinancial"
              class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
              Create
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="createFinancial">
              <div class="flex justify-center ">
                <div class="loading-small loading-main">
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
              </div>
            </div>
            <button type="button" wire:click.prevent="createModalClose" data-modal-hide="create-modal"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- End Create Financial --}}

  {{-- Edit Financial --}}
  <div id="edit-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-3xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Update Financial
          </h3>
          <button type="button" wire:click.prevent="closeEditModal"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <form wire:submit="updateFinancial">
          @if ($editInventory)
            <div class="p-4 md:p-5 space-y-4">
              <p class="mt-1 text-xs font-extrabold tei-text-secondary italic"> Please review all the input details
                before
                updating. Thank you </p>
              <div class="mb-6">
                <div>
                  <label for="inventoryId" class="block mb-2 text-sm font-extrabold tei-text-primary ">Inventory
                    Id</label>
                  <span class="tei-text-secondary">{{ $editInventory['inventory_id'] }}</span>
                </div>
                <div class="mt-5">
                  <label for="description" class="block mb-2 text-sm font-extrabold tei-text-primary ">Financial
                    Description</label>
                  <textarea id="description" rows="4"
                    class="{{ $errors->has('editInventory.description') ? ' border border-red-500 text-gray-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-xs rounded-lg border"
                    placeholder="Financial Description" wire:model="editInventory.description"></textarea>
                  @error('editInventory.description')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                      {{ $message }}
                    </p>
                  @enderror
                </div>
                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <div>
                      <label for="inventoryId"
                        class="block mb-2 text-sm font-extrabold tei-text-primary ">Class</label>
                      <select wire:model="editInventory.class_id"
                        class="{{ $errors->has('editInventory.class_id') ? ' border border-red-500 text-gray-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-500 focus:ring-gray-500 focus:border-gray-500 ' }} block w-full p-1.5 text-xs rounded-lg border">
                        <option value="">Select Class</option>
                        @foreach ($classIds as $id)
                          <option value='{{ $id->code }}'>{{ $id->description }}</option>
                        @endforeach
                      </select>
                      @error('editInventory.class_id')
                        <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                          {{ $message }}
                        </p>
                      @enderror
                    </div>
                    <div class="mt-5">
                      <label for="unitOfMeasure" class="block mb-2 text-sm font-extrabold tei-text-primary ">Unit of
                        Measure</label>
                      <input type="text" id="name" wire:model="editInventory.uom"
                        placeholder="Unit of measure"
                        class="{{ $errors->has('editInventory.uom') ? 'border border-red-500 text-gray-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-xs rounded-lg block w-1/2 p-1.5" />
                      @error('editInventory.uom')
                        <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                          {{ $message }}
                        </p>
                      @enderror
                    </div>
                  </div>
                  <div>
                    <div>
                      <label for="unitCost" class="block mb-2 text-sm font-extrabold tei-text-primary ">Unit Cost
                        (PHP)</label>
                      <input type="text" id="name" wire:model="editInventory.unit_cost"
                        placeholder="Unit cost"
                        class="{{ $errors->has('editInventory.unit_cost') ? 'border border-red-500 text-gray-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-xs rounded-lg block w-1/2 p-1.5" />
                      @error('editInventory.unit_cost')
                        <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                          {{ $message }}
                        </p>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endif
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            <button type="submit" wire:loading.remove wire:target="updateFinancial"
              class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
              Update
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="updateFinancial">
              <div class="flex justify-center ">
                <div class="loading-small loading-main">
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
              </div>
            </div>
            <button type="button" wire:click.prevent="closeEditModal"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- End Edit Financial --}}

</div>
@script
  <script>
    $wire.on('openModal', () => {
      var modalElement = document.getElementById('create-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeModal', () => {
      var modalElement = document.getElementById('create-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
    $wire.on('openEditModal', () => {
      var modalElement = document.getElementById('edit-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeEditModal', () => {
      var modalElement = document.getElementById('edit-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
