@extends('app_sched')

@section('title')
    Search
@stop

@section('content')

    <div class="page-heading">
        <h1>Search for Winter 2015 Classes</h1>
    </div>

    <form
        method="GET"
        action="{{ URL('results') }}"
        accept-charset="UTF-8"
        class="form-inline global-search" role="form">
        <div class="form-group">
            <input
                type="search"
                class="form-control floating-label"
                id="q"
                name="q"
                placeholder="i.e. ECE 200, Digital Logic, Kandasamy, or 22584"
            >
        </div>
        <button type="submit" class="btn btn-material-teal shadow-z-1">
            <span class="glyphicon glyphicon-search"></span>
        </button>
    </form>

    @include('errors.list')
    @include('js.classes-autocomplete')
    @include('js.select-all')
    @include('js.header-padding')

@stop

