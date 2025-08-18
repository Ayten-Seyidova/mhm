<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="utf-8">
    <title>MHM Kursu – İmtahan Nəticəsi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: "DejaVu Sans", Arial, Helvetica, sans-serif;
            color: #0f172a;
            line-height: 1.45;
            background: #f5f7fb;
        }

        .wrap {
            max-width: 720px;
            margin: 0 auto;
        }

        .card {
            position: relative;
            background: #fff;
            border: 1px solid #e6e9f2;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(22, 34, 51, .06);
            overflow: hidden;
        }

        .bar {
            height: 10px;
            background: linear-gradient(90deg, #10b981, #22c55e, #84cc16);
        }

        .ribbon {
            position: absolute;
            top: 14px;
            left: -48px;
            width: 200px;
            text-align: center;
            transform: rotate(-45deg);
            background: #0ea5e9;
            color: #fff;
            font-weight: 800;
            font-size: 12px;
            letter-spacing: .6px;
            padding: 8px 0;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .12);
            border: 1px solid rgba(255, 255, 255, .35);
        }

        .header {
            text-align: center;
            padding: 28px 24px 8px;
        }

        .icon {
            display: inline-block;
            width: 64px;
            height: 64px;
            margin-bottom: 8px;
        }

        .title {
            font-size: 20px;
            font-weight: 700;
            margin: 6px 0 2px;
        }

        .subtitle {
            font-size: 14px;
            color: #64748b;
            margin: 0 0 6px;
        }

        .course-tag {
            display: inline-block;
            margin-top: 6px;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: .4px;
            color: #0c4a6e;
            background: #e0f2fe;
            border: 1px solid #bae6fd;
            padding: 4px 10px;
            border-radius: 999px;
        }

        .lead {
            font-size: 16px;
            margin: 12px auto 0;
            max-width: 560px;
        }

        .lead strong {
            color: #0f172a;
        }

        .badge {
            display: inline-block;
            margin-top: 10px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .4px;
            color: #065f46;
            background: #d1fae5;
            border: 1px solid #a7f3d0;
            padding: 4px 8px;
            border-radius: 999px;
        }

        .content {
            padding: 22px 24px 26px;
        }

        .row {
            text-align: center;
            margin-top: 8px;
        }

        .score-card {
            display: inline-block;
            vertical-align: top;
            margin: 6px;
            padding: 18px 22px;
            background: #fafafa;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            width: 280px;
        }

        .score-ring {
            display: block;
            margin: 0 auto 10px;
        }

        .score-label {
            font-size: 13px;
            color: #64748b;
            margin-top: 6px;
            letter-spacing: .3px;
        }

        .score-value {
            font-size: 28px;
            font-weight: 800;
            margin-top: 4px;
            color: #065f46;
        }

        .mini {
            display: inline-block;
            vertical-align: top;
            margin: 6px;
            padding: 14px 16px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            width: 180px;
            text-align: left;
        }

        .mini .mini-top {
            display: table;
            width: 100%;
        }

        .mini .mini-icon, .mini .mini-text {
            display: table-cell;
            vertical-align: middle;
        }

        .mini .mini-icon {
            width: 28px;
        }

        .mini-title {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
            letter-spacing: .2px;
        }

        .mini-value {
            font-size: 18px;
            font-weight: 700;
            margin-top: 6px;
            color: #111827;
        }

        .mini.time {
            background: #f0f9ff;
            border-color: #bae6fd;
        }

        .mini.ok {
            background: #ecfdf5;
            border-color: #a7f3d0;
        }

        .mini.wrong {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .footer {
            padding: 14px 24px 22px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            border-top: 1px solid #eef2f7;
        }

        a {
            color: #0ea5e9;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="bar"></div>
        <div class="ribbon">MHM KURSU</div>
        <div class="header">
            <span class="icon" aria-hidden="true"></span>
            <div class="title">İmtahan nəticəsi</div>
            <div class="subtitle">Sınaq performans xülasəsi</div>
            <div class="course-tag">MHM Kursu – Nəticə Səhifəsi</div>
            <div class="lead">
                <strong>{{ $name }}</strong>, <strong>{{ $exam }}</strong> adlı sınaqdan aşağıdakı nəticəni əldə etdi.
            </div>
            <div class="badge">{{ $status }}</div>
        </div>
        <div class="content">
            <div class="row">
                <div class="score-card">
                    <div class="score-label">Qazanılan bal</div>
                    <div class="score-value">{{ $score }}</div>
                </div>
                <div class="mini time">
                    <div class="mini-top">
                        <div class="mini-text">
                            <div class="mini-title">Müddət</div>
                            <div class="mini-value">{{ $duration }}</div>
                        </div>
                    </div>
                </div>
                <div class="mini ok">
                    <div class="mini-top">
                        <div class="mini-text">
                            <div class="mini-title">Düzgün cavab</div>
                            <div class="mini-value">{{ $correct }}</div>
                        </div>
                    </div>
                </div>
                <div class="mini wrong">
                    <div class="mini-top">
                        <div class="mini-text">
                            <div class="mini-title">Səhv cavab</div>
                            <div class="mini-value">{{ $wrong }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            Bu sənəd MHM kursunun nəticə səhifəsi üçün sistem tərəfindən avtomatik yaradılmışdır.
        </div>
    </div>
</div>
</body>
</html>
