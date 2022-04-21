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
        initCalendar();
        const elements = document.getElementsByClassName("load");
        while (elements.length > 0) {
            elements[0].parentNode.removeChild(elements[0]);
        }
    }
</script>
@endsection