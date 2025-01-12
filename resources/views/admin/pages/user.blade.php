@extends('admin.index')

@section('title')
    Admin və müəllimlər | Admin panel
@endsection

@section('css')

    <link href="{{ asset('admin/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <style>
        .loc-group span {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
@endsection

@section('content')

    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Admin və müəllimlər</h4>
                            <button type="button" class="btn btn-primary btn-rounded mr-2" data-toggle="modal"
                                    data-target="#createModal"><span class="btn-icon-left text-primary"><i
                                        class="fa fa-plus color-info"></i></span>
                                Əlavə et
                            </button>
                        </div>
                        <div class="card-body">
                            <form method="get" id="searchForm" class="row justify-content-center" action="">
                                <input type="hidden" name="is_deleted" value="{{isset($_GET['is_deleted']) ? $_GET['is_deleted'] : ''}}">
                                <div class="col-3">
                                    <select class="form-control default-select" onchange="form.submit()" name="status"
                                            id="searchOption">
                                        <option value="" disabled selected>Status</option>
                                        <option
                                            value="1" {{isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : ''}} >
                                            Aktiv
                                        </option>
                                        <option
                                            value="'0'" {{isset($_GET['status']) && $_GET['status'] == "'0'" ? 'selected' : ''}} >
                                            Deaktiv
                                        </option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select class="form-control default-select" onchange="form.submit()" name="type">
                                        <option value="" disabled selected>Tip</option>
                                        <option
                                            value="admin" {{isset($_GET['type']) && $_GET['type'] == "admin" ? 'selected' : ''}}>
                                            Admin
                                        </option>
                                        <option
                                            value="teacher" {{isset($_GET['type']) && $_GET['type'] == "teacher" ? 'selected' : ''}}>
                                            Teacher
                                        </option>
                                    </select>
                                </div>
                                <div class="input-group col-4">
                                    <div class="form-item">
                                        <input id="search-input" name="search" type="search"
                                               placeholder="Axtarış et"  value="{{isset($_GET['search']) ? $_GET['search'] : ''}}" class="form-control"
                                               style="border-top-right-radius: 0; border-bottom-right-radius: 0"/>
                                    </div>
                                    <button id="search-button" type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="col-1">
                                    <button class="filter-search-btn btn btn-secondary clear-btn">Sıfırla</button>
                                </div>
                                @if(isset($_GET['is_deleted']) && $_GET['is_deleted'] == 1)
                                    <div class="col-1">
                                        <a href="{{route('user.index')}}"
                                           class="btn btn-primary clear-btn">
                                            <i class="fas fa-users"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="col-1">
                                        <a href="{{route('user.index', ['is_deleted'=>1])}}"
                                           class="btn btn-success clear-btn">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                @endif
                            </form>
                            <div class="table-responsive">
                                <table id="example3" class="display min-w850">
                                    <thead>
                                    <tr class="text-center">
                                        <th>Seç</th>
                                        <th>Ad və soyad</th>
                                        <th>Email</th>
                                        <th>Tip</th>
                                        <th>Yaranma tarixi</th>
                                        <th>Status</th>
                                        <th>Əməliyyatlar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($posts as $postItem)
                                        <tr id="row{{$postItem->id}}" class="text-center">
                                            <td class="text-center"><input value="{{$postItem->id}}" class="checkedItem"
                                                                           name="checked" type="checkbox"></td>
                                            <td>{{$postItem->name}}</td>
                                            <td>{{$postItem->email}}</td>
                                            <td>{{$postItem->type == 'teacher' ? 'Müəllim' : 'Admin'}}</td>
                                            <td>{{$postItem->created_at ? $postItem->created_at->translatedFormat('d.m.Y H:i') : ''}}</td>
                                            <td class="m-auto text-center">
                                                @if($postItem->status)
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input changeStatus checkStatus{{$postItem->id}}"
                                                            data-id="{{$postItem->id}}" type="checkbox"
                                                            id="flexSwitchCheckDefault" checked/>
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input changeStatus checkStatus{{$postItem->id}}"
                                                            data-id="{{$postItem->id}}" type="checkbox"
                                                            id="flexSwitchCheckDefault"/>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <a href="javascript:void(0)" data-id="{{$postItem->id}}"
                                                       data-target="#editModal"
                                                       data-toggle="modal"
                                                       class="btn btn-primary shadow btn-xs sharp mr-1 editModal"><i
                                                            class="fa fa-pencil"></i></a>
                                                    @if(isset($_GET['is_deleted']) && $_GET['is_deleted'] == 1)
                                                        <a data-id="{{$postItem->id}}"
                                                           class="btn btn-success shadow btn-xs sharp deleteItem"><i
                                                                class="fa fa-reply"></i></a>
                                                    @else
                                                        <a data-id="{{$postItem->id}}"
                                                           class="btn btn-danger shadow btn-xs sharp deleteItem"><i
                                                                class="fa fa-trash"></i></a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
                                @if(!empty($postItem))
                                    <div class="d-flex justify-content-start">
                                        <button class="checkedBtn btn-primary btn mr-3" value="0">SEÇİLƏNLƏRİ DEAKTİV ET
                                        </button>
                                        <button class="checkedBtn btn-primary btn mr-3" value="1">SEÇİLƏNLƏRİ AKTİV ET
                                        </button>
                                        @if(isset($_GET['is_deleted']) && $_GET['is_deleted'] == 1)
                                            <button class="checkedBtn btn-primary btn" value="2">SEÇİLƏNLƏRİ BƏRPA ET
                                            </button>
                                        @else
                                            <button class="checkedBtn btn-primary btn" value="2">SEÇİLƏNLƏRİ SİL
                                            </button>
                                        @endif
                                    </div>
                                    <br>
                                @endif
                                <div
                                    class="d-flex justify-content-center">{{$posts->appends(request()->input())->links()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="createModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Əlavə
                        et</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCreate" action="{{route('user.store')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body pb-0 pt-2">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Ad</label>
                                    <input class="form-control" value="{{old('name')}}"
                                           type="text" required maxlength="100"
                                           name="name" id="name"/>
                                </div>
                                <div class="form-group">
                                    <label for="email">E-poçt</label>
                                    <input class="form-control" value="{{old('email')}}"
                                           type="text" required maxlength="100"
                                           name="email" id="email"/>
                                </div>
                                <div class="form-group d-flex mt-4">
                                    <label for="status">Status</label>
                                    <div class="form-check form-switch ml-4">
                                        <input class="form-check-input"
                                               type="checkbox" checked name="status"
                                               id="status"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="type">Tip</label>
                                    <select class="form-control" name="type"
                                            id="type">
                                        <option value="admin">Admin</option>
                                        <option value="teacher">Müəllim</option>
                                    </select>
                                </div>
                                <div class="form-group" id="password_field">
                                    <label for="password" class="control-label">
                                        <span class="grey">Şifrə</span>
                                    </label>
                                    <div class="loc-group" style="position: relative;">
                                        <input type="password" class="form-control" name="password"
                                               id="newPassword">
                                        <span class="pass-show-eye" id="togglePassword"><i
                                                class="fas fa-eye-slash"></i></span>
                                    </div>
                                </div>
                                <div class="form-group" id="billing_password2_field">
                                    <label for="billing_password2" class="control-label">
                                        <span class="grey">Şifrənin təkrarı</span>
                                    </label>
                                    <div class="loc-group" style="position: relative;">
                                        <input type="password" class="form-control" name="confirm_password"
                                               id="reNewPassword">
                                        <span class="pass-show-eye" id="togglePasswordRepeat"><i
                                                class="fas fa-eye-slash"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">
                            Ləğv et
                        </button>
                        <button type="button" id="createBtn" class="btn btn-primary btn-xs">Yadda
                            saxla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Redaktə
                        et</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEdit" action="" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body pb-0 pt-2">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nameEdit">Ad</label>
                                    <input class="form-control"
                                           type="text" required maxlength="100"
                                           name="name" id="nameEdit"/>
                                </div>
                                <div class="form-group">
                                    <label for="emailEdit">E-poçt</label>
                                    <input class="form-control"
                                           type="text" required maxlength="100"
                                           name="email" id="emailEdit"/>
                                </div>
                                <div class="form-group d-flex mt-4">
                                    <label for="statusEdit">Status</label>
                                    <div class="form-check form-switch ml-4">
                                        <input class="form-check-input"
                                               type="checkbox" name="status"
                                               id="statusEdit"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="typeEdit">Tip</label>
                                    <select class="form-control" name="type"
                                            id="typeEdit">
                                        <option value="admin">Admin</option>
                                        <option value="teacher">Müəllim</option>
                                    </select>
                                </div>
                                <div class="form-group" id="password_fieldEdit">
                                    <label for="password" class="control-label">
                                        <span class="grey">Şifrə</span>
                                    </label>
                                    <div class="loc-group" style="position: relative;">
                                        <input type="password" class="form-control" name="password"
                                               id="newPasswordEdit">
                                        <span class="pass-show-eye" id="togglePasswordEdit"><i
                                                class="fas fa-eye-slash"></i></span>
                                    </div>
                                </div>
                                <div class="form-group" id="billing_password2_fieldEdit">
                                    <label for="billing_password2" class="control-label">
                                        <span class="grey">Şifrənin təkrarı</span>
                                    </label>
                                    <div class="loc-group" style="position: relative;">
                                        <input type="password" class="form-control" name="confirm_password"
                                               id="reNewPasswordEdit">
                                        <span class="pass-show-eye" id="togglePasswordRepeatEdit"><i
                                                class="fas fa-eye-slash"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">
                            Ləğv et
                        </button>
                        <button type="button" id="editPost" class="btn btn-primary btn-xs">Yadda
                            saxla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script src="{{ asset('admin/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/js/plugins-init/datatables.init.js') }}"></script>

    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#newPassword");

        togglePassword.addEventListener("click", function () {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            const icon = this.parentNode.querySelector("i");

            if (icon) {
                icon.classList.toggle("fa-eye");
                icon.classList.toggle("fa-eye-slash");
            }
        });

        const togglePasswordRepeat = document.querySelector("#togglePasswordRepeat");
        const passwordRepeat = document.querySelector("#reNewPassword");

        togglePasswordRepeat.addEventListener("click", function () {
            const type = passwordRepeat.getAttribute("type") === "password" ? "text" : "password";
            passwordRepeat.setAttribute("type", type);
            const icon = this.parentNode.querySelector("i");

            if (icon) {
                icon.classList.toggle("fa-eye");
                icon.classList.toggle("fa-eye-slash");
            }
        });

        const togglePasswordEdit = document.querySelector("#togglePasswordEdit");
        const passwordEdit = document.querySelector("#newPasswordEdit");

        togglePasswordEdit.addEventListener("click", function () {
            const type = passwordEdit.getAttribute("type") === "password" ? "text" : "password";
            passwordEdit.setAttribute("type", type);
            const icon = this.parentNode.querySelector("i");

            if (icon) {
                icon.classList.toggle("fa-eye");
                icon.classList.toggle("fa-eye-slash");
            }
        });

        const togglePasswordRepeatEdit = document.querySelector("#togglePasswordRepeatEdit");
        const passwordRepeatEdit = document.querySelector("#reNewPasswordEdit");

        togglePasswordRepeatEdit.addEventListener("click", function () {
            const type = passwordRepeatEdit.getAttribute("type") === "password" ? "text" : "password";
            passwordRepeatEdit.setAttribute("type", type);
            const icon = this.parentNode.querySelector("i");

            if (icon) {
                icon.classList.toggle("fa-eye");
                icon.classList.toggle("fa-eye-slash");
            }
        });

        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let checkedArr = [];

            $('.checkedItem').click(function () {
                let checkedID = $(this).val();
                if ($(this).is(':checked')) {
                    checkedArr.push(checkedID);
                    return checkedArr;
                } else {
                    checkedArr = checkedArr.filter(function (letter) {
                        return letter !== checkedID;
                    });
                }
            })

            $('.checkedBtn').click(function () {

                if (checkedArr.length != 0) {
                    let route = '{{route('user.checked')}}';
                    let currentVal = $(this).val();

                    let text = '';
                    let resultText = '';

                    if (currentVal == '0') {
                        text = 'Seçilənləri deaktiv etmək istədiyinizə əminsiniz?';
                        resultText = 'Deaktiv edildi';
                    } else if (currentVal == '1') {
                        text = 'Seçilənləri aktiv etmək istədiyinizə əminsiniz?';
                        resultText = 'Aktiv edildi';
                    } else {
                        text = 'Əminsinizmi?';
                        resultText = 'Uğurlu';
                    }
                    Swal.fire({
                        title: 'Xəbərdarlıq',
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#163A76',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Bəli',
                        cancelButtonText: 'Xeyr'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: route,
                                method: 'POST',
                                data: {
                                    arr: checkedArr,
                                    val: currentVal,
                                },
                                async: false,
                                success: function (response) {
                                    if (currentVal == '0') {
                                        for (let i of checkedArr) {
                                            $('.checkStatus' + i).attr('checked', false);
                                        }
                                    } else if (currentVal == '1') {
                                        for (let i of checkedArr) {
                                            $('.checkStatus' + i).attr('checked', true);
                                        }
                                    } else {
                                        for (let i of checkedArr) {
                                            $('#row' + i).remove();
                                        }
                                    }

                                    $('.checkedItem').prop('checked', false);
                                    checkedArr = [];
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Xəbərdarlıq',
                                        text: resultText,
                                        confirmButtonColor: '#163A76',
                                        confirmButtonText: 'Tamam'
                                    })
                                }
                            })
                        }
                    })
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Xəbərdarlıq',
                        text: 'Heç bir seçim edilməmişdir',
                        confirmButtonColor: '#163A76',
                        confirmButtonText: 'Tamam'
                    })
                }

            });

            $('.deleteItem').click(function () {
                let dataID = $(this).data('id');
                let route = '{{route('user.destroy', ['user'=>'id'])}}';
                route = route.replace('id', dataID);
                Swal.fire({
                    title: 'Xəbərdarlıq',
                    text: 'Əminsinizmi?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#163A76',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Bəli',
                    cancelButtonText: 'Xeyr'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: route,
                            method: 'DELETE',
                            data: {
                                id: dataID,
                            },
                            async: false,
                            success: function (response) {
                                $('#row' + dataID).remove();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Xəbərdarlıq',
                                    text: "Uğurlu",
                                    confirmButtonColor: '#163A76',
                                    confirmButtonText: 'Tamam'
                                })
                            }
                        })
                    }
                })
            });

            $('.changeStatus').click(function () {
                let dataID = $(this).data('id');

                $.ajax({
                    url: '{{route('user.changeStatus')}}',
                    method: 'POST',
                    data: {
                        id: dataID
                    },
                    async: false,
                })

            });

            $('#createBtn').click(function () {
                var name = $('#name').val()
                var email = $('#email').val()

                if (name.trim() == '' || email.trim() == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta',
                        text: 'Bütün xanalar doldurulmalıdır',
                        confirmButtonText: 'Tamam',
                        confirmButtonColor: '#163A76'
                    })
                } else if (name.trim().length > 100 || email.trim().length > 100) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta',
                        text: "Xanalar maksimum 100 simvoldan ibarət ola bilər",
                        confirmButtonText: 'Tamam',
                        confirmButtonColor: '#163A76'
                    })
                } else if ($("#newPassword").val() !== $("#reNewPassword").val()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta',
                        text: "Şifrə ilə şifrənin təkrarı uyğun deyil",
                        confirmButtonText: 'Tamam',
                        confirmButtonColor: '#163A76'
                    })
                } else if ($("#newPassword").val().trim() && $("#newPassword").val().trim().length < 8) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta',
                        text: "Şifrə ən azı 8 simvoldan ibarət olmalıdır",
                        confirmButtonText: 'Tamam',
                        confirmButtonColor: '#163A76'
                    })
                } else if ($("#newPassword").val().trim() == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta',
                        text: "Şifrə boş ola bilməz",
                        confirmButtonColor: '#163A76',
                        confirmButtonText: 'Tamam'
                    })
                } else {
                    $('#formCreate').submit();
                }
            })

            $('#editPost').click(function () {
                var name = $('#nameEdit').val()
                var email = $('#emailEdit').val()

                if (name.trim() == '' || email.trim() == '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta',
                        text: 'Bütün xanalar doldurulmalıdır',
                        confirmButtonText: 'Tamam',
                        confirmButtonColor: '#163A76'
                    })
                } else if (name.trim().length > 100 || email.trim().length > 100) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta',
                        text: "Xanalar maksimum 100 simvoldan ibarət ola bilər",
                        confirmButtonText: 'Tamam',
                        confirmButtonColor: '#163A76'
                    })
                } else if ($("#newPasswordEdit").val() !== $("#reNewPasswordEdit").val()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta',
                        text: "Şifrə ilə şifrənin təkrarı uyğun deyil",
                        confirmButtonText: 'Tamam',
                        confirmButtonColor: '#163A76'
                    })
                } else if ($("#newPasswordEdit").val().trim() && $("#newPasswordEdit").val().trim().length < 8) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta',
                        text: "Şifrə ən azı 8 simvoldan ibarət olmalıdır",
                        confirmButtonText: 'Tamam',
                        confirmButtonColor: '#163A76'
                    })
                } else {
                    $('#formEdit').submit();
                }
            })

            function editUser(dataID) {
                let nameEdit = $('#nameEdit');
                let emailEdit = $('#emailEdit');
                let typeEdit = $('#typeEdit');
                let statusEdit = $('#statusEdit');

                let route = '{{route('user.edit', ['user'=>'edit'])}}';
                route = route.replace('edit', dataID);
                let routeUpdate = '{{route('user.update', ['user' => 'update'])}}';
                routeUpdate = routeUpdate.replace('update', dataID);

                $('#formEdit').attr('action', routeUpdate);

                $.ajax({
                    url: route,
                    method: 'GET',
                    data: {
                        id: dataID
                    },
                    async: false,
                    success: function (response) {

                        var post = response.post;

                        nameEdit.val(post.name);
                        emailEdit.val(post.email);
                        typeEdit.val(post.type);

                        if (post.status == 1) {
                            statusEdit.attr('checked', true);
                        } else {
                            statusEdit.attr('checked', false);
                        }
                    }
                })
            }

            let searchParams = new URLSearchParams(window.location.search)
            if (searchParams.has('user_id')) {
                let dataId = searchParams.get('user_id');
                $('#editModal').modal('show');
                editUser(dataId);
            }

            $('.editModal').click(function () {
                let dataID = $(this).data('id');
                editUser(dataID);
            });

            $('.clear-btn').click(function () {
                $('#searchForm input').val('');
                $('#searchForm select').val('');
            })
        });
    </script>
@endsection
