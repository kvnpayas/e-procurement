<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      <div class="flex justify-between">
        <div class="w-1/2">
          Technical Requirements
          <p class="mt-1 text-sm font-normal text-gray-500 ">Please respond and answer all technical requirements. Make
            sure
            to click 'Save' if any changes are made. If something seems wrong, please refresh the page.
          </p>
        </div>
        @php
          $bidRemarks = $bid->envelopeRemarks->where('envelope', 'technical')->first();
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
    @forelse ($technicals as $index => $technical)
      {{-- <thead class="text-xs text-gray-700 uppercase ">
          <tr class="bg-white border-b ">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
              <span class="mr-2 font-black text-lg">{{ $index + 1 }}.</span><span
                class="tei-text-primary font-extrabold">{{ $technical->question }}</span>
            </th>
          </tr>
        </thead> --}}
      <tbody>
        <tr class="">
          <td scope="row" class="px-6 py-4 font-medium text-gray-900">
            <div class="flex justify-center">
              {{-- {{ $technical['question_type'] }} --}}
              @if ($technical->question_type == 'numeric' || $technical->question_type == 'numeric-percent')
                {{-- <input type="number"
                  class="rounded-full shadow-sm focus:ring-orange-700 max-h-8 focus:border-orange-700 w-1/2
                  {{ $errors->has('answers.' . $technical->id) ? 'border-red-600 ' : 'border-gray-300 ' }}"
                  wire:model="answers.{{ $technical->id }}">
                @error('answers.' . $technical->id)
                  <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                      class="font-medium">{{ $message }}</span></p>
                @enderror --}}
                <div class="grid lg:grid-cols-3 w-full max-w-7xl border border-gray-200 rounded-lg shadow-xl">
                  <div class="p-5 flex flex-col border-r border-gray-200">
                    <span class="tei-text-primary font-extrabold"><span
                        class="mr-2 font-black text-lg">{{ $index + 1 }}.</span>{{ $technical->question }}</span>
                    <div class="flex items-center mt-5">
                      <input type="number"
                        class="rounded-full shadow-sm focus:ring-orange-700 max-h-8 focus:border-orange-700 w-full
                      {{ $errors->has('answers.' . $technical->id) ? 'border-red-600 ' : 'border-gray-300 ' }}"
                        wire:model="answers.{{ $technical->id }}">
                    </div>
                    @error('answers.' . $technical->id)
                      <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                          class="font-medium">{{ $message }}</span></p>
                    @enderror
                  </div>
                  <div class="p-5 border-r">
                    <div class="">
                      <label class="tei-text-primary font-extrabold">Upload
                        file</label>
                      @if (isset($hasFiles[$technical->id]) && $hasFiles[$technical->id])
                        @foreach ($hasFiles[$technical->id] as $dataFile)
                          <div class="my-5 flex">
                            <button wire:click.prevent="viewFile('{{ $dataFile->file }}')"
                              class=" hover:scale-110 transition-transform duration-300 mr-4 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                                class="fa-solid fa-file-pdf text-xs"></i> {{ $dataFile->file }}</button>
                            <button wire:loading.remove
                              wire:target="{{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'removeFile' }}"
                              wire:click.prevent="{{ $vendorStatus->complete ? 'openSaveModalFromRemove(' . $dataFile->id . ',' . $technical->id . ')' : 'removeFile(' . $dataFile->id . ',' . $technical->id . ')' }}"
                              class="text-white bg-red-600 focus:outline-none font-medium rounded-lg text-xs px-2 py-1 hover:scale-110 transition-transform duration-300">
                              <i class="fa-solid fa-trash-can text-xs"></i></button>
                            <x-loading-spinner color="var(--secondary)"
                              target="{{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'removeFile' }}" />
                          </div>
                        @endforeach
                      @endif
                      @error('files.' . $technical->id)
                        <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                            class="font-medium">{{ $message }}</span></p>
                      @enderror
                      @error('hasFiles.' . $technical->id)
                        <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                            class="font-medium">{{ $message }}</span></p>
                      @enderror

                      <div class="relative w-28" wire:loading.remove wire:target="files.{{ $technical->id }}">
                        <button id="uploadButton"
                          class="tei-bg-primary text-xs text-white font-bold py-1.5 px-4 rounded hover:scale-110 transition-transform duration-300">
                          Upload File
                        </button>
                        <input type="file" id="fileInput" wire:model="files.{{ $technical->id }}"
                          class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                      </div>
                      <div class="w-20 rounded tei-bg-light flex justify-center p-3" wire:loading
                        wire:target="files.{{ $technical->id }}">
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
                  <div class="p-5 ">
                    <label class="tei-text-primary font-extrabold">Remarks</label>
                    <p class="tei-text-accent text-xs">
                      {{ $technical->pivot->remarks }}
                    </p>
                  </div>
                </div>
              @elseif ($technical->question_type == 'checkbox')
                <div class="grid lg:grid-cols-3 w-full max-w-7xl border border-gray-200 rounded-lg shadow-xl">
                  <div class="p-5 flex flex-col border-r border-gray-200">
                    <span class="tei-text-primary font-extrabold"><span
                        class="mr-2 font-black text-lg">{{ $index + 1 }}.</span>{{ $technical->question }}</span>
                    <div class="flex items-center mt-5">
                      <input type="checkbox" value=""
                        class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                        wire:model="answers.{{ $technical->id }}" checked>
                      <label class="ms-2 text-sm text-gray-500">Checkbox</label>
                    </div>
                    @error('answers.' . $technical->id)
                      <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                          class="font-medium">{{ $message }}</span></p>
                    @enderror
                  </div>
                  <div class="p-5 border-r">
                    <div class="">
                      <label class="tei-text-primary font-extrabold">Upload
                        file</label>
                      @if (isset($hasFiles[$technical->id]) && $hasFiles[$technical->id])
                        @foreach ($hasFiles[$technical->id] as $dataFile)
                          <div class="my-5 flex">
                            <button wire:click.prevent="viewFile('{{ $dataFile->file }}')"
                              class=" hover:scale-110 transition-transform duration-300 mr-4 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                                class="fa-solid fa-file-pdf text-xs"></i> {{ $dataFile->file }}</button>
                            <button wire:loading.remove
                              wire:target="{{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'removeFile' }}"
                              wire:click.prevent="{{ $vendorStatus->complete ? 'openSaveModalFromRemove(' . $dataFile->id . ',' . $technical->id . ')' : 'removeFile(' . $dataFile->id . ',' . $technical->id . ')' }}"
                              class="text-white bg-red-600 focus:outline-none font-medium rounded-lg text-xs px-2 py-1 hover:scale-110 transition-transform duration-300">
                              <i class="fa-solid fa-trash-can text-xs"></i></button>
                            <x-loading-spinner color="var(--secondary)"
                              target="{{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'removeFile' }}" />
                          </div>
                        @endforeach
                      @endif
                      @error('files.' . $technical->id)
                        <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                            class="font-medium">{{ $message }}</span></p>
                      @enderror
                      @error('hasFiles.' . $technical->id)
                        <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                            class="font-medium">{{ $message }}</span></p>
                      @enderror

                      <div class="relative w-28" wire:loading.remove wire:target="files.{{ $technical->id }}">
                        <button id="uploadButton"
                          class="tei-bg-primary text-xs text-white font-bold py-1.5 px-4 rounded hover:scale-110 transition-transform duration-300">
                          Upload File
                        </button>
                        <input type="file" id="fileInput" wire:model="files.{{ $technical->id }}"
                          class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                      </div>
                      <div class="w-20 rounded tei-bg-light flex justify-center p-3" wire:loading
                        wire:target="files.{{ $technical->id }}">
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
                  <div class="p-5 ">
                    <label class="tei-text-primary font-extrabold">Remarks</label>
                    <p class="tei-text-accent text-xs">
                      {{ $technical->pivot->remarks }}
                    </p>
                  </div>
                </div>
              @elseif ($technical->question_type == 'single-option')
                <div class="grid lg:grid-cols-3 w-full max-w-7xl border border-gray-200 rounded-lg shadow-xl">
                  <div class="p-5 flex flex-col border-r border-gray-200">
                    <span class="tei-text-primary font-extrabold"><span
                        class="mr-2 font-black text-lg">{{ $index + 1 }}.</span>{{ $technical->question }}</span>
                    <div class="flex items-center mt-5">
                      <select wire:model="answers.{{ $technical->id }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5 
                        {{ $errors->has('answers.' . $technical->id) ? 'border-red-600 ' : 'border-gray-300 ' }}">
                        <option value="">--Select Here--</option>
                        @foreach ($technical->options as $option)
                          <option {{ $technical->id == $option->technical_id ? 'selected' : '' }}
                            value="{{ $option->id }}">
                            {{ $option->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    @error('answers.' . $technical->id)
                      <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                          class="font-medium">{{ $message }}</span></p>
                    @enderror
                  </div>
                  <div class="p-5 border-r">
                    <div class="">
                      <label class="tei-text-primary font-extrabold">Upload
                        file</label>
                      @if (isset($hasFiles[$technical->id]) && $hasFiles[$technical->id])
                        @foreach ($hasFiles[$technical->id] as $dataFile)
                          <div class="my-5 flex">
                            <button wire:click.prevent="viewFile('{{ $dataFile->file }}')"
                              class=" hover:scale-110 transition-transform duration-300 mr-4 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                                class="fa-solid fa-file-pdf text-xs"></i> {{ $dataFile->file }}</button>
                            <button wire:loading.remove
                              wire:target="{{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'removeFile' }}"
                              wire:click.prevent="{{ $vendorStatus->complete ? 'openSaveModalFromRemove(' . $dataFile->id . ',' . $technical->id . ')' : 'removeFile(' . $dataFile->id . ',' . $technical->id . ')' }}"
                              class="text-white bg-red-600 focus:outline-none font-medium rounded-lg text-xs px-2 py-1 hover:scale-110 transition-transform duration-300">
                              <i class="fa-solid fa-trash-can text-xs"></i></button>
                            <x-loading-spinner color="var(--secondary)"
                              target="{{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'removeFile' }}" />
                          </div>
                        @endforeach
                      @else
                        <div class="mt-5">
                          <p class="text-sm text-gray-500">No file uploaded</p>
                        </div>
                      @endif
                      @error('files.' . $technical->id)
                        <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                            class="font-medium">{{ $message }}</span></p>
                      @enderror
                      @error('hasFiles.' . $technical->id)
                        <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                            class="font-medium">{{ $message }}</span></p>
                      @enderror

                      <div class="mt-5 relative w-28" wire:loading.remove wire:target="files.{{ $technical->id }}">
                        <button id="uploadButton"
                          class="tei-bg-primary text-xs text-white font-bold py-1.5 px-4 rounded hover:scale-110 transition-transform duration-300">
                          Upload File
                        </button>
                        <input type="file" id="fileInput" wire:model="files.{{ $technical->id }}"
                          class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                      </div>
                      <div class="w-20 rounded tei-bg-light flex justify-center p-3" wire:loading
                        wire:target="files.{{ $technical->id }}">
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
                  <div class="p-5 ">
                    <label class="tei-text-primary font-extrabold">Remarks</label>
                    <p class="tei-text-accent text-xs">
                      {{ $technical->pivot->remarks }}
                    </p>
                  </div>
                </div>
              @elseif ($technical->question_type == 'multi-option')
                <div class="grid lg:grid-cols-3 w-full max-w-7xl border border-gray-200 rounded-lg shadow-xl">
                  <div class="p-5 flex flex-col border-r border-gray-200">
                    <span class="tei-text-primary font-extrabold"><span
                        class="mr-2 font-black text-lg">{{ $index + 1 }}.</span>{{ $technical->question }}</span>
                    <div class="mt-5">
                      @foreach ($technical->options as $option)
                        <div class="flex items-center mb-4">
                          <input type="checkbox" wire:model="multiAnswers.{{ $technical->id }}.{{ $option->id }}"
                            class="w-4 h-4 tei-text-accent bg-gray-100 border-gray-300 rounded focus:ring-orange-500 dark:focus:ring-orange-600 ">
                          <label for="{{ $option->id }}"
                            class="ms-2 text-sm font-medium text-gray-900">{{ $option->name }}</label>
                        </div>
                      @endforeach
                    </div>
                    @error('answers.' . $technical->id)
                      <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                          class="font-medium">{{ $message }}</span></p>
                    @enderror
                  </div>
                  <div class="p-5 border-r">
                    <div class="">
                      <label class="tei-text-primary font-extrabold">Upload
                        file</label>
                      @if (isset($hasFiles[$technical->id]) && $hasFiles[$technical->id])
                        @foreach ($hasFiles[$technical->id] as $dataFile)
                          <div class="my-5 flex">
                            <button wire:click.prevent="viewFile('{{ $dataFile->file }}')"
                              class=" hover:scale-110 transition-transform duration-300 mr-4 bg-green-600 rounded-md px-2 py-1 text-white text-xs"><i
                                class="fa-solid fa-file-pdf text-xs"></i> {{ $dataFile->file }}</button>
                            <button wire:loading.remove
                              wire:target="{{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'removeFile' }}"
                              wire:click.prevent="{{ $vendorStatus->complete ? 'openSaveModalFromRemove(' . $dataFile->id . ',' . $technical->id . ')' : 'removeFile(' . $dataFile->id . ',' . $technical->id . ')' }}"
                              class="text-white bg-red-600 focus:outline-none font-medium rounded-lg text-xs px-2 py-1 hover:scale-110 transition-transform duration-300">
                              <i class="fa-solid fa-trash-can text-xs"></i></button>
                            <x-loading-spinner color="var(--secondary)"
                              target="{{ $vendorStatus->complete ? 'openSaveModalFromRemove' : 'removeFile' }}" />
                          </div>
                        @endforeach
                      @endif
                      @error('files.' . $technical->id)
                        <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                            class="font-medium">{{ $message }}</span></p>
                      @enderror
                      @error('hasFiles.' . $technical->id)
                        <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                            class="font-medium">{{ $message }}</span></p>
                      @enderror

                      <div class="relative w-28" wire:loading.remove wire:target="files.{{ $technical->id }}">
                        <button id="uploadButton"
                          class="tei-bg-primary text-xs text-white font-bold py-1.5 px-4 rounded hover:scale-110 transition-transform duration-300">
                          Upload File
                        </button>
                        <input type="file" id="fileInput" wire:model="files.{{ $technical->id }}"
                          class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                      </div>
                      <div class="w-20 rounded tei-bg-light flex justify-center p-3" wire:loading
                        wire:target="files.{{ $technical->id }}">
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
                  <div class="p-5 ">
                    <label class="tei-text-primary font-extrabold">Remarks</label>
                    <p class="tei-text-accent text-xs">
                      {{ $technical->pivot->remarks }}
                    </p>
                  </div>
                </div>
              @endif
              {{-- <div class="max-w-lg mt-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="user_avatar">Upload
                  file</label>
                <input
                  class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                  type="file" wire:model="files.{{ $technical->id }}">
                <div class="mt-1 text-sm text-gray-500">(Optional) Attach
                  support documents</div>
                @error('files.' . $technical->id)
                  <p id="outlined_error_help" class="mt-2 text-xs text-red-600 dark:text-red-400"><span
                      class="font-medium">{{ $message }}</span></p>
                @enderror
              </div> --}}
            </div>
          </td>
        </tr>
      </tbody>
    @empty
      <tr class="bg-white border-b">
        <th scope="row" colspan="100" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
          No Technical Requirements
        </th>
      </tr>
    @endforelse
    <tr>
      <td colspan="100" class="flex justify-center py-5">
        <div wire:loading.remove>
          <button wire:click="{{ $vendorStatus->complete ? 'openSaveModal' : 'saveForm' }}"
            class="text-white bg-green-700 hover:bg-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">
            Save
          </button>
        </div>
        <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading>
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
    {{-- </tbody> --}}
  </table>
  {{-- 
  <x-action-message class="me-3" on="update-message">
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
