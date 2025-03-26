<x-guest-layout>

  @if ($user->role_id == 1)
  <livewire:pages.auth.admin-register :user='$user'/>
  @else
  <livewire:pages.auth.vendor-register :user='$user'/>
  @endif

</x-guest-layout>
