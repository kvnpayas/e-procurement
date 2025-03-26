<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      <div class="flex justify-between">
        <div>
          Financial Offer
          <p class="mt-1 text-sm font-normal text-gray-500 ">Please respond and answer all financial requirements.
          </p>
        </div>
        @php
          $bidRemarks = $bid->envelopeRemarks->where('envelope', 'financial')->first();
          $remarks = $bidRemarks ? $bidRemarks->remarks : null;
        @endphp
        @if ($remarks)
          <div class="w-96">
            Remarks
            <p class="mt-1 text-sm font-normal text-gray-500 ">
              {{ $remarks }}
            </p>
          </div>
        @endif
      </div>
      <div class="flex justify-center">
        @error('vendorFiles.*')
          <div class="py-2 px-5 mb-2 text-sm text-red-800 rounded-lg bg-red-500 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="text-xs text-white"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
          </div>
        @enderror
        @error('hasFile')
          <div class="py-2 px-5 mb-2 text-sm text-red-800 rounded-lg bg-red-500 dark:bg-gray-800 dark:text-red-400"
            role="alert">
            <span class="-xs text-white"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
          </div>
        @enderror

      </div>
    </caption>
    <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
      <tr>
        <th colspan="4" class="bg-white"></th>
        <th colspan="2" class="text-center px-6 py-3 border-b-2">
          Vendor Inputs
        </th>
      </tr>
      <tr>
        <th scope="col" class="px-6 py-3">
          Inventory ID
        </th>
        <th scope="col" class="px-6 py-3">
          Inventory Description
        </th>
        <th scope="col" class="px-6 py-3">
          UOM
        </th>
        <th scope="col" class="px-6 py-3">
          Quantity
        </th>
        {{-- <th scope="col" class="px-6 py-3">
          Reserved Price(PHP)
        </th> --}}
        <th scope="col" class="px-6 py-3">
          Price/Item(PHP)
        </th>
        <th scope="col" class="px-6 py-3">
          Tax/Duties/Fees/Levies
        </th>
        <th scope="col" class="px-6 py-3">
          Total
        </th>
        <th scope="col" class="px-6 py-3">
          Remarks
        </th>
      </tr>
    </thead>
    <tbody>
      @forelse ($financials as $financial)
        <tr class="bg-white border-b font-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300">
          <th scope="row" class="px-6 py-4 font-medium tei-text-secondary whitespace-nowrap ">
            {{ $financial->inventory_id }}
          </th>
          <th scope="row" class="px-6 py-4 w-96">
            {{ $financial->description }}
          </th>
          <td class="px-6 py-4">
            {{ $financial->uom }}
          </td>
          <td class="px-6 py-4">
            {{ $financial->pivot->quantity }}
          </td>
          {{-- <td class="px-6 py-4">
              @if ($bid->reflect_price)
                PHP {{ number_format($financial->pivot->bid_price, 2) }}
              @endif
            </td> --}}
          <td class="px-6 py-4" style="width: 12rem;">
            <input type="text"
              class="rounded-full shadow-sm focus:ring-orange-700 max-h-8 focus:border-orange-700 w-full {{ $errors->has('vendorResponse.' . $financial->id . '.price') ? 'border-red-600 ' : '' }}"
              wire:model.live="vendorResponse.{{ $financial->id }}.price">
            @error('vendorResponse.' . $financial->id . '.price')
              <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                  class="font-medium">{{ $message }}</span></p>
            @enderror
          </td>
          <td class="px-6 py-4" style="width: 12rem;">
            <input type="text"
              class="rounded-full shadow-sm focus:ring-orange-700 max-h-8 focus:border-orange-700 w-full {{ $errors->has('vendorResponse.' . $financial->id . '.fees') ? 'border-red-600 ' : '' }}"
              wire:model.live='vendorResponse.{{ $financial->id }}.fees'>
            @error('vendorResponse.' . $financial->id . '.fees')
              <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                  class="font-medium">{{ $message }}</span></p>
            @enderror
          </td>
          <td class="px-6 py-4">
            PHP {{ $vendorResponse[$financial->id]['total'] }}
          </td>
          <td class="px-6 py-4">
            {{-- @if ($financial['attachment'])
                <span id="icon.{{ $financial->id }}" class="cursor-pointer hover:text-orange-600 tei-text-primary"
                  wire:click.prevent="showFile({{ $financial->id }})" data-modal-target="view-file"
                  data-modal-toggle="view-file">
                  <i class="fa-solid fa-paperclip"></i>
                </span>
              @else
                <span>No File</span>
              @endif --}}
            @if ($financial->pivot->remarks)
              <button wire:click.prevent="remarksModal({{ $financial->id }})"
                class="text-white text-xs uppercase bg-green-500 p-1.5 rounded hover:scale-110 transition-transform duration-300">
                <i class="fa-solid fa-comment-dots"></i> Remarks
              </button>
            @endif
          </td>
        </tr>
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
            No Financial Requirements
          </th>
        </tr>
      @endforelse
      <tr class="font-extrabold">
        <td colspan="5" class="px-6 py-4">
          <div class="flex gap-4">
            <div>
              <label for="myfile" class="mt-2 text-xs tei-text-accent">Upload Financial Offer:</label>
            </div>
            <div class="flex flex-col gap-4">
              <div class="flex flex-col gap-4">
                @forelse ($hasFile as $dataFile)
                  <div class="flex">
                    <button wire:click.prevent="viewFile('{{ $dataFile->file }}')"
                      class="block hover:scale-110 transition-transform duration-300 mr-4 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                        class="fa-solid fa-file-pdf text-xs"></i> {{ $dataFile->file }}</button>
                    <button wire:loading.remove
                      wire:target="{{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'removeFile' }}"
                      wire:click.prevent="{{ $vendorStatus->complete ? 'openSaveModalFromRemove(' . $dataFile->id . ')' : 'removeFile( ' . $dataFile->id . ' )' }}"
                      class="text-white bg-red-600 focus:outline-none font-medium rounded-lg text-xs px-2 py-1 hover:scale-110 transition-transform duration-300">
                      <i class="fa-solid fa-trash-can text-xs"></i></button>
                    <x-loading-spinner color="var(--secondary)"
                      target="{{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'removeFile' }}" />
                  </div>
                @empty
                  <div>
                    <p class="text-sm text-gray-500">No file uploaded</p>
                  </div>
                @endforelse

                {{-- <div>
                  <button wire:click.prevent="changeRemoveFiles"
                    class="block hover:scale-110 transition-transform duration-300 mr-4 bg-red-500 rounded-md px-2 py-1 text-white text-xs"><i
                      class="fa-solid fa-trash-can text-xs"></i> Change/Remove</button>
                </div> --}}
              </div>
              <div>
                <div class="relative w-28" wire:loading.remove wire:target="vendorFiles, removeFile">
                  <button id="uploadButton"
                    class="tei-bg-primary text-xs text-white font-bold py-1.5 px-4 rounded hover:scale-110 transition-transform duration-300">
                    Upload File
                  </button>
                  <input type="file" id="fileInput" wire:model="vendorFiles"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                </div>
                <div class="w-20 rounded tei-bg-light flex justify-center p-3" wire:loading
                  wire:target="vendorFiles, removeFile">
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
                {{-- <input
                class="text-sm tei-text-accent border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                type="file" wire:model="vendorFiles" multiple> --}}
                @error('vendorFiles.*')
                  <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                      class="font-medium">{{ $message }}</span></p>
                @enderror
                @error('hasFile')
                  <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                      class="font-medium">{{ $message }}</span></p>
                @enderror
              </div>
            </div>
          </div>
        </td>
        <td class="px-6 py-4 text-end tei-text-secondary">
          Grand total:
        </td>
        <td class="px-6 py-4">
          PHP
          {{ number_format($grandTotal, 2) }}
        </td>
        <td class="text-center px-6 py-4">
          <div wire:loading.remove wire:target="{{ $vendorStatus->complete ? 'openSaveModal' : 'saveForm' }}">
            <button wire:click="{{ $vendorStatus->complete ? 'openSaveModal' : 'saveForm' }}"
              wire:loading.attr="disabled" wire:target="vendorFiles, removeFile"
              class="bg-green-600 hover:bg-green-800 px-5 py-2 rounded-md text-white transition ease-in-out duration-150 font-semibold text-sm w-full">Save</button>
          </div>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="saveForm">
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
      </tr>
    </tbody>
  </table>
  {{-- <x-action-message class="me-3" on="update-message">
    {{ __($alertMessage) }}
  </x-action-message> --}}

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
          <span class="uppercase tei-text-primary text-lg font-black mb-5">{{ $financialFileName }}</span>
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

  {{-- Remarks Modal --}}
  <div id="remarks-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Remarks for {{ $inventoryDesc }}
          </h3>
          <button type="button" wire:click="closeRemarksModal" wire:loading.attr="disabled"
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
        <div class="p-4 md:p-5 space-y-4">
          <div class="mb-6">
            <div class="mt-5">
              <p class="tei-text-accent">{{ $inventoryRemarks }}</p>
            </div>
          </div>
        </div>
        <!-- Modal footer -->
        <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
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
          <button type="button" wire:click="closeRemarksModal" wire:loading.attr="disabled"
            wire:target="saveEnvelopeRemarks"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 hover:scale-110 transition-transform duration-300">Close</button>
        </div>
      </div>
    </div>
  </div>
  {{-- End Remarks Modal --}}

  {{-- Save Modal --}}
  <div id="save-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          wire:click="closeSaveModal">
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
          <div class="py-5">
            <h3 class="mb-5 text-lg font-normal tei-text-primary ">You have already submitted this bid. If you click
              accept, you will need to submit the bid again.</h3>
            <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to change your current
              requirements/offer?</h3>
          </div>
          <button type="button"
            class="text-white bg-green-600 hover:bg-green-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click.prevent="saveForm" wire:loading.remove wire:target="saveForm">
            Accept
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="saveForm">
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
          <button wire:click="closeSaveModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10">
            Cancel</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Save Modal --}}

  {{-- Save Remove Modal --}}
  <div id="save-remove-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          wire:click="closeSaveRemoveModal">
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
          <div class="py-5">
            <h3 class="mb-5 text-lg font-normal tei-text-primary ">You have already submitted this bid. If you click
              accept, you will need to submit the bid again.</h3>
            <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to change your current
              requirements/offer?</h3>
          </div>
          <button type="button"
            class="text-white bg-green-600 hover:bg-green-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click.prevent="saveRemoveForm" wire:loading.remove wire:target="saveRemoveForm">
            Accept
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="saveRemoveForm">
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
          <button wire:click="closeSaveRemoveModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10">
            Cancel</button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Save Remove Modal --}}
</div>
@script
  <script>
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
    $wire.on('openFileModal', () => {
      var modalElementOpen = document.getElementById('view-file');
      var modalOpen = new Modal(modalElementOpen, {
        backdrop: 'static'
      });
      modalOpen.show();
    });

    $wire.on('closeFileModal', () => {
      var modalElement = document.getElementById('view-file');
      var modal = new Modal(modalElement);
      modal.hide();
    });
    $wire.on('openSaveModal', () => {
      var modalElementOpen = document.getElementById('save-modal');
      var modalOpen = new Modal(modalElementOpen, {
        backdrop: 'static'
      });
      modalOpen.show();
    });

    $wire.on('closeSaveModal', () => {
      var modalElement = document.getElementById('save-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('openSaveRemoveModal', () => {
      var modalElementOpen = document.getElementById('save-remove-modal');
      var modalOpen = new Modal(modalElementOpen, {
        backdrop: 'static'
      });
      modalOpen.show();
    });

    $wire.on('closeSaveRemoveModal', () => {
      var modalElement = document.getElementById('save-remove-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
{{-- @section('page-script')
  <script>
    document.addEventListener("click", (e) => {
      const elementId = e.target.id;
      const id = elementId.split('.')[1]
      const detailId = document.getElementById('financialDetails.' + id)
      if (detailId.classList.contains('details-hide')) {
        detailId.classList.add('details-show')
        detailId.classList.remove('details-hide')
        setTimeout(() => {
          detailId.querySelector('.more-details').classList.remove('displayNone')
        }, 300);
      } else {
        detailId.classList.remove('details-show')
        detailId.classList.add('details-hide')
        setTimeout(() => {
          detailId.querySelector('.more-details').classList.add('displayNone')
        }, 200);
      }

    })
  </script>
@endsection --}}
