<div class="overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
      <div class="flex justify-between">
        <div>
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
                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-orange-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Search
                here</label>
            </div>
          </div>
        </div>
        <div>
          @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
            @if (roleAccessRights(['create', 'update']))
              <button type="button" wire:loading.remove
                wire:target="addFinancial"
                class="{{ $editPriceButton && $editQuantityButton ? 'tei-bg-primary hover:bg-sky-900 hover:scale-110 transition-transform duration-300' : 'bg-gray-500' }} text-white  font-medium rounded-lg text-xs px-5 py-2 me-2 mb-2 "
                wire:click="addFinancial" {{ $editPriceButton && $editQuantityButton ? null : 'disabled' }}>
                Add Financial
              </button>
              <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
                wire:target="addFinancial">
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
              <a href="{{ route('financial-envelope') }}"
                class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-2 me-2 mb-2 hover:scale-110 transition-transform duration-300">
                Update Financial
              </a>
            @endif
          @endif
        </div>
      </div>
      <div class="flex mt-4 pr-3">
        @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
          @if (roleAccessRights(['create', 'update']))
            <div class="text-xs">
              <label for="myfile" class="tei-text-secondary">Upload Inventories(Excel):</label>
              @if (!$hasFile)
                <input type="file" wire:model.live="inventoryUpload">
              @else
                <button class="tei-bg-secondary px-2 py-1 rounded-md text-white text-xs"
                  wire:click="openUploadedModal">View</button>
                <button class="bg-red-500 px-2 py-1 rounded-md text-white text-xs" wire:click="removeUploaded">Remove
                  uploads</button>
              @endif
              @error('inventoryUpload')
                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                  {{ $message }}
                </p>
              @enderror

            </div>
          @endif
        @endif
        <div class="ml-auto">
          <label for="title" class=" mr-2 text-sm font-extrabold tei-text-secondary ">Reserve Price:</label>
          <span
            class="text-sm font-extrabold tei-text-accent">{{ $projectbid->reserved_price ? 'PHP ' . number_format($projectbid->reserved_price, 2) : 'No maximum limit' }}</span>
        </div>
      </div>
      <div class="flex mt-4 pr-3">
        @error('customPrice.')
          <p class="mt-2 text-xs text-red-600 dark:text-red-500">
            {{ $message }}
          </p>
        @enderror
      </div>
      <div class="text-center">
        @error('tempPriceTotal')
          <p class="mt-2 text-xs text-red-600 dark:text-red-500">
            {{ $message }}
          </p>
        @enderror
      </div>
    </caption>
    <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
      <tr>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Inventory ID</span>
            {{-- <button wire:click.prevent="selectedFilters('inventory_id')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'inventory_id' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'inventory_id' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button> --}}
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Description</span>
            {{-- <button wire:click.prevent="selectedFilters('description')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'description' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'description' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button> --}}
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Class Id</span>
            {{-- <button wire:click.prevent="selectedFilters('class_id')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'class_id' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'class_id' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button> --}}
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Unit of Measure</span>
            {{-- <button wire:click.prevent="selectedFilters('uom')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'uom' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'uom' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button> --}}
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Unit Cost</span>
            {{-- <button wire:click.prevent="selectedFilters('unit_cost')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'unit_cost' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'unit_cost' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button> --}}
            @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
              @if (roleAccessRights(['create', 'update']))
                @if ($projectbid->financials()->count() != 0)
                  @if ($editPriceButton)
                    <div>
                      <button wire:click.prevent="editPrice" wire:loading.remove wire:target="editPrice"
                        class="tei-bg-secondary text-white px-3 py-0.5 rounded hover:scale-125 transition-transform duration-300">Edit</button>
                      <x-loading-spinner color="var(--secondary)" target="editPrice" />
                    </div>
                  @else
                    <button wire:click.prevent="savePrice" wire:loading.remove wire:target="savePrice"
                      class="bg-green-500 text-white px-3 py-0.5 rounded hover:scale-125 transition-transform duration-300">Save</button>
                    <x-loading-spinner color="var(--secondary)" target="savePrice" />
                  @endif
                @endif
              @endif
            @endif
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Quantity</span>
            @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
              @if (roleAccessRights(['create', 'update']))
                @if ($projectbid->financials()->count() != 0)
                  @if ($editQuantityButton)
                    <div>
                      <button wire:click.prevent="editQuantity" wire:loading.remove wire:target="editQuantity"
                        class="tei-bg-secondary text-white px-3 py-0.5 rounded hover:scale-125 transition-transform duration-300">Edit</button>
                      <x-loading-spinner color="var(--secondary)" target="editQuantity" />
                    </div>
                  @else
                    <button wire:click.prevent="saveQuantity" wire:loading.remove wire:target="saveQuantity"
                      class="bg-green-500 text-white px-3 py-0.5 rounded hover:scale-125 transition-transform duration-300">Save</button>
                    <x-loading-spinner color="var(--secondary)" target="saveQuantity" />
                  @endif
                @endif
              @endif
            @endif
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          Remarks
        </th>
        <th scope="col" class="px-6 py-3">
          Action
        </th>
        {{-- <th scope="col" class="px-6 py-3">
          Action
        </th> --}}
      </tr>
    </thead>
    <tbody>
      {{-- @php
        $financialTotal = 0;
      @endphp --}}
      @forelse ($financials as $financial)
        {{-- @php
        $financialTotal += $financial->pivot->bid_price * $financial->pivot->quantity
      @endphp --}}
        <tr class="bg-white border-b " wire:key="financial-{{ $financial->id }}">
          <th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap ">
            {{ $financial->inventory_id }}
          </th>
          <td class="px-6 py-2">
            {{ $financial->description }}
          </td>
          <td class="px-6 py-2">
            {{ $financial->class_id }}
          </td>
          <td class="px-6 py-2">
            {{ $financial->uom }}
          </td>
          <td class="px-6 py-2">
            @if ($editPriceButton)
              <span class="{{ $errors->has('tempPriceTotal') ? 'text-red-500' : '' }}">
                PHP {{ number_format($financial->pivot->bid_price, 2) }}
              </span>
            @else
              <input type="number"
                class="w-32 text-xs py-1.5 rounded tei-focus-secondary {{ $errors->has('customPrice.' . $financial->id) ? 'border-red-500 border-2' : '' }}"
                wire:model.live="customPrice.{{ $financial->id }}" />
              @error('customPrice.' . $financial->id)
                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                  {{ $message }}
                </p>
              @enderror
            @endif
          </td>
          <td class="px-6 py-2">
            @if ($editQuantityButton)
              <span class="{{ $errors->has('tempPriceTotal') ? 'text-red-500' : '' }}">
                {{ $financial->pivot->quantity }}
              </span>
            @else
              <input type="number"
                class="w-20 text-xs py-1.5 rounded tei-focus-secondary {{ $errors->has('customQuantity.' . $financial->id) ? 'border-red-500 border-2' : '' }}"
                wire:model.live="customQuantity.{{ $financial->id }}" />
              @error('customQuantity.' . $financial->id)
                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                  {{ $message }}
                </p>
              @enderror
            @endif
            {{-- <span
              class="{{ $financial->pivot->quantity < 1 ? 'text-red-500' : '' }}">{{ $financial->pivot->quantity }}
            </span> --}}
          </td>
          <td class="px-6 py-2">
            <button wire:click.prevent="remarksModal({{ $financial->id }})" wire:loading.remove
              wire:target="remarksModal({{ $financial->id }})"
              class="text-white text-xs uppercase bg-green-500 p-1.5 rounded hover:scale-110 transition-transform duration-300">
              <i class="fa-solid fa-comment-dots"></i> Remarks
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4" wire:loading
              wire:target="remarksModal({{ $financial->id }})">
              <div class="flex justify-center ">
                <div class="loading-small loading-main">
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
              </div>
          </td>
          <td class="px-6 py-2">
            @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
              @if (roleAccessRights(['create', 'update']))
                <button wire:click.prevent="removeFinancialModal({{ $financial->id }})" wire:loading.remove
                  wire:target="removeFinancialModal({{ $financial->id }})"
                  class="text-white text-xs uppercase bg-red-500 p-1.5 rounded hover:scale-110 transition-transform duration-300">
                  <i class="fa-solid fa-trash-can"></i>
                </button>
                <x-loading-spinner color="var(--secondary)" target="removeFinancialModal({{ $financial->id }})" />
              @endif
            @endif
          </td>
        </tr>
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100"
            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">No financials on records.</span>
          </th>
        </tr>
      @endforelse
      <tr class="text-lg">
        <td colspan="4" class="px-6 py-4">
          <label for="myfile" class="text-xs tei-text-secondary">Financial envelope remarks:</label>
          <button wire:click.prevent="remarksModalBid" wire:loading.remove wire:target="remarksModalBid"
            class="text-white text-xs uppercase bg-green-500 p-1.5 rounded hover:scale-110 transition-transform duration-300">
            <i class="fa-solid fa-comment-dots"></i> Remarks
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4" wire:loading
            wire:target="remarksModalBid">
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
        </td>
        <td colspan="2" class="text-end px-6 py-4">
          <span class="font-extrabold">Grand Total:</span>
        </td>
        <td class="truncate px-6 py-4 tei-text-secondary">
          PHP {{ number_format($financialTotal, 2) }}
        </td>
      </tr>
    </tbody>
  </table>

  {{ $financials->links('livewire.layout.pagination') }}
  {{-- <x-action-message class="me-3" on="alert-financial">
    {{ __($alertMessage) }}
  </x-action-message> --}}

  {{-- Remarks Modal --}}
  <div id="remarks-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Add Remarks
          </h3>
          <button type="button" wire:click="closeRemarksModal" wire:loading.attr="disabled"
            wire:target="saveRemarks"
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
        <form wire:submit="saveRemarks">
          <div class="p-4 md:p-5 space-y-4">
            <div class="mb-2">
              <div>
                <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Inventory ID:</label>
                <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $inventoryId }}</span>
              </div>
              <div>
                <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Description:</label>
                <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $description }}</span>
              </div>
            </div>
            <div class="mb-6">
              <div class="mt-5">
                <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Remarks</label>
                @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
                  <textarea id="message" rows="4"
                    class="{{ $errors->has('remarks') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                    placeholder="Remarks" wire:model="remarks" {{ roleAccessRights(['create', 'update']) ? '' : 'disabled' }}></textarea>
                @else
                  <span class="font-extrabold tei-text-accent text-sm">{{ $remarks ? $remarks : 'No Remarks' }}</span>
                @endif
              </div>
            </div>
          </div>
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
              @if (roleAccessRights(['create', 'update']))
                <button type="submit" wire:loading.remove wire:target="saveRemarks"
                  class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
                  Add
                </button>
              @endif
            @endif
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="saveRemarks">
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
            <button type="button" wire:click="closeRemarksModal" wire:loading.attr="disabled"
              wire:target="saveRemarks"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- End Remarks Modal --}}

  {{-- Financial Remarks Modal --}}
  <div id="financial-remarks-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Add Remarks
          </h3>
          <button type="button" wire:click="closeFinancialRemarksModal" wire:loading.attr="disabled"
            wire:target="saveEnvelopeRemarks"
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
        <form wire:submit="saveEnvelopeRemarks">
          <div class="p-4 md:p-5 space-y-4">
            <div class="mb-6">
              <div class="mt-5">
                @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
                  <textarea id="message" rows="4"
                    class="{{ $errors->has('envelopeRemarksInput') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                    placeholder="Remarks" wire:model="envelopeRemarksInput"
                    {{ roleAccessRights(['create', 'update']) ? '' : 'disabled' }}></textarea>
                @else
                  <span
                    class="font-extrabold tei-text-accent text-sm">{{ $envelopeRemarksInput ? $envelopeRemarksInput : 'No Remarks' }}</span>
                @endif
              </div>
            </div>
          </div>
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
              @if (roleAccessRights(['create', 'update']))
                <button type="submit" wire:loading.remove wire:target="saveEnvelopeRemarks"
                  class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
                  Add
                </button>
              @endif
            @endif
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="saveEnvelopeRemarks">
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
            <button type="button" wire:click="closeFinancialRemarksModal" wire:loading.attr="disabled"
              wire:target="saveEnvelopeRemarks"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- End Financial Remarks Modal --}}

  {{-- view uploaded inventories --}}
  <div id="upload-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-5xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Uploaded Inventories
          </h3>
          <button type="button" wire:click="closeUploadedModal"
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
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
          <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">

            <p class="mt-1 text-sm font-normal text-gray-500 ">
              Please remove invalid inventory before uploading.
            </p>

          </caption>
          <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
            <tr>
              <th scope="col" class="px-6 py-3">
                Invetory Id
              </th>
              <th scope="col" class="px-6 py-3">
                Description
              </th>
              <th scope="col" class="px-6 py-3">
                Quantity
              </th>
              <th scope="col" class="px-6 py-3">
                Unit Cost
              </th>
              <th scope="col" class="px-6 py-3">
                Notes
              </th>
              <th scope="col" class="px-6 py-3">
                Action
              </th>
            </tr>
          </thead>
          <tbody wire:ignore.self>
            @forelse ($financialsUpload as $index => $data)
              <tr
                class="{{ $data['notExist'] || $data['duplicate'] || $data['duplicateExcel'] ? 'text-red-500' : '' }}  {{ $errors->has('financialsUpload.' . $index . '.quantity') || $errors->has('financialsUpload.' . $index . '.reserved_price') ? 'text-red-500' : '' }}">
                <td class="text-truncate px-6 py-4">{{ $data['inventory_id'] }}</td>
                <td class="px-6 py-4">{{ $data['description'] }}</td>
                <td class="text-truncate px-6 py-4">{{ $data['quantity'] }}</td>
                <td class="text-truncate px-6 py-4">PHP {{ number_format($data['reserved_price'], 2) }}</td>
                <td class="px-6 py-4">
                  @if ($data['notExist'])
                    <span>Inventory Id not on system. please check again.</span>
                  @endif
                  @if ($data['duplicate'])
                    <span>Inventory already exists.</span>
                  @endif
                  @if ($data['duplicateExcel'])
                    <span>Multiple Inventory Id.</span>
                  @endif
                  @if ($errors->has('financialsUpload.' . $index . '.quantity'))
                    <div class="">
                      <small
                        class="text-danger fw-bolder">{{ $errors->first('financialsUpload.' . $index . '.quantity') }}</small>
                    </div>
                  @endif
                  @if ($errors->has('financialsUpload.' . $index . '.reserved_price'))
                    <div class="">
                      <small
                        class="text-danger fw-bolder">{{ $errors->first('financialsUpload.' . $index . '.reserved_price') }}</small>
                    </div>
                  @endif
                </td>
                <td class="px-6 py-4 text-truncate">
                  <button class="bg-red-500 px-2 py-1 rounded-md text-white text-xs"
                    wire:click.prevent="removeItem({{ $index }})"><i
                      class="fa-solid fa-trash-can"></i></button>
                </td>
              </tr>
            @empty
              <tr class="bg-white border-b">
                <th scope="row" colspan="100"
                  class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
                  <span class="font-black text-lg tei-text-primary">No financials on records.</span>
                </th>
              </tr>
            @endforelse
            <tr class="{{ $errors->has('total') ? 'text-red-500' : '' }}">
              <td colspan="3" class="text-end px-6 py-4">
                <span class="font-extrabold">Grand Total:</span>
              </td>
              <td class="truncate px-6 py-4">
                PHP {{ number_format($grandTotal, 2) }}
              </td>
              <td>
              </td>
              <td></td>
            </tr>
            @if ($errors->has('total'))
              <tr>
                <td colspan="10">
                  <div class="text-center">
                    <small class="text-red-500 font-extrabold">{{ $errors->first('total') }}</small>
                  </div>
                </td>
              </tr>
            @endif
          </tbody>
        </table>
        <!-- Modal footer -->
        <div class="flex justify-end p-4 md:p-5  rounded-b space-x-4">
          <button type="button"
            class="{{ $disabledUpload ? 'bg-gray-400' : 'bg-green-600 hover:bg-green-700 hover:scale-110 transition-transform duration-300' }} text-white focus:ring-0 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center"
            wire:click.prevent="uploadInventories" {{ $disabledUpload ? 'disabled' : '' }}>Upload</button>
          <button type="submit" wire:click="closeUploadedModal"
            class="text-white tei-bg-light hover:bg-gray-400 focus:ring-0 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Remove Modal --}}
  <div id="remove-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button" wire:click="closeRemoveModal" wire:loading.attr="disabled"
          wire:target="removeFinancial"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
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
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to remove this financial?</h3>

          <button type="button" wire:loading.remove wire:target="removeFinancial"
            class="text-white bg-red-600 hover:bg-red-900 focus:ring-4 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click= "removeFinancial">
            Remove
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="removeFinancial">
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
          <button wire:click="closeRemoveModal" type="button" wire:loading.attr="disabled"
            wire:target="removeFinancial"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10">
            Cancel</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Remove Modal --}}
  {{-- view uploaded inventories --}}

  {{-- <div x-data="{ 'showModal': false }" @keydown.escape="showModal = false">
    <!-- Trigger for Modal -->
    <button type="button" @click="showModal = true">Open Modal</button>

    <!-- Modal -->
    <div class="fixed inset-0 z-30 flex items-center justify-center overflow-auto bg-black bg-opacity-50"
      x-show="showModal">
      <!-- Modal inner -->
      <div class="max-w-3xl px-6 py-4 mx-auto text-left bg-white rounded shadow-lg" @click.away="showModal = false"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90">
        <!-- Title / Close-->
        <div class="flex items-center justify-between">
          <h5 class="mr-3 text-black max-w-none">Title</h5>

          <button type="button" class="z-50 cursor-pointer" @click="showModal = false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- content -->
        <div>Content goes here</div>
      </div>
    </div>
  </div> --}}
  @livewire('admin.bidding.envelope.financial-lists', ['id' => $projectbid->id])
</div>

@script
  <script>
    $wire.on('openUploadedModal', () => {
      var modalElement = document.getElementById('upload-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeUploadedModal', () => {
      var modalElement = document.getElementById('upload-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
    $wire.on('openRemarksModal', () => {
      var modalElement = document.getElementById('remarks-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeRemarksModal', () => {
      var modalElement = document.getElementById('remarks-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
    $wire.on('openRemoveModal', () => {
      var modalElement = document.getElementById('remove-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeRemoveModal', () => {
      var modalElement = document.getElementById('remove-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
    $wire.on('openFinancialRemarksModal', () => {
      var modalElement = document.getElementById('financial-remarks-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeFinancialRemarksModal', () => {
      var modalElement = document.getElementById('financial-remarks-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
