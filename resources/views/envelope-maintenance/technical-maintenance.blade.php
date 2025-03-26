<x-app-layout>
  <div class="max-w-full mx-auto sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-lg rounded-md">

      <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" role="tablist">
          <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-b-2 rounded-tl-lg" id="technical-tab" type="button" role="tab"
              aria-controls="technical" aria-selected="false">Technical Maintenance</button>
          </li>
          <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-b-2 hover:text-gray-600 hover:border-gray-300" id="group-tab"
              type="button" role="tab" aria-controls="group" aria-selected="false">Group Technicals</button>
          </li>
      </div>
      <div id="default-tab-content">
        <div class="hidden rounded-lg" id="technical" role="tabpanel" aria-labelledby="technical-tab">

          <livewire:envelope-maintenance.technical-maintenance />

        </div>
        <div class="hidden rounded-lg bg-gray-50" id="group" role="tabpanel" aria-labelledby="group-tab">

          <livewire:envelope-maintenance.group-technical />

        </div>
      </div>


    </div>
  </div>

  @include('components.toast-message')

  @section('envelope-script')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const tabElements = [{
            id: 'technical',
            triggerEl: document.querySelector('#technical-tab'),
            targetEl: document.querySelector('#technical'),
          },
          {
            id: 'group',
            triggerEl: document.querySelector('#group-tab'),
            targetEl: document.querySelector('#group'),
          },
        ];


        var tabElement = document.getElementById('default-tab');
        var activeTab = "{{ session('activeTab') }}";

        if (activeTab == 'technical-group') {
          var options = {
            defaultTabId: 'group',
            activeClasses: 'tei-text-primary border-orange-700 shadow-xl font-extrabold',
          };
        } else {
          var options = {
            defaultTabId: 'technical',
            activeClasses: 'tei-text-primary border-orange-700 shadow-xl font-extrabold',
          };
        }
        var eligibilityTabs = new Tabs(tabElement, tabElements, options)

      });
    </script>
  @endsection
</x-app-layout>
