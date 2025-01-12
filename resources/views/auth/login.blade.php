<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login | Admin panel</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('admin/images/logo.png')}}">
    <link rel="stylesheet" href="{{asset('sweet-alert/sweetalert2.min.css')}}">
    <link href="{{asset('admin/css/style.css')}}" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;family=Roboto:wght@100;300;400;500;700;900&amp;display=swap"
        rel="stylesheet">
</head>

<body class="h-100">
<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <div class="text-center mb-3 text-white">
                                    <strong>Admin panel</strong>
                                </div>
                                <h4 class="text-center mb-4 text-white">Giriş et</h4>
                                <form action="{{route('login')}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label class="mb-1 text-white"><strong>E-mail</strong></label>
                                        <input type="email" class="form-control" placeholder="E-mail" name="email"
                                               id="email">
                                    </div>

                                    <div class="form-group">
                                        <label class="mb-1 text-white"><strong>Password</strong></label>
                                        <input type="password" class="form-control" placeholder="Password"
                                               name="password">
                                    </div>
                                    <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox ml-1 text-white">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="basic_checkbox_1" name="remember">
                                                <label class="custom-control-label" for="basic_checkbox_1">Məni
                                                    xatırla</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn bg-white text-primary btn-block">Giriş</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('admin/vendor/global/global.min.js')}}"></script>
<script src="{{asset('admin/js/custom.min.js')}}"></script>
<script src="{{asset('admin/js/deznav-init.js')}}"></script>
<script src="{{asset('sweet-alert/sweetalert2.all.min.js')}}"></script>

@include('sweetalert::alert')
<script>
    <?php
    if ($errors->any()) {
        $swalText = '';
        foreach ($errors->all() as $error) {
            $swalText .= $error . '<br>';
        }

    }
    ?>
    @if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Xəta',
        html: '{!! $swalText !!}',
    });
    @endif
</script>
</body>
</html>
