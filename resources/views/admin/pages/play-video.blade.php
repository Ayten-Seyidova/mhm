<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('admin/images/logo.png')}}">
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css"/>
    <title>Video İzləmə</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            background-color: #000;
            height: 100%;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-container {
            width: 100%;
            max-width: 1000px;
            aspect-ratio: 16 / 9;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
<div class="video-container" id="videoContainer">
    <div class="plyr__video-embed" id="player">
        <iframe
            id="youtubeIframe"
            src="https://www.youtube.com/embed/{{ request()->uid }}?autoplay=1&loop=1&playlist={{ request()->uid }}&rel=0&modestbranding=1&controls=1"
            allowfullscreen
            allowtransparency
            allow="autoplay"
        ></iframe>
    </div>
</div>
<button id="fullscreenBtn" style="
    position: absolute;
    top: 20px;
    left: 20px;
    padding: 10px 20px;
    font-size: 16px;
    z-index: 9999;
    cursor: pointer;
">Full ekran
</button>

<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
<script>
    const player = new Plyr('#player', {
        youtube: {
            noCookie: true,
            modestbranding: 1,
            rel: 0,
            showinfo: 0
        },
        controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
    });

    document.getElementById('fullscreenBtn').addEventListener('click', function () {
        const container = document.getElementById('videoContainer');
        if (container.requestFullscreen) {
            container.requestFullscreen();
        } else if (container.webkitRequestFullscreen) {
            container.webkitRequestFullscreen();
        } else if (container.msRequestFullscreen) {
            container.msRequestFullscreen();
        }
    });
</script>
</body>
</html>
