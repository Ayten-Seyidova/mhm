@extends('admin.index')

@section('title')
    Qruplar | Admin panel
@endsection

@section('css')
    <link href="{{ asset('admin/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Qruplar</h4>
                        </div>
                        <div class="card-body">
                            <form method="get" id="searchForm" class="row justify-content-center" action="">
                                <div class="input-group col-4">
                                    <div class="form-item">
                                        <input id="search-input" name="search" type="search"
                                               placeholder="Axtarış et"
                                               value="{{isset($_GET['search']) ? $_GET['search'] : ''}}"
                                               class="form-control"
                                               style="border-top-right-radius: 0; border-bottom-right-radius: 0"/>
                                    </div>
                                    <button id="search-button" type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="col-1">
                                    <button class="filter-search-btn btn btn-secondary clear-btn">Sıfırla</button>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table id="example3" class="display min-w850">
                                    <thead>
                                    <tr class="text-center">
                                        <th>Seç</th>
                                        <th>Şəkil</th>
                                        <th>Qrup adı</th>
                                        <th>Şagird sayı</th>
                                        <th>Redaktə et</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($posts as $postItem)
                                        <tr id="row{{$postItem->id}}" class="text-center">
                                            <td class="text-center"><input value="{{$postItem->id}}" class="checkedItem"
                                                                           name="checked" type="checkbox"></td>
                                            <td>
                                                <img class="d-block m-auto" style="width: 100px"
                                                     src="{{asset($postItem->image)}}" alt=""></td>
                                            <td>{{$postItem->name}}</td>
                                            <td>
                                                @php($count = \App\Models\Customer::whereRaw("JSON_CONTAINS(group_ids, '\"$postItem->id\"')")->where('is_deleted', 0)->get())
                                                {{count($count)}}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <a href="javascript:void(0)" data-id="{{$postItem->id}}"
                                                       data-target="#editModal"
                                                       data-toggle="modal"
                                                       class="btn btn-primary shadow btn-xs sharp mr-1 editModal"><i
                                                            class="fa fa-pencil"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
                                <div
                                    class="d-flex justify-content-center">{{$posts->appends(request()->input())->links()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
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
                        <div class="form-group img-section">
                            <label for="uploadImage">Şəkil</label>
                            <div class="img-input d-flex justify-content-between mb-2">
                                <input id="uploadImage" type="file"
                                       name="image" value="" class="form-control-file"
                                       onchange="PreviewImage();">
                                <div class="delete-img c-pointer" onclick="deleteImage();">
                                    <i class="fas fa-trash"></i></div>
                                <input id="hiddenInput" type="hidden" name="hidden" value="1">
                            </div>

                            <img class="preview-img" id='previewImage'
                                 src="{{asset('admin/images/noPhoto.png')}}"
                                 style="width: 100%;" alt="">
                        </div>
                        <div class="form-group">
                            <label for="nameEdit">Qrup adı</label>
                            <input class="form-control"
                                   type="text" required readonly maxlength="100"
                                   name="name" id="nameEdit"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">
                            Ləğv et
                        </button>
                        <button type="submit" id="editPost" class="btn btn-primary btn-xs">Yadda
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
        function PreviewImage() {
            document.getElementById('hiddenInput').value = '1';
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage").src = oFREvent.target.result;
            };
        };

        function deleteImage() {
            document.getElementById("previewImage").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById('hiddenInput').value = '0';
        }

        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function editUser(dataID) {
                let nameEdit = $('#nameEdit');
                let imageEdit = $('#previewImage');

                let route = '{{route('teacher.edit', ['teacher'=>'edit'])}}';
                route = route.replace('edit', dataID);
                let routeUpdate = '{{route('teacher.update', ['teacher' => 'update'])}}';
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
                        imageEdit.attr("src", ('/' + post.image));
                    }
                })
            }

            let searchParams = new URLSearchParams(window.location.search)
            if (searchParams.has('teacher_id')) {
                let dataId = searchParams.get('teacher_id');
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
