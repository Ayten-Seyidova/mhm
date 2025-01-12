<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}"/>
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('admin/images/logo.png')}}">
    @yield('css')
    <link rel="stylesheet" href="{{asset('admin/vendor/chartist/css/chartist.min.css')}}">
    <link href="{{asset('admin/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/vendor/owl-carousel/owl.carousel.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
          integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
          crossorigin="anonymous"/>
    <link href="{{asset('admin/css/mdb.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/dropzone.css')}}" rel="stylesheet" type="text/css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <link href="{{asset('admin/css/style.css')}}" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;family=Roboto:wght@100;300;400;500;700;900&amp;display=swap"
        rel="stylesheet">
    <script src="{{asset('admin/js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('admin/js/dropzone.js')}}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{asset('sweet-alert/sweetalert2.min.css')}}">
</head>

<body>
<style>
    .content-body {
        min-height: fit-content !important;
    }

    .dataTables_paginate {
        display: none;
    }

    .dataTables_length {
        display: none;
    }

    .dataTables_info {
        display: none;
    }

    .dataTables_filter {
        display: none;
    }
    .add-menu-sidebar:after, .add-menu-sidebar:before {
        display:none;
    }
</style>

<div id="main-wrapper">
    @include('admin.layouts.header')
    @include('admin.layouts.sidebar')
    @yield('content')

    <div class="footer">
        <div class="copyright pt-0">
            <p>Copyright © {{ date('Y') }}. MHM</p>
        </div>
    </div>
</div>

<script src="{{asset('admin/vendor/global/global.min.js')}}"></script>
<script src="{{asset('admin/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
<script src="{{asset('admin/vendor/chart.js/Chart.bundle.min.js')}}"></script>
<script src="{{asset('admin/js/custom.min.js')}}"></script>
<script src="{{asset('admin/js/deznav-init.js')}}"></script>
<script src="{{asset('admin/vendor/owl-carousel/owl.carousel.js')}}"></script>
<script src="{{asset('admin/vendor/peity/jquery.peity.min.js')}}"></script>
<script src="{{asset('admin/js/dashboard/dashboard-1.js')}}"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="{{asset('sweet-alert/sweetalert2.all.min.js')}}"></script>
@include('sweetalert::alert')

@yield('js')

<script>
    $('.hamburger').addClass('is-active');
    // $('#main-wrapper').addClass('menu-toggle');
    <?php
    if ($errors->any()) {
        $swalText = '';
        foreach ($errors->all() as $error) {
            $swalText .= $error . '<br';
        }

    }
    ?>
    @if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Xəta',
        html: '{!! $swalText !!}',
        confirmButtonText: 'Tamam',
        confirmButtonColor: '#163A76'
    });
    @endif
</script>

</body>

</html>
