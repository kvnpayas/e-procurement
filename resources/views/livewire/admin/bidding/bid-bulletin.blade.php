<div>
  <div class="overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 boder-collapse">
      <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
        Bid Bulletin
        <p class="mt-1 text-sm font-normal text-gray-500 ">Here you will find all the latest updates and information
          regarding our ongoing bids and procurement processes.
        </p>
        <div class="py-4">
          <div>
            <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Bid Title</label>
            <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $bidding->title }}</span>
          </div>
          <div>
            <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Score Method</label>
            <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $bidding->score_method }} Based</span>
          </div>
          <div>
            <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Sales</label>
            <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $bidding->scrap ? 'Yes' : 'No' }}</span>
          </div>
        </div>
        <div class="flex pt-5 gap-4">
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
                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-orange-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Search
                here</label>
            </div>
          </div>
          <div class="ml-auto">
            <button wire:click="createBulletinForm"
              class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-2 hover:scale-110 transition-transform duration-300">
              Create Bulletin
            </button>
          </div>
        </div>
      </caption>
      <thead class="text-xs tei-text-primary uppercase tei-bg-light font-extrabold">
        <tr>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Title</span>
              {{-- <button wire:click.prevent="selectedFilters('{{ $index }}')">
                <div class="flex flex-col pt-0.5 text-gray-900">
                  <i class="fa-solid fa-sort-up {{ $orderBy == $index && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                    style="line-height: 0"></i>
                  <i class="fa-solid fa-sort-down {{ $orderBy == $index && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                    style="line-height: 0"></i>
                </div>
              </button> --}}
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Bulletin Details</span>
              {{-- <button wire:click.prevent="selectedFilters('{{ $index }}')">
                <div class="flex flex-col pt-0.5 text-gray-900">
                  <i class="fa-solid fa-sort-up {{ $orderBy == $index && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                    style="line-height: 0"></i>
                  <i class="fa-solid fa-sort-down {{ $orderBy == $index && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                    style="line-height: 0"></i>
                </div>
              </button> --}}
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900">
            <div class="flex justify-between">
              <span>Date</span>
              {{-- <button wire:click.prevent="selectedFilters('{{ $index }}')">
                <div class="flex flex-col pt-0.5 text-gray-900">
                  <i class="fa-solid fa-sort-up {{ $orderBy == $index && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                    style="line-height: 0"></i>
                  <i class="fa-solid fa-sort-down {{ $orderBy == $index && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                    style="line-height: 0"></i>
                </div>
              </button> --}}
            </div>
          </td>
          <td scope="col" class="px-4 py-3 border-gray-100 border-e text-gray-900 w-96">
            Action
          </td>
        </tr>
      </thead>
      <tbody>
        @forelse ($bulletins as $bulletin)
          <tr class="bg-white border-b font-extrabold hover:bg-neutral-200 hover:shadow-xl transition duration-300">
            <th scope="row" class="px-6 py-2 font-medium tei-text-secondary whitespace-nowrap ">
              {{ $bulletin->title }}
            </th>
            <td class="px-6 py-2 w-2/3 text-xs">
              {{-- {!! nl2br(e($bulletin->description)) !!} --}}
              @if ($showFullText[$bulletin->id])
                <div>
                  {!! nl2br(e($bulletin->description)) !!}
                </div>
                <div>
                  @if (strlen($bulletin->description) > 100)
                    <button class="text-xs uppercase font-extrabold tei-text-secondary"
                      wire:click="toggleText({{ $bulletin->id }})">Read
                      Less</button>
                  @endif
                </div>
              @else
                <div>
                  {!! nl2br(e(Str::limit($bulletin->description, 100))) !!}
                </div>
                <div>
                  @if (strlen($bulletin->description) > 100)
                    <button class="text-xs uppercase font-extrabold tei-text-secondary"
                      wire:click="toggleText({{ $bulletin->id }})">Read
                      More</button>
                  @endif
                </div>
              @endif
            </td>
            <td class="px-6 py-2">
              {{ date('F j,Y', strtotime($bulletin->created_at)) }}
            </td>
            <td class="px-6 py-2">
              <button wire:click.prevent="viewFile({{ $bulletin->id }})"
                class="hover:scale-110 transition-transform duration-300 mr-4 tei-bg-secondary rounded-md px-2 py-1 text-white text-xs">View
                File</button>
              <button wire:click.prevent="editModal({{ $bulletin->id }})"
                class="hover:scale-110 transition-transform duration-300 mr-4 tei-bg-primary rounded-md px-2 py-1 text-white text-xs">Edit</button>
              <button wire:click.prevent="deleteBulletinModal({{ $bulletin->id }})"
                class="hover:scale-110 transition-transform duration-300 mr-4 bg-red-500 rounded-md px-2 py-1 text-white text-xs">Delete</button>
            </td>

          </tr>
        @empty
          <tr class="bg-white border-b">
            <th scope="row" colspan="100" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap text-center">
              <span class="font-black text-lg tei-text-primary">No bulletin.</span>
            </th>
          </tr>
        @endforelse
      </tbody>
    </table>
    {{-- <x-action-message class="me-3" on="alert-eligibility">
      {{ __($alertMessage) }}
      </x-action-message> --}}

    {{ $bulletins->links('livewire.layout.pagination') }}

    {{-- Create Bulletin --}}
    <div id="create-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
      class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-full max-w-xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
          <!-- Modal header -->
          <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
            <h3 class="text-xl font-extrabold tei-text-primary">
              Create Bulletin
            </h3>
            <button type="button" wire:click.prevent="closeCreateModal" wire:loading.attr="disabled"
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
          <form wire:submit="createBulletin">
            <div class="p-4 md:p-5 space-y-4">
              <p class="mt-1 text-xs font-extrabold tei-text-secondary italic"> Please review all the input details
                before
                creating. Thank you </p>
              <div class="mb-6">
                <div>
                  <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Title</label>
                  <input type="text" id="name" wire:model="title" placeholder="Title"
                    class="tei-text-accent {{ $errors->has('title') ? 'border border-red-500 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-sm rounded-lg block w-full p-1" />
                  @error('title')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                      {{ $message }}
                    </p>
                  @enderror
                </div>
                <div class="mt-5">
                  <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">
                    Description</label>
                  <textarea id="message" rows="15"
                    class="tei-text-accent {{ $errors->has('description') ? ' border border-red-500 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                    placeholder="Description" wire:model="description"></textarea>
                  @error('description')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                      {{ $message }}
                    </p>
                  @enderror
                </div>
                <div class="mt-5">
                  <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">
                    File uploads</label>
                  <input
                    class="block w-full text-sm text-gray-900 border {{ $errors->has('file') ? 'border-red-600 ' : 'border-gray-300 ' }} rounded-e-lg cursor-pointer bg-gray-50 "
                    type="file" wire:model="file">
                  <span class="text-xs tei-text-secondary">{{ $file ? $file->getClientOriginalName() : '' }}</span>
                  @error('file')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                      {{ $message }}
                    </p>
                  @enderror
                </div>
              </div>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
              <button type="submit" wire:loading.remove
                class="text-white bg-green-600 hover:bg-green-700 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
                Create
              </button>
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
              <button type="button" wire:click.prevent="closeCreateModal"
                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    {{-- End Create Bulletin --}}

    {{-- Edit Bulletin --}}
    <div id="edit-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
      class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
      <div class="relative p-4 w-full max-w-xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
          <!-- Modal header -->
          <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
            <h3 class="text-xl font-extrabold tei-text-primary">
              Edit Bulletin
            </h3>
            <button type="button" wire:click.prevent="closeEditModal" wire:loading.attr="disabled"
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
          <form wire:submit="updateBulletin">
            <div class="p-4 md:p-5 space-y-4">
              <p class="mt-1 text-xs font-extrabold tei-text-secondary italic"> Please review all the input details
                before
                creating. Thank you </p>
              <div class="mb-6">
                <div>
                  <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">Title</label>
                  <input type="text" id="name" wire:model="titleEdit" placeholder="Title"
                    class="tei-text-accent {{ $errors->has('titleEdit') ? 'border border-red-500 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} text-sm rounded-lg block w-full p-1" />
                  @error('titleEdit')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                      {{ $message }}
                    </p>
                  @enderror
                </div>
                <div class="mt-5">
                  <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">
                    Description</label>
                  <textarea id="message" rows="15"
                    class="tei-text-accent {{ $errors->has('descriptionEdit') ? ' border border-red-500 focus:ring-red-500 focus:border-red-500' : 'bg-gray-50 border border-gray-300 text-gray-900 focus:ring-gray-500 focus:border-gray-500 ' }} block p-2.5 w-full text-sm rounded-lg border"
                    placeholder="Description" wire:model="descriptionEdit"></textarea>
                  @error('descriptionEdit')
                    <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                      {{ $message }}
                    </p>
                  @enderror
                </div>
                <div class="mt-5">
                  @if ($hasFile)
                    <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">
                      File uploads</label>
                    <span class="text-xs font-semibold uppercase tei-text-secondary">{{ $fileEdit }}</span>
                    <button wire:click.prevent="uploadFile"
                      class="block hover:scale-110 transition-transform duration-300 mr-4 bg-green-500 rounded-md px-2 py-1 text-white text-xs">Change
                      uploaded files</button>
                  @else
                    <label for="name" class="block mb-2 text-sm font-extrabold tei-text-primary ">
                      File uploads</label>
                    <input
                      class="block w-full text-sm text-gray-900 border {{ $errors->has('fileEdit') ? 'border-red-600 ' : 'border-gray-300 ' }} rounded-e-lg cursor-pointer bg-gray-50 "
                      id="file_input" type="file" wire:model="fileEdit">
                    <span
                      class="text-xs tei-text-secondary">{{ $fileEdit ? $fileEdit->getClientOriginalName() : '' }}</span>
                    @error('fileEdit')
                      <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                        {{ $message }}
                      </p>
                    @enderror
                  @endif
                </div>
              </div>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
              <button type="submit" wire:loading.remove
                class="text-white bg-green-600 hover:bg-green-700  font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
                Update
              </button>
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
              <button type="button" wire:click.prevent="closeEditModal"
                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 hover:scale-110 transition-transform duration-300">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    {{-- End Edit Bulletin --}}


    {{-- File Modal --}}
    <div id="view-file" tabindex="-1"
      class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
      wire:ignore.self>
      <div class="relative p-4 w-full max-w-7xl max-h-full">
        <div class="relative bg-white rounded-lg shadow">
          <button type="button"
            class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
            wire:click.prevent="closeFileModal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
          <div class="p-4 md:p-5 text-center ">
            <span
              class="uppercase tei-text-primary text-lg font-black mb-5">{{ $selectedFile ? $selectedFile->title : null }}</span>
            <hr>
            <div class=" flex justify-center mt-5">
              @if ($selectedFile)
                <iframe src="{{ $selectedFileAttach }}" frameborder="1" width="1000" height="850"></iframe>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- END File Modal --}}

    {{-- Delete Modal --}}
    <div id="delete-modal" tabindex="-1"
      class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
      wire:ignore.self>
      <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow">
          <button type="button" wire:click="closeDeleteModal"
            class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
          <div class="p-4 md:p-5 text-center" wire:loading.remove wire:target="rejectWinner">
            <svg class="mx-auto mb-4 tei-text-primary w-12 h-12" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h3 class="mb-5 text-lg font-normal tei-text-primary ">Are you sure you want to delete this bulletin
              <span
                class="font-semibold underline uppercase">{{ $deleteBulletin ? $deleteBulletin->title : '' }}</span>?
            </h3>
            <button type="button" wire:loading.remove wire:target="deleteSelectedBulletin"
              class="text-white bg-red-600 hover:bg-red-900 focus:outline-none  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
              wire:click= "deleteSelectedBulletin">
              Delete
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="deleteSelectedBulletin">
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
            <button wire:click="closeDeleteModal" type="button"
              class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-400 focus:z-10 ">
              Cancel</button>
          </div>

          {{-- <div class="bg-white w-full rounded-md px-32 py-14" wire:loading wire:target="rejectWinner">
            <div class="text-center">
              <span class="tei-text-primary font-extrabold">Please wait</span>
            </div>
            <div class="flex justify-center">
              <div class="loading loading-main">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
              </div>
            </div>
          </div> --}}
        </div>
      </div>
    </div>
    {{-- END Delete Modal --}}

    @livewire('admin.print-reports-modal')
  </div>

  @script
    <script>
      $wire.on('openCreateModal', () => {
        var modalElement = document.getElementById('create-modal');
        var modal = new Modal(modalElement, {
          backdrop: 'static'
        });
        modal.show();
      });

      $wire.on('closeCreateModal', () => {
        var modalElement = document.getElementById('create-modal');
        var modal = new Modal(modalElement);
        modal.hide();
      });

      $wire.on('openDeleteModal', () => {
        var modalElement = document.getElementById('delete-modal');
        var modal = new Modal(modalElement, {
          backdrop: 'static'
        });
        modal.show();
      });

      $wire.on('closeDeleteModal', () => {
        var modalElement = document.getElementById('delete-modal');
        var modal = new Modal(modalElement);
        modal.hide();
      });

      $wire.on('openEditModal', () => {
        var modalElement = document.getElementById('edit-modal');
        var modal = new Modal(modalElement, {
          backdrop: 'static'
        });
        modal.show();
      });

      $wire.on('closeEditModal', () => {
        var modalElement = document.getElementById('edit-modal');
        var modal = new Modal(modalElement);
        modal.hide();
      });

      $wire.on('openFileModal', () => {
        var modalElement = document.getElementById('view-file');
        var modal = new Modal(modalElement);
        modal.show();
      });

      $wire.on('closeFileModal', () => {
        var modalElement = document.getElementById('view-file');
        var modal = new Modal(modalElement);
        modal.hide();
      });
    </script>
  @endscript

</div>
