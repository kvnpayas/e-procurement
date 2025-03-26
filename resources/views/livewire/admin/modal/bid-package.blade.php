  {{-- Bid Package Modal --}}
  <div id="bid-package-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full"
    wire:ignore.self>
    <div class="relative p-4 w-full max-w-2xl max-h-full">
      <div class="relative bg-white rounded-lg shadow">
        <div class="p-4">
          <button type="button"
            class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
            wire:click="closePackageModal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
          <div>
            <h3 class="text-xl tei-text-secondary font-semibold">Download Bid Package</h3>
            <p class="mt-1 text-sm font-normal text-gray-500 ">Download a complete package containing all reports and
              vendor documents.
            </p>
          </div>
        </div>
        <hr>
        <div class="px-4 md:p-5 ">
          <div class="flex justify-around py-4">
            <div class="text-center">
              <span class="block text-xs uppercase font-semibold tei-text-accent mb-2">Click the button to generate
                Zip file</span>
              <button wire:click="downloadZip" wire:loading.remove wire:target="downloadZip"
                class="text-xs bg-green-500 hover:bg-green-600 uppercase font-semibold text-white py-1.5 px-4 rounded-md shadow-lg hover:scale-110 transition-transform duration-300"><i
                  class="fa-solid fa-file-excel"></i> Generate Zip File</button>
              <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
                wire:target="downloadZip">
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
        </div>
        <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b space-x-4">
          <button type="submit" wire:click="closePackageModal"
            class="text-white bg-gray-400 hover:bg-gray-500 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:scale-110 transition-transform duration-300">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
  {{-- END Bid Package Modal --}}
  @script
    <script>
      $wire.on('openPackageModal', () => {
        var modalElementOpen = document.getElementById('bid-package-modal');
        var modalOpen = new Modal(modalElementOpen, {
          backdrop: 'static'
        });
        modalOpen.show();
      });

      $wire.on('closePackageModal', () => {
        var modalElement = document.getElementById('bid-package-modal');
        var modal = new Modal(modalElement);
        modal.hide();
      });
    </script>
  @endscript
