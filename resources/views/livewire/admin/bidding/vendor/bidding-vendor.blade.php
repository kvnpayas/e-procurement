<div class="overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500 boder-collapse">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Vendor Lists
      <p class="mt-1 text-sm font-normal text-gray-500 ">List of vendors that will be invited to this project bid.
      </p>
      <div class="mt-5">
        <div>
          <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Project No:</label>
          <span class="text-xs font-extrabold tei-text-accent">{{ $projectbid->project_id }}</span>
        </div>
        <div>
          <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Project Title:</label>
          <span class="text-xs font-extrabold tei-text-accent">{{ $projectbid->title }}</span>
        </div>
        <div>
          <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Deadline Date:</label>
          <span
            class="text-xs font-extrabold tei-text-accent">{{ date('F j,Y @ h:i A', strtotime($projectbid->deadline_date)) }}</span>
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
          @if ($projectbid->status == 'Active' || $projectbid->status == 'On Hold')
            <button wire:click="openVendorModalLists({{ $projectbid->id }})" wire:loading.remove
              wire:target="openVendorModalLists({{ $projectbid->id }})"
              class="text-white tei-bg-primary hover:bg-sky-900 font-medium rounded-lg text-xs px-5 py-2 me-2 hover:scale-110 transition-transform duration-300">
              Add Vendor
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="openVendorModalLists({{ $projectbid->id }})">
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
            <span>Vendor Name</span>
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
            <span>Email</span>
            <button wire:click.prevent="selectedFilters('email')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'email' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'email' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Address</span>
            <button wire:click.prevent="selectedFilters('address')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'address' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'address' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          <div class="flex justify-between">
            <span>Number</span>
            <button wire:click.prevent="selectedFilters('number')">
              <div class="flex flex-col pt-0.5 text-gray-900">
                <i class="fa-solid fa-sort-up {{ $orderBy == 'number' && $sort == 'desc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
                <i class="fa-solid fa-sort-down {{ $orderBy == 'number' && $sort == 'asc' ? 'tei-text-secondary' : '' }}"
                  style="line-height: 0"></i>
              </div>
            </button>
          </div>
        </th>
        <th scope="col" class="px-6 py-3">
          Status
        </th>
      </tr>
    </thead>
    <tbody>
      @forelse ($vendors as $vendor)
        <tr class="bg-white border-b font-extrabold">
          <th scope="row" class="px-6 py-4 font-medium tei-text-secondary whitespace-nowrap ">
            {{ $vendor->id }}
          </th>
          <td class="px-6 py-4">
            {{ $vendor->name }}
          </td>
          <td class="px-6 py-4">
            {{ $vendor->email }}
          </td>
          <td class="px-6 py-4">
            {{ $vendor->address }}
          </td>
          <td class="px-6 py-4">
            {{ $vendor->number }}
          </td>
          <td class="px-6 py-4">
            @if (in_array($vendor->pivot->status, ['Joined', 'Under Evaluation']))
              <div class="flex justify-between">
                <span class="uppercase text-green-500">{{ $vendor->pivot->status }}</span>
                @if ($projectbid->bidVendorStatus->where('vendor_id', $vendor->id)->first()->complete)
                  <i class="fa-solid fa-square-check text-green-500"
                    data-tooltip-target="tooltip-complete-{{ $vendor->id }}"></i>
                  <div id="tooltip-complete-{{ $vendor->id }}" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-secondary rounded-lg shadow-sm opacity-0 tooltip">
                    Tag as complete by vendor
                    <div class="tooltip-arrow" data-popper-arrow></div>
                  </div>
                @endif
              </div>
            @elseif (in_array($vendor->pivot->status, ['Declined', 'Bid Failure']))
              <span class="uppercase text-red-500">{{ $vendor->pivot->status }}</span>
            @else
              <span class="uppercase text-green-500">{{ $vendor->pivot->status }}</span>
            @endif
          </td>
        </tr>
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">There is no vendor on this bid.</span>
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>
  {{-- <x-action-message class="me-3" on="alert-eligibility">
    {{ __($alertMessage) }}
    </x-action-message> --}}

  {{ $vendors->links('livewire.layout.pagination') }}
  @livewire('admin.bidding.vendor.vendor-lists', ['id' => $projectbid->id])

</div>

{{-- @section('content-script')
  <script>
    // Maintenance
    document.addEventListener("click", (e) => {
      var buttonId = e.target.id;
      var id = buttonId.split('-')[2]

      var actionMenu = document.getElementById("action-" + id);
      if (actionMenu.classList.contains('bidding-hide')) {
        actionMenu.classList.add('bidding-show')
        actionMenu.classList.remove('bidding-hide')
      } else {
        actionMenu.classList.remove('bidding-show')
        actionMenu.classList.add('bidding-hide')
      }
    })
    // end Maintenance
  </script>
@endsection --}}
