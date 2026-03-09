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

    <style>
        .lib-modal .modal-header {
            background: linear-gradient(135deg, #163A76 0%, #1e4f9f 100%);
            color: #fff;
            border-radius: 8px 8px 0 0;
            padding: 16px 24px;
        }
        .lib-modal .modal-header .close { color: #fff; opacity: .8; }
        .lib-modal .modal-header .close:hover { opacity: 1; }
        .lib-modal .modal-content { border-radius: 8px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,.18); }
        .lib-modal .modal-body { padding: 24px; background: #f8f9fc; }
        .lib-modal .modal-footer { background: #fff; border-top: 1px solid #e9ecef; padding: 14px 24px; border-radius: 0 0 8px 8px; }

        .lib-section-card {
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 20px;
            margin-bottom: 16px;
        }
        .lib-section-card .section-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #163A76;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
        }
        .lib-modal label {
            font-size: 12px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        .lib-modal .form-control {
            border-radius: 6px;
            border: 1px solid #dee2e6;
            font-size: 13px;
            padding: 8px 12px;
            transition: border-color .2s;
        }
        .lib-modal .form-control:focus { border-color: #163A76; box-shadow: 0 0 0 3px rgba(22,58,118,.1); }

        .lib-cover-box {
            position: relative;
            width: 100%;
            aspect-ratio: 3/4;
            background: #f0f2f8;
            border-radius: 8px;
            border: 2px dashed #c5cfe8;
            overflow: hidden;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color .2s;
        }
        .lib-cover-box:hover { border-color: #163A76; }
        .lib-cover-box img { width: 100%; height: 100%; object-fit: cover; border-radius: 6px; }
        .lib-cover-box .cover-placeholder {
            text-align: center;
            color: #adb5bd;
            pointer-events: none;
        }
        .lib-cover-box .cover-placeholder i { font-size: 32px; display: block; margin-bottom: 6px; }
        .lib-cover-box .cover-placeholder span { font-size: 11px; }
        .lib-cover-box input[type=file] {
            position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
        }
        .lib-cover-actions { display: flex; gap: 6px; margin-top: 8px; }
        .lib-cover-actions .btn { flex: 1; font-size: 11px; padding: 5px; }

        .lib-pdf-upload {
            background: #f8f9fc;
            border: 1.5px dashed #c5cfe8;
            border-radius: 8px;
            padding: 14px 16px;
            transition: border-color .2s, background .2s;
        }
        .lib-pdf-upload:hover { border-color: #163A76; background: #f0f4ff; }
        .lib-pdf-upload .pdf-label {
            font-size: 12px; font-weight: 700; color: #163A76; margin-bottom: 4px; display: block;
        }
        .lib-pdf-upload .pdf-sub {
            font-size: 11px; color: #6c757d; margin-bottom: 8px; display: block;
        }
        .lib-pdf-upload .pdf-status {
            font-size: 11px; margin-bottom: 6px;
        }
        .lib-pdf-upload .pdf-status.has-file { color: #28a745; }
        .lib-pdf-upload .pdf-status.no-file { color: #adb5bd; }
        .lib-pdf-upload input[type=file] { font-size: 12px; }

        .lib-toggle-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 14px;
            background: #f8f9fc;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            margin-bottom: 8px;
        }
        .lib-toggle-row label { margin: 0; font-size: 13px; font-weight: 600; color: #343a40; }

        .inline-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .inline-fields-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; }
        .modal-title { color: #fff !important; }
    </style>

    {{-- CREATE MODAL --}}
    <div class="modal fade lib-modal" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-plus mr-2"></i>Yeni kitab əlavə et</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="formCreate" action="{{route('library-book.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            {{-- SOL SÜTUN: Üz qabığı --}}
                            <div class="col-xl-3 col-lg-3">
                                <div class="lib-section-card">
                                    <div class="section-title"><i class="fa fa-image mr-1"></i>Üz qabığı</div>
                                    <div class="lib-cover-box" id="coverBoxCreate">
                                        <div class="cover-placeholder" id="coverPlaceholderCreate">
                                            <i class="fa fa-upload"></i>
                                            <span>Şəkil yüklə</span>
                                        </div>
                                        <img id='previewImage-create' src="" style="display:none;" alt="">
                                        <input id="uploadImage-create" type="file" name="image" accept="image/*"
                                               onchange="PreviewImageCreate();">
                                    </div>
                                    <div class="lib-cover-actions">
                                        <button type="button" class="btn btn-outline-danger btn-xs" onclick="deleteImageCreate()">
                                            <i class="fa fa-trash mr-1"></i>Sil
                                        </button>
                                    </div>
                                </div>

                                <div class="lib-section-card">
                                    <div class="section-title"><i class="fa fa-toggle-on mr-1"></i>Parametrlər</div>
                                    <div class="lib-toggle-row">
                                        <label for="isFeaturedCreate">Seçilmiş (Featured)</label>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_featured" id="isFeaturedCreate"/>
                                        </div>
                                    </div>
                                    <div class="lib-toggle-row">
                                        <label for="statusCreate">Status (Aktiv)</label>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" checked name="status" id="statusCreate"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ORTA SÜTUN: Kitab məlumatları --}}
                            <div class="col-xl-5 col-lg-5">
                                <div class="lib-section-card">
                                    <div class="section-title"><i class="fa fa-book mr-1"></i>Kitab məlumatları</div>

                                    <div class="form-group">
                                        <label>Başlıq <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" maxlength="190" required name="title" placeholder="Kitabın adı"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Müəllif</label>
                                        <input class="form-control" type="text" maxlength="190" name="author" placeholder="Müəllifin adı"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Nəşriyyat</label>
                                        <input class="form-control" type="text" maxlength="190" name="publisher" placeholder="Nəşriyyat adı"/>
                                    </div>

                                    <div class="inline-fields-3">
                                        <div class="form-group">
                                            <label>Dil</label>
                                            <input class="form-control" type="text" maxlength="100" name="language" placeholder="Azərbaycan"/>
                                        </div>
                                        <div class="form-group">
                                            <label>İl</label>
                                            <input class="form-control" type="text" maxlength="10" name="year" placeholder="2024"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Səhifə sayı</label>
                                            <input class="form-control" type="number" name="page_count" placeholder="342"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Qiymət (AZN) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" type="number" step="0.01" required name="price" placeholder="0.00"/>
                                            <div class="input-group-append">
                                                <span class="input-group-text" style="background:#163A76;color:#fff;border-color:#163A76;font-size:12px;font-weight:700;">AZN</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SAĞ SÜTUN: Məzmun + PDF --}}
                            <div class="col-xl-4 col-lg-4">
                                <div class="lib-section-card">
                                    <div class="section-title"><i class="fa fa-align-left mr-1"></i>Haqqında</div>
                                    <div class="form-group mb-0">
                                        <textarea name="description" class="editor-create" id="editor-create" cols="30" rows="6"></textarea>
                                    </div>
                                </div>

                                <div class="lib-section-card">
                                    <div class="section-title"><i class="fa fa-file-pdf-o mr-1"></i>PDF Fayllar</div>

                                    <div class="lib-pdf-upload mb-3">
                                        <span class="pdf-label"><i class="fa fa-eye mr-1"></i>Demo PDF</span>
                                        <span class="pdf-sub">Pulsuz, bir neçə nümunə səhifə</span>
                                        <input type="file" name="demo_pdf" class="form-control-file" accept=".pdf"/>
                                    </div>

                                    <div class="lib-pdf-upload">
                                        <span class="pdf-label"><i class="fa fa-lock mr-1"></i>Tam PDF</span>
                                        <span class="pdf-sub">Ödənişli — access verdikdən sonra açılır</span>
                                        <input type="file" name="full_pdf" class="form-control-file" accept=".pdf"/>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                            <i class="fa fa-times mr-1"></i>Ləğv et
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="fa fa-save mr-1"></i>Yadda saxla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade lib-modal" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-pencil mr-2"></i>Kitabı redaktə et</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="formEdit" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input id="hiddenInput" type="hidden" name="hidden" value="1">
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-xl-3 col-lg-3">
                                <div class="lib-section-card">
                                    <div class="section-title"><i class="fa fa-image mr-1"></i>Üz qabığı</div>
                                    <div class="lib-cover-box" id="coverBoxEdit">
                                        <div class="cover-placeholder" id="coverPlaceholderEdit">
                                            <i class="fa fa-upload"></i>
                                            <span>Şəkil yüklə</span>
                                        </div>
                                        <img id='previewImage' src="" style="display:none;" alt="">
                                        <input id="uploadImage" type="file" name="image" accept="image/*"
                                               onchange="PreviewImage();">
                                    </div>
                                    <div class="lib-cover-actions">
                                        <button type="button" class="btn btn-outline-danger btn-xs" onclick="deleteImage()">
                                            <i class="fa fa-trash mr-1"></i>Sil
                                        </button>
                                    </div>
                                </div>

                                <div class="lib-section-card">
                                    <div class="section-title"><i class="fa fa-toggle-on mr-1"></i>Parametrlər</div>
                                    <div class="lib-toggle-row">
                                        <label for="isFeaturedEdit">Seçilmiş (Featured)</label>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_featured" id="isFeaturedEdit"/>
                                        </div>
                                    </div>
                                    <div class="lib-toggle-row">
                                        <label for="statusEdit">Status (Aktiv)</label>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="status" id="statusEdit"/>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-5 col-lg-5">
                                <div class="lib-section-card">
                                    <div class="section-title"><i class="fa fa-book mr-1"></i>Kitab məlumatları</div>

                                    <div class="form-group">
                                        <label>Başlıq <span class="text-danger">*</span></label>
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

                                    <div class="inline-fields-3">
                                        <div class="form-group">
                                            <label>Dil</label>
                                            <input class="form-control" type="text" maxlength="100" name="language" id="languageEdit"/>
                                        </div>
                                        <div class="form-group">
                                            <label>İl</label>
                                            <input class="form-control" type="text" maxlength="10" name="year" id="yearEdit"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Səhifə sayı</label>
                                            <input class="form-control" type="number" name="page_count" id="pageCountEdit"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Qiymət (AZN) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" type="number" step="0.01" required name="price" id="priceEdit"/>
                                            <div class="input-group-append">
                                                <span class="input-group-text" style="background:#163A76;color:#fff;border-color:#163A76;font-size:12px;font-weight:700;">AZN</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-4">
                                <div class="lib-section-card">
                                    <div class="section-title"><i class="fa fa-align-left mr-1"></i>Haqqında</div>
                                    <div class="form-group mb-0">
                                        <textarea name="description" class="editorEdit" id="editorEdit" cols="30" rows="6"></textarea>
                                    </div>
                                </div>

                                <div class="lib-section-card">
                                    <div class="section-title"><i class="fa fa-file-pdf-o mr-1"></i>PDF Fayllar</div>

                                    <div class="lib-pdf-upload mb-3">
                                        <span class="pdf-label"><i class="fa fa-eye mr-1"></i>Demo PDF</span>
                                        <span class="pdf-sub">Pulsuz nümunə</span>
                                        <div id="demoPdfCurrent" class="pdf-status mb-2"></div>
                                        <input type="file" name="demo_pdf" class="form-control-file" accept=".pdf"/>
                                        <small class="text-muted" style="font-size:10px;">Yeni seçsəniz köhnəsi əvəz olunur</small>
                                    </div>

                                    <div class="lib-pdf-upload">
                                        <span class="pdf-label"><i class="fa fa-lock mr-1"></i>Tam PDF</span>
                                        <span class="pdf-sub">Access verdikdən sonra açılır</span>
                                        <div id="fullPdfCurrent" class="pdf-status mb-2"></div>
                                        <input type="file" name="full_pdf" class="form-control-file" accept=".pdf"/>
                                        <small class="text-muted" style="font-size:10px;">Yeni seçsəniz köhnəsi əvəz olunur</small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                            <i class="fa fa-times mr-1"></i>Ləğv et
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="fa fa-save mr-1"></i>Yadda saxla
                        </button>
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
            var file = document.getElementById("uploadImage-create").files[0];
            if (!file) return;
            var oFReader = new FileReader();
            oFReader.readAsDataURL(file);
            oFReader.onload = function (oFREvent) {
                var img = document.getElementById("previewImage-create");
                img.src = oFREvent.target.result;
                img.style.display = 'block';
                document.getElementById("coverPlaceholderCreate").style.display = 'none';
            };
        }

        function deleteImageCreate() {
            var img = document.getElementById("previewImage-create");
            img.src = '';
            img.style.display = 'none';
            document.getElementById("coverPlaceholderCreate").style.display = 'block';
            document.getElementById("uploadImage-create").value = '';
        }

        function PreviewImage() {
            document.getElementById('hiddenInput').value = '1';
            var file = document.getElementById("uploadImage").files[0];
            if (!file) return;
            var oFReader = new FileReader();
            oFReader.readAsDataURL(file);
            oFReader.onload = function (oFREvent) {
                var img = document.getElementById("previewImage");
                img.src = oFREvent.target.result;
                img.style.display = 'block';
                document.getElementById("coverPlaceholderEdit").style.display = 'none';
            };
        }

        function deleteImage() {
            var img = document.getElementById("previewImage");
            img.src = '';
            img.style.display = 'none';
            document.getElementById("coverPlaceholderEdit").style.display = 'block';
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
                        if (post.cover) {
                            $('#previewImage').attr('src', post.cover).show();
                            $('#coverPlaceholderEdit').hide();
                        } else {
                            $('#previewImage').hide();
                            $('#coverPlaceholderEdit').show();
                        }
                        $('#demoPdfCurrent').html(post.demo_pdf_url
                            ? '<span class="has-file">✓ Demo PDF mövcuddur</span>'
                            : '<span class="no-file">— Demo PDF yoxdur</span>');
                        $('#fullPdfCurrent').html(post.full_pdf_url
                            ? '<span class="has-file">✓ Tam PDF mövcuddur</span>'
                            : '<span class="no-file">— Tam PDF yoxdur</span>');
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
