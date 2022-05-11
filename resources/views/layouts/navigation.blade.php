<div class="min-h-full mb-5">
  <nav class="flex items-center justify-between flex-wrap bg-gray-800 p-6">
    <div class="flex items-center flex-shrink-0 text-white mr-6">
      <a href="{{ url('/') }}" class="flex items-center">
        <x-svg.logo />
        <span class="ml-2 font-semibold text-2xl tracking-tight">SmartCalendar</span>
      </a>
    </div>
    <div class="block flex-grow justify-end lg:flex lg:items-center lg:w-auto">
      <div class="text-sm">
        <div class="flex items-center">
          <a href="{{ url('/') }}" class="px-3 py-2 rounded-md text-white font-semibold tracking-tight hover:bg-gray-700 focus:outline-none focus:bg-gray-700">Home</a>
          @auth
          @if (Route::has('Profile'))
          <a href="{{ route('Profile') }}" class="px-3 py-2 rounded-md text-white font-semibold tracking-tight hover:bg-gray-700 focus:outline-none focus:bg-gray-700">{{ __('Profile') }}</a>
          @endif
          @if (Route::has('Contact'))
          <a href="{{ route('Contact') }}" class="px-3 py-2 rounded-md text-white font-semibold tracking-tight hover:bg-gray-700 focus:outline-none focus:bg-gray-700">{{ __('Contact') }}</a>
          @endif
          @if (Route::has('Settings'))
          <a href="{{ route('Settings') }}" class="px-3 py-2 rounded-md text-white font-semibold tracking-tight hover:bg-gray-700 focus:outline-none focus:bg-gray-700">{{ __('Settings') }}</a>
          @endif
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}" class="px-3 py-2 rounded-md text-white font-semibold tracking-tight hover:bg-gray-700 focus:outline-none focus:bg-gray-700" onclick="event.preventDefault();
                                                this.closest('form').submit();">{{ __('Log Out') }}</a>
          </form>
          @else
          <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-white font-semibold tracking-tight hover:bg-gray-700 focus:outline-none focus:bg-gray-700">{{ __('Log in') }}</a>
          @if (Route::has('register'))
          <a href="{{ route('register') }}" class="px-3 py-2 rounded-md text-white font-semibold tracking-tight hover:bg-gray-700 focus:outline-none focus:bg-gray-700">{{ __('Register') }}</a>
          @endif
          @endauth
        </div>
      </div>
    </div>
  </nav>
</div>