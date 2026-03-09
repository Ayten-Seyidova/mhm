@extends('admin.index')
@section('title')
    Kitabxana | Admin panel
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
                            <h4 class="card-title">Kitabxana</h4>
                            <button type="button" class="btn btn-primary btn-rounded mr-2" data-toggle="modal"
                                    data-target="#createModal">
                                <span class="btn-icon-left text-primary"><i class="fa fa-plus color-info"></i></span>
                                Əlavə et
                            </button>
                        </div>
                        <div class="card-body">
                            <form method="get" id="searchForm" class="row justify-content-center" action="">
                                <div class="col-3">
                                    <select class="form-control default-select" onchange="form.submit()" name="status">
                                        <option value="" disabled selected>Status</option>
                                        <option value="1" {{isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : ''}}>Aktiv</option>
                                        <option value="'0'" {{isset($_GET['status']) && $_GET['status'] == "'0'" ? 'selected' : ''}}>Deaktiv</option>
                                    </select>
                                </div>
                                <div class="input-group col-3 flex-nowrap">
                                    <div class="form-item">
                                        <input id="search-input" value="{{isset($_GET['search']) ? $_GET['search'] : ''}}"
                                               name="search" type="search" placeholder="Axtarış et" class="form-control"
                                               style="border-top-right-radius: 0; border-bottom-right-radius: 0"/>
                                    </div>
                                    <button id="search-button" type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="col-1">
                                    <button class="filter-search-btn btn btn-secondary clear-btn"><i class="fas fa-eraser"></i></button>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table id="example3" class="display min-w850">
                                    <thead>
                                    <tr class="text-center">
                                        <th>Seç</th>
                                        <th>№</th>
                                        <th>Şəkil</th>
                                        <th>Başlıq</th>
                                        <th>Müəllif</th>
                                        <th>Qiymət</th>
                                        <th>Seçilmiş</th>
                                        <th>Status</th>
                                        <th>Əməliyyatlar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($posts as $key => $postItem)
                                        <tr id="row{{$postItem->id}}" class="text-center">
                                            <td><input value="{{$postItem->id}}" class="checkedItem" name="checked" type="checkbox"></td>
                                            <td>
                                                @if(request('page'))
                                                    {{(request('page')-1)*20 + ($key+1)}}
                                                @else
                                                    {{$key+1}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($postItem->cover)
                                                    <img class="d-block" style="width: 60px; height: 80px; object-fit: cover; margin: auto"
                                                         src="{{$postItem->cover}}" alt="">
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{$postItem->title}}</td>
                                            <td>{{$postItem->author ?? '—'}}</td>
                                            <td>{{$postItem->price ? $postItem->price.' AZN' : '—'}}</td>
                                            <td>
                                                @if($postItem->is_featured)
                                                    <span class="badge badge-success">Bəli</span>
                                                @else
                                                    <span class="badge badge-secondary">Xeyr</span>
                                                @endif
                                            </td>
                                            <td class="m-auto text-center">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input changeStatus checkStatus{{$postItem->id}}"
                                                           data-id="{{$postItem->id}}" type="checkbox"
                                                           id="flexSwitchCheckDefault" {{$postItem->status ? 'checked' : ''}}/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <a href="javascript:void(0)" data-id="{{$postItem->id}}"
                                                       data-target="#editModal" data-toggle="modal"
                                                       class="btn btn-primary shadow btn-xs sharp mr-1 editModal">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" data-id="{{$postItem->id}}"
                                                       data-target="#accessModal" data-toggle="modal"
                                                       class="btn btn-success shadow btn-xs sharp mr-1 accessModal"
                                                       title="Access ver">
                                                        <i class="fa fa-key"></i>
                                                    </a>
                                                    <a data-id="{{$postItem->id}}"
                                                       class="btn btn-danger shadow btn-xs sharp deleteItem">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
                                @if(!empty($postItem))
                                    <div class="d-flex justify-content-start">
                                        <button class="checkedBtn btn-primary btn mr-3" value="0">SEÇİLƏNLƏRİ DEAKTİV ET</button>
                                        <button class="checkedBtn btn-primary btn mr-3" value="1">SEÇİLƏNLƏRİ AKTİV ET</button>
                                        <button class="checkedBtn btn-primary btn mr-3" value="2">SEÇİLƏNLƏRİ SİL</button>
                                    </div>
                                    <br>
                                @endif
                                <div class="d-flex justify-content-center">{{$posts->appends(request()->input())->links()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CREATE MODAL --}}
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Əlavə et</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="formCreate" action="{{route('library-book.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body pb-0 pt-2">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group img-section">
                                    <label>Üz qabığı (şəkil)</label>
                                    <div class="img-input d-flex justify-content-between mb-2">
                                        <input id="uploadImage-create" type="file" name="image" class="form-control-file"
                                               onchange="PreviewImageCreate();">
                                        <div class="delete-img c-pointer" onclick="deleteImageCreate();">
                                            <i class="fas fa-trash"></i>
                                        </div>
                                    </div>
                                    <img class="preview-img" id='previewImage-create'
                                         src="{{asset('admin/images/noPhoto.png')}}" style="width: 100%;" alt="">
                                </div>
                                <div class="form-group">
                                    <label>Başlıq *</label>
                                    <input class="form-control" type="text" maxlength="190" required name="title"/>
                                </div>
                                <div class="form-group">
                                    <label>Müəllif</label>
                                    <input class="form-control" type="text" maxlength="190" name="author"/>
                                </div>
                                <div class="form-group">
                                    <label>Nəşriyyat</label>
                                    <input class="form-control" type="text" maxlength="190" name="publisher"/>
                                </div>
                                <div class="form-group">
                                    <label>Dil</label>
                                    <input class="form-control" type="text" maxlength="100" name="language" placeholder="Azərbaycan"/>
                                </div>
                                <div class="form-group">
                                    <label>Səhifə sayı</label>
                                    <input class="form-control" type="number" name="page_count"/>
                                </div>
                                <div class="form-group">
                                    <label>İl</label>
                                    <input class="form-control" type="text" maxlength="10" name="year" placeholder="2024"/>
                                </div>
                                <div class="form-group">
                                    <label>Qiymət (AZN) *</label>
                                    <input class="form-control" type="number" step="0.01" required name="price"/>
                                </div>
                                <div class="form-group d-flex mt-2">
                                    <label class="mr-3">Seçilmiş (Featured)</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_featured"/>
                                    </div>
                                </div>
                                <div class="form-group d-flex mt-2">
                                    <label class="mr-3">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" checked name="status"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label>Haqqında</label>
                                    <textarea name="description" class="editor-create" id="editor-create" cols="30" rows="8"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Demo PDF <small class="text-muted">(pulsuz, bir neçə səhifə)</small></label>
                                    <input type="file" name="demo_pdf" class="form-control-file" accept=".pdf"/>
                                </div>
                                <div class="form-group">
                                    <label>Tam PDF <small class="text-muted">(ödənişli, access verdikdən sonra açılır)</small></label>
                                    <input type="file" name="full_pdf" class="form-control-file" accept=".pdf"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Ləğv et</button>
                        <button type="submit" class="btn btn-sm btn-primary">Yadda saxla</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Redaktə et</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="formEdit" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input id="hiddenInput" type="hidden" name="hidden" value="1">
                    <div class="modal-body pb-0 pt-2">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group img-sectionEdit">
                                    <label>Üz qabığı (şəkil)</label>
                                    <div class="img-input d-flex justify-content-between mb-2">
                                        <input id="uploadImage" type="file" name="image" class="form-control-file"
                                               onchange="PreviewImage();">
                                        <div class="delete-img c-pointer" onclick="deleteImage();">
                                            <i class="fas fa-trash"></i>
                                        </div>
                                    </div>
                                    <img class="preview-img" id='previewImage'
                                         src="{{asset('admin/images/noPhoto.png')}}" style="width: 100%;" alt="">
                                </div>
                                <div class="form-group">
                                    <label>Başlıq *</label>
                                    <input class="form-control" type="text" maxlength="190" required name="title" id="titleEdit"/>
                                </div>
                                <div class="form-group">
                                    <label>Müəllif</label>
                                    <input class="form-control" type="text" maxlength="190" name="author" id="authorEdit"/>
                                </div>
                                <div class="form-group">
                                    <label>Nəşriyyat</label>
                                    <input class="form-control" type="text" maxlength="190" name="publisher" id="publisherEdit"/>
                                </div>
                                <div class="form-group">
                                    <label>Dil</label>
                                    <input class="form-control" type="text" maxlength="100" name="language" id="languageEdit"/>
                                </div>
                                <div class="form-group">
                                    <label>Səhifə sayı</label>
                                    <input class="form-control" type="number" name="page_count" id="pageCountEdit"/>
                                </div>
                                <div class="form-group">
                                    <label>İl</label>
                                    <input class="form-control" type="text" maxlength="10" name="year" id="yearEdit"/>
                                </div>
                                <div class="form-group">
                                    <label>Qiymət (AZN) *</label>
                                    <input class="form-control" type="number" step="0.01" required name="price" id="priceEdit"/>
                                </div>
                                <div class="form-group d-flex mt-2">
                                    <label class="mr-3">Seçilmiş (Featured)</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_featured" id="isFeaturedEdit"/>
                                    </div>
                                </div>
                                <div class="form-group d-flex mt-2">
                                    <label class="mr-3">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status" id="statusEdit"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="form-group">
                                    <label>Haqqında</label>
                                    <textarea name="description" class="editorEdit" id="editorEdit" cols="30" rows="8"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Demo PDF</label>
                                    <div id="demoPdfCurrent" class="mb-1 text-muted small"></div>
                                    <input type="file" name="demo_pdf" class="form-control-file" accept=".pdf"/>
                                    <small class="text-muted">Yeni fayl seçsəniz köhnəsi əvəz olunacaq</small>
                                </div>
                                <div class="form-group">
                                    <label>Tam PDF</label>
                                    <div id="fullPdfCurrent" class="mb-1 text-muted small"></div>
                                    <input type="file" name="full_pdf" class="form-control-file" accept=".pdf"/>
                                    <small class="text-muted">Yeni fayl seçsəniz köhnəsi əvəz olunacaq</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Ləğv et</button>
                        <button type="submit" class="btn btn-sm btn-primary">Yadda saxla</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ACCESS MODAL --}}
    <div class="modal fade" id="accessModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kitab Accessi — <span id="accessBookTitle"></span></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <h6>Access ver</h6>
                            <select id="guestSelect" class="form-control mb-2">
                                <option value="">İstifadəçi seç</option>
                            </select>
                            <button class="btn btn-success btn-sm" id="grantAccessBtn">Access ver</button>
                        </div>
                        <div class="col-6">
                            <h6>Mövcud accesslər</h6>
                            <div id="accessList"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="{{ asset('admin/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/js/plugins-init/datatables.init.js') }}"></script>
    <script>
        // CKEditor
        $('textarea.editor-create').each(function () {
            CKEDITOR.replace('editor-create', {
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form'
            })
        });
        $('textarea.editorEdit').each(function () {
            CKEDITOR.replace('editorEdit', {
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form'
            })
        });

        function PreviewImageCreate() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage-create").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage-create").src = oFREvent.target.result;
            };
        }

        function deleteImageCreate() {
            document.getElementById("previewImage-create").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById("uploadImage-create").value = '';
        }

        function PreviewImage() {
            document.getElementById('hiddenInput').value = '1';
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage").src = oFREvent.target.result;
            };
        }

        function deleteImage() {
            document.getElementById("previewImage").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById('hiddenInput').value = '0';
        }

        $(function () {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });

            let checkedArr = [];

            $('.checkedItem').click(function () {
                let checkedID = $(this).val();
                if ($(this).is(':checked')) {
                    checkedArr.push(checkedID);
                } else {
                    checkedArr = checkedArr.filter(function (letter) {
                        return letter !== checkedID;
                    });
                }
            });

            $('.checkedBtn').click(function () {
                if (checkedArr.length != 0) {
                    let route = '{{route('library-book.checked')}}';
                    let currentVal = $(this).val();
                    let text = '';
                    let resultText = '';
                    if (currentVal == '0') { text = 'Seçilənləri deaktiv etmək istədiyinizə əminsiniz?'; resultText = 'Deaktiv edildi'; }
                    else if (currentVal == '1') { text = 'Seçilənləri aktiv etmək istədiyinizə əminsiniz?'; resultText = 'Aktiv edildi'; }
                    else if (currentVal == '2') { text = 'Əminsinizmi?'; resultText = 'Uğurlu'; }

                    Swal.fire({
                        title: 'Xəbərdarlıq', text: text, icon: 'warning',
                        showCancelButton: true, confirmButtonColor: '#163A76',
                        cancelButtonColor: '#d33', confirmButtonText: 'Bəli', cancelButtonText: 'Xeyr'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: route, method: 'POST',
                                data: {arr: checkedArr, val: currentVal},
                                async: false,
                                success: function () {
                                    if (currentVal == '0') { for (let i of checkedArr) { $('.checkStatus' + i).prop('checked', false); } }
                                    else if (currentVal == '1') { for (let i of checkedArr) { $('.checkStatus' + i).prop('checked', true); } }
                                    else if (currentVal == '2') { for (let i of checkedArr) { $('#row' + i).remove(); } }
                                    $('.checkedItem').prop('checked', false);
                                    checkedArr = [];
                                    Swal.fire({icon: 'success', title: 'Uğurlu', confirmButtonColor: '#163A76', text: resultText, confirmButtonText: 'Tamam'});
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire({icon: 'warning', title: 'Xəbərdarlıq', text: 'Heç bir seçim edilməmişdir', confirmButtonColor: '#163A76', confirmButtonText: 'Tamam'});
                }
            });

            $('.deleteItem').click(function () {
                let dataID = $(this).data('id');
                let route = '{{route('library-book.destroy', ['library_book'=>'id'])}}';
                route = route.replace('id', dataID);
                Swal.fire({
                    title: 'Xəbərdarlıq', text: 'Əminsinizmi?', icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#163A76',
                    cancelButtonColor: '#d33', confirmButtonText: 'Bəli', cancelButtonText: 'Xeyr'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: route, method: 'DELETE', data: {id: dataID}, async: false,
                            success: function () {
                                $('#row' + dataID).remove();
                                Swal.fire({icon: 'success', title: 'Uğurlu', confirmButtonColor: '#163A76', text: 'Silindi', confirmButtonText: 'Tamam'});
                            }
                        });
                    }
                });
            });

            $('.changeStatus').click(function () {
                let dataID = $(this).data('id');
                $.ajax({
                    url: '{{route('library-book.changeStatus')}}',
                    method: 'POST', data: {id: dataID}, async: false
                });
            });

            // Edit modal
            function editBook(dataID) {
                let route = '{{route('library-book.edit', ['library_book'=>'edit'])}}';
                route = route.replace('edit', dataID);
                let routeUpdate = '{{route('library-book.update', ['library_book' => 'update'])}}';
                routeUpdate = routeUpdate.replace('update', dataID);
                $('#formEdit').attr('action', routeUpdate);

                $.ajax({
                    url: route, method: 'GET', data: {id: dataID}, async: false,
                    success: function (response) {
                        var post = response.post;
                        $('#titleEdit').val(post.title);
                        $('#authorEdit').val(post.author);
                        $('#publisherEdit').val(post.publisher);
                        $('#languageEdit').val(post.language);
                        $('#pageCountEdit').val(post.page_count);
                        $('#yearEdit').val(post.year);
                        $('#priceEdit').val(post.price);
                        $('#isFeaturedEdit').prop('checked', post.is_featured == 1);
                        $('#statusEdit').prop('checked', post.status == 1);
                        $('#previewImage').attr('src', post.cover ?? '{{asset('admin/images/noPhoto.png')}}');
                        $('#demoPdfCurrent').html(post.demo_pdf_url ? '✓ Demo PDF mövcuddur' : 'Demo PDF yoxdur');
                        $('#fullPdfCurrent').html(post.full_pdf_url ? '✓ Tam PDF mövcuddur' : 'Tam PDF yoxdur');
                        CKEDITOR.instances['editorEdit'].setData(post.description ?? '');
                    }
                });
            }

            $('.editModal').click(function () {
                editBook($(this).data('id'));
            });

            // Access modal
            let currentBookId = null;

            $('.accessModal').click(function () {
                currentBookId = $(this).data('id');
                let route = '{{route('library-book.accesses', ['id'=>'bookid'])}}';
                route = route.replace('bookid', currentBookId);

                $.ajax({
                    url: route, method: 'GET', async: false,
                    success: function (response) {
                        $('#accessBookTitle').text(response.book.title);

                        // Guest select
                        let guestOpts = '<option value="">İstifadəçi seç</option>';
                        response.guests.forEach(function (g) {
                            guestOpts += '<option value="' + g.id + '">' + g.name + ' (' + g.phone + ')</option>';
                        });
                        $('#guestSelect').html(guestOpts);

                        // Access list
                        renderAccessList(response.accesses);
                    }
                });
            });

            function renderAccessList(accesses) {
                if (accesses.length === 0) {
                    $('#accessList').html('<p class="text-muted">Heç bir istifadəçiyə access verilməyib</p>');
                    return;
                }
                let html = '<ul class="list-group">';
                accesses.forEach(function (a) {
                    let name = a.guest ? a.guest.name : 'İstifadəçi #' + a.guest_id;
                    html += '<li class="list-group-item d-flex justify-content-between align-items-center">' +
                        name +
                        '<button class="btn btn-danger btn-xs revokeBtn" data-guestid="' + a.guest_id + '">Sil</button>' +
                        '</li>';
                });
                html += '</ul>';
                $('#accessList').html(html);
            }

            $('#grantAccessBtn').click(function () {
                let guestId = $('#guestSelect').val();
                if (!guestId) {
                    Swal.fire({icon: 'warning', text: 'İstifadəçi seçin', confirmButtonColor: '#163A76'});
                    return;
                }
                $.ajax({
                    url: '{{route('library-book.grantAccess')}}',
                    method: 'POST',
                    data: {library_book_id: currentBookId, guest_id: guestId},
                    success: function () {
                        // Refresh access list
                        let route = '{{route('library-book.accesses', ['id'=>'bookid'])}}';
                        route = route.replace('bookid', currentBookId);
                        $.get(route, function (response) {
                            renderAccessList(response.accesses);
                        });
                        Swal.fire({icon: 'success', text: 'Access verildi', confirmButtonColor: '#163A76', timer: 1500, showConfirmButton: false});
                    }
                });
            });

            $(document).on('click', '.revokeBtn', function () {
                let guestId = $(this).data('guestid');
                $.ajax({
                    url: '{{route('library-book.revokeAccess')}}',
                    method: 'POST',
                    data: {library_book_id: currentBookId, guest_id: guestId, _method: 'POST'},
                    success: function () {
                        let route = '{{route('library-book.accesses', ['id'=>'bookid'])}}';
                        route = route.replace('bookid', currentBookId);
                        $.get(route, function (response) {
                            renderAccessList(response.accesses);
                        });
                    }
                });
            });

            $('.clear-btn').click(function () {
                $('#searchForm input').val('');
                $('#searchForm select').val('');
            });
        });
    </script>
@endsection
