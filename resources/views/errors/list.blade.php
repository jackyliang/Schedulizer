@if ($errors->any())
    <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }} {{ "\n" }}
        @endforeach
    </ul>
@endif