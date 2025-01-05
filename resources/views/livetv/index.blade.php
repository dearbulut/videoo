@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Live TV Channels</h2>
    <div class="row">
        @foreach($channels as $channel)
            <div class="col-md-3 mb-4">
                <div class="card">
                    @if($channel['stream_icon'])
                        <img src="{{ $channel['stream_icon'] }}" class="card-img-top" alt="{{ $channel['name'] }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $channel['name'] }}</h5>
                        <a href="{{ session('xtream_dns') }}/live/{{ session('xtream_username') }}/{{ session('xtream_password') }}/{{ $channel['stream_id'] }}.m3u8" 
                           class="btn btn-primary watch-btn" 
                           data-stream-url="{{ session('xtream_dns') }}/live/{{ session('xtream_username') }}/{{ session('xtream_password') }}/{{ $channel['stream_id'] }}.m3u8">
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
            
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(streamUrl);
                hls.attachMedia(video);
                hls.on(Hls.Events.MANIFEST_PARSED, function() {
                    video.play();
                });
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = streamUrl;
                video.addEventListener('loadedmetadata', function() {
                    video.play();
                });
            }
            
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