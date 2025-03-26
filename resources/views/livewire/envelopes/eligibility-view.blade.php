<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      <div class="flex justify-between">
        <div>
          Eligibility Requirements
          <p class="mt-1 text-sm font-normal text-gray-500 ">Please respond and attach a file to all eligibility
            requirements.
          </p>
        </div>
        @php
          $bidRemarks = $bid->envelopeRemarks->where('envelope', 'eligibility')->first();
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
    </caption>
    <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
      <tr>
        <th scope="col" class="px-6 py-3">
          Eligibility ID
        </th>
        <th scope="col" class="px-6 py-3">
          Eligibility Name
        </th>
        <th scope="col" class="px-6 py-3">
          Eligibility Description
        </th>
        <th scope="col" class="px-6 py-3">
          Status
        </th>
        <th scope="col" class="px-6 py-3">
          Action
        </th>
      </tr>
    </thead>
    <tbody>
      @forelse ($eligibilities as $eligibility)
        <tr class="bg-white border-b text-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300"
          wire:key="eligibility-{{ $eligibility->id }}" wire:ignore.self>
          <th scope="row" class="px-6 py-4 tei-text-secondary whitespace-nowrap ">
            {{ $eligibility->id }}
          </th>
          <th scope="row" class="px-6 py-4 whitespace-nowrap ">
            {{ $eligibility['name'] }}
          </th>
          <td class="px-6 py-4">
            {{ $eligibility['description'] }}
          </td>
          <td class="px-6 py-4">
            @if ($eligibility->vendorStatus)
              <i class="fa-solid fa-circle-check text-green-600"></i>
            @else
              <i class="fa-solid fa-circle-xmark text-red-600"></i>
            @endif
          </td>
          <td class="px-6 py-4">
            <span id="icon.{{ $eligibility['id'] }}" class="cursor-pointer" wire:loading.remove
              wire:target="eligibilityModal({{ $eligibility['id'] }})"
              wire:click.prevent="eligibilityModal({{ $eligibility['id'] }})">
              <i class="fa-solid fa-circle-plus text-green-800"></i> show
              more
            </span>
            <x-loading-spinner color="var(--secondary)" target="eligibilityModal({{ $eligibility['id'] }})" />
          </td>
        </tr>
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
            No Eligibility Requirements
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div id="eligibility-modal" tabindex="-1" data-modal-backdrop="static"
    class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full hidden"
    wire:ignore.self>
    <div class="relative w-full max-w-4xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow ">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
          <h3 class="text-xl font-extrabold tei-text-secondary">
            {{ $eligibilityName }}
          </h3>
          <button type="button" wire:click.prevent="closeModal"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
            data-modal-hide="eligibility-modal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5 w-full tei-text gap-">
          <div class="w-full mb-5">
            <span class="font-black text-lg tei-text-secondary">Remarks</span>
            <p class="tei-text-accent text-sm">
              {{ $eligibilityRemarks }}
            </p>
          </div>
          <div class="grid sm:grid-cols-2 grid-cols-1 gap-10">
            <div class="w-full">
              <span class="font-black text-lg tei-text-secondary">Input Fields</span>
              @foreach ($eligibilityDetails as $detail)
                <div class="py-2">
                  <label class="block font-extrabold text-sm tei-text-primary pb-2">{{ $detail['field'] }}</label>
                  <input
                    class="text-gray-500 text-xs rounded-full shadow-sm tei-focus-secondary focus:ring-0 max-h-7 w-full  {{ $errors->has('input.' . $detail['id']) ? 'border-red-600 ' : 'border-gray-300 ' }}"
                    type="{{ $detail['field_type'] }}" wire:model.live="input.{{ $detail['id'] }}"
                    min="{{ isset($dateValidation[$detail->id]) && $dateValidation[$detail->id] ? $dateValidation[$detail->id]['date'] : '' }}">
                  <span
                    class="text-xs italic font-bold text-yellow-500">{{ isset($dateValidation[$detail->id]['warning']) && $dateValidation[$detail->id]['warning'] ? 'Warning! The document will expire in less than 3 months.' : '' }}
                  </span>
                  @error('input.' . $detail['id'])
                    <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                        class="font-medium">{{ $message }}</span></p>
                  @enderror
                </div>
                {{-- <h1>{{$detail['field_type']}}</h1> --}}
              @endforeach
            </div>
            <div class="w-full m-0">
              <span class="font-black text-lg tei-text-secondary">File Attachment</span>
              <div class="py-5 flex flex-col justify-center">
                @forelse($eligibilityFiles as $index => $file)
                  <div class="mb-4 flex">
                    {{-- <button data-modal-hide="eligibility-modal" type="button"
                        wire:click.prevent="viewFile">show</button> --}}
                    <button wire:click.prevent="viewFile('{{ $file->file }}')"
                      class="hover:scale-110 transition-transform duration-300 mr-4 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                        class="fa-solid fa-file-pdf text-xs"></i> {{ $file->file }}</button>
                    <button wire:loading.remove
                      wire:target="{{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'uploadFile' }}"
                      wire:click.prevent="{{ $vendorStatus->complete ? 'openSaveModalFromRemove(' . $file->id . ')' : 'uploadFile( ' . $file->id . ' )' }}"
                      class="text-white bg-red-600 focus:outline-none font-medium rounded-lg text-xs px-2 py-1 hover:scale-110 transition-transform duration-300">
                      <i class="fa-solid fa-trash-can text-xs"></i></button>
                    <x-loading-spinner color="var(--secondary)"
                      target="{{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'uploadFile' }}" />
                  </div>
                @empty
                  <div>
                    <p class="text-sm text-gray-500">No file uploaded</p>
                  </div>
                @endforelse
              </div>
              <div>
                {{-- <input
                  class="block w-full text-sm text-gray-900 border {{ $errors->has('fileInputs.*') ? 'border-red-600 ' : 'border-gray-300 ' }} rounded-e-lg cursor-pointer bg-gray-50 "
                  id="file_input" type="file" wire:model="fileInputs"> --}}
                <div class="relative w-28" wire:loading.remove
                  wire:target="fileInputs, {{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'uploadFile' }}">
                  <button id="uploadButton"
                    class="tei-bg-primary text-xs text-white font-bold py-1.5 px-4 rounded hover:scale-110 transition-transform duration-300">
                    Upload File
                  </button>
                  <input type="file" id="fileInput" wire:model="fileInputs"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                </div>
                <div class="w-20 rounded tei-bg-light flex justify-center p-3" wire:loading
                  wire:target="fileInputs, {{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'uploadFile' }}">
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
                @error('fileInputs')
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
        </div>
        <!-- Modal footer -->
        <div class="flex justify-center p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t rounded-b border-gray-200">
          <div wire:loading.remove wire:target="{{ $vendorStatus->complete ? 'openSaveModal' : 'saveForm' }}">
            <button wire:click="{{ $vendorStatus->complete ? 'openSaveModal' : 'saveForm' }}"
              wire:loading.attr="disabled" wire:target="fileInputs, uploadFile"
              class="h-full hover:scale-110 transition-transform duration-300 text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-1.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
              Save/update
            </button>
          </div>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="{{ $vendorStatus->complete ? 'openSaveModal' : 'saveForm' }}">
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
          <button data-modal-hide="eligibility-modal" type="button" wire:click.prevent="closeModal"
            wire:loading.attr="disabled" wire:target="fileInputs, uploadFile"
            class="hover:scale-110 transition-transform duration-300 py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10">
            Close
          </button>
        </div>
      </div>
    </div>
    {{-- <x-action-message class="me-3" on="update-message">
      {{ __($messageAction) }}
    </x-action-message> --}}

  </div>

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
          <span class="uppercase tei-text-primary text-lg font-black mb-5">{{ $eligibilityFileName }}</span>
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

    $wire.on('openEligibilityModal', () => {
      var modalElement = document.getElementById('eligibility-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeEligibilityModal', () => {
      var modalElement = document.getElementById('eligibility-modal');
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
