{{--<!DOCTYPE html>--}}
{{--<html lang="az">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('admin/images/logo.png')}}">--}}
{{--    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />--}}
{{--    <title>Video İzləmə</title>--}}
{{--    <style>--}}
{{--        html, body {--}}
{{--            margin: 0;--}}
{{--            padding: 0;--}}
{{--            background-color: #000;--}}
{{--            height: 100%;--}}
{{--            width: 100%;--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--        }--}}
{{--        .video-container {--}}
{{--            width: 100%;--}}
{{--            max-width: 1000px;--}}
{{--            aspect-ratio: 16 / 9;--}}
{{--        }--}}
{{--        iframe {--}}
{{--            width: 100%;--}}
{{--            height: 100%;--}}
{{--            border: none;--}}
{{--        }--}}
{{--        .ytp-button {--}}
{{--            display: none !important;--}}
{{--            visibility: hidden !important;--}}
{{--        }--}}
{{--        .fullscreen-action-menu {--}}
{{--            display: none !important;--}}
{{--            visibility: hidden !important;--}}
{{--        }--}}
{{--    </style>--}}
{{--</head>--}}
{{--<body>--}}
{{--<div class="video-container">--}}
{{--    <div class="plyr__video-embed" id="player">--}}
{{--        <iframe--}}
{{--            src="https://www.youtube.com/embed/{{ request()->uid }}?loop=1&playlist={{ request()->uid }}&rel=0&modestbranding=1&controls=1"--}}
{{--            allowfullscreen--}}
{{--            allowtransparency--}}
{{--            allow="autoplay"--}}
{{--        ></iframe>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>--}}
{{--<script>--}}
{{--    const player = new Plyr('#player', {--}}
{{--        youtube: {--}}
{{--            noCookie: true,--}}
{{--            modestbranding: 1,--}}
{{--            rel: 0,--}}
{{--            showinfo: 0--}}
{{--        },--}}
{{--        controls: ['play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],--}}
{{--    });--}}
{{--</script>--}}
{{--</body>--}}
{{--</html>--}}
    <!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video İzləmə</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .video-container {
            width: 90%;
            max-width: 1000px;
            aspect-ratio: 16 / 9;
            background-color: #000;
            position: relative;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .hidden-div {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 6%;
            height: 10%;
            background: gray;
            z-index: 99999999;
        }
    </style>
</head>
<body>
<div class="video-container">
    {{--    <iframe--}}
    {{--        src="https://www.youtube.com/embed/{{ request()->uid }}?controls=0"--}}
    {{--        allowfullscreen--}}
    {{--        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share">--}}
    {{--    </iframe>--}}
    <div class="hidden-div"></div>
    <iframe
        src="https://www.youtube.com/embed/{{ request()->uid }}?rel=0&modestbranding=1&showinfo=0"
        allowfullscreen
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
    </iframe>

    {{--    <iframe--}}
    {{--        width="560"--}}
    {{--        height="315"--}}
    {{--        src="https://www.youtube.com/embed/YWZAP7bUBzs?controls=0&modestbranding=1&rel=0&autoplay=1&mute=1"--}}
    {{--        frameborder="0"--}}
    {{--        allow="autoplay; encrypted-media"--}}
    {{--        allowfullscreen>--}}
    {{--    </iframe>--}}
</div>
</body>
</html>

