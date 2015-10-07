@extends('app_sched')

@section('title')
    Schedule
@stop

@section('schedule')
    <div class="col-md-9 col-centered col-md-push-3">
        @include('schedulizer.schedule-panel')
    </div>

    <div class="col-md-3 col-md-pull-9">
        @include('schedulizer.classes-panel')
        @include('schedulizer.time-span-options-panel')
        @include('schedulizer.other-options-panel')
    </div>

    @include('js.schedule-options-panel')
@stop
