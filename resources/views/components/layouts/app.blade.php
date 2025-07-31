<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  {{-- <title> &mdash; {{ config('app.name') }}</title> --}}
  <title> {{ config('app.name') }}</title>

  <link rel="icon" type="image/png" sizes="16x16" href="/logo.webp">

  @include('components.layouts.css')
  
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>

      @include('components.layouts.navbar')

      @include('components.layouts.sidebar')

      <!-- Main Content -->
      <div class="main-content">
       {{ $slot }}
      </div>

      @include('components.layouts.footer')

    </div>

    @include('components.layouts.logout-modal')

  </div>

  @include('components.layouts.scripts')

  @include('components.layouts.toast')

  @stack('scripts')
</body>
</html>
