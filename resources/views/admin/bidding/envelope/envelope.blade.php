<x-app-layout>
  @php

    $biddingId = request()->route('biddingId');
    $bidding = projectBid($biddingId);
    $allEnvelopes = [
        'eligibility' => (bool) $bidding->eligibility,
        'technical' => (bool) $bidding->technical,
        'financial' => (bool) $bidding->financial,
    ];
    $firstEnvelope = array_search(true, $allEnvelopes, true);

    $envelopes = array_filter($allEnvelopes, function ($value) {
        return $value === true;
    });
  @endphp
  <div class="max-w-full mx-auto sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-lg rounded-md">

      <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" role="tablist">
          @foreach ($envelopes as $envelope => $value)
            <li class="me-2" role="presentation">
              <a href="{{ route('project-bidding.' . $envelope . '-envelope', $bidding->id) }}"
                class="{{$firstEnvelope == $envelope ? 'rounded-tl-md' : '' }} inline-block p-4 border-b-2 font-extrabold transition-all duration-300 {{ Route::currentRouteName() == 'project-bidding.' . $envelope . '-envelope' ? 'tei-text-primary border-orange-700 shadow-2xl font-extrabold' : 'tei-text-accent hover:shadow-xl hover:text-sky-900 hover:border-orange-700' }}">
                {{ strtoupper($envelope) }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>
      <div id="default-tab-content">
        <div class="ml-5">
          <div>
            <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Project Title:</label>
            <span class="text-xs uppercase font-extrabold tei-text-accent">{{ $bidding->title }}</span>
          </div>
          <div>
            <label for="title" class=" mr-2 text-lg font-extrabold tei-text-secondary ">Deadline Date:</label>
            <span
              class="text-xs uppercase font-extrabold tei-text-accent">{{ date('F j,Y @ h:i A', strtotime($bidding->deadline_date)) }}</span>
          </div>
        </div>
        @isset($slot)
          {{ $slot }}
        @endisset
      </div>
    </div>
  </div>
  @include('components.toast-message')
</x-app-layout>

{{-- @section('envelope-script')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const tabElements = [{
            id: 'eligibility',
            triggerEl: document.querySelector('#eligibility-tab'),
            targetEl: document.querySelector('#eligibility'),
          },
          {
            id: 'technical',
            triggerEl: document.querySelector('#technical-tab'),
            targetEl: document.querySelector('#technical'),
          },
          {
            id: 'financial',
            triggerEl: document.querySelector('#financial-tab'),
            targetEl: document.querySelector('#financial'),
          },
        ];

        var tabElement = document.getElementById('default-tab');
        var activeTab = "{{ session('activeTab') }}";
        console.log(activeTab);
        if (activeTab == 'financial-envelope') {
          var options = {
            defaultTabId: 'financial',
            activeClasses: 'tei-text-primary border-orange-700 shadow-xl font-extrabold',
          };
        } else if (activeTab == 'technical-envelope') {
          var options = {
            defaultTabId: 'technical',
            activeClasses: 'tei-text-primary border-orange-700 shadow-xl font-extrabold',
          };
        } else {
          var options = {
            defaultTabId: 'eligibility',
            activeClasses: 'tei-text-primary border-orange-700 shadow-xl font-extrabold',
          };
        }
        var envelopeTabs = new Tabs(tabElement, tabElements, options)

        // console.log(envelopeTabs);
        // eligibilityTabs.show('dashboard');
      });
    </script>
  @endsection --}}
