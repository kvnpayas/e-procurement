<div class="overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Eligibility Maintenance
      <p class="mt-1 text-sm font-normal text-gray-500 ">List of all eligibilities.
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
              Create Eligibility
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
    <thead class="text-xs tei-text-primary uppercase tei-bg-light font-extrabold">
      <tr>
        <th scope="col" class="px-4 py-3">
          <div class="flex justify-between">
            <span>ID</span>
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
                <i class="fa-solid fa-sort-up {{ $orderBy == 'name' && $sort == '' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'name' && $sort == '' ? 'tei-text-secondary' : '' }}"
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
        <th scope="col" class="px-6 py-3 truncate">
          <div class="flex justify-between">
            <span class="mr-3">No. of fields</span>
            <button wire:click.prevent="selectedFilters('details_count')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'details_count' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'details_count' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span class="mr-3">Status</span>
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
      @forelse ($eligibilities as $eligibility)
        <tr class="bg-white border-b ">
          <th scope="row" class="px-6 font-medium tei-text-secondary whitespace-nowrap ">
            {{ $eligibility->id }}
          </th>
          <td class="px-6">
            {{ $eligibility->name }}
          </td>
          <td class="px-6">
            {{ $eligibility->description }}
          </td>
          <td class="px-6">
            {{ $eligibility->details->where('status', 'Active')->count() }}
          </td>
          <td class="px-6">
            <span class="{{ $eligibility->status == 'Active' ? 'text-green-500' : 'text-red-600' }}">
              {{ $eligibility->status }}
            </span>
          </td>
          <td class="px-6">
            <div class="flex gap-4">
              @if (roleAccessRights('update'))
                <div>
                  <button data-tooltip-target="tooltip-edit-{{ $eligibility->id }}" type="button"
                    class="hover:scale-125 transition-transform duration-300"
                    wire:click.prevent="editModal({{ $eligibility->id }})" wire:loading.remove
                    wire:target="editModal({{ $eligibility->id }})">
                    <i class="fa-solid fa-pen-to-square text-green-600 text-lg"></i>
                  </button>
                  <x-loading-spinner color="var(--secondary)" target="editModal({{ $eligibility->id }})" />
                  {{-- <div id="tooltip-edit-{{ $eligibility->id }}" role="tooltip"
                  class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-accent rounded-lg shadow-sm opacity-0 tooltip">
                  Edit eligibility
                  <div class="tooltip-arrow" data-popper-arrow></div>
                </div> --}}
                </div>
              @endif
              @if (roleAccessRights('view'))
                <div>
                  <a href="{{ route('eligibility-envelope.eligibility-details', $eligibility->id) }}"
                    data-tooltip-target="tooltip-add-{{ $eligibility->id }}">
                    <i
                      class="fa-solid fa-square-plus text-blue-600 text-lg hover:scale-125 transition-transform duration-300"></i>
                  </a>
                  {{-- <div id="tooltip-add-{{ $eligibility->id }}" role="tooltip"
                  class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-accent rounded-lg shadow-sm opacity-0 tooltip">
                  Add fields
                  <div class="tooltip-arrow" data-popper-arrow></div>
                </div> --}}
                </div>
              @endif
            </div>
          </td>
        </tr>
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">No eligibilities on records.</span>
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>
  {{-- <x-action-message class="me-3" on="alert-eligibility">
    {{ __($alertMessage) }}
    </x-action-message> --}}

  {{ $eligibilities->links('livewire.layout.pagination') }}

  {{-- Create Eligibility --}}
  <div id="create-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Create Eligibility
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
        <form wire:submit="createEligibility">
          <div class="p-4 md:p-5 space-y-4">
            <p class="mt-1 text-xs font-extrabold tei-text-secondary italic"> Please review all the input details
              before
              creating. Thank you </p>
            <div class="mb-6">
              <div>
                <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Eligibility
                  Name</label>
                <input type="text" id="name" wire:model="eligibilityName" placeholder="Eligibility Name"
                  class="{{ $errors->has('eligibilityName') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-sm rounded-lg block w-full p-1" />
                @error('eligibilityName')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div class="mt-5">
                <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Eligibility
                  Description</label>
                <textarea id="message" rows="4"
                  class="{{ $errors->has('eligibilityDescription') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                  placeholder="Eligibility Description" wire:model="eligibilityDescription"></textarea>
                @error('eligibilityDescription')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
            </div>
          </div>
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            <button type="submit" wire:loading.remove wire:target="createEligibility"
              class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
              Create
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="createEligibility">
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
  {{-- End Create Eligibility --}}

  {{-- Edit Eligibility --}}
  <div id="edit-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Edit Eligibility
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
        @if ($eligibility)
          <form wire:submit="editEligibility">
            <div class="p-4 md:p-5 space-y-4">
              <p class="mt-1 text-xs font-extrabold tei-text-secondary italic"> Please review all the input details
                before updating. Thank you </p>
              <div class="mb-6">
                <div>
                  <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Eligibility
                    Name</label>
                  <div wire:loading.remove wire:target="editModal({{ $eligibility->id }})">
                    <input type="text" id="name" wire:model="editName" placeholder="Eligibility Name"
                      class="{{ $errors->has('editName') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-sm rounded-lg block w-full p-1" />
                    @error('editName')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                  <div wire:loading wire:target="editModal({{ $eligibility->id }})" class="w-full">
                    <div role="status" class="animate-pulse">
                      <div class="h-2.5 bg-gray-500 rounded-full w-11/12 mb-1"></div>
                      <div class="h-2.5 bg-gray-500 rounded-full w-11/12 mb-1"></div>
                    </div>
                  </div>
                </div>
                <div class="mt-5">
                  <label for="description" class="block mb-2 text-sm font-extrabold tei-text-primary ">Eligibility
                    Description</label>
                  <div wire:loading.remove wire:target="editModal({{ $eligibility->id }})">
                    <textarea id="message" rows="4"
                      class="{{ $errors->has('editDescription') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                      placeholder="Eligibility Description" wire:model="editDescription"></textarea>
                    @error('editDescription')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                  <div wire:loading wire:target="editModal({{ $eligibility->id }})" class="w-full">
                    <div role="status" class="animate-pulse">
                      <div class="h-2.5 bg-gray-500 rounded-full w-11/12 mb-1"></div>
                      <div class="h-2.5 bg-gray-500 rounded-full w-11/12 mb-1"></div>
                      <div class="h-2.5 bg-gray-500 rounded-full w-11/12 mb-1"></div>
                      <div class="h-2.5 bg-gray-500 rounded-full w-11/12 mb-1"></div>
                    </div>
                  </div>
                </div>
                <div class="mt-5">
                  <div wire:loading.remove wire:target="editModal({{ $eligibility->id }})">
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
                  <div wire:loading wire:target="editModal({{ $eligibility->id }})" class="w-full">
                    <div role="status" class="animate-pulse">
                      <div class="h-2.5 bg-gray-500 rounded-full w-11/12 mb-1"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
              <button type="submit" wire:loading.remove wire:target="editEligibility"
                class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
                Update
              </button>
              <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
                wire:target="editEligibility">
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
  {{-- End Edit Eligibility --}}

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
  </script>
@endscript
