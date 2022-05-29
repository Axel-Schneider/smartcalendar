<nav class="flex justify-between bg-gray-800 text-white px-5 py-2 items-center drop-shadow-2xl">
  <div>
    <a href="{{ url('/') }}" class="flex items-center">
      <x-svg.logo />
      <span class="ml-5 font-semibold text-2xl tracking-tight">SmartCalendar</span>
    </a>
  </div>
  <div>
    <div class="flex items-center">
      <a href="{{ url('/') }}" class="px-3 py-2 rounded-md font-semibold tracking-tight hover:bg-gray-700 focus:outline-none focus:bg-gray-700">{{__('home')}}</a>
    </div>
  </div>
  <div class="">
    <div class="text-sm">
      <div class="flex items-center">
        @auth
        <div>
          <button id="dropdownDefault" data-dropdown-toggle="dropdown" class="px-3 py-2 rounded-md font-semibold tracking-tight hover:bg-gray-700 focus:outline-none focus:bg-gray-700" type="button">
            <svg width="30" height="30" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M26.6895 23.2736C23.6228 20.088 23.1613 18.4896 23.1613 13.4C23.1613 8.16603 19.0764 3.9231 14.0371 3.9231C8.99785 3.9231 4.91294 8.16608 4.91294 13.4C4.91294 16.1443 4.84256 17.1687 4.47879 18.4264C4.02642 19.9932 3.09774 21.4933 1.38423 23.2736C0.56336 24.1266 1.14483 25.5846 2.30599 25.5846H9.54021L9.47457 26.2616C9.47457 28.8786 11.5171 31 14.0367 31C16.5563 31 18.5987 28.8786 18.5987 26.2616L18.5331 25.5846H25.7678C26.9294 25.5846 27.5109 24.1266 26.6895 23.2736ZM14.0367 29.1539C12.2371 29.1539 11.0741 28.1303 11.0741 26.2616V25.5846H16.9335L17 26.2616C16.9999 28.1303 15.8362 29.1539 14.0367 29.1539ZM4.47879 23.2736C8.38903 19.2121 7.51852 17.4616 7.51852 13.4C7.51852 9.33848 10.4815 6.38463 14.0367 6.38463C17.898 6.38463 20.5556 9.67067 20.5556 13.4C20.5556 17.1294 19.6083 19.2121 23.5185 23.2736H4.47879Z" fill="#F6EFFF" />
              <g>
                <circle cx="24" cy="8" r="8" fill="#EC1515" />
                <text id="notification-count" x="23.75" y="8" font-family="Verdana" font-size="10" fill="#fff" dominant-baseline="central" text-anchor="middle">
                  {{auth()->user()->unreadNotifications->count()+10 > 9 ? '9+' : auth()->user()->unreadNotifications->count()}}
                </text>
              </g>
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
        <div>
          <button id="dropdown-UserParam" data-dropdown-toggle="dropdownUserParam" class="px-3 py-2 rounded-md font-semibold tracking-tight hover:bg-gray-700 focus:outline-none focus:bg-gray-700" type="button">
            <svg width="30" height="30" viewBox="0 0 32 32" fill="#fff" xmlns="http://www.w3.org/2000/svg">
              <g class=" ">
                <circle cx="16" cy="16" r="16" fill="#ccc" />
                <text x="15" y="15" font-size="20" fill="#000" dominant-baseline="central" text-anchor="middle">
                  {{ Auth::user()->getInitial() }}
                </text>
              </g>
            </svg>

          </button>
          <div id="dropdownUserParam" class="z-10 hidden bg-white divide-y divide-gray-100 rounded shadow w-50 dark:bg-gray-700">
            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefault">
              @if (Route::has('Profile'))
              <li class="px-4 py-2 text-lg flex-auto hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white flex items-center justify-between">
                <a href="{{ route('Profile') }}">{{ __('Profile') }}</a>
              </li>
              @endif
              @if (Route::has('Contact'))
              <li class="px-4 py-2 text-lg flex-auto hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white flex items-center justify-between">
                <a href="{{ route('Contact') }}">{{ __('Contact') }}</a>
              </li>
              @endif
              @if (Route::has('Settings'))
              <li class="px-4 py-2 text-lg flex-auto hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white flex items-center justify-between">
                <a href="{{ route('Settings') }}">{{ __('Settings') }}</a>
              </li>
              @endif
              <li class="px-4 py-2 text-lg flex-auto hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white flex items-center justify-between">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</a>
                </form>
              </li>
            </ul>
          </div>
        </div>
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