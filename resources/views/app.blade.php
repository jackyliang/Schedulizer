<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>

    @include('libs.libs')
    @include('analytics.google')
    @include('analytics.sumome')

</head>
<body>
    @include('partials.nav')

    <div class="container">
	    @yield('content')
    </div>

    <div class="panel-footer navbar-bottom">
        @include('partials.footer')
    </div>
</body>
</html>

@include('js.libs')
