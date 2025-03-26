<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Company Logo --}}
  <link rel="icon" href="{{ asset('img/tei_logo.png') }}" type="image/png" sizes="16x16">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Scripts -->
  @livewireStyles
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="tei-fonts antialiased">
  <div class="min-h-screen" style="background-color: var(--main)">
    <div class="fixed w-full bg-white z-20">
      <livewire:layout.nav />
    </div>
    <div class="flex">
      <div class="bg-white min-h-screen">
        @if (Auth::user()->role_id == 2)
          <livewire:layout.sidebar />
        @else
          <livewire:layout.admin-sidebar />
        @endif
      </div>
      <!-- Page Content -->
      <main class="w-full pt-[4rem] tei-main tei-main-close" style="background-color: #C2C4C3">
        <livewire:layout.header />
        {{ $slot }}
      </main>
    </div>
  </div>
  @livewireScripts
  @yield('page-script')
  @yield('page-sidebar-script')
  @yield('content-script')
  @yield('envelope-script')
  @yield('toast-script')

</body>

</html>
