@extends('admin.index')
@section('title')
    Nəticələr | Admin panel
@endsection
@section('css')
    <link href="{{ asset('admin/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
    <style>
        /* Select2 height fix */
        .select2-container--default .select2-selection--single {
            height: 45px !important;
            border: 1px solid #e6e6e6 !important;
            border-radius: 8px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 45px !important;
            padding-left: 14px !important;
            color: #495057 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px !important;
            right: 8px !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #4d6cfa !important;
            box-shadow: 0 0 0 3px rgba(77, 108, 250, 0.1) !important;
        }

        /* Filter card */
        .filter-card {
            background: #f8f9fc;
            border: 1px solid #eef0f5;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 24px;
        }
        .filter-card .form-label-sm {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #7a8194;
            margin-bottom: 6px;
        }
        .filter-card .form-control {
            height: 45px;
            border-radius: 8px;
            border: 1px solid #e6e6e6;
            font-size: 14px;
        }
        .filter-card .form-control:focus {
            border-color: #4d6cfa;
            box-shadow: 0 0 0 3px rgba(77, 108, 250, 0.1);
        }
        .filter-card .btn-action {
            height: 45px;
            border-radius: 8px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0 18px;
        }

        /* Result table */
        .results-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .results-table thead th {
            background: #f8f9fc;
            color: #5a6378;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 14px 12px;
            border: none;
            border-bottom: 1px solid #eef0f5;
            white-space: nowrap;
        }
        .results-table tbody td {
            padding: 14px 12px;
            border-bottom: 1px solid #f1f3f8;
            font-size: 14px;
            color: #3a4258;
            vertical-align: middle;
        }
        .results-table tbody tr:hover {
            background: #fafbfd;
        }
        .results-table tbody tr:last-child td {
            border-bottom: none;
        }
        .results-table a {
            color: #4d6cfa;
            font-weight: 500;
            text-decoration: none;
        }
        .results-table a:hover {
            text-decoration: underline;
        }

        /* Badges for stats */
        .stat-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            min-width: 38px;
            text-align: center;
        }
        .stat-badge.point {
            background: rgba(77, 108, 250, 0.1);
            color: #4d6cfa;
        }
        .stat-badge.correct {
            background: rgba(40, 199, 111, 0.12);
            color: #28c76f;
        }
        .stat-badge.incorrect {
            background: rgba(234, 84, 85, 0.12);
            color: #ea5455;
        }
        .stat-badge.time {
            background: rgba(255, 159, 67, 0.12);
            color: #ff9f43;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9aa0b3;
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        /* Header download button */
        .btn-download {
            background: #4d6cfa;
            border-color: #4d6cfa;
            color: #fff;
            padding: 9px 18px;
            border-radius: 8px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-download:hover {
            background: #3957e8;
            border-color: #3957e8;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(77, 108, 250, 0.25);
        }

        /* Phone/email muted style */
        .text-muted-soft {
            color: #8a91a3;
            font-size: 13px;
        }

        /* Card header */
        .results-card .card-header {
            border-bottom: 1px solid #eef0f5;
            padding: 20px 24px;
            background: #fff;
        }
        .results-card .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3142;
            margin: 0;
        }
        .results-card .card-body {
            padding: 24px;
        }
    </style>
@endsection
@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card results-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title">Nəticələr</h4>
                                <small class="text-muted-soft">Cəmi {{ $posts->total() }} nəticə</small>
                            </div>
                            <?php
                            $queryParams = request()->query();
                            $downloadUrl = route('downloadGuestResult');
                            if (!empty($queryParams)) {
                                $downloadUrl .= '?' . http_build_query($queryParams);
                            }
                            ?>
                            <a href="{{ $downloadUrl }}" class="btn btn-download">
                                <i class="fas fa-download"></i> Excel-ə yüklə
                            </a>
                        </div>
                        <div class="card-body">
                            {{-- Filter card --}}
                            <div class="filter-card">
                                <form method="get" id="searchForm" action="">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label-sm">İmtahan</label>
                                            <select class="form-control search-select" onchange="form.submit()" name="exam_id">
                                                <option value="">Hamısı</option>
                                                @if(!empty($exams[0]))
                                                    @foreach($exams as $exam)
                                                        <option value="{{$exam->id}}" {{isset($_GET['exam_id']) && $_GET['exam_id'] == $exam->id ? 'selected' : ''}}>
                                                            {{$exam->name}}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label-sm">Qonaq</label>
                                            <select class="form-control search-select" onchange="form.submit()" name="customer_id">
                                                <option value="">Hamısı</option>
                                                @if(!empty($guests[0]))
                                                    @foreach($guests as $guest)
                                                        <option value="{{$guest->id}}" {{isset($_GET['customer_id']) && $_GET['customer_id'] == $guest->id ? 'selected' : ''}}>
                                                            {{$guest->name}}{{ !empty($guest->phone) ? ' — '.$guest->phone : '' }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label-sm">Axtarış</label>
                                            <div class="input-group">
                                                <input id="search-input"
                                                       value="{{isset($_GET['search']) ? $_GET['search'] : ''}}"
                                                       name="search" type="search"
                                                       placeholder="Ad, telefon, imtahan və ya bal üzrə axtar..."
                                                       class="form-control"
                                                       style="border-top-right-radius: 0; border-bottom-right-radius: 0"/>
                                                <button type="submit" class="btn btn-primary btn-action"
                                                        style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-action w-100">
                                                <i class="fas fa-eraser"></i> Təmizlə
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- Results table --}}
                            <div class="table-responsive">
                                @if(count($posts) > 0)
                                    <table class="results-table">
                                        <thead>
                                        <tr>
                                            <th width="40"><input type="checkbox" id="checkAll"></th>
                                            <th>İmtahan</th>
                                            <th>Qonaq</th>
                                            <th>Əlaqə</th>
                                            <th class="text-center">Bal</th>
                                            <th class="text-center">Düzgün</th>
                                            <th class="text-center">Səhv</th>
                                            <th class="text-center">Vaxt</th>
                                            <th>Tarix</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($posts as $postItem)
                                            <tr id="row{{$postItem->id}}">
                                                <td>
                                                    <input value="{{$postItem->id}}" class="checkedItem" name="checked" type="checkbox">
                                                </td>
                                                <td>
                                                    @if(!empty($postItem->guestExam))
                                                        <a href="{{route('guest-exam.index', ['guest-exam_id'=>$postItem->guest_exam_id])}}">
                                                            {{$postItem->guestExam->name}}
                                                        </a>
                                                    @else
                                                        <span class="text-muted-soft">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($postItem->guest))
                                                        @auth('admin')
                                                            <a href="{{route('guest.index', ['guest_id'=>$postItem->guest_id])}}">
                                                                {{$postItem->guest->name}}
                                                            </a>
                                                        @else
                                                            <span style="font-weight:500;">{{$postItem->guest->name}}</span>
                                                        @endauth
                                                    @else
                                                        <span class="text-muted-soft">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($postItem->guest) && $postItem->guest->phone)
                                                        <div class="text-muted-soft">
                                                            <i class="fas fa-phone-alt" style="font-size:11px; margin-right:4px;"></i>
                                                            {{ $postItem->guest->phone }}
                                                        </div>
                                                    @endif
                                                    @if(!empty($postItem->guest) && $postItem->guest->email)
                                                        <div class="text-muted-soft" style="margin-top:2px;">
                                                            <i class="fas fa-envelope" style="font-size:11px; margin-right:4px;"></i>
                                                            {{ $postItem->guest->email }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="stat-badge point">{{$postItem->point}}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="stat-badge correct">{{$postItem->correct_count}}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="stat-badge incorrect">{{$postItem->incorrect_count}}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="stat-badge time">
                                                        <i class="far fa-clock" style="font-size:11px; margin-right:3px;"></i>
                                                        {{$postItem->time}}
                                                    </span>
                                                </td>
                                                <td class="text-muted-soft">
                                                    {{$postItem->created_at ? $postItem->created_at->translatedFormat('d.m.Y H:i') : ''}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h5 style="color:#7a8194; font-weight:500;">Nəticə tapılmadı</h5>
                                        <p style="color:#9aa0b3; font-size:14px;">Filterləri dəyişərək yenidən cəhd edin.</p>
                                    </div>
                                @endif

                                @if($posts->hasPages())
                                    <div class="d-flex justify-content-center mt-4">
                                        {{$posts->appends(request()->input())->links()}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('admin/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".search-select").select2({
                width: '100%'
            });

            // Check all
            $('#checkAll').on('change', function () {
                $('.checkedItem').prop('checked', $(this).is(':checked'));
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
@endsection
