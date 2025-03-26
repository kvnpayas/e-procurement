<div class="max-w-full mx-auto sm:px-6 lg:px-8 py-10">
  <div class="overflow-x-auto rounded-md shadow-lg">
    <div class="p-2 flex justify-end tei-bg-light">
      <button wire:click="printReport" wire:loading.remove wire:target="printReport"
        class="text-xs bg-green-500 hover:bg-green-600 uppercase font-semibold text-white py-1.5 px-4 rounded-md shadow-lg hover:scale-110 transition-transform duration-300"><i
          class="fa-solid fa-file-lines"></i> Prints</button>
      <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4" wire:loading wire:target="printReport">
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
    </div>
    @foreach ($vendors as $index => $vendor)
      <div id="eligibility-vendor-{{ $vendor->id }}" class="">
        <div class="p-2 tei-bg-gradient shadow-none hover:shadow-lg">
          <h3 id="header-{{ $vendor->id }}" onclick="toggleArrow({{ $vendor->id }})"
            class=" px-2 cursor-pointer tei-text-light text-lg font-semibold rounded-t-md hover:text-orange-500 transition ease-in-out">
            <i id="arrow-icon-{{ $vendor->id }}"
              class="fa-solid fa-chevron-up text-sm transition-transform duration-500 ease-in-out {{ $index == 0 ? 'down-arrow' : 'up-arrow' }}"></i>
            <span class="ml-4">{{ $vendor->name }}</span>
          </h3>
        </div>
        <div class="vendor-content tei-bg-light pt-4 {{ $index == 0 ? 'content-show' : 'content-hide' }}"
          id="vendor-content-{{ $vendor->id }}" wire:ignore.self>
          <div class="p-4 ">
            <div>
              <table class="w-full text-sm text-left rtl:text-right text-gray-500 shadow-lg">
                <thead class="text-xs tei-text-secondary uppercase bg-white">
                  <tr>
                    <th scope="col" class="px-6 py-3 rounded-tl-lg">
                      Eligibility Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                      File Attachment(s)
                    </th>
                    <th scope="col" class="px-6 py-3 rounded-tr-lg">
                      Review
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($eligibilities as $eligibility)
                    <tr class="bg-white ">
                      <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                        {{ $eligibility->name }}
                      </th>
                      <td class="px-6 py-4">

                        {{-- Check if vendors response all requirements --}}

                        @if ($this->eligibilityResult[$vendor->id][$eligibility->id])
                          <i class="fa-solid fa-circle-check text-green-500"
                            data-tooltip-target="tooltip-status-{{ $eligibility->id }}-{{ $vendor->id }}"></i>
                          <div id="tooltip-status-{{ $eligibility->id }}-{{ $vendor->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
                            The vendor submit and response all requirements.
                            <div class="tooltip-arrow" data-popper-arrow></div>
                          </div>
                        @else
                          <i class="fa-solid fa-circle-xmark text-red-500"
                            data-tooltip-target="tooltip-status-{{ $eligibility->id }}-{{ $vendor->id }}"></i>
                          <div id="tooltip-status-{{ $eligibility->id }}-{{ $vendor->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
                            The vendor failed to submit and response all requirements.
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        @endif
                        {{-- End Check if vendors response all requirements --}}
                      </td>
                      <td class="px-6 py-4">

                        {{-- Check if venodr uploads files --}}
                        @php
                          $files = $eligibility->vendorFiles
                              ->where('bidding_id', $bidding->id)
                              ->where('vendor_id', $vendor->id)
                              ->where('file', '!=', '');
                          $filesAdmin = $eligibility->vendorFiles
                              ->where('bidding_id', $bidding->id)
                              ->where('vendor_id', $vendor->id)
                              ->where('admin_file', '!=', '');
                        @endphp

                        {{-- Check if venodr uploads files --}}

                        @if ($files->isEmpty() && $filesAdmin->isEmpty())
                          <span class="text-red-500 text-sm font-semibold">No files</span>
                        @endif
                        @foreach ($files as $file)
                          <div class="mb-2">
                            <button
                              wire:click.prevent="viewAttachFile('{{ $file->file }}', '{{ $eligibility->name }}', {{ $file->id }})"
                              wire:loading.remove
                              wire:target="viewAttachFile('{{ $file->file }}', '{{ $eligibility->name }}', {{ $file->id }})"
                              class="{{ $fileStatus[$file->id]['status'] ? 'bg-green-600' : 'bg-gray-400' }} hover:scale-110 transition-transform duration-300 mr-4 rounded-md px-2 py-1 text-white text-xs"><i
                                class="fa-solid fa-file-pdf text-xs"></i> {{ $file->file }}</button>
                            <x-loading-spinner color="var(--secondary)"
                              target="viewAttachFile('{{ $file->file }}', '{{ $eligibility->name }}', {{ $file->id }})" />
                          </div>
                        @endforeach
                        @if (!$filesAdmin->isEmpty())
                          @foreach ($filesAdmin as $fileAdmin)
                            <div class="py-2">
                              <button wire:loading.remove
                                wire:target="viewAttachFile('{{ $fileAdmin->admin_file }}', '{{ $eligibility->name }}', {{ $fileAdmin->id }})"
                                wire:click.prevent="viewAttachFile('{{ $fileAdmin->admin_file }}', '{{ $eligibility->name }}', {{ $fileAdmin->id }})"
                                class="hover:scale-110 transition-transform duration-300 mr-4 tei-bg-secondary rounded-md px-2 py-1 text-white text-xs"><i
                                  class="fa-solid fa-file-pdf text-xs"></i> {{ $fileAdmin->admin_file }}</button>
                              <x-loading-spinner color="var(--primary)"
                                target="viewAttachFile('{{ $fileAdmin->admin_file }}', '{{ $eligibility->name }}', {{ $fileAdmin->id }})" />
                              <i class="fa-solid fa-user-tie"></i>
                            </div>
                          @endforeach
                        @endif
                      </td>
                      <td class="px-6 py-4">
                        <button wire:click.prevent="reviewModal({{ $eligibility->id }}, {{ $vendor->id }})"
                          wire:loading.remove wire:target="reviewModal({{ $eligibility->id }}, {{ $vendor->id }})"
                          class="hover:scale-110 transition-transform duration-300 mr-4 tei-bg-primary rounded-md px-2 py-1 text-white text-xs">Review</button>
                        <div class="w-14 rounded tei-bg-light flex justify-center py-2.5 mr-4" wire:loading
                          wire:target="reviewModal({{ $eligibility->id }}, {{ $vendor->id }})">
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
                        @if (Auth::user()->role->id == 4 || Auth::user()->role->id == 1)
                          <button wire:click.prevent="adminModal({{ $eligibility->id }}, {{ $vendor->id }})"
                            wire:loading.remove wire:target="adminModal({{ $eligibility->id }}, {{ $vendor->id }})"
                            class="hover:scale-110 transition-transform duration-300 mr-4 bg-green-500 rounded-md px-2 py-1 text-white text-xs">Admin</button>
                          <div class="w-14 rounded tei-bg-light flex justify-center py-2.5 mr-4" wire:loading
                            wire:target="adminModal({{ $eligibility->id }}, {{ $vendor->id }})">
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
                      </td>
                    </tr>
                  @endforeach

                </tbody>
              </table>

              <div>
                <div class="grid grid-cols-2 py-5 px-5">
                  <div class="">
                    <h3 class="text-xl tei-text-secondary font-semibold mb-4">Eligibility Results</h3>
                    @php
                      $vendorResults = collect($results)->where('vendor_id', $vendor->id)->first();
                    @endphp
                    @if ($vendorResults && $vendorResults->result)
                      <p class="mt-1 text-lg uppercase font-extrabold text-green-500 ml-2"> Passed</p>
                    @else
                      <p class="mt-1 text-lg uppercase font-extrabold text-red-500 ml-2"> Failed</p>
                    @endif
                  </div>
                  <div>
                    <h3 class="text-xl tei-text-secondary font-semibold mb-4">Remarks</h3>
                    <textarea id="message" rows="4"
                      class="{{ $errors->has('vendorRemarks') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                      placeholder="Enter Remarks" wire:model.live.debounce.250ms="vendorRemarks.{{ $vendor->id }}"></textarea>
                    <div class="flex">
                      <div class="mt-2" wire:loading
                        wire:target="vendorRemarks.{{ $vendor->id }}">
                        <x-loading-spinner color="var(--secondary)" target="vendorRemarks.{{ $vendor->id }}" />
                      </div>
                      <div class="flex items-center justify-center mt-3 text-xs tei-text-accent" wire:loading
                        wire:target="vendorRemarks.{{ $vendor->id }}">
                        <span class="ml-2">Saving...</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach

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
          <span class="uppercase tei-text-primary text-lg font-black mb-5">{{ $eligibilityName }}</span>
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

  {{-- Review Modal --}}
  <div id="review-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          wire:click="closeReviewModal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5">
          @if ($eligibilityReview)
            <div class="py-4">
              <h3 class="text-xl tei-text-secondary font-semibold">{{ $eligibilityReview->name }}</h3>
              <p class="mt-1 text-sm font-normal text-gray-500 ">Lists of vendor responses on this eligibility.
              </p>
            </div>
            <hr>
            {{-- {{dd($eligibilityReview->details)}} --}}
            @foreach ($eligibilityReview->details as $detail)
              <div class="my-5">
                <label class="block text-lg font-extrabold tei-text-primary mb-2">{{ $detail->field }}</label>
                <span class="tei-text-accent">Response: </span>
                @php
                  $response = $eligibilityResponse->where('eligibility_detail_id', $detail->id)->first();
                @endphp

                @if ($response)
                  <span
                    class="tei-text-accent font-black mb-5">{{ $detail->field_type == 'text' ? $response->response : date('F j,Y', strtotime($response->response)) }}</span>
                @else
                  <span class="text-red-500 font-black mb-5">No response</span>
                @endif
              </div>
            @endforeach
          @endif
        </div>
        <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
          <button type="submit" wire:click="closeReviewModal"
            class="text-white bg-gray-400 hover:bg-gray-500 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Review Modal --}}

  {{-- Admin Modal --}}
  <div id="admin-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          wire:click.prevent="closeAdminModal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5">
          @if ($eligibilityDetails)
            <div class="py-4">
              <h3 class="text-xl tei-text-secondary font-semibold">{{ $eligibilityDetails->name }}</h3>
              <p class="mt-1 text-sm font-normal text-gray-500 ">Lists of vendor responses on this eligibility.
              </p>
            </div>
            <hr>
            <div class="grid grid-cols-2 py-4">
              <div>
                <label class="text-sm uppercase tei-text-secondary font-semibold">Vendor Response</label>
                @foreach ($eligibilityDetails->details as $detail)
                  <div class="my-5">
                    <label class="block text-lg font-extrabold tei-text-primary mb-2">{{ $detail->field }}</label>
                    <span class="tei-text-accent">Response: </span>
                    @php
                      $response = $vendorResponse->where('eligibility_detail_id', $detail->id)->first();
                    @endphp

                    @if ($response)
                      <span
                        class="tei-text-accent font-black mb-5">{{ $detail->field_type == 'text' ? $response->response : date('F j,Y', strtotime($response->response)) }}</span>
                    @else
                      <span class="text-red-500 font-black mb-5">No response</span>
                    @endif
                  </div>
                @endforeach
              </div>
              <div>
                <label class="text-sm uppercase tei-text-secondary font-semibold">Admin Response</label>
                @foreach ($eligibilityDetails->details as $detail)
                  <div class="my-5">
                    <label class="block text-lg font-extrabold tei-text-primary mb-2">{{ $detail->field }}</label>
                    <input type="{{ $detail->field_type }}" id="name"
                      wire:model="adminResponse.{{ $detail->id }}"
                      class="text-xs text-gray-900 {{ $errors->has('adminResponse.' . $detail->id) ? 'border border-red-500 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border tei-border-primary tei-focus-secondary ' }} rounded-lg block w-full p-1.5 focus:ring-1" />
                    @error('adminResponse.' . $detail->id)
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                @endforeach
                <div>
                  @if ($hasFile)
                    <div class="py-2 flex flex-col justify-center">
                      @foreach ($adminFiles as $index => $file)
                        <div class="mb-4">
                          {{-- <span class="tei-text-primary font-extrabold pr-4">File {{ $index + 1 }}</span> --}}
                          {{-- <button data-modal-hide="eligibility-modal" type="button"
                          wire:click.prevent="viewFile">show</button> --}}
                          {{-- <button wire:click.prevent="viewFile('{{ $file->file }}')"
                            class="hover:scale-110 transition-transform duration-300 mr-4 tei-bg-secondary rounded-md px-2 py-1 text-white text-xs"><i
                              class="fa-solid fa-file-pdf text-xs"></i> View file</button> --}}
                          <span
                            class="text-xs tei-text-secondary uppercase font-semibold">{{ $file->admin_file }}</span>
                        </div>
                      @endforeach
                      <div class="">
                        <button wire:click.prevent="uploadFile" wire:loading.remove wire:target="uploadFile"
                          class="text-white bg-green-600 focus:outline-none  font-medium rounded-lg text-sm px-2 py-1.5 text-center hover:scale-110 transition-transform duration-300">Change
                          uploaded files</button>
                        <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
                          wire:target="uploadFile">
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
                      </div>
                    </div>
                  @else
                    <div class="py-8">
                      <input
                        class="block w-full text-sm text-gray-900 border {{ $errors->has('fileInputs.*') ? 'border-red-600 ' : 'border-gray-300 ' }} rounded-e-lg cursor-pointer bg-gray-50 "
                        id="file_input" type="file" wire:model.live="fileInputs" multiple>
                      @error('fileInputs.*')
                        <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                            class="font-medium">{{ $message }}</span></p>
                      @enderror
                      @error('fileInputs')
                        <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                            class="font-medium">{{ $message }}</span></p>
                      @enderror
                    </div>
                  @endif
                </div>
              </div>
            </div>
          @endif
        </div>
        <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b space-x-4">
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="fileInputs, submitAdminResponse">
            <div class="flex justify-center">
              <div class="loading-small loading-main">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
              </div>
            </div>
          </div>
          <button type="submit" wire:click="submitAdminResponse" wire:target="fileInputs, submitAdminResponse"
            wire:loading.remove
            class="text-white bg-green-500 hover:bg-green-700 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
            Submit
          </button>
          <button type="submit" wire:click.prevent="closeAdminModal"
            class="text-white bg-gray-400 hover:bg-gray-500 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Admin Modal --}}


  @livewire('admin.print-reports-modal')
</div>
@section('content-script')
  <script>
    function toggleArrow(vendorId) {
      var arrowIcon = document.getElementById("arrow-icon-" + vendorId);
      var content = document.getElementById("vendor-content-" + vendorId);

      if (arrowIcon.classList.contains('up-arrow')) {
        arrowIcon.classList.remove('up-arrow');
        arrowIcon.classList.add('down-arrow');

        content.classList.add('content-show');
        content.classList.remove('content-hide');
      } else {
        arrowIcon.classList.remove('down-arrow');
        arrowIcon.classList.add('up-arrow');

        content.classList.remove('content-show');
        content.classList.add('content-hide');
      }
    }
  </script>
@endsection
@script
  <script>
    $wire.on('openAdminModal', () => {
      var modalElementOpen = document.getElementById('admin-modal');
      var modalOpen = new Modal(modalElementOpen, {
        backdrop: 'static'
      });
      modalOpen.show();
    });

    $wire.on('closeAdminModal', () => {
      var modalElement = document.getElementById('admin-modal');
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
    $wire.on('openReviewModal', () => {
      var modalElementOpen = document.getElementById('review-modal');
      var modalOpen = new Modal(modalElementOpen, {
        backdrop: 'static'
      });
      modalOpen.show();
    });

    $wire.on('closeReviewModal', () => {
      var modalElement = document.getElementById('review-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
