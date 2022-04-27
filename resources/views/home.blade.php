@extends('layouts.app')

@section("content")

<div class="load">
    <x-Loading></x-Loading>
</div>
<div id="calendar"></div>

@endsection

@section("script")
<script>
    window.onload = function() {
        const events = [];
        const tmpevents = <?php echo json_encode($events); ?>;
        for(const ele of tmpevents) {
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