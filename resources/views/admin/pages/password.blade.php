@extends('admin.index')

@section('title')
    Şifrəni dəyiş | Admin panel
@endsection

@section('css')

@endsection

@section('content')

    <div class="content-body">
        <div class="container-fluid">
            <form action="{{route('changePassword')}}" id="formEdit" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Şifrəni dəyiş</h4>
                            </div>
                            <div class="card-body w-50 m-auto">
                                <div class="form-group position-relative">
                                    <label for="passwordEdit">Şifrə</label>
                                    <input type="password" class="form-control"
                                           name="password"
                                           id="passwordEdit" required
                                           placeholder="********"
                                           maxlength="15">
                                    <i class="fas fa-eye-slash c-pointer position-absolute"
                                       style="right: 20px; top: 53px;" id="togglePassword"></i>
                                </div>

                                <div class="form-group position-relative">
                                    <label for="passwordEditRepeat">Şifrənin təkrarı</label>
                                    <input type="password" class="form-control"
                                           placeholder="********" required
                                           id="passwordEditRepeat"
                                           maxlength="15">
                                    <i class="fas fa-eye-slash c-pointer position-absolute"
                                       style="right: 20px; top: 53px;" id="togglePasswordRepeat"></i>
                                </div>

                                <div class="form-group">
                                    <button type="button" id="editPost"
                                            class="btn btn-primary btn-block">Yadda
                                        saxla
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')

    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#passwordEdit");

        togglePassword.addEventListener("click", function () {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });

        const togglePasswordRepeat = document.querySelector("#togglePasswordRepeat");
        const passwordRepeat = document.querySelector("#passwordEditRepeat");

        togglePasswordRepeat.addEventListener("click", function () {
            const type = passwordRepeat.getAttribute("type") === "password" ? "text" : "password";
            passwordRepeat.setAttribute("type", type);
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });

        $("#editPost").on("click", function () {
            if ($("#passwordEdit").val() !== $("#passwordEditRepeat").val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Xəta',
                    text: "Yeni parol ilə təsdiq parolu uyğun deyil",
                    confirmButtonText: 'Tamam'
                })
            } else if ($("#passwordEdit").val().trim() && $("#passwordEdit").val().trim().length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Xəta',
                    text: "Şifrə ən azı 8 simvoldan ibarət olmalıdır",
                    confirmButtonText: 'Tamam'
                })
            } else if ($("#passwordEdit").val().trim() == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Xəta',
                    text: "Şifrə boş ola bilməz",
                    confirmButtonText: 'Tamam'
                })
            } else {
                $("#formEdit").submit();
            }
        })
    </script>

@endsection
