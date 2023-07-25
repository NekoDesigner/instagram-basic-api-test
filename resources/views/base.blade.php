<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Big Boss Studio - Paramètres</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
</head>
<body>
  <div class="flex">
    <!-- Sidebar -->
    <div class="w-1/6 bg-slate-50 h-screen drop-shadow-md">
      <!-- Sidebar content goes here -->
      <ul class="py-4">
        <li class="px-4 py-2 text-dark text-lg">
          <a href="{{route('index')}}">
            <i class="fa-regular fa-user mr-2"></i> {{ $user['username'] }}
          </a>
        </li>
        <hr />
        <li class="px-4 py-2 text-dark">
          <p class="text-neutral-500 mb-2 font-medium">Type de compte</p>
          <div class="px-2 py-1 bg-fuchsia-500 rounded-md text-white inline-block text-xs">
            {{ $user['account_type'] }}
          </div>
        </li>
        <li class="px-4 py-2 text-dark">
          <a href="{{route('index')}}">
            <i class="fa-solid fa-camera-retro"></i> Instagram Feed
          </a>
        </li>
        <li class="px-4 pt-6 pb-2 text-dark">
          <a href="{{ route('getting-started') }}">
            <i class="fa-solid fa-book"></i> Getting Started
          </a>
        </li>
        <li class="px-4 pt-6 pb-2 text-dark">
          <a href="{{route('settings')}}">
            <i class="fa fa-cog"></i> Paramètres
          </a>
        </li>
      </ul>
    </div>

    <!-- Main content area -->
    <div class="w-5/6 h-screen bg-gray-100 p-4 overflow-y-auto">
      <!-- Main content goes here -->
      @yield('content')
    </div>
  </div>

  @yield('scripts')

</body>
</html>