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

<div class="modal fade in" id="overlap-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="overlap-title" class="modal-title"></h4>
            </div>
            <div id="overlap-body" class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>