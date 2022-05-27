<div class="min-h-full mb-5">
  <nav class="flex items-center justify-between flex-wrap bg-gray-800">
    <div class="flex items-center flex-shrink-0 text-white mr-6">
      <a href="{{ url('/') }}" class="flex items-center">
        <x-svg.logo />
        <span class="ml-2 font-semibold text-2xl tracking-tight">SmartCalendar</span>
      </a>
    </div>
    <div class="text-white">
      {{ Auth::user()->name }}
    </div>
    <div class="block flex-grow justify-end lg:flex lg:items-center lg:w-auto">
      <div class="text-sm">
        <div class="flex items-center">
          <a href="{{ url('/') }}" class="px-3 py-2 rounded-md text-white font-semibold tracking-tight hover:bg-gray-700 focus:outline-none focus:bg-gray-700">Home</a>
          @auth
          <div>
            <button id="dropdownDefault" data-dropdown-toggle="dropdown" class="px-3 py-2 rounded-md text-white font-semibold tracking-tight hover:bg-gray-700 focus:outline-none focus:bg-gray-700" type="button">
              <svg width="32" height="32" viewBox="0 0 32 32" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                <path d="M31.5281 22.8688C27.7644 19.104 27.198 17.2149 27.198 11.2C27.198 5.01437 22.1847 0 16.0001 0C9.81556 0 4.80224 5.01443 4.80224 11.2C4.80224 14.4432 4.71587 15.6538 4.26943 17.1402C3.71424 18.992 2.5745 20.7648 0.47156 22.8688C-0.535877 23.8768 0.177747 25.6 1.60281 25.6H10.4812L10.4006 26.4C10.4006 29.4928 12.9073 31.9999 15.9995 31.9999C19.0918 31.9999 21.5985 29.4928 21.5985 26.4L21.5179 25.6H30.3969C31.8225 25.6 32.5361 23.8768 31.5281 22.8688ZM16.0006 30.3999C13.7921 30.3999 12.0012 28.6085 12.0012 26.4L12.0817 25.6H19.9185L20.0002 26.4C20.0001 28.6085 18.2092 30.3999 16.0006 30.3999ZM2.5 23.5C7.29893 18.7 7 16 7 11.2C7 5.89868 10.6987 2.5 15.9995 2.5C21.3004 2.5 24.9995 5.6987 25 11C25 15.8 24.7011 18.7 29.5 23.5H16.5H2.5Z" />
                @if(auth()->user()->unreadNotifications->count() > 0)
                <g id="notification-group" class=" ">
                  <circle cx="24" cy="8" r="8" fill="#EC1515" />
                  <text id="notification-count" x="24" y="8" font-family="Verdana" font-size="12" fill="#fff" dominant-baseline="central" text-anchor="middle">
                    {{auth()->user()->unreadNotifications->count()}}
                  </text>
                </g>
                @endif
              </svg>
            </button>
            <!-- Dropdown menu -->
            <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded shadow w-50 dark:bg-gray-700">
              <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefault">
                @foreach(auth()->user()->unreadNotifications as $notification)
                <li id="user-notification-{{ $notification->data['from_id'] }}">
                  <div class="px-4 py-2 text-lg flex-auto hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white flex items-center justify-between">
                    <span>{{ $notification->data['from_name'] }}</span>
                    <span class="block flex-grow justify-end lg:flex lg:items-center lg:w-auto ml-4">
                      <div class="flex items-center">
                        <span class="mr-4 roundedpy-1 px-1 hover:bg-gray-300 hover:shadow-lg" onclick="blockContact({{ $notification->data['from_id'] }}, '{{ $notification->id }}')">
                          <svg width="22" height="22" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_130_3696)">
                              <path d="M25.2672 18.113H14.2084C10.496 18.113 7.47559 21.1334 7.47559 24.8459V28.7354H32.0001V24.8459C32.0001 21.1335 28.9797 18.113 25.2672 18.113ZM30.5389 27.2743H8.93677V24.846C8.93677 21.9392 11.3016 19.5743 14.2084 19.5743H25.2671C28.1739 19.5743 30.5388 21.9392 30.5388 24.846L30.5389 27.2743Z" fill="#FF0000" />
                              <path d="M19.7378 17.4212C23.6408 17.4212 26.816 14.2459 26.816 10.3429C26.816 6.44 23.6408 3.26465 19.7378 3.26465C15.8349 3.26465 12.6595 6.4399 12.6595 10.3429C12.6595 14.246 15.8349 17.4212 19.7378 17.4212ZM19.7378 4.72593C22.8351 4.72593 25.3548 7.2457 25.3548 10.343C25.3548 13.4404 22.8351 15.9601 19.7378 15.9601C16.6405 15.9601 14.1207 13.4403 14.1207 10.343C14.1207 7.2458 16.6405 4.72593 19.7378 4.72593Z" fill="#FF0000" />
                              <path d="M4.75 9C2.12657 9 0 11.1266 0 13.75C0 16.3734 2.12657 18.5 4.75 18.5C7.37343 18.5 9.5 16.3734 9.5 13.75C9.5 11.1266 7.37343 9 4.75 9ZM0.95 13.75C0.95 11.6514 2.65145 9.95 4.75 9.95C5.6278 9.95 6.43387 10.2507 7.0775 10.7508L1.75085 16.0775C1.25067 15.4339 0.95 14.6278 0.95 13.75ZM4.75 17.55C3.8722 17.55 3.06565 17.2493 2.4225 16.7491L7.74915 11.4225C8.24932 12.0661 8.55 12.8722 8.55 13.75C8.55 15.8485 6.84855 17.55 4.75 17.55Z" fill="#FF0000" />
                            </g>
                          </svg>
                        </span>
                        <span class="mr-4 rounded py-1 px-1 hover:bg-gray-300 hover:shadow-lg" onclick="declineContact({{ $notification->data['from_id'] }}, '{{ $notification->id }}')">
                          <svg width="22" height="22" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_130_3683)">
                              <path d="M25.2672 18.113H14.2084C10.496 18.113 7.47559 21.1334 7.47559 24.8459V28.7354H32.0001V24.8459C32.0001 21.1335 28.9797 18.113 25.2672 18.113ZM30.5389 27.2743H8.93677V24.846C8.93677 21.9392 11.3016 19.5743 14.2084 19.5743H25.2671C28.1739 19.5743 30.5388 21.9392 30.5388 24.846L30.5389 27.2743Z" fill="#2D2438" />
                              <path d="M19.7378 17.4212C23.6408 17.4212 26.816 14.2459 26.816 10.3429C26.816 6.44 23.6408 3.26465 19.7378 3.26465C15.8349 3.26465 12.6595 6.4399 12.6595 10.3429C12.6595 14.246 15.8349 17.4212 19.7378 17.4212ZM19.7378 4.72593C22.8351 4.72593 25.3548 7.2457 25.3548 10.343C25.3548 13.4404 22.8351 15.9601 19.7378 15.9601C16.6405 15.9601 14.1207 13.4403 14.1207 10.343C14.1207 7.2458 16.6405 4.72593 19.7378 4.72593Z" fill="#2D2438" />
                              <path d="M8.52655 9.89078L7.49334 8.85757L4.69222 11.6587L1.89104 8.8575L0.857823 9.89071L3.65901 12.6919L0.857825 15.4931L1.89104 16.5263L4.69222 13.7251L7.49327 16.5262L8.52648 15.4929L5.72544 12.6919L8.52655 9.89078Z" fill="#2D2438" />
                            </g>
                          </svg>
                        </span>
                        <span class="rounded py-1 px-1 hover:bg-gray-300 hover:shadow-lg" onclick="acceptContact({{ $notification->data['from_id'] }}, '{{ $notification->id }}')">
                          <svg width="22" height="22" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_130_3689)">
                              <path d="M25.2672 18.113H14.2084C10.496 18.113 7.47559 21.1334 7.47559 24.8459V28.7354H32.0001V24.8459C32.0001 21.1335 28.9797 18.113 25.2672 18.113ZM30.5389 27.2743H8.93677V24.846C8.93677 21.9392 11.3016 19.5743 14.2084 19.5743H25.2671C28.1739 19.5743 30.5388 21.9392 30.5388 24.846L30.5389 27.2743Z" fill="#2D2438" />
                              <path d="M19.7378 17.4212C23.6408 17.4212 26.816 14.2459 26.816 10.3429C26.816 6.44 23.6408 3.26465 19.7378 3.26465C15.8349 3.26465 12.6595 6.4399 12.6595 10.3429C12.6595 14.246 15.8349 17.4212 19.7378 17.4212ZM19.7378 4.72593C22.8351 4.72593 25.3548 7.2457 25.3548 10.343C25.3548 13.4404 22.8351 15.9601 19.7378 15.9601C16.6405 15.9601 14.1207 13.4403 14.1207 10.343C14.1207 7.2458 16.6405 4.72593 19.7378 4.72593Z" fill="#2D2438" />
                              <g clip-path="url(#clip1_130_3689)">
                                <path d="M1 12.7188L3.26562 14.9844L8.25 10" stroke="#2D2438" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                              </g>
                            </g>
                          </svg>
                        </span>
                      </div>

                    </span>
                  </div>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
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