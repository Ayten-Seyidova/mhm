@php
    $v = fn ($path) => asset($path) . '?v=' . @filemtime(public_path($path));
    $shot = fn ($slug) => asset("assets/site/img/screens/{$slug}-360.webp") . ' 360w, ' . asset("assets/site/img/screens/{$slug}-720.webp") . ' 720w';
    $kurs = [
        'ios' => 'https://apps.apple.com/az/app/mhm-kurs/id6751434928',
        'android' => 'https://play.google.com/store/apps/details?id=com.app.mhm.kurd',
    ];
    $qonaq = [
        'ios' => 'https://apps.apple.com/az/app/mhm-qonaq/id6737334423',
        'android' => 'https://play.google.com/store/apps/details?id=com.app.mhm',
    ];
    $gallery = [
        ['home', 'Ana səhifə', 'Qruplarınız və yeniliklər bir baxışda'],
        ['group', 'Qrup menyusu', 'Video kurslar, dərs yazıları, quizlər və PDF-lər'],
        ['lessons', 'Mövzu səhifəsi', 'Mövzunun videoları və rəylər'],
        ['player', 'Video pleyer', 'Qaldığınız yerdən davam, ±10 saniyə'],
        ['live', 'Dərs yazıları', 'Canlı dərslərin yazıları və tapşırıq izahları'],
        ['quizzes', 'Quizlər', 'Müddəti və sual sayı göstərilən sınaqlar'],
        ['quiz', 'Sual ekranı', 'Taymer, sual xəritəsi və cavab variantları'],
        ['result', 'Quiz nəticəsi', 'Düzgün, səhv, cavabsız və müddət'],
        ['result-detail', 'Sual-sual analiz', 'Sizin və düzgün cavab yan-yana'],
        ['results', 'Nəticələrim', 'Bütün sınaqlarınızın tarixçəsi'],
        ['pdf', 'PDF materiallar', 'Dərs vəsaitləri axtarışla'],
        ['notifications', 'Bildirişlər', 'Yeni dərs və quizlərdən xəbərdar olun'],
        ['mhm-results', 'MHM və nəticələri', 'Tələbələrimizin uğurları'],
        ['support', 'Dəstək', 'WhatsApp, Instagram və Facebook'],
    ];
@endphp
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MHM Tədris Mərkəzi — Onlayn MİQ, Sertifikasiya və Abituriyent hazırlığı</title>
    <meta name="description" content="MHM Kurs tətbiqi: video dərslər, canlı dərslərin yazıları, real imtahan formatında quizlər və sual-sual izahlı nəticələr. MİQ, Sertifikasiya və Abituriyent hazırlığı bir tətbiqdə.">
    <link rel="canonical" href="https://mhmapp.az/">
    <meta name="theme-color" content="#f2a711">
    <meta name="apple-itunes-app" content="app-id=6751434928">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="az_AZ">
    <meta property="og:site_name" content="MHM Tədris Mərkəzi">
    <meta property="og:url" content="https://mhmapp.az/">
    <meta property="og:title" content="MHM — MİQ, Sertifikasiya və Abituriyent hazırlığı bir tətbiqdə">
    <meta property="og:description" content="Video dərslər, real formatda quizlər və izahlı nəticələr — MHM Kurs tətbiqində.">
    <meta property="og:image" content="{{ asset('assets/site/img/og.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="{{ asset('assets/site/favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('assets/site/favicon-32.png') }}" sizes="32x32" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('assets/site/apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="{{ $v('assets/site/site.css') }}">
    <link rel="preload" as="image" href="{{ asset('assets/site/img/screens/home-720.webp') }}" type="image/webp">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "MHM Tədris Mərkəzi",
        "url": "https://mhmapp.az/",
        "logo": "{{ asset('assets/site/icon-512.png') }}",
        "email": "info@mhmapp.az",
        "telephone": "+994773227953",
        "address": {"@type": "PostalAddress", "streetAddress": "Elmlər Akademiyası m/st", "addressLocality": "Bakı", "addressCountry": "AZ"},
        "sameAs": [
            "https://www.instagram.com/mhm_tedris_merkezi/",
            "https://www.facebook.com/MHM.tedris.merkezi/",
            "https://www.youtube.com/channel/UCT18265mJDfk4t2-4Sqc_GA"
        ]
    }
    </script>
</head>
<body>
<svg xmlns="http://www.w3.org/2000/svg" style="display:none">
    <symbol id="mark" viewBox="0 0 1149 819"><path d="M0 0 246 328v164L0 819z"/><path d="M83 0h368l123.5 164L697 0h368L574.5 656z"/><path d="M1149 0 903 328v491l246-327z"/></symbol>
    <symbol id="i-apple" viewBox="0 0 24 24"><path d="M12.152 6.896c-.948 0-2.415-1.078-3.96-1.04-2.04.027-3.91 1.183-4.961 3.014-2.117 3.675-.546 9.103 1.519 12.09 1.013 1.454 2.208 3.09 3.792 3.039 1.52-.065 2.09-.987 3.935-.987 1.831 0 2.35.987 3.96.948 1.637-.026 2.676-1.48 3.676-2.948 1.156-1.688 1.636-3.325 1.662-3.415-.039-.013-3.182-1.221-3.22-4.857-.026-3.04 2.48-4.494 2.597-4.559-1.429-2.09-3.623-2.324-4.39-2.376-2-.156-3.675 1.09-4.61 1.09zM15.53 3.83c.843-1.012 1.4-2.427 1.245-3.83-1.207.052-2.662.805-3.532 1.818-.78.896-1.454 2.338-1.273 3.714 1.338.104 2.715-.688 3.559-1.701"/></symbol>
    <symbol id="i-play" viewBox="0 0 24 24"><path d="M22.018 13.298l-3.919 2.218-3.515-3.493 3.543-3.521 3.891 2.202a1.49 1.49 0 0 1 0 2.594zM1.337.924a1.486 1.486 0 0 0-.112.568v21.017c0 .217.045.419.124.6l11.155-11.087L1.337.924zm12.207 10.065l3.258-3.238L3.45.195a1.466 1.466 0 0 0-.946-.179l11.04 10.973zm0 2.067l-11 10.933c.298.036.612-.016.906-.183l13.324-7.54-3.23-3.21z"/></symbol>
    <symbol id="i-video" viewBox="0 0 24 24"><path d="m22 8-6 4 6 4V8Z"/><rect width="14" height="12" x="2" y="6" rx="2"/></symbol>
    <symbol id="i-live" viewBox="0 0 24 24"><circle cx="12" cy="12" r="2"/><path d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14"/></symbol>
    <symbol id="i-quiz" viewBox="0 0 24 24"><path d="m3 17 2 2 4-4"/><path d="m3 7 2 2 4-4"/><path d="M13 6h8"/><path d="M13 12h8"/><path d="M13 18h8"/></symbol>
    <symbol id="i-chart" viewBox="0 0 24 24"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></symbol>
    <symbol id="i-file" viewBox="0 0 24 24"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></symbol>
    <symbol id="i-bell" viewBox="0 0 24 24"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></symbol>
    <symbol id="i-check-circle" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></symbol>
    <symbol id="i-resume" viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="m10 9 5 3-5 3z"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></symbol>
    <symbol id="i-left" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></symbol>
    <symbol id="i-right" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></symbol>
    <symbol id="i-down" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></symbol>
    <symbol id="i-menu" viewBox="0 0 24 24"><path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h16"/></symbol>
    <symbol id="i-close" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></symbol>
    <symbol id="i-phone" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.070 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></symbol>
    <symbol id="i-mail" viewBox="0 0 24 24"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></symbol>
    <symbol id="i-pin" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></symbol>
    <symbol id="i-chat" viewBox="0 0 24 24"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></symbol>
    <symbol id="i-instagram" viewBox="0 0 24 24"><rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><path d="M17.5 6.5h.01"/></symbol>
    <symbol id="i-facebook" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></symbol>
    <symbol id="i-youtube" viewBox="0 0 24 24"><path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/></symbol>
    <symbol id="i-users" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m16 11 2 2 4-4"/></symbol>
    <symbol id="i-download" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/></symbol>
</svg>

<header class="site-header">
    <div class="container">
        <a class="logo" href="{{ url('/') }}" aria-label="MHM Tədris Mərkəzi — əsas səhifə">
            <span class="logo-mark"><svg viewBox="0 0 1149 819" aria-hidden="true"><use href="#mark"/></svg></span>
            <span class="logo-text"><b>MHM</b><small>Tədris Mərkəzi</small></span>
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="Menyunu aç">
            <svg class="ic ic-menu"><use href="#i-menu"/></svg>
            <svg class="ic ic-close"><use href="#i-close"/></svg>
        </button>
        <nav class="nav" id="site-nav" aria-label="Əsas menyu">
            <a href="#imkanlar">İmkanlar</a>
            <a href="#ekranlar">Ekranlar</a>
            <a href="#istiqametler">İstiqamətlər</a>
            <a href="#suallar">Suallar</a>
            <a href="#elaqe">Əlaqə</a>
            <a class="btn btn--primary" href="#yukle"><svg class="ic ic--sm"><use href="#i-download"/></svg>Tətbiqi yüklə</a>
        </nav>
    </div>
</header>

<main>
    <section class="hero" id="esas">
        <div class="container hero-grid">
            <div class="hero-copy">
                <span class="eyebrow"><span class="dot"><svg class="ic"><use href="#i-check"/></svg></span>MHM Tədris Mərkəzi · 10 ildir sizinlə</span>
                <h1>MİQ, Sertifikasiya və Abituriyent hazırlığı <em>bir tətbiqdə</em></h1>
                <p class="hero-lead">Video dərslər, canlı dərslərin yazıları, real imtahan formatında quizlər və sual-sual izahlı nəticələr — MHM Kurs tətbiqində, istədiyiniz vaxt və yerdə.</p>
                <div class="stores">
                    <a class="store" href="{{ $kurs['ios'] }}" target="_blank" rel="noopener"><svg class="ic ic--fill"><use href="#i-apple"/></svg><span><small>Yükləyin</small><b>App Store</b></span></a>
                    <a class="store" href="{{ $kurs['android'] }}" target="_blank" rel="noopener"><svg class="ic ic--fill"><use href="#i-play"/></svg><span><small>Yükləyin</small><b>Google Play</b></span></a>
                </div>
                <p class="hero-note">MHM Kurs — mərkəzimizin tələbələri üçündür. Hələ tələbə deyilsiniz? <a href="#qonaq">MHM Qonaq tətbiqinə baxın</a>.</p>
                <div class="trust">
                    <div><b>10+ il</b><span>tədris təcrübəsi</span></div>
                    <div><b>10K+</b><span>tətbiq yüklənməsi</span></div>
                    <div><b>3</b><span>istiqamət üzrə qruplar</span></div>
                </div>
            </div>
            <div class="hero-visual" aria-hidden="true">
                <div class="phone phone--back">
                    <img src="{{ asset('assets/site/img/screens/quiz-720.webp') }}" width="360" height="800" alt="">
                </div>
                <div class="phone phone--front">
                    <img src="{{ asset('assets/site/img/screens/home-720.webp') }}" width="360" height="800" alt="" fetchpriority="high">
                </div>
                <div class="float-card float-card--result"><span class="badge"><svg class="ic"><use href="#i-check-circle"/></svg></span><span><b>17 / 20 düzgün</b>Nəticə dərhal hazırdır</span></div>
                <div class="float-card float-card--resume"><span class="badge"><svg class="ic"><use href="#i-resume"/></svg></span><span><b>Qaldığınız yerdən</b>video davam edir</span></div>
            </div>
        </div>
    </section>

    <section class="section" id="imkanlar">
        <div class="container">
            <div class="section-head reveal">
                <span class="kicker">İmkanlar</span>
                <h2>Hazırlıq üçün lazım olan hər şey</h2>
                <p>Mövzunu videodan öyrənin, quizlə yoxlayın, nəticədə səhvlərinizi görün — hamısı bir yerdə, telefonunuzda.</p>
            </div>
            <div class="features">
                <article class="feature reveal">
                    <span class="feature-icon"><svg class="ic"><use href="#i-video"/></svg></span>
                    <h3>Video dərslər</h3>
                    <p>Mövzu-mövzu video izahlar. Qaldığınız yerdən davam edin, 10 saniyə irəli-geri çəkin, tam ekranda izləyin.</p>
                </article>
                <article class="feature reveal">
                    <span class="feature-icon"><svg class="ic"><use href="#i-live"/></svg></span>
                    <h3>Dərs yazıları</h3>
                    <p>Canlı dərslərin yazıları və ev tapşırıqlarının izahı — buraxdığınız dərsi istənilən vaxt izləyin.</p>
                </article>
                <article class="feature reveal">
                    <span class="feature-icon"><svg class="ic"><use href="#i-quiz"/></svg></span>
                    <h3>Real formatda quizlər</h3>
                    <p>Taymerli sınaqlar, şəkilli və mətnli suallar, <span class="nowrap">A–E</span> variantları — imtahandakı kimi.</p>
                </article>
                <article class="feature reveal">
                    <span class="feature-icon"><svg class="ic"><use href="#i-chart"/></svg></span>
                    <h3>İzahlı nəticələr</h3>
                    <p>Düzgün, səhv və cavabsız suallar, sərf olunan vaxt; hər sualda sizin və düzgün cavab.</p>
                </article>
                <article class="feature reveal">
                    <span class="feature-icon"><svg class="ic"><use href="#i-file"/></svg></span>
                    <h3>PDF materiallar</h3>
                    <p>Dərs vəsaitləri və test materialları tətbiqin içində — axtarışla tez tapın.</p>
                </article>
                <article class="feature reveal">
                    <span class="feature-icon"><svg class="ic"><use href="#i-bell"/></svg></span>
                    <h3>Bildirişlər</h3>
                    <p>Yeni video, quiz və canlı dərs vaxtı haqqında bildirişlər — heç nəyi qaçırmayın.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section section--soft">
        <div class="container split">
            <div class="reveal">
                <span class="kicker">Video dərslər</span>
                <h2>Mövzu-mövzu izah, sizə rahat olan vaxtda</h2>
                <p class="lead">Hər mövzunun videoları müəllimin adı ilə bir yerdədir. Dərsi yarımçıq saxlasanız belə, tətbiq yerinizi unutmur.</p>
                <ul class="checks">
                    <li><span class="tick"><svg class="ic"><use href="#i-check"/></svg></span><span><b>Qaldığınız yerdən davam</b> — video yenidən açılanda oradan başlayır.</span></li>
                    <li><span class="tick"><svg class="ic"><use href="#i-check"/></svg></span><span><b>±10 saniyə</b> düymələri və tam ekran rejimi.</span></li>
                    <li><span class="tick"><svg class="ic"><use href="#i-check"/></svg></span><span><b>Dərs yazıları</b> — canlı dərsləri sonradan da izləyin.</span></li>
                    <li><span class="tick"><svg class="ic"><use href="#i-check"/></svg></span><span><b>PDF materiallar</b> — videonun yanında dərs vəsaitləri.</span></li>
                </ul>
            </div>
            <div class="split-media reveal" aria-hidden="true">
                <div class="phone"><img src="{{ asset('assets/site/img/screens/lessons-360.webp') }}" srcset="{{ $shot('lessons') }}" sizes="250px" width="360" height="800" alt="" loading="lazy"></div>
                <div class="phone"><img src="{{ asset('assets/site/img/screens/player-360.webp') }}" srcset="{{ $shot('player') }}" sizes="250px" width="360" height="800" alt="" loading="lazy"></div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container split split--reverse">
            <div class="reveal">
                <span class="kicker">Quizlər və nəticələr</span>
                <h2>İmtahan formatında yoxlayın, səhvlərinizi anlayın</h2>
                <p class="lead">Quizlər MİQ, Sertifikasiya və Abituriyent imtahanlarının formatındadır. Bitirən kimi nəticəni və hər sualın təhlilini görürsünüz.</p>
                <ul class="checks">
                    <li><span class="tick"><svg class="ic"><use href="#i-check"/></svg></span><span><b>Taymer və sual xəritəsi</b> — vaxtınızı və cavablarınızı izləyin.</span></li>
                    <li><span class="tick"><svg class="ic"><use href="#i-check"/></svg></span><span><b>Dərhal nəticə</b> — düzgün, səhv, cavabsız və müddət.</span></li>
                    <li><span class="tick"><svg class="ic"><use href="#i-check"/></svg></span><span><b>Sual-sual analiz</b> — sizin cavabınız və düzgün cavab yan-yana; izah videosu.</span></li>
                    <li><span class="tick"><svg class="ic"><use href="#i-check"/></svg></span><span><b>Nəticələrim</b> — bütün sınaqlarınızın tarixçəsi bir yerdə.</span></li>
                </ul>
            </div>
            <div class="split-media reveal" aria-hidden="true">
                <div class="phone"><img src="{{ asset('assets/site/img/screens/quiz-360.webp') }}" srcset="{{ $shot('quiz') }}" sizes="250px" width="360" height="800" alt="" loading="lazy"></div>
                <div class="phone"><img src="{{ asset('assets/site/img/screens/result-360.webp') }}" srcset="{{ $shot('result') }}" sizes="250px" width="360" height="800" alt="" loading="lazy"></div>
            </div>
        </div>
    </section>

    <section class="section section--soft" id="ekranlar">
        <div class="container">
            <div class="section-head reveal">
                <span class="kicker">Ekranlar</span>
                <h2>Tətbiqə yaxından baxın</h2>
                <p>MHM Kurs tətbiqinin əsas bölmələri. Ekranlardakı ad və nəticələr nümunədir.</p>
            </div>
        </div>
        <div class="gallery" data-gallery>
            <div class="gallery-track">
                @foreach ($gallery as [$slug, $title, $text])
                    <figure class="shot">
                        <div class="phone"><img src="{{ asset("assets/site/img/screens/{$slug}-360.webp") }}" srcset="{{ $shot($slug) }}" sizes="(max-width: 600px) 200px, 236px" width="360" height="800" alt="MHM Kurs — {{ $title }}" loading="lazy"></div>
                        <h3>{{ $title }}</h3>
                        <p>{{ $text }}</p>
                    </figure>
                @endforeach
            </div>
            <div class="gallery-nav">
                <button type="button" data-dir="-1" aria-label="Əvvəlki ekranlar"><svg class="ic"><use href="#i-left"/></svg></button>
                <button type="button" data-dir="1" aria-label="Növbəti ekranlar"><svg class="ic"><use href="#i-right"/></svg></button>
            </div>
        </div>
    </section>

    <section class="section" id="istiqametler">
        <div class="container">
            <div class="section-head reveal">
                <span class="kicker">İstiqamətlər</span>
                <h2>Hansı imtahana hazırlaşırsınız?</h2>
                <p>Üç istiqamət üzrə qruplar — hər qrupun öz video kursları, quizləri və materialları var.</p>
            </div>
            <div class="programs">
                <article class="program program--miq reveal">
                    <span class="program-tag">MİQ</span>
                    <h3>Müəllimlərin işə qəbulu</h3>
                    <p>MİQ imtahanına ixtisas fənni üzrə mövzu izahları və imtahan formatında sınaqlarla hazırlıq.</p>
                    <ul>
                        <li><svg class="ic"><use href="#i-check"/></svg>Mövzu-mövzu video kurslar</li>
                        <li><svg class="ic"><use href="#i-check"/></svg>MİQ formatında quizlər</li>
                        <li><svg class="ic"><use href="#i-check"/></svg>PDF test materialları</li>
                    </ul>
                    <a href="#elaqe">Qrupa yazılın <svg class="ic"><use href="#i-arrow"/></svg></a>
                </article>
                <article class="program program--sert reveal">
                    <span class="program-tag">Sertifikasiya</span>
                    <h3>Müəllimlərin sertifikasiyası</h3>
                    <p>Sertifikasiya imtahanına sınaqlar, izahlı nəticələr və canlı dərslərin yazıları ilə hazırlıq.</p>
                    <ul>
                        <li><svg class="ic"><use href="#i-check"/></svg>Taymerli sınaq imtahanları</li>
                        <li><svg class="ic"><use href="#i-check"/></svg>Sual-sual nəticə təhlili</li>
                        <li><svg class="ic"><use href="#i-check"/></svg>Dərs yazıları</li>
                    </ul>
                    <a href="#elaqe">Qrupa yazılın <svg class="ic"><use href="#i-arrow"/></svg></a>
                </article>
                <article class="program program--abit reveal">
                    <span class="program-tag">Abituriyent</span>
                    <h3>Qəbul imtahanları</h3>
                    <p>Abituriyentlər üçün fənlər üzrə video dərslər, quizlər və sınaq imtahanları.</p>
                    <ul>
                        <li><svg class="ic"><use href="#i-check"/></svg>Fənlər üzrə video dərslər</li>
                        <li><svg class="ic"><use href="#i-check"/></svg>Quiz və sınaqlar</li>
                        <li><svg class="ic"><use href="#i-check"/></svg>Nəticə tarixçəsi</li>
                    </ul>
                    <a href="tel:+994773941424">Abituriyent xətti: 077 394 14 24 <svg class="ic"><use href="#i-arrow"/></svg></a>
                </article>
            </div>
        </div>
    </section>

    <section class="section section--soft" id="baslamaq">
        <div class="container">
            <div class="section-head reveal">
                <span class="kicker">Necə başlamalı?</span>
                <h2>Üç addımda hazırlığa başlayın</h2>
            </div>
            <div class="steps">
                <div class="step reveal">
                    <span class="step-num">1</span>
                    <h3>Qrupa yazılın</h3>
                    <p>Bizə zəng edin və ya <a href="https://wa.me/994516546852" target="_blank" rel="noopener">WhatsApp-dan yazın</a> — sizə uyğun qrupu birlikdə seçək.</p>
                </div>
                <div class="step reveal">
                    <span class="step-num">2</span>
                    <h3>Tətbiqi yükləyin</h3>
                    <p>MHM Kurs-u App Store və ya Google Play-dən yükləyin, sizə verilən istifadəçi adı və şifrə ilə daxil olun.</p>
                </div>
                <div class="step reveal">
                    <span class="step-num">3</span>
                    <h3>Hazırlaşın</h3>
                    <p>Videolara baxın, quiz həll edin, nəticələrinizi izləyin — öz tempinizlə.</p>
                </div>
            </div>
            <div class="guest reveal" id="qonaq">
                <span class="guest-icon"><svg class="ic"><use href="#i-users"/></svg></span>
                <div class="guest-text">
                    <h3>Hələ MHM tələbəsi deyilsiniz?</h3>
                    <p>MHM Qonaq tətbiqində video dərslərə və kitablara baxın, müəllimlərimiz və kurslarımızla tanış olun, qeydiyyatdan keçin.</p>
                </div>
                <div class="stores">
                    <a class="store" href="{{ $qonaq['ios'] }}" target="_blank" rel="noopener"><svg class="ic ic--fill"><use href="#i-apple"/></svg><span><small>MHM Qonaq</small><b>App Store</b></span></a>
                    <a class="store" href="{{ $qonaq['android'] }}" target="_blank" rel="noopener"><svg class="ic ic--fill"><use href="#i-play"/></svg><span><small>MHM Qonaq</small><b>Google Play</b></span></a>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="suallar">
        <div class="container faq-wrap">
            <div class="faq-aside reveal">
                <span class="kicker">Suallar</span>
                <h2>Tez-tez verilən suallar</h2>
                <p>Cavabını tapmadığınız sual var? Bizə yazın — kömək edək.</p>
                <a class="btn btn--ghost" href="https://wa.me/994516546852" target="_blank" rel="noopener"><svg class="ic ic--sm"><use href="#i-chat"/></svg>WhatsApp-dan yazın</a>
            </div>
            <div class="faq reveal">
                <details open>
                    <summary>MHM Kurs tətbiqinə necə daxil olum?<svg class="ic"><use href="#i-down"/></svg></summary>
                    <div class="answer">Tətbiq MHM Tədris Mərkəzinin tələbələri üçündür. Qrupa yazıldıqdan sonra sizə verilən istifadəçi adı və şifrə ilə daxil olursunuz.</div>
                </details>
                <details>
                    <summary>Tələbə deyiləm, tətbiqə baxa bilərəm?<svg class="ic"><use href="#i-down"/></svg></summary>
                    <div class="answer">Bəli — <a href="#qonaq">MHM Qonaq</a> tətbiqində kurslarımız və müəllimlərimizlə tanış ola, qeydiyyatdan keçə bilərsiniz.</div>
                </details>
                <details>
                    <summary>Videonu yarımçıq saxlasam, qaldığım yerdən davam edə bilərəm?<svg class="ic"><use href="#i-down"/></svg></summary>
                    <div class="answer">Bəli. Tətbiq hər videoda qaldığınız yeri yadda saxlayır; videonu yenidən açanda oradan davam edirsiniz.</div>
                </details>
                <details>
                    <summary>Canlı dərsi buraxsam nə olar?<svg class="ic"><use href="#i-down"/></svg></summary>
                    <div class="answer">Canlı dərslərin yazıları “Dərs yazıları” bölməsində olur — istənilən vaxt izləyə bilərsiniz.</div>
                </details>
                <details>
                    <summary>Nəticələrimi haradan görürəm?<svg class="ic"><use href="#i-down"/></svg></summary>
                    <div class="answer">“Nəticələrim” bölməsində bütün sınaqlarınız, düzgün və səhv cavabların sayı və müddət görünür. Hər nəticəni açıb sual-sual cavablara baxa bilərsiniz.</div>
                </details>
                <details>
                    <summary>Tətbiq hansı telefonlarda işləyir?<svg class="ic"><use href="#i-down"/></svg></summary>
                    <div class="answer">Android və iPhone telefonlarında. Tətbiqi <a href="{{ $kurs['android'] }}" target="_blank" rel="noopener">Google Play</a> və <a href="{{ $kurs['ios'] }}" target="_blank" rel="noopener">App Store</a>-dan pulsuz yükləyə bilərsiniz.</div>
                </details>
                <details>
                    <summary>Qrupa necə yazılım?<svg class="ic"><use href="#i-down"/></svg></summary>
                    <div class="answer">Zəng edin: <a href="tel:+994773227953">077 322 79 53</a>, Abituriyent üçün: <a href="tel:+994773941424">077 394 14 24</a>, və ya <a href="https://wa.me/994516546852" target="_blank" rel="noopener">WhatsApp-dan</a> yazın.</div>
                </details>
            </div>
        </div>
    </section>

    <section id="yukle">
        <div class="container">
            <div class="cta reveal">
                <div>
                    <h2>Hazırlığa bu gün başlayın</h2>
                    <p>MHM Kurs tətbiqini yükləyin — video dərslər, quizlər və nəticələriniz həmişə yanınızda.</p>
                    <div class="stores">
                        <a class="store store--light" href="{{ $kurs['ios'] }}" target="_blank" rel="noopener"><svg class="ic ic--fill"><use href="#i-apple"/></svg><span><small>Yükləyin</small><b>App Store</b></span></a>
                        <a class="store store--light" href="{{ $kurs['android'] }}" target="_blank" rel="noopener"><svg class="ic ic--fill"><use href="#i-play"/></svg><span><small>Yükləyin</small><b>Google Play</b></span></a>
                    </div>
                </div>
                <div class="cta-media" aria-hidden="true">
                    <div class="phone"><img src="{{ asset('assets/site/img/screens/result-detail-360.webp') }}" srcset="{{ $shot('result-detail') }}" sizes="250px" width="360" height="800" alt="" loading="lazy"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="elaqe">
        <div class="container">
            <div class="section-head reveal">
                <span class="kicker">Əlaqə</span>
                <h2>Bizimlə əlaqə</h2>
                <p>Qruplar, qiymətlər və dərs cədvəli barədə məlumat üçün zəng edin və ya yazın.</p>
            </div>
            <div class="contact">
                <div class="contact-cards">
                    <div class="contact-card reveal">
                        <span class="c-ic"><svg class="ic"><use href="#i-phone"/></svg></span>
                        <h3>Telefon</h3>
                        <a href="tel:+994773227953">077 322 79 53</a>
                        <a href="tel:+994773941424">077 394 14 24 <small>· Abituriyent</small></a>
                    </div>
                    <div class="contact-card reveal">
                        <span class="c-ic"><svg class="ic"><use href="#i-chat"/></svg></span>
                        <h3>WhatsApp</h3>
                        <a href="https://wa.me/994516546852" target="_blank" rel="noopener">+994 51 654 68 52</a>
                        <small>Tətbiqlə bağlı dəstək</small>
                    </div>
                    <div class="contact-card reveal">
                        <span class="c-ic"><svg class="ic"><use href="#i-pin"/></svg></span>
                        <h3>Ünvan</h3>
                        <p>Bakı şəhəri, Elmlər Akademiyası m/st</p>
                    </div>
                    <div class="contact-card reveal">
                        <span class="c-ic"><svg class="ic"><use href="#i-mail"/></svg></span>
                        <h3>E-poçt</h3>
                        <a href="mailto:info@mhmapp.az">info@mhmapp.az</a>
                    </div>
                    <div class="contact-card contact-card--wide reveal">
                        <div>
                            <h3>Sosial şəbəkələr</h3>
                            <p>Yeniliklər, nəticələr və dərslərdən parçalar</p>
                        </div>
                        <div class="socials">
                            <a href="https://www.instagram.com/mhm_tedris_merkezi/" target="_blank" rel="noopener" aria-label="Instagram"><svg class="ic"><use href="#i-instagram"/></svg></a>
                            <a href="https://www.facebook.com/MHM.tedris.merkezi/" target="_blank" rel="noopener" aria-label="Facebook"><svg class="ic"><use href="#i-facebook"/></svg></a>
                            <a href="https://www.youtube.com/channel/UCT18265mJDfk4t2-4Sqc_GA" target="_blank" rel="noopener" aria-label="YouTube"><svg class="ic"><use href="#i-youtube"/></svg></a>
                        </div>
                    </div>
                </div>
                <div class="map reveal">
                    <iframe title="MHM Tədris Mərkəzi xəritədə"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3038.1285210820365!2d49.9446510760156!3d40.40600355629215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40306311fa7c7765%3A0x38976adaa7667584!2zTUhNIFTJmWRyaXMgTcmZcmvJmXpp!5e0!3m2!1saz!2saz!4v1757676954801!5m2!1saz!2saz"
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a class="logo" href="{{ url('/') }}">
                    <span class="logo-mark"><svg viewBox="0 0 1149 819" aria-hidden="true"><use href="#mark"/></svg></span>
                    <span class="logo-text"><b>MHM</b><small>Tədris Mərkəzi</small></span>
                </a>
                <p class="footer-about">Onlayn MİQ, Sertifikasiya və Abituriyent hazırlığı: video dərslər, canlı dərslərin yazıları, quizlər və izahlı nəticələr.</p>
            </div>
            <div>
                <h4>Səhifə</h4>
                <ul>
                    <li><a href="#imkanlar">İmkanlar</a></li>
                    <li><a href="#ekranlar">Ekranlar</a></li>
                    <li><a href="#istiqametler">İstiqamətlər</a></li>
                    <li><a href="#suallar">Suallar</a></li>
                    <li><a href="#elaqe">Əlaqə</a></li>
                </ul>
            </div>
            <div>
                <h4>Tətbiqlər</h4>
                <ul>
                    <li><a href="{{ $kurs['ios'] }}" target="_blank" rel="noopener">MHM Kurs <small>· App Store</small></a></li>
                    <li><a href="{{ $kurs['android'] }}" target="_blank" rel="noopener">MHM Kurs <small>· Google Play</small></a></li>
                    <li><a href="{{ $qonaq['ios'] }}" target="_blank" rel="noopener">MHM Qonaq <small>· App Store</small></a></li>
                    <li><a href="{{ $qonaq['android'] }}" target="_blank" rel="noopener">MHM Qonaq <small>· Google Play</small></a></li>
                </ul>
            </div>
            <div>
                <h4>Məlumat</h4>
                <ul>
                    <li><a href="{{ asset('privacy-student.html') }}">Məxfilik siyasəti · MHM Kurs</a></li>
                    <li><a href="{{ asset('privacy-new1.html') }}">Məxfilik siyasəti · MHM Qonaq</a></li>
                    <li><a href="{{ asset('mhm-kurs-delete.html') }}">Hesabın silinməsi</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} MHM Tədris Mərkəzi. Bütün hüquqlar qorunur.</span>
            <span>Sayt: <a href="https://nss.az" target="_blank" rel="noopener">NS Studio</a></span>
        </div>
    </div>
</footer>

<script src="{{ $v('assets/site/site.js') }}" defer></script>
</body>
</html>
