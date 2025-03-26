<div class="overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Technical Groups
      <p class="mt-1 text-sm font-normal text-gray-500 ">Assign technicals to group.
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
            <button type="button"  wire:loading.remove
              wire:target="createModal"
              class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-2 hover:scale-110 transition-transform duration-300"
              wire:click.prevent="createModal">
              Create Group
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
            <span>Group Name</span>
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
            <span>No. of technicals</span>
            <button wire:click.prevent="selectedFilters('technicals_count')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'technicals_count' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'technicals_count' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
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
      @forelse ($groups as $group)
        <tr class="bg-white border-b ">
          <th scope="row" class="px-6 font-medium text-gray-900 whitespace-nowrap ">
            {{ $group->name }}
          </th>
          <td class="px-6">
            {{ $group->description }}
          </td>
          <td class="px-6">
            {{ $group->technicals->count() }}
          </td>
          <td class="px-6">
            <div class="flex gap-4">
              @if (roleAccessRights('update'))
                <div>
                  <button data-tooltip-target="tooltip-edit-{{ $group->id }}" type="button"
                    class="hover:scale-125 transition-transform duration-300"
                    wire:click.prevent="editModal({{ $group->id }})" wire:loading.remove wire:target="editModal({{ $group->id }})">
                    <i class="fa-solid fa-pen-to-square text-green-600 text-lg"></i>
                  </button>
                  <x-loading-spinner color="var(--secondary)" target="editModal({{ $group->id }})" />
                  {{-- <div id="tooltip-edit-{{ $group->id }}" role="tooltip"
                  class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-accent rounded-lg shadow-sm opacity-0 tooltip">
                  Edit Technical
                  <div class="tooltip-arrow" data-popper-arrow></div>
                </div> --}}
                </div>
              @endif
              @if (roleAccessRights(['create', 'update']))
                <div>
                  <button
                    wire:click="$dispatch('technicalsModal', {id: {{ $group->id }}})"
                    data-tooltip-target="tooltip-add-{{ $group->id }}">
                    <i
                      class="fa-solid fa-square-plus text-blue-600 text-lg hover:scale-125 transition-transform duration-300"></i>
                  </button>
                  {{-- <div id="tooltip-add-{{ $group->id }}" role="tooltip"
                  class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-accent rounded-lg shadow-sm opacity-0 tooltip">
                  Add/Remove Technical
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
            <span class="font-black text-lg tei-text-primary">No groups on records.</span>
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>
  {{-- <x-action-message class="me-3" on="alert-eligibility">
    {{ __($alertMessage) }}
    </x-action-message> --}}

  {{ $groups->links('livewire.layout.pagination') }}

  {{-- Create Technicals --}}
  <div id="create-group-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Create group for technicals
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
        <form wire:submit="createGroup">
          <div class="p-4 md:p-5 space-y-4">
            <p class="mt-1 text-xs font-extrabold tei-text-secondary italic"> Please review all the input details
              before
              creating. Thank you </p>
            <div class="mb-6">
              <div>
                <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Group
                  Name</label>
                <input type="text" id="name" wire:model="groupName" placeholder="Group Name"
                  class="{{ $errors->has('groupName') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-sm rounded-lg block w-full p-1" />
                @error('groupName')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
              <div class="mt-5">
                <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Group
                  Description</label>
                <textarea id="message" rows="4"
                  class="{{ $errors->has('groupDescription') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                  placeholder="Description" wire:model="groupDescription"></textarea>
                @error('groupDescription')
                  <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                    {{ $message }}
                  </p>
                @enderror
              </div>
            </div>
          </div>
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            <button type="submit" wire:loading.remove wire:target="createGroup"
              class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
              Create
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="createGroup">
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
  {{-- End Create Technicals --}}

  {{-- Edit Financials --}}
  <div id="edit-group-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-extrabold tei-text-primary">
            Edit Group
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
        @if ($group)
          <form wire:submit="editGroup">
            <div class="p-4 md:p-5 space-y-4">
              <p class="mt-1 text-xs font-extrabold tei-text-secondary italic"> Please review all the input details
                before updating. Thank you </p>
              <div class="mb-6">
                <div>
                  <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Group
                    Name</label>
                  <div wire:loading.remove wire:target="editModal({{ $group->id }})">
                    <input type="text" id="name" wire:model="groupEditName" placeholder="Group Name"
                      class="{{ $errors->has('groupEditName') ? 'border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-sm rounded-lg block w-full p-1" />
                    @error('groupEditName')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                  <div wire:loading wire:target="editModal({{ $group->id }})" class="w-full">
                    <div role="status" class="animate-pulse">
                      <div class="h-2.5 bg-gray-500 rounded-full w-11/12 mb-1"></div>
                      <div class="h-2.5 bg-gray-500 rounded-full w-11/12 mb-1"></div>
                    </div>
                  </div>
                </div>
                <div class="mt-5">
                  <label for="description"
                    class="block mb-2 text-sm font-extrabold tei-text-primary ">Description</label>
                  <div wire:loading.remove wire:target="editModal({{ $group->id }})">
                    <textarea id="message" rows="4"
                      class="{{ $errors->has('groupEditDescription') ? ' border border-red-500 text-red-900  focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                      placeholder="Financial Description" wire:model="groupEditDescription"></textarea>
                    @error('groupEditDescription')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                  <div wire:loading wire:target="editModal({{ $group->id }})" class="w-full">
                    <div role="status" class="animate-pulse">
                      <div class="h-2.5 bg-gray-500 rounded-full w-11/12 mb-1"></div>
                      <div class="h-2.5 bg-gray-500 rounded-full w-11/12 mb-1"></div>
                      <div class="h-2.5 bg-gray-500 rounded-full w-11/12 mb-1"></div>
                      <div class="h-2.5 bg-gray-500 rounded-full w-11/12 mb-1"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
              <button type="submit" wire:loading.remove wire:target="editGroup"
                class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
                Update
              </button>
              <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
                wire:target="editGroup">
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
  {{-- End Edit Financials --}}

  {{-- Tecnical Lists --}}
  @livewire('envelope-maintenance.technical-lists')
  {{-- End Technical Lists --}}
</div>
@script
  <script>
    $wire.on('closeModal', () => {
      var modalElement = document.getElementById('create-group-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });

    $wire.on('closeEditModal', () => {
      var modalEditElement = document.getElementById('edit-group-modal');
      var modalEdit = new Modal(modalEditElement);
      modalEdit.hide();
    });

    $wire.on('openEditModal', () => {
      var modalEditElement = document.getElementById('edit-group-modal');
      var modalEdit = new Modal(modalEditElement);
      modalEdit.show();
    });
    $wire.on('openModal', () => {
      var modalElement = document.getElementById('create-group-modal');
      var modal = new Modal(modalElement);
      modal.show();
    });
  </script>
@endscript
