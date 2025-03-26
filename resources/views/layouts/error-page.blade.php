<x-guest-layout>
  <!-- Session Status -->
  <x-auth-session-status class="mb-4" :status="session('status')" />
  <div class="flex justify-center m-10">
    <p class="text-xl text-slate-200">{{$message}} | {{$code}}</p>
  </div>

</x-guest-layout>
