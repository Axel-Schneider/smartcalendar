@extends('layouts.app')

@section("content")
<div>
    <div id="sharedWidth">
        Axel, Marie,...
    </div>
    <button id="dropdownUsersDefault" data-dropdown-toggle="dropdownUsers" class="px-3 py-2 rounded-md text-white font-semibold tracking-tight hover:bg-gray-700 focus:outline-none focus:bg-gray-700" type="button">
        <svg width="32" height="32" viewBox="0 0 32 32" fill="#000" xmlns="http://www.w3.org/2000/svg">
            <path d="M31.5281 22.8688C27.7644 19.104 27.198 17.2149 27.198 11.2C27.198 5.01437 22.1847 0 16.0001 0C9.81556 0 4.80224 5.01443 4.80224 11.2C4.80224 14.4432 4.71587 15.6538 4.26943 17.1402C3.71424 18.992 2.5745 20.7648 0.47156 22.8688C-0.535877 23.8768 0.177747 25.6 1.60281 25.6H10.4812L10.4006 26.4C10.4006 29.4928 12.9073 31.9999 15.9995 31.9999C19.0918 31.9999 21.5985 29.4928 21.5985 26.4L21.5179 25.6H30.3969C31.8225 25.6 32.5361 23.8768 31.5281 22.8688ZM16.0006 30.3999C13.7921 30.3999 12.0012 28.6085 12.0012 26.4L12.0817 25.6H19.9185L20.0002 26.4C20.0001 28.6085 18.2092 30.3999 16.0006 30.3999ZM2.5 23.5C7.29893 18.7 7 16 7 11.2C7 5.89868 10.6987 2.5 15.9995 2.5C21.3004 2.5 24.9995 5.6987 25 11C25 15.8 24.7011 18.7 29.5 23.5H16.5H2.5Z" />
            @if(auth()->user()->unreadNotifications->count() > 0)
            <circle cx="24" cy="8" r="8" fill="#EC1515" />
            <text id="notification-count" x="24" y="8" font-family="Verdana" font-size="12" fill="#fff" dominant-baseline="central" text-anchor="middle">
                {{auth()->user()->unreadNotifications->count()}}
            </text>
            @endif
        </svg>
    </button>
    <div id="dropdownUsers" class="z-10 hidden bg-white divide-y divide-gray-100 rounded shadow w-50 dark:bg-gray-700">
        <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefault">
            @foreach(Auth::user()->contacts() as $user)
            <li id="">
                <div class="px-4 bg-black py-2 text-lg flex-auto hover:bg-gray-100 dark:hoverbg-gray-600 dark:hover:text-white flex items-center justify-between">
                    <span>{{ $user->name }}</span>
                    <span class="block flex-grow justify-end lg:flex lg:items-center lg:w-auto ml-4">
                        <div class="flex items-center">
                        </div>
                    </span>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection

@section('scripts')


@endsection