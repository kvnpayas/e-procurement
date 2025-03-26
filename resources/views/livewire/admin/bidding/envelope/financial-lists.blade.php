<div id="financials-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
  class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <div class="relative p-4 w-full max-w-5xl max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow">
      <!-- Modal header -->
      <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
        <h3 class="text-xl font-extrabold tei-text-primary">
          Add Financials
        </h3>
        <button type="button" wire:click="closeFinancialModal" wire:loading.attr="disabled" wire:target="addFinancials"
          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <!-- Modal body -->

      <form wire:submit="addFinancials">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
          <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
            {{-- {{ $group->name }} --}}
            <p class="mt-1 text-sm font-normal text-gray-500 ">
              {{-- {{ $group->description }} --}}
            </p>
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
                <div class="flex">
                  <span
                    class="inline-flex items-center px-3 text-sm tei-text-light tei-bg-primary border rounded-e-0 border-gray-300 border-e-0 rounded-s-md ">
                    Groups
                  </span>
                  <select wire:model.live.debounce.500ms="selectedGroup"
                    class="bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-e-lg border-s-gray-100 border-s-2 tei-focus-secondary focus:ring-0 block w-full py-1.5">
                    <option value=''>--Select Groups--</option>
                    @foreach ($groups as $group)
                      <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="text-center mt-4">
              @error('grandTotal')
                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                  {{ $message }}
                </p>
              @enderror
            </div>
          </caption>
          <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
            <tr>
              <th scope="col" class="px-6 py-3">
                Invetory Id
              </th>
              <th scope="col" class="px-6 py-3">
                Description
              </th>
              <th scope="col" class="px-6 py-3">
                class Id
              </th>
              <th scope="col" class="px-6 py-3">
                uom
              </th>
              <th scope="col" class="px-6 py-3">
                unit cost
              </th>
              <th scope="col" class="px-6 py-3">
                selected
              </th>
            </tr>
          </thead>
          <tbody wire:ignore.self>
            @forelse ($financials as $financial)
              <tr class="bg-white border-b " wire:key="financial-{{ $financial->id }}">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                  {{ $financial->inventory_id }}
                </th>
                <td class="px-6 py-4">
                  {{ $financial->description }}
                </td>
                <td class="px-6 py-4">
                  {{ $financial->class_id }}
                </td>
                <td class="px-6 py-4">
                  {{ $financial->uom }}
                </td>
                <td class="px-6 py-4">
                  PHP {{ $financial->unit_cost }}
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center me-4">
                    <input wire:model.live="checkFinancials.{{ $financial->id }}" value="{{ $financial->id }}"
                      type="checkbox"
                      class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                  </div>
                </td>
              </tr>
            @empty
              <tr class="bg-white border-b">
                <th scope="row" colspan="100"
                  class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
                  <span class="font-black text-lg tei-text-primary">No financials on records.</span>
                </th>
              </tr>
            @endforelse
          </tbody>
        </table>
        <div>
          {{ $financials->links('livewire.layout.pagination') }}
        </div>
        <!-- Modal footer -->
        <div class="flex justify-end p-4 md:p-5  rounded-b">
          <button type="submit" wire:loading.remove wire:target="addFinancials"
            class="text-white bg-green-600 hover:bg-green-700 focus:ring-0 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
            ADD
          </button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading wire:target="addFinancials">
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
      </form>
    </div>
  </div>
</div>
@script
  <script>
    $wire.on('openFinancialModal', () => {
      var modalElement = document.getElementById('financials-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeFinancialModal', () => {
      var modalElement = document.getElementById('financials-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
