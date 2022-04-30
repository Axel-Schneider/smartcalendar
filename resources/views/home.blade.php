@extends('layouts.app')

@section("content")

<div class="load">
    <x-Loading></x-Loading>
</div>
<div id="calendar"></div>

@endsection
@section("popup")
<div id="new-event-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center flex">
    <div id="modal-opacity" class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
    <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
        <div class="relative bg-white rounded-lg shadow">
            <div class="p-4 rounded-t border-b">
                <h3 class="text-xl text-center font-semibold text-gray-900">{{__('new_event')}}</h3>
            </div>
            <div class="p-6 space-y-6">
                <form action="{{route('events.store')}}" method="POST">
                    @csrf
                    <div class="grid gap-6 mb-6 lg:grid-cols-2">
                        <div>
                            <label for="startDate" class="block mb-1 text-sm font-medium text-gray-900">{{__('start_date')}}</label>
                            <input type="datetime-local" name="startDate" id="startDate" class="form-control bg-gray-50 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" required>
                        </div>
                        <div>
                            <label for="endDate" class="block mb-1 text-sm font-medium text-gray-900">{{__('end_date')}}</label>
                            <input type="datetime-local" name="endDate" id="endDate" class="form-control bg-gray-50 block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" required>
                        </div>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox" name="fullDay" id="fullDay" class="form-check-input" checked>
                        <label for="fullDay" class="form-check-label">{{__('full_day')}}</label>
                    </div>
                    <div class="mb-6">
                        <input type="text" name="title" id="title" placeholder="{{__('title')}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>
                    <div class="mb-6">
                        <textarea name="description" id="description" placeholder="{{__('description')}}" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                    </div>
                    <div class="flex justify-between mt-10">
                        <button type="submit" class="items-center text-sm font-medium text-center text-black rounded-lg focus:ring-4" data-modal-toggle="new-evet-modal">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg%22%3E"> <path d=" M10.2626 16L24.4516 1.81105C24.807 1.45562 24.5435 0.621994 24.1881 0.266569C23.8327 -0.0888563 23.2627 -0.0888563 22.9072 0.266569L7.81173 15.3621C7.45631 15.7175 7.45631 16.2875 7.81173 16.643L22.9072 31.7318C23.0816 31.9061 23.3163 32 23.5443 32C23.7723 32 24.0071 31.9128 24.1814 31.7318C24.5368 31.3763 24.7996 30.5435 24.4442 30.1881L10.2626 16Z"></path> </svg>
                        </button>
                        <button type="submit" class="items-center text-sm font-medium text-center text-black rounded-lg focus:ring-4">
                            <x-ok-button></x-ok-button>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section("script")
<script>
    window.onload = function() {
        const events = [];
        const tmpevents = <?php echo json_encode($events); ?>;
        for (const ele of tmpevents) {
            events.push({
                "id": ele.id,
                "allDay": ele.fullDay,
                "start": ele.startDate,
                "end": ele.endDate,
                "title": ele.title,
                "classNames": []
            });
        }
        initCalendar(events);
        const elements = document.getElementsByClassName("load");
        while (elements.length > 0) {
            elements[0].parentNode.removeChild(elements[0]);
        }
    }
</script>
@endsection