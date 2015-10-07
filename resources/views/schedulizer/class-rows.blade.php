<td><a
        href="{{ URL('schedulizer/results?q=' . $class->subject_code . ' ' . $class->course_no) }}"

        {{-- TODO: Refactor this duplicate if-statement --}}
        @if(
            $class->enroll === $class->max_enroll ||
            $class->enroll === "FULL"
        )
            class="moreMuted"
        @endif
    >
        {{ $class->subject_code . ' ' . $class->course_no}}
    </a>
</td>
<td> {{ $class->section}} </td>
<td> {{ $class->crn}} </td>
<td> {{ $class->day}} </td>
<td> {{ $class->time}} </td>
<td>
    <?php $name = explode(' ', $class->instructor); ?>
    @if($class->instructor != "STAFF")
    <a
        href=
        "{{ 'http://www.ratemyprofessors.com/search.jsp?' .
            'queryBy=teacherName&' .
            'queryoption=HEADER&' .
            'query=' . end($name) . '&' .
            'facetSearch=true&' .
            'schoolName=drexel+university'
        }}"
        target="_blank"
        {{-- TODO: Refactor this duplicate if-statement --}}
        @if(
            $class->enroll === $class->max_enroll ||
            $class->enroll === "FULL"
        )
            class="moreMuted"
        @endif
    >
        <span class="glyphicon glyphicon-education"></span>
        {{ $class->instructor}}
    </a>
    @else
        {{ $class->instructor}}
    @endif
</td>
<td> {{ $class->enroll}} </td>
<td> {{ $class->max_enroll}} </td>
<td> {{ $class->credits}} </td>
<tr>
    <td colspan="10" class="hiddenRow">

        <div id="class{{ $i }}" class="collapse">

        <div class="col-md-4">
            <h5>Description</h5>
            <p>{{ $class->description }}</p>
        </div>

        <div class="col-md-2">
            <h5>Campus</h5>
            <p>{{ $class->campus }}</p>
        </div>

        <div class="col-md-2">
            <h5>Building</h5>
            <p>{{ $class->building . ' ' . $class->room }}</p>
        </div>

        <div class="col-md-2">
            <h5>Type</h5>
            <p>{{ $class->instr_method }}</p>
        </div>

        <div class="col-md-2">
            <h5>Pre-reqs</h5>
            <p>{{ $class->pre_reqs }}</p>
        </div>
    </td>
</tr>
