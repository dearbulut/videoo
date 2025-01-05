@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            @if($seriesInfo['info']['cover'])
                <img src="{{ $seriesInfo['info']['cover'] }}" class="img-fluid" alt="{{ $seriesInfo['info']['name'] }}">
            @endif
        </div>
        <div class="col-md-8">
            <h2>{{ $seriesInfo['info']['name'] }}</h2>
            <p>{{ $seriesInfo['info']['plot'] }}</p>
            <p>
                <strong>Rating:</strong> {{ $seriesInfo['info']['rating'] }}<br>
                <strong>Genre:</strong> {{ $seriesInfo['info']['genre'] }}<br>
                <strong>Director:</strong> {{ $seriesInfo['info']['director'] }}<br>
                <strong>Cast:</strong> {{ $seriesInfo['info']['cast'] }}
            </p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h3>Episodes</h3>
            <div class="accordion" id="seasonsAccordion">
                @foreach($seriesInfo['episodes'] as $seasonNum => $episodes)
                    <div class="card">
                        <div class="card-header" id="heading{{ $seasonNum }}">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" 
                                        data-target="#collapse{{ $seasonNum }}">
                                    Season {{ $seasonNum }}
                                </button>
                            </h2>
                        </div>

                        <div id="collapse{{ $seasonNum }}" class="collapse" 
                             data-parent="#seasonsAccordion">
                            <div class="card-body">
                                <div class="row">
                                    @foreach($episodes as $episode)
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                @if($episode['info']['movie_image'])
                                                    <img src="{{ $episode['info']['movie_image'] }}" 
                                                         class="card-img-top" 
                                                         alt="Episode {{ $episode['episode_num'] }}">
                                                @endif
                                                <div class="card-body">
                                                    <h5 class="card-title">Episode {{ $episode['episode_num'] }}</h5>
                                                    <p class="card-text">{{ $episode['title'] }}</p>
                                                    <a href="{{ session('xtream_dns') }}/series/{{ session('xtream_username') }}/{{ session('xtream_password') }}/{{ $episode['id'] }}.{{ $episode['container_extension'] }}" 
                                                       class="btn btn-primary watch-btn"
                                                       data-stream-url="{{ session('xtream_dns') }}/series/{{ session('xtream_username') }}/{{ session('xtream_password') }}/{{ $episode['id'] }}.{{ $episode['container_extension'] }}">
                                                        Watch Now
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const watchButtons = document.querySelectorAll('.watch-btn');
    
    watchButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const streamUrl = this.dataset.streamUrl;
            
            // Create modal for video player
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <video id="video" controls style="width: 100%;"></video>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            $(modal).modal('show');
            
            const video = modal.querySelector('#video');
            video.src = streamUrl;
            video.play();
            
            $(modal).on('hidden.bs.modal', function() {
                video.pause();
                video.src = "";
                modal.remove();
            });
        });
    });
});
</script>
@endpush