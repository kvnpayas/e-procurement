<div id="vendor-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
  class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <div class="relative p-4 w-full max-w-7xl max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow">
      <!-- Modal header -->
      <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
        <h3 class="text-xl font-extrabold tei-text-primary">
          Select Vendors
        </h3>
        <button type="button" wire:click="closeVendorModal"
          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <!-- Modal body -->

      <form wire:submit="addVendors">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
          <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
            <p class="mt-1 text-xs font-extrabold tei-text-secondary italic">Please carefully select vendors that will
              participate in this project bid.</p>
            <div class="flex justify-start pt-5 ">
              <div class="flex space-x-4">
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
              </div>
            </div>
            @if ($projectbid && $projectbid->financial)
              <div class="mt-2 w-1/2">
                <span class="tei-text-primary text-sm">Financial Class:</span>
                @foreach ($classes as $class)
                  <span class="text-xs tei-text-secondary"
                    data-tooltip-target="tooltip-class-info-{{ $class->id }}">{{ $class->code }},</span>
                  <div id="tooltip-class-info-{{ $class->id }}" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-accent rounded-lg shadow-sm opacity-0 tooltip">
                    {{ $class->description }}
                    <div class="tooltip-arrow" data-popper-arrow></div>
                  </div>
                @endforeach
              </div>
            @endif
            <div class="flex mt-2">
              <label class="inline-flex items-center cursor-pointer">
                <input type="checkbox" value="" class="sr-only peer" wire:model.live="showAll">
                <div
                  class="relative w-9 h-5 bg-gray-500 peer-focus:outline-none  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-700">
                </div>
                <span class="ms-1 text-xs tei-text-secondary  font-semibold">Show All Vendors</span>
              </label>
            </div>

          </caption>
          <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
            <tr>
              <th scope="col" class="px-6 py-3">
                Id
              </th>
              <th scope="col" class="px-6 py-3">
                Vendor Name
              </th>
              <th scope="col" class="px-6 py-3">
                Email
              </th>
              <th scope="col" class="px-6 py-3">
                Class
              </th>
              <th scope="col" class="px-6 py-3">
                Address
              </th>
              <th scope="col" class="px-6 py-3">
                NUMBER
              </th>
              <th scope="col" class="px-6 py-3">
                selected
              </th>
            </tr>
          </thead>
          <tbody wire:ignore.self>
            @forelse ($vendors as $vendor)
              <tr class="bg-white border-b text-xs" wire:key="vendor-{{ $vendor->id }}">
                <th scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap ">
                  {{ $vendor->id }}
                </th>
                <td class="px-4 py-2">
                  {{ $vendor->name }}
                </td>
                <td class="px-4 py-2">
                  {{ $vendor->email }}
                </td>
                <td class="px-4 py-2">
                  @foreach ($vendor->vendorClasses as $class)
                    <span class="text-xs {{ in_array($class->code, $classesArray) ? 'tei-text-secondary' : '' }}"
                      data-tooltip-target="tooltip-class-{{ $class->id }}">{{ $class->code }}</span>
                    <div id="tooltip-class-{{ $class->id }}" role="tooltip"
                      class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 tei-bg-accent rounded-lg shadow-sm opacity-0 tooltip">
                      {{ $class->description }}
                      <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                  @endforeach
                </td>
                <td class="px-4 py-2">
                  {{ $vendor->address }}
                </td>
                <td class="px-4 py-2">
                  {{ $vendor->number }}
                </td>
                <td class="px-4 py-2">
                  <div class="flex items-center me-4">
                    <input wire:model="checkVendors.{{ $vendor->id }}" value="{{ $vendor->id }}" type="checkbox"
                      class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                  </div>
                </td>
              </tr>
            @empty
              <tr class="bg-white border-b">
                <th scope="row" colspan="100"
                  class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
                  <span class="font-black text-lg tei-text-primary">No vendors on records.</span>
                </th>
              </tr>
            @endforelse
          </tbody>
        </table>

        {{ $vendors->links('livewire.layout.pagination') }}
        <!-- Modal footer -->
        <div class="flex justify-end p-4 md:p-5  rounded-b">
          <button type="submit" wire:loading.remove wire:target="addVendors"
            class="text-white bg-green-600 hover:bg-green-700 focus:ring-0 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
            ADD
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="addVendors">
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
      </form>

    </div>
  </div>
</div>
@script
  <script>
    $wire.on('openVendorModal', () => {
      var modalElement = document.getElementById('vendor-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeVendorModal', () => {
      var modalElement = document.getElementById('vendor-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
