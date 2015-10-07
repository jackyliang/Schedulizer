@extends('app_sched')

@section('title')
    Results - {{ $term }}
@stop

@section('content')
    <div class="page-heading-results">
        <h1>Results</h1>
        <p
            class="text-muted">
            About {{ $classCount }} {{$classCount === 1 ? ' result' : ' results'}} last updated {{ $lastUpdated }}

        </p>
    </div>

{{--    @include('schedulizer.cart-fixed-button')--}}

    <div class="container table-responsive">
    @if($classesByLabelAndType)
        <?php $i = 1; ?>
        @foreach ($classesByLabelAndType as $label => $classesByType)
            <div class="panel panel-primary">
                <h3
                    class="margin-class-name panel-heading"
                >
                    {{ $label }}
                </h3>
                @foreach ($classesByType as $type => $classes)
                    <div
                        class="margin-class-type panel-body"
                    >
                        <h4>{{ $type }}
                            <a
                                data-class-name="{{ $label }} {{ $type}}"
                                data-action="add"
                                class="btn btn-material-yellow-600 btn-xs
                                btn-font-12-px btn-raised margin-add-to-cart
                                mdi-content-add-circle-outline">
                                Add This!
                            </a>
                        </h4>
                        <table
                            class="table table-striped table-hover"
                        >
                            <th>Class</th>
                            <th>Section</th>
                            <th>CRN</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Professor</th>
                            <th>Enrolled</th>
                            <th>Max Enroll</th>
                            <th>Credits</th>
                            @foreach ($classes as $class)
                                {{-- Mute the text if class is full --}}
                                @if(
                                    $class->enroll === $class->max_enroll ||
                                    $class->enroll === "FULL"
                                )
                                    <tr
                                        data-toggle="collapse"
                                        data-target="#class{{ $i }}"
                                        class="accordion-toggle moreMuted"
                                    >
                                        @include('schedulizer.class-rows')
                                    </tr>
                                @else
                                    <tr
                                        data-toggle="collapse"
                                        data-target="#class{{ $i }}"
                                        class="accordian-toggle info"
                                    >
                                        @include('schedulizer.class-rows')
                                    </tr>
                                @endif
                                <?php $i++; ?>
                            @endforeach
                        </table>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
    </div>
@stop

