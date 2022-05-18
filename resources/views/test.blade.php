@extends('layouts.app')

@section("content")
<div class="grid content-center grid-cols-3 ">
    <div class="w-full"></div>
    <div class="w-full">
        <form action="/test" method="POST">


            <div id="sharedUsers">
                <button data-dropdown-toggle="dropdownsharedWith" class="form-control inline-flex justify-between bg-gray-50 w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" type="button">
                    <span id="select-input-show-sharedWith" default-text="Select users">Select users</span>
                </button>
                <div id="dropdownsharedWith" class="z-10 hidden rounded w-1/3 border-2 bg-white divide-y divide-gray-100 dark:bg-gray-700">
                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefault">
                        @foreach(Auth::user()->contacts() as $user)
                        <li>
                            <div class="block px-4 py-2 hover:bg-gray-100" input-option-id="{{ $user->id }}" input-id="sharedWith" onclick="AddOption();">{{ $user->name }}</div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @error('sharedWith')

                @foreach ($errors->all() as $error)
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $error }}</p>
                @endforeach
                @enderror
                <select class="hidden" multiple="multiple" name="sharedWith[]" id="select-input-select-sharedWith">
                    @foreach(Auth::user()->contacts() as $user)
                    <option value="{{ $user->id }}" id="select-input-option-{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>


            <input type="submit" value="Submit">

        </form>
    </div>
</div>

@endsection