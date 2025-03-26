<div class="overflow-x-auto shadow-md sm:rounded-lg">
  <div class="p-5">
    <h3 class="text-lg font-semibold tei-text-secondary">Create Project Bidding</h3>
    <p class="mt-1 text-sm font-normal text-gray-500 ">Please review all the input data before creating bids.
    </p>
  </div>

  <div class="p-5">
    <form wire:submit.prevent="createForm">
      <div class="my-5 text-center">
        @error('projectCode')
          <p class="mt-2 text-xs text-red-600 dark:text-red-500">
            {{ $message }}
          </p>
        @enderror
      </div>
      <div class="grid grid-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="title" class="block mb-2 text-sm font-extrabold tei-text-primary ">Project Title</label>
          <input type="text" id="name" wire:model="projectTitle" placeholder="Project Title"
            class="{{ $errors->has('projectTitle') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border tei-border-primary text-gray-900 tei-focus-secondary ' }} rounded-lg block w-full p-1.5 text-sm focus:ring-1" />
          @error('projectTitle')
            <p class="mt-2 text-xs text-red-600 dark:text-red-500">
              {{ $message }}
            </p>
          @enderror
        </div>
        <div class="grid grid-cols-3 gap-5">
          <div>
            <label for="type" class="block mb-2 text-sm font-extrabold tei-text-primary ">Bidding Type</label>
            <select wire:model="biddingType"
              class="{{ $errors->has('biddingType') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border tei-border-primary text-gray-900 tei-focus-secondary ' }} text-sm rounded-lg block w-full p-1.5">
              <option selected value="bid">Bidding</option>
              <option value="rfi">RFI</option>
              <option value="rfq">RFQ</option>
              <option value="rfe">RFE</option>
              <option value="rfp">RFP</option>
            </select>
            @error('biddingType')
              <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                {{ $message }}
              </p>
            @enderror
          </div>
          <div>
            <label for="sales" class="block mb-2 text-sm font-extrabold tei-text-primary ">Sales</label>
            <label class="inline-flex items-center mb-5 cursor-pointer" data-tooltip-target="tooltip-sales">
              <input type="checkbox" value="" class="sr-only peer" wire:model.live="sales">
              <div
                class="relative w-9 h-5 bg-gray-500 peer-focus:outline-none  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-700">
              </div>
              <span class="ms-3 text-xs tei-text-secondary  font-semibold">Scrap bidding</span>
            </label>
            <div id="tooltip-sales" role="tooltip"
              class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
              Bidding for scrap material
              <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
          </div>
          <div>
            <label for="user" class="block mb-2 text-sm font-extrabold tei-text-primary ">Created By</label>
            <span class="font-extrabold tei-text-secondary">{{ Auth::user()->name }}</span>
          </div>
        </div>
      </div>

      <div class="grid grid-1 sm:grid-cols-2 gap-4 mt-2">
        <div class="grid grid-cols-2 gap-4 py-4">
          <div>
            <label for="budget" class="block mb-2 text-sm font-extrabold tei-text-primary ">Budget</label>
            <input type="text" id="name" wire:model="budget" placeholder="Optional"
              class="{{ $errors->has('budget') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border tei-border-primary text-gray-900 tei-focus-secondary ' }} rounded-lg block w-full p-1.5 text-sm focus:ring-1" />
          </div>
          <div>
            <label for="projectType" class="block mb-2 text-sm font-extrabold tei-text-primary ">Project Type</label>
            <input type="text" id="name" wire:model="projectType" placeholder="Optional"
              class="{{ $errors->has('projectType') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border tei-border-primary text-gray-900 tei-focus-secondary ' }} rounded-lg block w-full p-1.5 text-sm focus:ring-1" />
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4 border-2 tei-border-accent rounded-lg py-4 px-2">
          <div>
            <label for="reservedPrice" class="block mb-2 text-sm font-extrabold tei-text-primary ">Reserved Price
              (PHP)</label>
            <input type="number" id="name" wire:model="reservedPrice" placeholder="Reserved Price"
              class="{{ $errors->has('reservedPrice') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : ' border tei-border-primary text-gray-900 tei-focus-secondary ' }} {{ $disabledPrice ? 'tei-bg-light' : '' }} rounded-lg block w-full p-1.5 text-sm focus:ring-1"
              {{ $disabledPrice ? 'disabled' : '' }} />
            @error('reservedPrice')
              <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                {{ $message }}
              </p>
            @enderror
          </div>
          <div>
            <label for="settings" class="block mb-2 text-sm font-extrabold tei-text-primary ">Additional
              Settings</label>
            <label class="inline-flex items-center cursor-pointer mr-3" data-tooltip-target="tooltip-reserved">
              <input type="checkbox" value="" class="sr-only peer" wire:model.live="switchReservedPrice">
              <div
                class="relative w-9 h-5 bg-gray-500 peer-focus:outline-none  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-700">
              </div>
              <span class="ms-1 text-xs tei-text-secondary font-semibold">Reserved Price</span>
            </label>
            <div id="tooltip-reserved" role="tooltip"
              class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
              Enable/disable reserved price
              <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <label class="inline-flex items-center mb-5 cursor-pointer" data-tooltip-target="tooltip-reflect">
              <input type="checkbox" value="" class="sr-only peer" wire:model="reflectPrice">
              <div
                class="relative w-9 h-5 bg-gray-500 peer-focus:outline-none  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-700">
              </div>
              <span class="ms-1 text-xs tei-text-secondary  font-semibold">Reflect Price</span>
            </label>
            <div id="tooltip-reflect" role="tooltip"
              class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
              Show/hide price to vendors
              <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-1 sm:grid-cols-2 gap-4 mt-2">
        <div>
          <label for="deadline" class="block mb-2 text-sm font-extrabold tei-text-primary ">Deadline date</label>
          <div class="grid grid-cols-2">
            <div class="relative max-w-sm">
              <div class="absolute top-2 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                </svg>
              </div>
              <input type="date"
                class="text-sm rounded-lg block w-full ps-10 p-1.5
              {{ $errors->has('dateTime') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border tei-border-primary text-gray-500 tei-focus-secondary ' }}"
                placeholder="Select date" wire:model.live="deadlineDate" min="{{ date('Y-m-d') }}" />
              @error('dateTime')
                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                  {{ $message }}
                </p>
              @enderror
            </div>
            <div class="ml-3">
              <div class="max-w-[8rem]">
                <div class="relative">
                  {{-- <div class="absolute inset-y-0 end- top-0 flex items-center pe-3.5 pointer-events-none">
                  <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                      d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                      clip-rule="evenodd" />
                  </svg>
                </div> --}}
                  <input type="time" id="time"
                    class="leading-none text-sm rounded-lg block w-full p-1.5
                  {{ $errors->has('time') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border tei-border-primary text-gray-500 tei-focus-secondary ' }}"
                    wire:model="time" />
                  @error('time')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                      {{ $message }}
                    </p>
                  @enderror
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <hr class="mt-5 border-2 tei-border-accent">

      <div class="mt-5">
        <label for="envelopes" class="block mb-2 text-sm font-extrabold tei-text-primary ">Score Method</label>
        <div class="grid grid-cols-3 justify-around px-5 gap-5 ">
          <div class="pt-1">
            <label class="inline-flex items-center mb-5 cursor-pointer" data-tooltip-target="tooltip-score">
              <span
                class="me-2 font-semibold transition-colors delay-150 text-xs {{ $scoreMethod ? 'tei-text-light' : 'tei-text-secondary' }}">Cost
                Based</span>
              <input type="checkbox" value="" class="sr-only peer" wire:model.live="scoreMethod">
              <div
                class="relative w-9 h-5 bg-green-700 peer-focus:outline-none  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-700">
              </div>
              <span
                class="ms-2 text-xs font-semibold transition-colors delay-150 {{ $scoreMethod ? 'tei-text-secondary' : 'tei-text-light' }}">Rating
                Based</span>
            </label>
            <div id="tooltip-score" role="tooltip"
              class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
              Choose what score method use on this bid.
              <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-5">
        <label for="envelopes" class="block mb-2 text-sm font-extrabold tei-text-primary ">Envelopes</label>
        <div class="grid grid-cols-3 justify-around px-5 gap-5 ">
          <div class="pt-1">
            <label class="inline-flex items-center mb-5 cursor-pointer" data-tooltip-target="tooltip-eligibility">
              <input type="checkbox" value="" class="sr-only peer" wire:model="eligibility">
              <div
                class="relative w-9 h-5 bg-gray-500 peer-focus:outline-none  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-700">
              </div>
              <span class="ms-1 text-xs tei-text-secondary  font-semibold">Eligibility</span>
            </label>
            <div id="tooltip-eligibility" role="tooltip"
              class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
              Enable/disable eligibility envelopes
              <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
          </div>
          <div class="grid grid-cols-3 gap-4">
            <div class="pt-1">
              <label class="inline-flex items-center mb-5 cursor-pointer" data-tooltip-target="tooltip-technical">
                <input type="checkbox" value="" class="sr-only peer" wire:model.live="technical">
                <div
                  class="relative w-9 h-5 bg-gray-500 peer-focus:outline-none  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-700">
                </div>
                <span class="ms-1 text-xs tei-text-secondary  font-semibold">Technical</span>
              </label>
              <div id="tooltip-technical" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
                Enable/disable technical envelopes
                <div class="tooltip-arrow" data-popper-arrow></div>
              </div>
            </div>
            <div>
              <input type="number" id="name" wire:model.live="technicalWeight" placeholder="Weight"
                {{ $disableTechnicalWeight ? 'disabled' : '' }}
                class="{{ $errors->has('technicalWeight') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : ' border tei-border-primary text-gray-900 tei-focus-secondary ' }} rounded-lg block w-full p-1.5 text-xs focus:ring-1 {{ $disableTechnicalWeight ? 'tei-bg-light' : '' }}" />
              @error('technicalWeight')
                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                  {{ $message }}
                </p>
              @enderror
            </div>
          </div>
          <div class="grid grid-cols-3 gap-4">
            <div class="pt-1">
              <label class="inline-flex items-center mb-5 cursor-pointer" data-tooltip-target="tooltip-financial">
                <input type="checkbox" value="" class="sr-only peer" wire:model.live="financial">
                <div
                  class="relative w-9 h-5 bg-gray-500 peer-focus:outline-none  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-700">
                </div>
                <span class="ms-1 text-xs tei-text-secondary  font-semibold">Financial</span>
              </label>
              <div id="tooltip-financial" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
                Enable/disable financial envelopes
                <div class="tooltip-arrow" data-popper-arrow></div>
              </div>
            </div>
            <div>
              <input type="number" id="name" wire:model.live="financialWeight" placeholder="Weight"
                {{ $disableFinancialWeight ? 'disabled' : '' }}
                class="{{ $errors->has('financialWeight') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'border tei-border-primary text-gray-900 tei-focus-secondary ' }} rounded-lg block w-full p-1.5 text-xs focus:ring-1 {{ $disableFinancialWeight ? 'tei-bg-light' : '' }}" />
              @error('financialWeight')
                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                  {{ $message }}
                </p>
              @enderror
            </div>
          </div>
        </div>
        <div class="text-center mt-4">
          <label for="total" class="block mb-2 text-sm font-extrabold tei-text-primary ">Total Weight
            Percentage</label>
          <span
            class="{{ $errors->has('totalWeight') ? 'text-red-600 ' : 'tei-text-secondary' }} font-extrabold">{{ $totalWeight == 'N/A' ? 'N/A' : $totalWeight . '%' }}</span>
          @error('totalWeight')
            <p class="mt-2 text-xs text-red-600 dark:text-red-500">
              {{ $message }}
            </p>
          @enderror
        </div>
      </div>

      <hr class="mt-5 border-2 tei-border-accent">

      <div class="mt-5 w-3/5">
        <div class="mb-5">
          <label for="instruction" class="block mb-2 text-sm font-extrabold tei-text-primary ">Instruction
            Details</label>
          <textarea id="message" rows="20"
            class="{{ $errors->has('instructionDetails') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
            placeholder="Enter additional instructions" wire:model="instructionDetails"></textarea>
        </div>
        <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Instruction
          Attachment</label>
        {{-- <div class="grid grid-cols-2 gap-4">
          <div>
            @if ($fileExist)
              <span class="text-sm tei-text-secondary font-extrabold">{{ $technical->attachments }}</span>
            @else
              <input
                class="{{ $errors->has('attachments') || $errors->has('attachments.*') ? ' border border-red-500  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 focus:ring-orange-600 focus:border-orange-600 ' }} {{ $fileSwitch ? 'text-gray-900' : 'text-gray-400' }} focus:outline-none block w-full text-sm border rounded-lg cursor-pointer"
                type="file" wire:model="attachments" {{ $fileSwitch ? '' : 'disabled' }} multiple>
              @error('attachments')
                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                  {{ $message }}
                </p>
              @enderror
              @error('attachments.*')
              <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                {{ $message }}
              </p>
            @enderror
            @endif
          </div>
          <div>
            <label class="inline-flex items-center cursor-pointer">
              <input type="checkbox" value="" class="sr-only peer" wire:model.live="fileSwitch">
              <div
                class="relative w-9 h-5 bg-gray-500 peer-focus:outline-none  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-700">
              </div>
              <span class="ms-3 text-sm font-medium">If disabled, it will not upload files.</span>
            </label>
          </div>
        </div> --}}
        @forelse($tmpAttachments as $index => $file)
          <div class="mb-4 flex">
            <button wire:click.prevent="viewFile('{{ $file }}')"
              class="hover:scale-110 transition-transform duration-300 mr-4 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                class="fa-solid fa-file-pdf text-xs"></i> {{ $file }}</button>
            <button wire:loading.remove wire:target="removeFile({{ $index }})"
              wire:click.prevent="removeFile({{ $index }})"
              class="text-white bg-red-600 focus:outline-none font-medium rounded-lg text-xs px-2 py-1 hover:scale-110 transition-transform duration-300">
              <i class="fa-solid fa-trash-can text-xs"></i></button>
            <x-loading-spinner color="var(--secondary)" target="" />
          </div>
        @empty
          <div>
            <p class="text-sm text-gray-500">No file uploaded</p>
          </div>
        @endforelse

        <div>
          {{-- <input
            class="block w-full text-sm text-gray-900 border {{ $errors->has('fileInputs.*') ? 'border-red-600 ' : 'border-gray-300 ' }} rounded-e-lg cursor-pointer bg-gray-50 "
            id="file_input" type="file" wire:model="fileInputs"> --}}
          <div class="relative w-28" wire:loading.remove wire:target="attachments">
            <button id="uploadButton"
              class="tei-bg-primary text-xs text-white font-bold py-1.5 px-4 rounded hover:scale-110 transition-transform duration-300">
              Upload File
            </button>
            <input type="file" id="fileInput" wire:model="attachments"
              class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
          </div>
          <div class="w-20 rounded tei-bg-light flex justify-center p-3" wire:loading wire:target="attachments">
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
          @error('attachments')
            <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                class="font-medium">{{ $message }}</span></p>
          @enderror
        </div>
      </div>

      <hr class="mt-5 border-2 tei-border-accent">

      <div class="text-center mt-4">
        <button
          class="text-white tei-bg-primary hover:bg-sky-900 font-semibold rounded-lg text-lg px-5 py-2 me-2 mb-2 hover:scale-110 transition-transform duration-300"
          type="submit">Proceed</button>
      </div>

    </form>
  </div>
  {{-- {{ $classes->links('livewire.layout.pagination') }} --}}
  {{-- 
  <x-action-message class="me-3" on="alert-message">
    {{ __($alertMessage) }}
  </x-action-message>  --}}

  {{-- Confirmation Modal --}}
  <div id="confirm-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-5xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Create Project Bid
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            wire:click.prevent="closeModal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        @if ($inputedData)
          <div class="p-4 md:p-5">
            <h3 class="text-lg font-extrabold tei-text-secondary">
              Warning
            </h3>
            <p class="mt-1 text-xs font-extrabold tei-text-secondary italic">
              Please review all the details before creating this bid. Thank you
            </p>

            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2">
              <div>
                <label for="title" class="block mb-2 text-lg font-extrabold tei-text-primary ">Project Title:
                  <span class="font-semibold text-sm tei-text-accent pl-2">{{ $inputedData['title'] }}</span>
                </label>
                <label for="title" class="block mb-2 text-lg font-extrabold tei-text-primary ">Bidding Type:
                  <span
                    class="font-semibold text-sm tei-text-accent pl-2">{{ strtoupper($inputedData['type']) }}</span>
                </label>
                <label for="title" class="block mb-2 text-lg font-extrabold tei-text-primary ">Sales Bid:
                  <span
                    class="font-semibold text-sm tei-text-accent pl-2">{{ $inputedData['scrap'] ? 'Yes' : 'No' }}</span>
                </label>
                <label for="title" class="block mb-2 text-lg font-extrabold tei-text-primary ">Budget Id:
                  <span
                    class="font-semibold text-sm tei-text-accent pl-2">{{ $inputedData['budget_id'] ? $inputedData['budget_id'] : 'N/A' }}</span>
                </label>
                <label for="title" class="block mb-2 text-lg font-extrabold tei-text-primary ">Project Type:
                  <span
                    class="font-semibold text-sm tei-text-accent pl-2">{{ $inputedData['icss_project_id'] ? $inputedData['icss_project_id'] : 'N/A' }}</span>
                </label>
              </div>
              <div>
                <label for="title" class="block mb-2 text-lg font-extrabold tei-text-primary ">Reserved Price:
                  <span
                    class="font-semibold text-sm tei-text-accent pl-2">{{ $inputedData['reserved_price'] ? 'PHP ' . number_format($inputedData['reserved_price'], 2) : 'The reserved price is disabled' }}</span>
                </label>
                <label for="title" class="block mb-2 text-lg font-extrabold tei-text-primary ">Reflect price:
                  <span
                    class="font-semibold text-sm tei-text-accent pl-2">{{ $inputedData['reflect_price'] ? 'Vendor can see reserved price' : 'The vendor cannot see the reserved price' }}</span>
                </label>

                <label for="title" class="block mb-2 text-lg font-extrabold tei-text-primary ">Score Method:
                  <span class="font-semibold text-sm tei-text-accent pl-2">{{ $inputedData['score_method'] }}
                    Based</span>
                </label>

                <label for="title" class="block mb-1 text-lg font-extrabold tei-text-primary ">Envelopes:
                </label>
                <div class="flex flex-col space-y-2 mb-2">
                  @if ($inputedData['eligibility'])
                    <span class="font-semibold text-sm text-green-500 pl-2">Eligibility </span>
                  @endif
                  @if ($inputedData['technical'])
                    <span class="font-semibold text-sm text-green-500 pl-2">Technical - {{ $technicalWeight }}%</span>
                  @endif
                  @if ($inputedData['financial'])
                    <span class="font-semibold text-sm text-green-500 pl-2">Financial - {{ $financialWeight }}%</span>
                  @endif
                </div>

              </div>
            </div>
            <div class="w-1/2">
              <label for="title" class="block mb-1 text-lg font-extrabold tei-text-primary ">Instruction Details:
                <span
                  class="font-semibold text-sm pl-2 tei-text-accent">{{ $inputedData['instruction_details'] ? $inputedData['instruction_details'] : 'N/A' }}</span>
              </label>
              {{-- <label for="title" class="block mb-1 text-lg font-extrabold tei-text-primary ">Attachment:
              </label>
              @if ($inputedData['attachment'])
              <span class="font-semibold text-sm pl-2 tei-text-accent">{{$inputedData['attachment'] }}</span>
              <iframe src="{{ $attachment->temporaryUrl() }}" frameborder="1" width="1000"
              height="850"></iframe>
              @else

              <span class="font-semibold text-sm pl-2 tei-text-accent">{{$inputedData['instruction_details'] ? $inputedData['instruction_details'] : 'N/A'}}</span>
              @endif --}}
            </div>
          </div>
        @endif
        <!-- Modal footer -->
        <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
          <button type="submit"
            class="text-white bg-green-600 hover:bg-green-700 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300"
            wire:click.prevent="createBid">
            Create Bid
          </button>
          <button wire:click.prevent="closeModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 hover:scale-110 transition-transform duration-300">Close</button>
        </div>
      </div>
    </div>
  </div>
  {{-- End Confirmation Modal --}}

  {{-- File Modal --}}
  <div id="view-file" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-7xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          wire:click="closeFileModal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center ">
          {{-- <span class="uppercase tei-text-primary text-lg font-black mb-5">{{ $fileName }}</span> --}}
          <hr>
          <div class=" flex justify-center mt-5">
            @if ($fileAttachment)
              <iframe src="{{ $fileAttachment }}" frameborder="1" width="1000" height="850"></iframe>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- END File Modal --}}
</div>

@script
  <script>
    $wire.on('openConfirmModal', () => {
      var modalElement = document.getElementById('confirm-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeConfirmModal', () => {
      var modalElement = document.getElementById('confirm-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
    $wire.on('openFileModal', () => {
      var modalElement = document.getElementById('view-file');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });

    $wire.on('closeFileModal', () => {
      var modalElement = document.getElementById('view-file');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
