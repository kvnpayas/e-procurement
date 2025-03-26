<div class="overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Technical Maintenance
      <p class="mt-1 text-sm font-normal text-gray-500 ">List of all Technicals/Question.
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
            <button type="button" wire:loading.remove wire:target="createModal"
              class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-2 hover:scale-110 transition-transform duration-300"
              wire:click.prevent="createModal">
              Create Technical
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="createModal">
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
        </div>
      </div>
    </caption>
    <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
      <tr>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Id</span>
            <button wire:click.prevent="selectedFilters('id')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'id' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'id' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Name</span>
            <button wire:click.prevent="selectedFilters('name')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'name' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'name' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
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
            <span>Status</span>
            <button wire:click.prevent="selectedFilters('status')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'status' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'status' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          Action
        </th>
      </tr>
    </thead>
    <tbody>
      <form wire:submit.prevent="saveForm">
        @forelse ($technicals as $technical)
          <tr class="bg-white border-b ">
            <td scope="row" class="px-6 tei-text-secondary whitespace-nowrap ">
              {{ $technical->id }}
            </td>
            <td scope="row" class="px-6 whitespace-nowrap ">
              {{ $technical->name }}
            </td>
            <td class="px-6">
              {{ $technical->description }}
            </td>
            <td class="px-6">
              <span class="{{ $technical->status == 'Active' ? 'text-green-500' : 'text-red-600' }}">
                {{ $technical->status }}
              </span>
            </td>
            <td class="px-6">
              <div class="flex gap-4">
                <div>
                  @if (roleAccessRights('update'))
                    <button data-tooltip-target="tooltip-edit-{{ $technical->id }}" type="button"
                      class="hover:scale-125 transition-transform duration-300"
                      wire:click.prevent="editModal({{ $technical->id }})" wire:loading.remove
                      wire:target="editModal({{ $technical->id }})">
                      <i class="fa-solid fa-pen-to-square text-green-600 text-lg"></i>
                    </button>
                    <x-loading-spinner color="var(--secondary)" target="editModal({{ $technical->id }})" />
                  @endif
                  {{-- <div id="tooltip-edit-{{ $technical->id }}" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 text-sm font-medium text-white transition-opacity duration-300 tei-bg-accent rounded-lg shadow-sm opacity-0 tooltip">
                    Edit technical
                    <div class="tooltip-arrow" data-popper-arrow></div>
                  </div> --}}
                </div>
                <span id="icon.{{ $technical->id }}"
                  class="cursor-pointer mt-1 hover:scale-110 transition-transform duration-300">
                  <i class="fa-solid fa-circle-plus text-green-600"></i> show
                  more
                </span>
              </div>
            </td>
          </tr>
          <tr id="tehcnialDetails.{{ $technical->id }}" class="bidding-details details-hide shadow-inner tei-bg-main"
            wire:ignore.self>
            <td colspan="100">
              <div class="displayNone more-details px-5" wire:ignore.self>
                @if (!$technical->question)
                  <div class="pl-3">
                    @if (roleAccessRights('create'))
                      <button data-tooltip-target="tooltip-add-{{ $technical->id }}" type="button" wire:loading.remove
                        wire:target="questionModal"
                        class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-2 me-2 mb-2 hover:scale-110 transition-transform duration-300"
                        wire:click.prevent="questionModal({{ $technical->id }})">
                        <i class="fa-solid fa-clipboard-question text-white text-xs"></i> Create Question
                      </button>
                      <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
                        wire:target="questionModal">
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
                      <div id="tooltip-add-{{ $technical->id }}" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-accent rounded-lg shadow-sm opacity-0 tooltip">
                        Add technical question
                        <div class="tooltip-arrow" data-popper-arrow></div>
                      </div>
                    @endif
                  </div>
                @endif

                @if ($technical->question)
                  <div class="grid grid-cols-5 gap-5 px-3 pt-3">
                    <div>
                      <label for="question"
                        class="block mb-2 text-sm font-extrabold tei-text-primary ">Question</label>
                      <span>{{ $technical->question }}</span>
                    </div>
                    <div>
                      <label for="question" class="block mb-2 text-sm font-extrabold tei-text-primary ">Question
                        Type</label>
                      <span>{{ ucfirst($technical->question_type) }}</span>
                    </div>
                    <div>
                      <label for="question"
                        class="block mb-2 text-sm font-extrabold tei-text-primary ">Remarks</label>
                      <span>{{ $technical->remarks }}</span>
                    </div>
                    <div>
                      <label for="question"
                        class="block mb-2 text-sm font-extrabold tei-text-primary ">Attachment</label>
                      {{-- <span>{{ $technical->attachment ? $technical->attachment : 'N/A' }}</span> --}}
                      @if ($technical->attachment)
                        <div>

                          <button data-tooltip-target="question-edit-{{ $technical->id }}" type="button"
                            class="hover:scale-110 transition-transform duration-300" data-modal-target="view-file"
                            data-modal-toggle="view-file" wire:click.prevent="viewFileModal({{ $technical->id }})">
                            <span>{{ $technical->attachment }}</span>
                            <i class="fa-solid fa-file-pdf tei-text-primary pl-2 text-lg"></i>
                          </button>
                        </div>
                      @endif
                    </div>
                    <div>
                      <label for="question" class="block mb-2 text-sm font-extrabold tei-text-primary ">Edit
                        Question</label>
                      @if (roleAccessRights('update'))
                        <div>
                          <button data-tooltip-target="question-edit-{{ $technical->id }}" type="button"
                            wire:loading.remove wire:target="questionEditModal({{ $technical->id }})"
                            class="hover:scale-125 transition-transform duration-300"
                            wire:click.prevent="questionEditModal({{ $technical->id }})">
                            <i class="fa-solid fa-pen-to-square text-green-600 text-lg"></i>
                          </button>
                          <x-loading-spinner color="var(--secondary)" target="questionEditModal({{ $technical->id }})" />
                          <div id="question-edit-{{ $technical->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-accent rounded-lg shadow-sm opacity-0 tooltip">
                            Edit question
                            <div class="tooltip-arrow" data-popper-arrow></div>
                          </div>
                        </div>
                      @endif
                    </div>
                  </div>
                @else
                  <div class="text-center p-3">
                    <span>No question yet. Please add or create a question.</span>
                  </div>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr class="bg-white border-b">
            <th scope="row" colspan="100"
              class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
              <span class="font-black text-lg tei-text-primary">No technicals on records.</span>
            </th>
          </tr>
        @endforelse
      </form>
    </tbody>
  </table>

  {{-- <x-action-message class="me-3" on="update-message">
    {{ __($alertMessage) }}
  </x-action-message> --}}
  {{ $technicals->links('livewire.layout.pagination') }}

  {{-- Create Technical --}}
  <div id="create-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Create Technical
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
        <form wire:submit="createTechnical">
          <div class="p-4 md:p-5 space-y-4">
            <p class="mt-1 text-xs font-extrabold tei-text-secondary italic"> Please review all the input details
              before
              creating. Thank you </p>
            <div class="mb-6">
              <div>
                <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Technical
                  Name</label>
                <input type="text" id="name" wire:model="technicalName" placeholder="Technical Name"
                  class="{{ $errors->has('technicalName') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-sm rounded-lg block w-full p-1" />
                @error('technicalName')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div class="mt-5">
                <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Technical
                  Description</label>
                <textarea id="message" rows="4"
                  class="{{ $errors->has('technicalDescription') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                  placeholder="Technical Description" wire:model="technicalDescription"></textarea>
                @error('technicalDescription')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
            </div>
          </div>
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            <button type="submit" wire:loading.remove wire:target="createTechnical"
              class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
              Create
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="createTechnical">
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
            <button type="button" wire:click.prevent="createModalClose"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- End Create Technical --}}

  {{-- Edit Technical --}}
  <div id="edit-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Edit Technical
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            wire:click.prevent="editModalClose">
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
          <form wire:submit="editTechnical">
            <div class="p-4 md:p-5 space-y-4">
              <p class="mt-1 text-xs font-extrabold tei-text-secondary italic"> Please review all the input details
                before updating. Thank you </p>
              <div class="mb-6">
                <div>
                  <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Technical
                    Name</label>
                  <div>
                    <input type="text" id="name" wire:model="editName" placeholder="Technical Name"
                      class="{{ $errors->has('editName') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-sm rounded-lg block w-full p-1" />
                    @error('editName')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                </div>
                <div class="mt-5">
                  <label for="description" class="block mb-2 text-sm font-extrabold tei-text-primary ">Technical
                    Description</label>
                  <div>
                    <textarea id="message" rows="4"
                      class="{{ $errors->has('editDescription') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                      placeholder="Technical Description" wire:model="editDescription"></textarea>
                    @error('editDescription')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                </div>
                <div class="mt-5">
                  <div>
                    <label class="inline-flex items-center cursor-pointer">
                      <input type="checkbox" value="" class="sr-only peer" wire:model.live="editStatus">
                      <div
                        class="relative w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 focus:outline-none peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600">
                      </div>
                      <span
                        class="ms-3 text-sm font-medium {{ $editStatus ? 'text-green-500' : 'text-red-500' }}">{{ $switchStatus }}</span>
                    </label>
                  </div>
                  @error('editStatus')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                      {{ $message }}
                    </p>
                  @enderror
                  <p class="mt-2 text-xs text-yellow-500">
                    Warning! Changing its status to inactive will remove it from active bidding requirements.
                  </p>
                </div>
              </div>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
              <button type="submit" wire:loading.remove wire:target="editTechnical"
                class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
                Update
              </button>
              <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
                wire:target="editTechnical">
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
              <button wire:click.prevent="editModalClose" type="button"
                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
            </div>
          </form>
        @endif
      </div>
    </div>
  </div>
  {{-- End Edit Technical --}}

  {{-- Add/Edit Question --}}
  @livewire('envelope-maintenance.technical-question')
  {{-- End Question --}}

  {{-- File Modal --}}
  <div id="view-file" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-7xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <button type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          data-modal-hide="view-file">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center">
          <div class=" flex justify-center">
            <iframe src="{{ asset('storage/envelope_maintenance/technical/' . $viewAttachment) }}" frameborder="1"
              width="1000" height="850"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
  {{-- END File Modal --}}
</div>
@script
  <script>
    $wire.on('closeModal', () => {
      var modalElement = document.getElementById('create-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('closeEditModal', () => {
      var modalEditElement = document.getElementById('edit-modal');
      var modalEdit = new Modal(modalEditElement);
      modalEdit.hide();
    });
    $wire.on('closeEditQuestionModal', () => {
      var modalEditElement = document.getElementById('edit-question-modal');
      var modalEdit = new Modal(modalEditElement);
      modalEdit.hide();
    });

    $wire.on('openEditModal', () => {
      var modalEditElement = document.getElementById('edit-modal');
      var modalEdit = new Modal(modalEditElement, {
        backdrop: 'static'
      });
      modalEdit.show();
    });
    $wire.on('openModal', () => {
      var modalElement = document.getElementById('create-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('openEditQuestionModal', () => {
      var modalElement = document.getElementById('edit-question-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
  </script>
@endscript

@section('content-script')
  <script>
    document.addEventListener("click", (e) => {
      const elementId = e.target.id;
      const id = elementId.split('.')[1]
      const detailId = document.getElementById('tehcnialDetails.' + id)
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
@endsection
