<div class="panel panel-default">
    <div class="panel-heading">
        <h3 id="schedule-panel-title" class="panel-title">
        </h3>
        <div class="btn-group">
            <a data-direction="left" class="btn btn-xs btn-primary toggle-schedules mdi-hardware-keyboard-arrow-left"></a>
            <a data-direction="right" class="btn btn-xs btn-primary toggle-schedules mdi-hardware-keyboard-arrow-right"></a>
        </div>
        {{--<a class="btn btn-xs btn-raised pull-right" href="/schedulesave">--}}
            {{--<i class="fa fa-facebook-official" aria-hidden="true"></i> Share to Facebook</a>--}}
        <a id="save-schedule" class="btn btn-xs btn-raised pull-right">
            <i class="fa fa-link" aria-hidden="true"></i> Save schedule </a>
        {{--<a class="btn btn-xs btn-raised pull-right" href="#">--}}
            {{--<i class="fa fa-file-image-o"></i> Save as Image</a>--}}
    </div>
    <div id="schedule" class="panel-body panel-options">
        <div id="calendar"></div>
    </div>
</div>