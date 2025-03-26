<x-app-layout>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-lg rounded-md">

      <livewire:envelope-maintenance.eligibility-details-maintenance :eligibilityId='$eligibilityId' />
    </div>
  </div>

  @include('components.toast-message')

</x-app-layout>
