<x-app-layout>
  <div class="max-w-full mx-auto sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-lg rounded-md">

      <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" role="tablist">
          <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-b-2 rounded-tl-lg" id="financial-tab" type="button" role="tab"
              aria-controls="financial" aria-selected="false">Financial Maintenance</button>
          </li>
          <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-b-2 hover:text-gray-600 hover:border-gray-300" id="group-tab"
              type="button" role="tab" aria-controls="group" aria-selected="false">Group Financials</button>
          </li>
      </div>
      <div id="default-tab-content">
        <div class="hidden rounded-lg" id="financial" role="tabpanel" aria-labelledby="financial-tab">

          <livewire:envelope-maintenance.financial-maintenance />

        </div>
        <div class="hidden rounded-lg bg-gray-50" id="group" role="tabpanel" aria-labelledby="group-tab">

          <livewire:envelope-maintenance.group-financial />

        </div>
      </div>


    </div>
  </div>

  @include('components.toast-message')

  @section('envelope-script')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const tabElements = [{
            id: 'financial',
            triggerEl: document.querySelector('#financial-tab'),
            targetEl: document.querySelector('#financial'),
          },
          {
            id: 'group',
            triggerEl: document.querySelector('#group-tab'),
            targetEl: document.querySelector('#group'),
          },
        ];


        var tabElement = document.getElementById('default-tab');
        var activeTab = "{{ session('activeTab') }}";
        console.log(activeTab);
        if (activeTab == 'financial-group') {
          var options = {
            defaultTabId: 'group',
            activeClasses: 'tei-text-primary border-orange-700 shadow-xl font-extrabold',
            onShow: () => {
              console.log('tab is shown');
            },
          };
        } else {
          var options = {
            defaultTabId: 'financial',
            activeClasses: 'tei-text-primary border-orange-700 shadow-xl font-extrabold',
            onShow: () => {
              console.log('tab is shown');
            },
          };
        }
        var eligibilityTabs = new Tabs(tabElement, tabElements, options)

        // console.log(eligibilityTabs);
        // eligibilityTabs.show('dashboard');
      });
    </script>
  @endsection
</x-app-layout>
