<div class="panel panel-default">
    <div class="panel-heading">
        <h3
            class="panel-title">Times You Don't Want Classes
        </h3>
    </div>
    <div class="panel-body panel-options">
        <div id="limit" class="checkbox">
            <label>
                <input data-date="M" type="checkbox"> Mon
            </label>
            <label>
                <input data-date="T" type="checkbox"> Tues
            </label>
            <label>
                <input data-date="W" type="checkbox"> Wed
            </label>
            <label>
                <input data-date="R" type="checkbox"> Thurs
            </label>
            <label>
                <input data-date="F" type="checkbox"> Fri
            </label>
        </div>
        <div class="btn-group">
            <a id="from-text" class="btn btn-default">10:00 AM</a>
            <a
                class="btn btn-default dropdown-toggle"
                data-toggle="dropdown">
                <span
                    class="caret"
                >
                </span>
            </a>
            <ul id="from" class="dropdown-menu time-span">
                @foreach ($timeIncrements as $military => $standard)
                    <li><a data-military="{{ $military }}">{{ $standard }}</a></li>
                @endforeach
            </ul>
        </div>
        to
        <div class="btn-group">
            <a id="to-text" class="btn btn-default">12:00 PM</a>
            <a
                class="btn btn-default dropdown-toggle"
                data-toggle="dropdown">
                <span
                    class="caret"
                >
                </span>
            </a>
            <ul id="to" class="dropdown-menu time-span">
                @foreach ($timeIncrements as $military => $standard)
                    <li><a data-military="{{ $military }}">{{ $standard }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

