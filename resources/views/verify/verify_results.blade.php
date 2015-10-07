@extends('app')

@section('title')
    loop.tf - Results
@stop


@section('content')
    <h1 class="page-heading">Results</h1>
    <div class="container half-page">
        <h3>Number of unrostered players:
            @if($unrosteredNumber == 0)
                <span class="label label-success">{{ $unrosteredNumber }}</span>
            @elseif($unrosteredNumber > 0 && $unrosteredNumber < 3)
                <span class="label label-warning">{{ $unrosteredNumber }}</span>
            @else
                <span class="label label-danger">{{ $unrosteredNumber }}</span>
            @endif
        </h3>

        @if($unrostered)
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Player Ranking</th>
                    <th>UGC Profile</th>
                </tr>
                </thead>
                <tbody>
                    <?php $index = 1; ?>
                    @foreach($unrostered as $name => $profile)
                        <tr>
                            <td>
                                <?= $index; $index++; ?>
                            </td>
                            <td>
                                <a
                                    href="{{ $profile }}">{{ $name }}
                                </a>
                            </td>
                            <td>
                                <a
                                    href="{{ $unrosteredRanks[$name] }}">
                                    <img
                                        src={{ asset('/logos/tpr_abbv.png') }}
                                        height="15"
                                        width="30"
                                    >
                                </a>
                            </td>
                            <td>
                                <a
                                    href="{{ $unrosteredUGC[$name] }}">
                                    <img
                                        src={{ asset('/logos/ugc.png') }}
                                        height="15"
                                        width="30"
                                    >
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <h3>Team <a href="{{ $ourRosterURL }}">{{ $ourRosterName }}</a> has

            @if($ourRosterSize == 9)
                <span class="label label-success">{{ $ourRosterSize }}</span>
            @else
                <span class="label label-warning">{{ $ourRosterSize }}</span>
            @endif

            players
        </h3>

        @if($ourTeamProfile)
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Player Rankings</th>
                    <th>UGC Profile</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 1; ?>
                @foreach($ourTeamProfile as $name => $profile)
                    <tr>
                        <td>
                            <?= $index; $index++; ?>
                        </td>
                        <td>
                            <a
                                href="{{ $profile }}">{{ $name }}
                            </a>
                        </td>
                        <td>
                            <a
                                href="{{ $ourTeamRanks[$name] }}">
                                <img
                                    src={{ asset('/logos/tpr_abbv.png') }}
                                    height="15"
                                    width="30"
                                >
                            </a>
                        </td>
                        <td>
                            <a
                                href="{{ $ourTeamUGC[$name] }}">
                                <img
                                    src={{ asset('/logos/ugc.png') }}
                                    height="15"
                                    width="30"
                                >
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        <h3>Team <a href="{{ $theirRosterURL }}">{{ $theirRosterName }}</a> has

            @if($theirRosterSize == 9)
                <span class="label label-success">{{ $theirRosterSize }}</span>
            @else
                <span class="label label-warning">{{ $theirRosterSize }}</span>
            @endif
            players
        </h3>

        @if($theirTeamProfile)
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Player Rankings</th>
                    <th>UGC Profile</th>
                </tr>
                </thead>
                <tbody>
                <?php $index = 1; ?>
                @foreach($theirTeamProfile as $name => $profile)
                    <tr>
                        <td>
                            <?= $index; $index++; ?>
                        </td>
                        <td>
                            <a
                                href="{{ $profile }}">{{ $name }}
                            </a>
                        </td>
                        <td>
                            <a
                                href="{{ $theirTeamRanks[$name] }}">
                                <img
                                    src={{ asset('/logos/tpr_abbv.png') }}
                                    height="15"
                                    width="30"
                                >
                            </a>
                        </td>
                        <td>
                            <a
                                href="{{ $theirTeamUGC[$name] }}">
                                <img
                                    src={{ asset('/logos/ugc.png') }}
                                    height="15"
                                    width="30"
                                >
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

    </div>
    @include('errors.list')
@stop