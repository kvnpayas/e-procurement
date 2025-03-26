<div id="eligibilities-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
  class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <div class="relative p-4 w-full max-w-3xl max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow overflow-x-auto">
      <!-- Modal header -->
      <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
        <h3 class="text-xl font-extrabold tei-text-primary">
          Add Eligibilities
        </h3>
        <button type="button" wire:click.prevent="closeAddModal"
          class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
      </div>
      <!-- Modal body -->
      @if ($group)
        <form wire:submit="addEligibilities">
          <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
              {{ $group->name }}
              <p class="mt-1 text-sm font-normal text-gray-500 ">{{ $group->description }}
              </p>
              <div class="flex justify-start pt-5">
                <div class="flex">
                  <div class="flex">
                    <span
                      class="inline-flex items-center px-3 text-sm tei-text-light tei-bg-primary border rounded-e-0 border-gray-300 border-e-0 rounded-s-md ">
                      Search
                    </span>
                    <input type="text" wire:model.live.debounce.500ms="search"
                      class="rounded-none rounded-e-lg bg-gray-50 border focus:outline-none text-grays-900 block flex-1 min-w-0 w-full text-xs border-gray-300 p-2 "
                      placeholder="search here">
                  </div>
                  <div class="mt-1.5 pl-2">
                    <label class="inline-flex items-center cursor-pointer">
                      <input type="checkbox" value="" class="sr-only peer" wire:model.live="showSelected">
                      <div
                        class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all ">
                      </div>
                      <span class="ms-3 text-sm font-medium">Show Current Eligibilities</span>
                    </label>
                  </div>
                </div>
              </div>

            </caption>
            <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
              <tr>
                <th scope="col" class="px-6 py-3">
                  ID
                </th>
                <th scope="col" class="px-6 py-3">
                  Name
                </th>
                <th scope="col" class="px-6 py-3">
                  Description
                </th>
                <th scope="col" class="px-6 py-3">
                  Selected
                </th>
              </tr>
            </thead>
            <tbody>
              @forelse ($eligibilities as $eligibility)
                <tr class="bg-white border-b" wire:key="eligibility-{{ $eligibility->id }}">
                  <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                    {{ $eligibility->id }}
                  </th>
                  <td class="px-6 py-4">
                    {{ $eligibility->name }}
                  </td>
                  <td class="px-6 py-4">
                    {{ $eligibility->description }}
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center me-4">
                      <input wire:model="checkEligibilities.{{ $eligibility->id }}" value="{{ $eligibility->id }}"
                        name="eligibilities[]" type="checkbox"
                        class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                    </div>
                  </td>
                </tr>
              @empty
                <tr class="bg-white border-b">
                  <th scope="row" colspan="100"
                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
                    <span class="font-black text-lg tei-text-primary">No eligibilities on records.</span>
                  </th>
                </tr>
              @endforelse
            </tbody>
          </table>

          {{ $eligibilities->links('livewire.layout.pagination') }}
          <!-- Modal footer -->
          <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
            <button type="submit" wire:loading.remove
              wire:target="addEligibilities"
              class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
              ADD
            </button>
            <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
              wire:target="addEligibilities">
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
      @endif
    </div>
  </div>
</div>
@script
  <script>
    $wire.on('openAddModal', () => {
      var modalEditElement = document.getElementById('eligibilities-modal');
      var modalEdit = new Modal(modalEditElement, {
        backdrop: 'static'
      });
      modalEdit.show();
    });
    $wire.on('closeAddModal', () => {
      var modalEditElement = document.getElementById('eligibilities-modal');
      var modalEdit = new Modal(modalEditElement);
      modalEdit.hide();
    });
  </script>
@endscript
