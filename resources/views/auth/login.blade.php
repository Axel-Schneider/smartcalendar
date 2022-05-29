<x-guest-layout class="text-gray-800">
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <div class="grid w-full mb-5">
                    <svg width="90" height="90" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg" class="justify-self-center">
                        <rect width="500" height="500" rx="100" fill="url(#paint0_linear_242_1876)" />
                        <path d="M0 100C0 44.7715 44.7715 0 100 0H400C455.228 0 500 44.7715 500 100V400C500 455.228 455.228 500 400 500H162.086C160.726 500 159.398 499.594 158.27 498.835C94.9223 456.179 47.6949 393.526 24.1294 320.883L0.549556 248.194C0.185449 247.072 0 245.899 0 244.719V100Z" fill="url(#paint1_linear_242_1876)" />
                        <path d="M0 100C0 44.7715 44.7715 0 100 0H400C455.228 0 500 44.7715 500 100V417.091C500 459.416 467.524 494.659 425.34 498.112L415.918 498.884C414.977 498.961 414.03 498.912 413.101 498.739L359.504 488.746C178.8 455.056 36.6342 315.052 0.179136 134.885C0.0600091 134.297 0 133.697 0 133.097V100Z" fill="url(#paint2_linear_242_1876)" />

                        <text id="logo-day" x="50%" y="53%" font-family="Verdana" font-size="275" fill="#fff" style="filter: drop-shadow(0px 0px 10px rgb(0 0 0 / 0.5));" dominant-baseline="middle" text-anchor="middle">
                        </text>
                        <path d="M0 134L10.7661 171.433C24.8957 220.56 54.1387 263.99 94.3453 295.558C137.204 329.209 190.118 347.5 244.609 347.5H289.654C324.633 347.5 354.933 371.766 362.575 405.901L375.602 464.087C378.067 475.098 385.384 484.401 395.505 489.39L415 499L360.406 488.836C179.166 455.093 36.5616 314.693 0 134Z" fill="url(#paint3_linear_242_1876)" />
                        <defs>
                            <filter id="filter0_d_242_1876" x="59.9365" y="122.266" width="381.665" height="256.543" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                                <feOffset />
                                <feGaussianBlur stdDeviation="12.5" />
                                <feComposite in2="hardAlpha" operator="out" />
                                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.5 0" />
                                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_242_1876" />
                                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_242_1876" result="shape" />
                            </filter>
                            <linearGradient id="paint0_linear_242_1876" x1="61.5" y1="435.5" x2="500" y2="9.80124e-06" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#672E91" />
                                <stop offset="1" stop-color="#FF73A7" />
                            </linearGradient>
                            <linearGradient id="paint1_linear_242_1876" x1="9" y1="407.5" x2="500" y2="2.28169e-05" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#BA549D" />
                                <stop offset="0.276629" stop-color="#672E91" />
                                <stop offset="1" stop-color="#FF73A7" />
                            </linearGradient>
                            <linearGradient id="paint2_linear_242_1876" x1="45" y1="624.5" x2="396" y2="136" gradientUnits="userSpaceOnUse">
                                <stop offset="0.0919582" stop-color="#672E91" />
                                <stop offset="1" stop-color="#FF73A7" />
                            </linearGradient>
                            <linearGradient id="paint3_linear_242_1876" x1="282.5" y1="216" x2="-111" y2="630" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FF7BAC" />
                                <stop offset="1" stop-color="#672E91" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>

                <div class="text-4xl font-bold">
                    {{ config('app.name', 'Laravel') }}
                </div>

            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex mt-4 justify-end">
                <div class="grid content-between justify-start text-right">
                    @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                    @endif
                    @if (Route::has('register'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
                        {{ __('No account ?') }}
                    </a>
                    @endif
                </div>

                <x-button class="ml-3 h-full">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
<script>
    let logoText = document.getElementById("logo-day");
    logoText.innerHTML = new Date().getDate().toLocaleString('local', {
        minimumIntegerDigits: 2
    });
</script>