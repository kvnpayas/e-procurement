<x-app-layout>
  <div class="max-w-full mx-auto sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-lg rounded-md">

      <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" role="tablist">
          <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-b-2 rounded-tl-lg" id="profile-tab" type="button" role="tab"
              aria-controls="profile" aria-selected="false">Eligibility Maintenance</button>
          </li>
          <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-b-2 hover:text-gray-600 hover:border-gray-300" id="dashboard-tab"
              type="button" role="tab" aria-controls="dashboard" aria-selected="false">Group Eligibilities</button>
          </li>
      </div>
      <div id="default-tab-content">
        <div class="hidden rounded-lg" id="profile" role="tabpanel" aria-labelledby="profile-tab">

          <livewire:envelope-maintenance.eligibility-maintenance />

        </div>
        <div class="hidden rounded-lg bg-gray-50" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">

          <livewire:envelope-maintenance.group-eligibility />

        </div>
      </div>


    </div>
  </div>

  @include('components.toast-message')

  @section('envelope-script')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const tabElements = [{
            id: 'profile',
            triggerEl: document.querySelector('#profile-tab'),
            targetEl: document.querySelector('#profile'),
          },
          {
            id: 'dashboard',
            triggerEl: document.querySelector('#dashboard-tab'),
            targetEl: document.querySelector('#dashboard'),
          },
        ];


        var tabElement = document.getElementById('default-tab');
        var activeTab = "{{ session('activeTab') }}";
        console.log(activeTab);
        if (activeTab == 'eligibility-group') {
          var options = {
            defaultTabId: 'dashboard',
            activeClasses: 'tei-text-primary border-orange-700 shadow-xl font-extrabold',
            onShow: () => {
              console.log('tab is shown');
            },
          };
        } else {
          var options = {
            defaultTabId: 'profile',
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
