@extends('layouts.app')

@section('content')
<div class="container">
    <h2>TV Series</h2>
    <div class="row">
        @foreach($series as $show)
            <div class="col-md-3 mb-4">
                <div class="card">
                    @if($show['cover'])
                        <img src="{{ $show['cover'] }}" class="card-img-top" alt="{{ $show['name'] }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $show['name'] }}</h5>
                        <p class="card-text">
                            @if($show['rating']) Rating: {{ $show['rating'] }} @endif
                            <br>
                            @if($show['episode_run_time']) Duration: {{ $show['episode_run_time'] }} min @endif
                        </p>
                        <a href="{{ route('series.info', $show['series_id']) }}" class="btn btn-primary">
                            View Episodes
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection