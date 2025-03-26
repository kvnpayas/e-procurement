<div>
  {{-- Add/Edit Question --}}
  <div id="add-question-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-5xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Technical Question
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            wire:click.prevent="closeAddModal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        @if ($technical)
          <form wire:submit="questionForm">
            <div class="p-4 md:p-5 space-y-4">
              <h3 class="text-lg font-extrabold tei-text-secondary">
                {{ $technical->name }}
              </h3>
              {{-- <p class="mt-1 text-xs font-extrabold tei-text-secondary italic"> Please review all the input details
              before updating. Thank you </p> --}}
              <div class="mb-6">
                <div>
                  <label for="question" class="block mb-2 text-sm font-extrabold tei-text-primary ">Question</label>
                  <div>
                    <textarea id="message" rows="4"
                      class="{{ $errors->has('question') ? ' border border-red-500 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-orange-600 focus:border-orange-600 ' }} block p-2.5 w-full text-sm rounded-lg border"
                      placeholder="Question" wire:model="question"></textarea>
                    @error('question')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                </div>
                {{-- <div class="mt-5">
                  <label for="remarks" class="block mb-2 text-sm font-extrabold tei-text-primary ">Remarks</label>
                  <div>
                    <textarea id="message" rows="4"
                      class="{{ $errors->has('remarks') ? ' border border-red-500  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-orange-600 focus:border-orange-600 ' }} block p-2.5 w-full text-sm rounded-lg border"
                      placeholder="Remarks" wire:model="remarks"></textarea>
                    @error('remarks')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                </div> --}}

                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 ">
                  <div>
                    <label for="attachment" class="block mb-2 text-sm font-extrabold tei-text-primary ">File
                      Attachment</label>
                    <div>

                      @if ($this->fileExist)
                        <span class="text-sm tei-text-secondary font-extrabold">{{ $technical->attachment }}</span>
                      @else
                        <input
                          class="{{ $errors->has('attachment') ? ' border border-red-500  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 focus:ring-orange-600 focus:border-orange-600 ' }} {{ $fileSwitch ? 'text-gray-900' : 'text-gray-400' }} focus:outline-none block w-full text-sm border rounded-lg cursor-pointer"
                          type="file" wire:model="attachment" {{ $fileSwitch ? '' : 'disabled' }}>
                        @error('attachment')
                          <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                            {{ $message }}
                          </p>
                        @enderror
                      @endif

                    </div>
                  </div>
                  <div>
                    @if ($this->fileExist)
                      <label for="attachment" class="block mb-2 text-sm font-extrabold tei-text-primary ">Action</label>
                      <button type="button"
                        class="hover:scale-110 transition-transform duration-300 text-white bg-red-500 hover:bg-red-700 font-medium rounded-lg text-xs px-5 py-2 me-2 mb-2"
                        wire:click.prevent="changeAttach">
                        <i class="fa-solid fa-trash text-xs"></i> Remove
                      </button>
                    @else
                      <label for="attachment"
                        class="block mb-2 text-sm font-extrabold tei-text-primary ">Enabled/disabled
                        file
                        upload</label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" wire:model.live="fileSwitch">
                        <div
                          class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 focus:outline-none peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600">
                        </div>
                        <span class="ms-3 text-sm font-medium">If disabled, it will not upload files.</span>
                      </label>
                    @endif

                  </div>
                </div>


                <div class="mb-6 w-full sm:w-1/2">
                  <label for="question_type" class="block mb-2 text-sm font-extrabold tei-text-primary ">Question
                    Type</label>
                  <select id="small"
                    class="block w-full p-2 text-sm border  rounded-lg
                  {{ $errors->has('selectedQuestionType') ? ' border border-red-500  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-orange-600 focus:border-orange-600 ' }}"
                    wire:model.live="selectedQuestionType">
                    <option selected>--Select Question Type--</option>
                    <option value="numeric">Numeric</option>
                    <option value="numeric-percent">Numeric Percentage</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="single-option">Single Option</option>
                    <option value="multi-option">Multi Option</option>
                  </select>
                  @error('selectedQuestionType')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                      {{ $message }}
                    </p>
                  @enderror
                </div>
                {{-- Qeustion Type Content --}}
                <div class="mt-5">
                  @if ($questionType == 'numeric')
                    <label for="passing" class="block mb-2 text-sm font-extrabold tei-text-primary ">Enter Passing
                      Range</label>
                    <div class="flex">
                      <label for="passing" class="block text-sm font-extrabold tei-text-primary mr-3">From</label>
                      <input type="number" wire:model="numericFrom"
                        class="{{ $errors->has('numericFrom') ? 'border border-red-500  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-xs rounded-lg block w-24 p-1 mr-3" />

                      <label for="passing" class="block mb-2 text-sm font-extrabold tei-text-primary mr-3">To</label>
                      <input type="number" wire:model="numericTo"
                        class="{{ $errors->has('numericTo') ? 'border border-red-500  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-xs rounded-lg block w-24 p-1 mr-3" />

                    </div>
                    @error('numericFrom')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                    @error('numericTo')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  @elseif($questionType == 'numeric-percent')
                    <label for="passing" class="block mb-2 text-sm font-extrabold tei-text-primary ">Enter Passing
                      Range (100%)</label>
                    <div class="flex">
                      <label for="passing" class="block text-sm font-extrabold tei-text-primary mr-3">From</label>
                      <input type="number" wire:model="numericFrom"
                        class="{{ $errors->has('numericFrom') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-xs rounded-lg block w-24 p-1 mr-3" />
                      <label for="passing" class="block mb-2 text-sm font-extrabold tei-text-primary mr-3">To</label>
                      <input type="number" wire:model="numericTo"
                        class="{{ $errors->has('numericTo') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-xs rounded-lg block w-24 p-1 mr-3" />
                    </div>
                    @error('numericFrom')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                    @error('numericTo')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  @elseif($questionType == 'single-option')
                    <div>
                      <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                          <label for="passing" class="block mb-2 text-sm font-extrabold tei-text-primary mr-3">Enter
                            Option</label>
                          <input type="text" wire:model="option"
                            class="{{ $errors->has('option') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-orange-600 focus:border-orange-600 ' }} text-xs rounded-lg block w-full p-1 mr-3" />
                          @error('option')
                            <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                              {{ $message }}
                            </p>
                          @enderror
                        </div>
                        <div>
                          <label for="action"
                            class="block mb-2 text-sm font-extrabold tei-text-primary mr-3">Action</label>
                          <button type="button" wire:click.prevent="addOption"
                            class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-1.5 me-2 mb-2 hover:scale-110 transition-transform duration-300">
                            Add
                          </button>
                        </div>
                      </div>
                      <label for="action" class="block mb-2 text-sm font-extrabold tei-text-primary mr-3">Option
                        Maintenance</label>
                      <span class="font-extrabold text-xs italic text-yellow-500">Note: The maximum score for every
                        option should be 100.</span>
                      <div class="shadow-md sm:rounded-lg">
                        <div class="text-center py-4">
                          @error('options')
                            <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                              {{ $message }}
                            </p>
                          @enderror

                        </div>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                          <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
                            <th class="px-6 py-3">Option</th>
                            <th class="px-6 py-3">Weight Score</th>
                            <th class="px-6 py-3">Action</th>
                          </thead>
                          <tbody class="font-medium">
                            @forelse ($options as $key => $option)
                              <tr class="border-b " wire:key="option-{{ $key }}">
                                <td class="px-6 py-4">
                                  {{ $option['name'] }}
                                </td>
                                <td class="px-6 py-4">
                                  <div class="flex">
                                    <input wire:model="options.{{ $key }}.score" type="text"
                                      class="mr-4 w-24 rounded-md text-xs p-1 {{ $errors->has('options.' . $key . '.score') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-orange-600 focus:border-orange-600 ' }}">
                                    @error('options.' . $key . '.score')
                                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                                        {{ $message }}
                                      </p>
                                    @enderror
                                  </div>
                                </td>
                                <td class="px-6 py-4">
                                  <button data-tooltip-target="tooltip-remove-{{ $key }}" type="button"
                                    class="hover:scale-125 transition-transform duration-300"
                                    wire:click.prevent="removeOption({{ $key }})">
                                    <i class="fa-solid fa-trash text-red-600 text-lg"></i>
                                  </button>
                                  <div id="tooltip-remove-{{ $key }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-accent rounded-lg shadow-sm opacity-0 tooltip">
                                    remove option
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                  </div>
                                </td>
                              </tr>
                            @empty
                              <tr>
                                <td colspan="10" class="text-center p-2">
                                  <span>Please add options</span>
                                </td>
                              </tr>
                            @endforelse
                          </tbody>
                        </table>
                      </div>
                    </div>
                  @elseif($questionType == 'multi-option')
                    <div>
                      <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                          <label for="passing" class="block mb-2 text-sm font-extrabold tei-text-primary mr-3">Enter
                            Option</label>
                          <input type="text" wire:model="option"
                            class="{{ $errors->has('option') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-orange-600 focus:border-orange-600 ' }} text-xs rounded-lg block w-full p-1 mr-3" />
                          @error('option')
                            <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                              {{ $message }}
                            </p>
                          @enderror
                        </div>
                        <div>
                          <label for="action"
                            class="block mb-2 text-sm font-extrabold tei-text-primary mr-3">Action</label>
                          <button type="button" wire:click.prevent="addOption"
                            class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-1.5 me-2 mb-2 hover:scale-110 transition-transform duration-300">
                            Add
                          </button>
                        </div>
                      </div>
                      <label for="action" class="block mb-2 text-sm font-extrabold tei-text-primary mr-3">Option
                        Maintenance</label>
                      <span class="font-extrabold text-xs italic text-yellow-500">Note: The maximum score for every
                        option should be 100.</span>
                      <div class="shadow-md sm:rounded-lg">
                        <div class="text-center py-4">
                          @error('options')
                            <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                              {{ $message }}
                            </p>
                          @enderror

                        </div>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                          <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
                            <th class="px-6 py-3">Option</th>
                            <th class="px-6 py-3">Weight Score</th>
                            <th class="px-6 py-3">Action</th>
                          </thead>
                          <tbody class="font-medium">
                            @forelse ($options as $key => $option)
                              <tr class="border-b " wire:key="option-{{ $key }}">
                                <td class="px-6 py-4">
                                  {{ $option['name'] }}
                                </td>
                                <td class="px-6 py-4">
                                  <div class="flex">
                                    <input wire:model="options.{{ $key }}.score" type="text"
                                      class="mr-4 w-24 rounded-md text-xs p-1 {{ $errors->has('options.' . $key . '.score') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-orange-600 focus:border-orange-600 ' }}">
                                    @error('options.' . $key . '.score')
                                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                                        {{ $message }}
                                      </p>
                                    @enderror
                                  </div>
                                </td>
                                <td class="px-6 py-4">
                                  <button data-tooltip-target="tooltip-remove-{{ $key }}" type="button"
                                    class="hover:scale-125 transition-transform duration-300"
                                    wire:click.prevent="removeOption({{ $key }})">
                                    <i class="fa-solid fa-trash text-red-600 text-lg"></i>
                                  </button>
                                  {{-- <div id="tooltip-remove-{{ $key }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-accent rounded-lg shadow-sm opacity-0 tooltip">
                                    remove option
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                  </div> --}}
                                </td>
                              </tr>
                            @empty
                              <tr>
                                <td colspan="10" class="text-center p-2">
                                  <span>Please add options</span>
                                </td>
                              </tr>
                            @endforelse
                          </tbody>
                        </table>
                      </div>
                    </div>
                  @endif
                </div>
                {{-- End Qeustion Type Content --}}

              </div>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
              <button type="submit" wire:loading.remove wire:target="questionForm"
                class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
                Update
              </button>
              <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
                wire:target="questionForm">
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
              <button wire:click.prevent="closeAddModal" type="button"
                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
            </div>
          </form>
        @endif
      </div>
    </div>
  </div>
  {{-- End Question --}}


  {{-- Delete option Modal --}}
  {{-- <div id="delete-option-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          data-modal-hide="delete-option-modal">
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
          <h3 class="mb-5 text-lg font-normal tei-text-primary ">Update Products/Services?</h3>
          <button data-modal-hide="delete-option-modal" type="button"
            class="text-white bg-green-600 hover:bg-green-900 focus:ring-4 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
            wire:click= "syncClassess">
            Confirm
          </button>
          <button data-modal-hide="delete-option-modal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 focus:ring-4 focus:ring-gray-100">
            Cancel</button>
        </div>
      </div>
    </div>
  </div> --}}
  {{-- END Delete option Modal --}}
</div>
@script
  <script>
    $wire.on('closeAddModal', () => {
      var modalEditElement = document.getElementById('add-question-modal');
      var modalEdit = new Modal(modalEditElement);
      modalEdit.hide();
    });

    $wire.on('openAddModal', () => {
      var modalEditElement = document.getElementById('add-question-modal');
      var modalEdit = new Modal(modalEditElement, {
        backdrop: 'static'
      });
      modalEdit.show();
    });
  </script>
@endscript
