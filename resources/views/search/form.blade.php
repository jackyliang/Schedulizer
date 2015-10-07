{!! Form::open([
    'action' => ['SchedulizerController@results'],
    'method' => 'GET',
    'class' => 'navbar-form',
    'role' => 'search',
    'id' => 'formID'
]) !!}
        <div class="input-group">
            {!! Form::text('q', $term, [
                'class' => 'form-control',
                'id' =>  'q',
                'placeholder' =>  'i.e. ECE 201, Digital Logic, Kandasamy, or 10121'
            ]) !!}
        <span class="input-group-btn">
            <button type="submit" class="btn btn-material-teal">
                <span class="glyphicon glyphicon-search"></span>
            </button>
        </span>
    </div>
{!! Form::close() !!}

@include('js.classes-autocomplete')
@include('js.select-all')
