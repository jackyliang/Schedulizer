@extends('app')

@section('title')
    Search
@stop

@section('content')

    <div class="page-heading">
        <h1>Search for a Stock Ticker</h1>
    </div>

    <form
        method="GET"
        action="{{ URL('groupthink') }}"
        accept-charset="UTF-8"
        class="form-inline global-search" role="form">
        <div class="form-group">
            <input
                type="search"
                class="form-control floating-label"
                id="q"
                name="q"
                placeholder="i.e. AAPL, BABA, SPX"
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

