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
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>
<body>
<div class="video-container">
    <iframe
        src="https://www.youtube.com/embed/{{ request()->uid }}"
        allowfullscreen
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share">
    </iframe>
</div>
</body>
</html>
