<div class="max-w-full mx-auto sm:px-6 lg:px-8 py-10">
  {{-- <div class="mb-5">
    <div>
      <span class="text-green-500 text-xs uppercase font-semibold">Fully Compliant</span>
      <span class="text-xs font-extrabold"> - 100%</span>
    </div>
    <div>
      <span class="text-yellow-500 text-xs uppercase font-semibold">Partially Complaint</span>
      <span class="text-xs font-extrabold"> - 90%</span>
    </div>
    <div>
      <span class="text-red-500 text-xs uppercase font-semibold">Non-compliant</span>
      <span class="text-xs font-extrabold"> - 70%</span>
    </div>
  </div> --}}
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
  <div class="overflow-x-auto rounded-md shadow-lg">
    @foreach ($vendors as $index => $vendor)
      <div id="technical-vendor-{{ $vendor->id }}" class="">
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
            <div class="relative overflow-x-auto">
              <table class="w-full text-sm text-left rtl:text-right text-gray-500 shadow-lg">
                <thead class="text-xs tei-text-secondary uppercase bg-white">
                  <tr>
                    <th scope="col" class="px-6 py-3 rounded-tl-lg" style="width: 323px">
                      Technical Question
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Type
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Response - score
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Rating Score (%)
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Weight (%)
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Rating
                    </th>
                    <th scope="col" class="px-6 py-3">
                      Attachment(s)
                    </th>
                    @if (Auth::user()->role->id == 4 || Auth::user()->role->id == 1)
                      <th scope="col" class="px-6 py-3 rounded-tr-lg">
                        Admin
                      </th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @foreach ($technicals as $technical)
                    <tr class="bg-white font-semibold text-xs">
                      <td class="px-6 py-4">
                        {{ $technical->question }}
                      </td>
                      <td class="px-6 py-4">
                        <span class="uppercase">{{ $technical->question_type }}</span>
                      </td>
                      <td class="px-6 py-4">
                        @if ($technical->question_type == 'multi-option')
                          @php
                            // dd($technicalResult[$vendor->id][$technical->id]['answer']);
                          @endphp
                          @if ($technicalResult[$vendor->id][$technical->id]['answer'])
                            @foreach ($technicalResult[$vendor->id][$technical->id]['answer'] as $answer)
                              @php
                                $answerOption = $technical->options->where('id', $answer)->first();
                              @endphp
                              <span
                                class="block {{ $technicalResult[$vendor->id][$technical->id]['admin_answer'] ? 'tei-text-secondary ' : '' }}">{{ $answerOption ? $answerOption->name : null }}
                                - <span class="text-green-500">{{ $answerOption ? $answerOption->score : null }}</span>
                                @if ($technicalResult[$vendor->id][$technical->id]['admin_answer'])
                                  <i class="fa-solid fa-user-tie ml-5"></i>
                                @endif
                              </span>
                            @endforeach
                          @else
                            <span class="text-red-500">Null</span>
                          @endif
                        @elseif ($technical->question_type == 'single-option')
                          @php
                            $answerId = $technicalResult[$vendor->id][$technical->id]
                                ? ($technicalResult[$vendor->id][$technical->id]['admin_answer']
                                    ? $technicalResult[$vendor->id][$technical->id]['admin_answer']
                                    : $technicalResult[$vendor->id][$technical->id]['answer'])
                                : null;
                            // dd($answerId);
                            $answerOption = $technical->options->where('id', $answerId)->first();
                          @endphp
                          @if ($answerOption)
                            @if ($technicalResult[$vendor->id][$technical->id]['admin_answer'])
                              <span class="tei-text-secondary">{{ $answerOption->name }} - <span
                                  class="text-green-500">{{ $answerOption->score }}</span></span>
                              <i class="fa-solid fa-user-tie ml-5 tei-text-secondary"></i>
                            @else
                              <span class="block">{{ $answerOption->name }} - <span
                                  class="text-green-500">{{ $answerOption->score }}</span></span>
                            @endif
                          @else
                            <span class="text-red-500">Null</span>
                          @endif
                        @else
                          <span
                            class="{{ $technicalResult[$vendor->id][$technical->id]['admin_answer'] ? 'tei-text-secondary' : '' }}">{{ $technicalResult[$vendor->id][$technical->id]['answer'] }}</span>
                          @if ($technicalResult[$vendor->id][$technical->id]['admin_answer'])
                            <i class="fa-solid fa-user-tie ml-5 tei-text-secondary"></i>
                          @endif
                        @endif
                      </td>
                      <td class="px-6 py-4">
                        <span>{{ $technicalResult[$vendor->id][$technical->id]['rating_score'] !== null ? number_format($technicalResult[$vendor->id][$technical->id]['rating_score'], 2) . '%' : 'Null' }}</span>
                      </td>
                      <td class="px-6 py-4">
                        <span>
                          {{ number_format($technicalResult[$vendor->id][$technical->id]['weight'], 2) }}%
                        </span>
                      </td>
                      <td class="px-6 py-4">
                        @if ($technicalResult[$vendor->id][$technical->id]['compliance'] == 'Fully Compliant')
                          <span
                            class="text-green-500">{{ $technicalResult[$vendor->id][$technical->id]['compliance'] }}</span>
                        @elseif($technicalResult[$vendor->id][$technical->id]['compliance'] == 'Partially Compliant')
                          <span
                            class="text-yellow-500">{{ $technicalResult[$vendor->id][$technical->id]['compliance'] }}</span>
                        @else
                          <span
                            class="text-red-500">{{ $technicalResult[$vendor->id][$technical->id]['compliance'] }}</span>
                        @endif
                      </td>
                      <td class="px-6 py-4">
                        @if ($technicalResult[$vendor->id][$technical->id]['attach'])
                          @foreach ($technicalResult[$vendor->id][$technical->id]['attach'] as $dataFile)
                            <div class="flex flex-col">
                              <button wire:click.prevent="viewFile('{{ $dataFile->file }}', {{ $dataFile->id }})"
                                wire:loading.remove
                                wire:target="viewFile('{{ $dataFile->file }}', {{ $dataFile->id }})"
                                class="{{ $fileStatus[$dataFile->id]['status'] ? 'bg-green-600' : 'bg-gray-400' }} flex mb-1 hover:scale-110 transition-transform duration-300 rounded-md px-2 py-1 text-white text-xs"><i
                                  class="fa-solid fa-file-pdf text-xs"></i> {{ $dataFile->file }}</button>
                              <div class=" mb-1">
                                <x-loading-spinner color="var(--secondary)"
                                  target="viewFile('{{ $dataFile->file }}', {{ $dataFile->id }})" />
                              </div>
                            </div>
                          @endforeach
                          {{-- <button
                            wire:click.prevent="viewFile('{{ $technicalResult[$vendor->id][$technical->id]['attach'] }}')"
                            class="hover:scale-110 transition-transform duration-300 mr-4 tei-bg-secondary rounded-md px-2 py-1 text-white text-xs">View
                            Attach</button> --}}
                        @endif
                      </td>
                      @if (Auth::user()->role->id == 4 || Auth::user()->role->id == 1)
                        <td class="px-6 py-4">
                          <button wire:click.prevent="adminModal({{ $technical->id }}, {{ $vendor->id }})"
                            wire:loading.remove wire:target="adminModal({{ $technical->id }}, {{ $vendor->id }})"
                            class="hover:scale-110 transition-transform duration-300 mr-4 bg-green-500 rounded-md px-2 py-1 text-white text-xs">Admin</button>
                          <div class="w-14 rounded tei-bg-light flex justify-center py-2.5 mr-4" wire:loading
                            wire:target="adminModal({{ $technical->id }}, {{ $vendor->id }})">
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
                      @endif
                    </tr>
                  @endforeach
                  <tr>
                    <td colspan="3" class="px-6 py-4 ">

                    </td>
                    <td class="px-6 py-4 ">
                      {{-- @if ($answerOption)
                        <span class="text-xs uppercase font-extrabold">
                          Total: {{ number_format(collect($technicalResult[$vendor->id])->sum('rating_score')) }}%
                        </span>
                      @else
                        <span class="text-xs uppercase font-extrabold text-red-500">
                          NULL
                        </span>
                      @endif --}}
                      <span class="text-xs uppercase font-extrabold">
                        Total: {{ number_format(collect($technicalResult[$vendor->id])->sum('rating_score'), 2) }}%
                      </span>
                    </td>
                    <td class="px-6 py-4 ">
                      <span class="text-xs uppercase font-extrabold">
                        Total: {{ $bidding->weights->where('envelope', 'technical')->first()->weight }}%
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div>
                <div class="grid grid-cols-2 py-5 px-5">
                  <div class="">
                    <h3 class="text-xl tei-text-secondary font-semibold mb-4">Technical Results</h3>
                    @php
                      $result = $results ? collect($results)->where('vendor_id', $vendor->id)->first() : 0;
                    @endphp
                    <div>
                      @php
                        // $envelopeResult = $bidding->technicalResult->where('vendor_id', $vendor->id)->first();
                        $envelopeResult = collect($results)->where('vendor_id', $vendor->id)->first();
                      @endphp
                      <span class="tei-text-accent font-semibold">Rating Score:</span>
                      @if (($envelopeResult ? $envelopeResult->score : 0) != 0)
                        <span class="mt-1 uppercase font-extrabold ml-2 tei-text-accent">
                          {{ number_format($envelopeResult->score, 2) }}%</span>
                      @else
                        <span class="mt-1 uppercase font-extrabold ml-2 text-red-500"> NULL</span>
                      @endif
                    </div>
                    <div>
                      <span class="tei-text-accent font-semibold">Result:</span>
                      @if ($envelopeResult && $envelopeResult->result)
                        <span class="mt-1 text-lg uppercase font-extrabold text-green-500 ml-2"> Passed</span>
                      @else
                        <span class="mt-1 text-lg uppercase font-extrabold text-red-500 ml-2"> Failed</span>
                      @endif
                    </div>
                  </div>
                  <div>
                    <h3 class="text-xl tei-text-secondary font-semibold mb-4">Remarks</h3>
                    <textarea id="message" rows="4"
                      class="{{ $errors->has('vendorRemarks') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                      placeholder="Enter Remarks" wire:model.live.debounce.250ms="vendorRemarks.{{ $vendor->id }}"></textarea>
                    <div class="flex">
                      <div class="mt-2" wire:loading wire:target="vendorRemarks.{{ $vendor->id }}">
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

  {{-- Admin Modal --}}
  <div id="admin-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-4xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <h3 class="tei-text-secondary text-lg font-semibold p-4">
          Admin
        </h3>
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          wire:click="closeAdminModal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        @if ($technicalVendor)
          <div class="p-4 md:p-5 border-t-2 flex flex-col space-y-4">
            <div>
              <label class="font-extrabold tei-text-primary mb-2">Question Type:</label>
              <span
                class="tei-text-accent text-xs uppercase font-semibold">{{ $technicalVendor->question_type }}</span>
            </div>
            <div>
              <label class="block font-extrabold tei-text-primary">Question:</label>
              <span class="tei-text-accent text-xs uppercase font-semibold">{{ $technicalVendor->question }}</span>
            </div>
            <div>
              <label class="font-extrabold tei-text-primary">Vendor Response:</label>
              @if ($adminResponse)
                @if ($technicalVendor->question_type == 'checkbox')
                  <span
                    class="tei-text-accent text-xs uppercase font-semibold">{{ $adminResponse->answer ? 'Yes' : 'No' }}</span>
                @elseif ($technicalVendor->question_type == 'single-option')
                  @php
                    $option = $technicalVendor->options->where('id', $adminResponse->answer)->first();
                  @endphp
                  <span
                    class="tei-text-accent text-xs uppercase font-semibold">{{ $option ? $option->name : 'No Response' }}</span>
                @elseif ($technicalVendor->question_type == 'multi-option')
                  @if ($adminResponse->answer)
                    @php
                      // $options = $technicalVendor->options->where('id', $adminResponse->answer)->first();
                      $optionArray = explode('&@!', $adminResponse->answer);
                      foreach ($optionArray as $data) {
                          $option = $technicalVendor->options->where('id', $data)->first();
                          $options[] = $option->name;
                      }
                    @endphp
                    <span class="tei-text-accent text-xs uppercase font-semibold">{{ implode(', ', $options) }}</span>
                  @else
                    <span class="text-red-500 text-xs uppercase font-semibold">No Response</span>
                  @endif
                @else
                  <span class="tei-text-accent text-xs uppercase font-semibold">{{ $adminResponse->answer }}</span>
                @endif
              @else
                <span class="tei-text-accent text-xs uppercase font-semibold">No Response</span>
              @endif
            </div>
            <div>
              @if ($technicalVendor->question_type == 'checkbox')
                <label class="block font-extrabold tei-text-primary">Admin Response:</label>
                <label class="inline-flex items-center cursor-pointer">
                  <input type="checkbox" value="" class="sr-only peer" wire:model="adminAnswer" checked>
                  <div
                    class="relative w-11 h-6 rounded-full peer tei-bg-accent peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full  after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                  </div>
                  <span class="ms-3 text-sm font-black tei-text-accent">Yes/No</span>
                </label>
                @error('adminAnswer')
                  <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                      class="font-medium">{{ $message }}</span></p>
                @enderror
              @elseif ($technicalVendor->question_type == 'single-option')
                <div class="w-1/2">
                  <select wire:model="adminAnswer"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 
                    {{ $errors->has('adminAnswer') ? 'border-red-600 ' : 'border-gray-300 ' }}">
                    <option value="">--Select Here--</option>
                    @foreach ($technicalVendor->options as $option)
                      <option
                        {{ ($adminResponse ? $adminResponse->answer : '') == $option->technical_id ? 'selected' : '' }}
                        value="{{ $option->id }}">
                        {{ $option->name }}</option>
                    @endforeach
                  </select>
                  @error('adminAnswer')
                    <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                        class="font-medium">{{ $message }}</span></p>
                  @enderror
                </div>
              @elseif ($technicalVendor->question_type == 'multi-option')
                @foreach ($technicalVendor->options as $option)
                  <div class="flex items-center mb-4">
                    <input type="checkbox" wire:model="adminMulitAnswer.{{ $option->id }}"
                      class="w-4 h-4 tei-text-accent bg-gray-100 border-gray-300 rounded focus:ring-orange-500 dark:focus:ring-orange-600 ">
                    <label for="{{ $option->id }}"
                      class="ms-2 text-sm font-medium text-gray-900">{{ $option->name }}</label>
                  </div>
                @endforeach
                @error('adminAnswer')
                  <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                      class="font-medium">{{ $message }}</span></p>
                @enderror
              @else
                <div class="flex">
                  <span
                    class="inline-flex items-center px-3 text-sm tei-text-light tei-bg-primary border rounded-e-0 border-gray-300 border-e-0 rounded-s-md ">
                    Admin Response
                  </span>
                  <div class="relative">
                    <input type="text" wire:model="adminAnswer"
                      class="block w-full text-xs tei-text-accent bg-transparent rounded-e-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 tei-focus-secondary peer"
                      placeholder=" " />
                  </div>
                </div>
                @error('adminAnswer')
                  <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                      class="font-medium">{{ $message }}</span></p>
                @enderror
              @endif
            </div>
          </div>
        @endif
        <div class="flex justify-end p-4 md:p-5 border-t-2 border-gray-200 rounded-b space-x-4">
          <button type="button" wire:click="closeAdminModal"
            class="text-white bg-gray-400 hover:bg-gray-500 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
            Close
          </button>
          <button type="submit" wire:click="submitAdminResponse" wire:loading.remove
            wire:target="submitAdminResponse"
            class="text-white bg-green-500 hover:bg-green-700 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
            Submit
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="submitAdminResponse">
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
        </div>
      </div>
    </div>
  </div>
  {{-- END Admin Modal --}}

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
          <span class="uppercase tei-text-primary text-lg font-black mb-5">{{ $technicalFileName }}</span>
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
      var modalElement = document.getElementById('admin-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
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
  </script>
@endscript
