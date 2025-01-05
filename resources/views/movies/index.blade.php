@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Movies</h2>
    <div class="row">
        @foreach($movies as $movie)
            <div class="col-md-3 mb-4">
                <div class="card">
                    @if($movie['stream_icon'])
                        <img src="{{ $movie['stream_icon'] }}" class="card-img-top" alt="{{ $movie['name'] }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $movie['name'] }}</h5>
                        <p class="card-text">
                            @if($movie['rating']) Rating: {{ $movie['rating'] }} @endif
                        </p>
                        <a href="{{ session('xtream_dns') }}/movie/{{ session('xtream_username') }}/{{ session('xtream_password') }}/{{ $movie['stream_id'] }}.{{ $movie['container_extension'] }}" 
                           class="btn btn-primary watch-btn"
                           data-stream-url="{{ session('xtream_dns') }}/movie/{{ session('xtream_username') }}/{{ session('xtream_password') }}/{{ $movie['stream_id'] }}.{{ $movie['container_extension'] }}">
                            Watch Now
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
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