<nav class="navbar navbar-default shadow-z-1">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a
                class="navbar-brand" href="{{ url('/') }}"
            >
                <span class="glyphicon glyphicon-education"></span>
                Schedulizer
                <em><small><small>beta</small></small></em></a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li
                    class="{{ Request::is('search') ? 'active' : '' }}"
                >
                    <a
                        href="{{ url('/search') }}"
                    >
                        <span class="glyphicon glyphicon-search"></span>
                        Search
                    </a>
                </li>
                @if(Session::has('q'))
                    <li
                        class="{{ Request::is('results') ? 'active' : '' }}"
                    >
                        <a
                            href="{{ url('/results') }}?q={{ Session::get('q') }}"
                        >
                            <span class="glyphicon glyphicon-align-justify"></span>
                            Results
                        </a>
                    </li>
                @endif
                <li
                    class="{{ Request::is('schedule') ? 'active' : '' }}"
                >
                    <a
                        href="{{ url('/schedule') }}"
                    >
                    <span class="glyphicon glyphicon-calendar"></span>
                    Schedule
                        <li
                            id="jewel"
                            class="jewel"
                        >
                        </li>
                    </a>
                </li>
            </ul>

            {{-- Show search bar if we're in the results page --}}
            <ul class="nav navbar-nav navbar-right">
                @if(Request::is('results') || Request::is('schedule'))
                    @include('search.form')
                @endif
            </ul>
        </div>

    </div>

</nav>