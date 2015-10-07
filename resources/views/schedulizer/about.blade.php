@extends('app_sched')

@section('title')
    About
@stop

@section('content')
    <div class="jumbotron">
        <h1>Drexel Schedulizer</h1>
        <p class="lead">Made by Drexel students for Drexel students, the Drexel Schedulizer allows you to find classes and generate the perfect schedule.</p>
        <p><a class="btn btn-lg btn-success" href="{{ URL('schedulizer/search') }}" role="button">Search for classes now!</a></p>
    </div>

    <div class="row featurette">
        <div class="col-md-7">
            <h2 class="featurette-heading">We know you're busy. <span class="text-muted">Find the perfect schedule in under a minute.</span></h2>
            <p class="lead">With advanced filtering capabilities, custom time slots, and RateMyProfessor integration, we guarantee you'll find the perfect schedule in under a minute. </p>
        </div>
        <div class="col-md-5">
            <img class="featurette-image img-responsive center-block" src="{{ URL('images/1.jpg') }}" alt="Generic placeholder image">
        </div>
    </div>


    <div class="row featurette">
        <div class="col-md-7 col-md-push-5">
            <h2 class="featurette-heading">It's accurate. <span class="text-muted">We provide only the most accurate class information for you.</span></h2>
            <p class="lead">"Made by Drexel students for Drexel students" isn't a cheesy tag line. Well, maybe. But we know how fast classes fill up during registration time, so Schedulizer only gives you the most up-to-date information, synced every half an hour.</p>
        </div>
        <div class="col-md-5 col-md-pull-7">
            <img class="featurette-image img-responsive center-block" src="{{ URL('images/2.jpg') }}"  alt="Generic placeholder image">
        </div>
    </div>


    <div class="row featurette">
        <div class="col-md-7">
            <h2 class="featurette-heading">It's beautiful. <span class="text-muted">Good bye Drexel Term Master Schedule.</span></h2>
            <p class="lead">Say goodbye to the dreadful feeling of looking at the Drexel Term Master Schedule. With advanced search by professor name, CRN, subject code, and course name, spend less time making the perfect schedule and more time living. Did you know you can also bookmark your favorite classes? Take that TMS.</p>
        </div>
        <div class="col-md-5">
            <img class="featurette-image img-responsive center-block" src="{{ URL('images/3.jpg') }}" alt="Generic placeholder image">
        </div>
    </div>


@stop

