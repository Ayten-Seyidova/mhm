@extends('admin.index')

@section('title')
    Tələbələr | Admin panel
@endsection

@section('css')

    <link href="{{ asset('admin/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>

    <style>
        .select2-container--default .select2-selection--single {
            height: 56px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 56px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 16px !important;
        }

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
                            <h4 class="card-title">Tələbələr</h4>
                            <span>Aktiv - <span>{{count($countActive)}}</span></span>
                            <span>Deaktiv - <span>{{count($countDeactive)}}</span></span>
                            <button type="button" class="btn btn-primary btn-rounded mr-2" data-toggle="modal"
                                    data-target="#createModal"><span class="btn-icon-left text-primary"><i
                                        class="fa fa-plus color-info"></i></span>
                                Əlavə et
                            </button>
                        </div>
                        <div class="card-body">
                            <form method="get" id="searchForm" class="row justify-content-center" action="">
                                <input type="hidden" name="is_deleted"
                                       value="{{isset($_GET['is_deleted']) ? $_GET['is_deleted'] : ''}}">
                                <div class="col-2">
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
                                <div class="col-2">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="group_id">
                                        <option value="" disabled selected>Qrup</option>
                                        @if(!empty($groups[0]))
                                            @foreach($groups as $group)
                                                <option
                                                    value="{{$group->id}}" {{isset($_GET['group_id']) && $_GET['group_id'] == $group->id ? 'selected' : ''}}>
                                                    {{$group->name}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="blocked_subject_id">
                                        <option value="" disabled selected>Bloklanan mövzular</option>
                                        @if(!empty($subjects[0]))
                                            @foreach($subjects as $subject)
                                                <option
                                                    value="{{$subject->id}}" {{isset($_GET['blocked_subject_id']) && $_GET['blocked_subject_id'] == $subject->id ? 'selected' : ''}}>
                                                    {{$subject->name . ' - ' . ($subject->created_at ? $subject->created_at->format('d.m.Y H:i') : '')}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="input-group col-4">
                                    <div class="form-item">
                                        <input id="search-input"
                                               value="{{isset($_GET['search']) ? $_GET['search'] : ''}}" name="search"
                                               type="search"
                                               placeholder="Axtarış et" class="form-control"
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
                                        <a href="{{route('customer.index')}}"
                                           class="btn btn-primary clear-btn">
                                            <i class="fas fa-users"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="col-1">
                                        <a href="{{route('customer.index', ['is_deleted'=>1])}}"
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
                                        <th>№</th>
                                        <th>Şəkil</th>
                                        <th>İstifadəçi adı</th>
                                        <th>Şifrə</th>
                                        <th>Ad və soyad</th>
                                        <th>Qruplar</th>
                                        <th>Status</th>
                                        <th>Cihaz sıfırlama</th>
                                        <th>Əməliyyatlar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($posts as $key => $postItem)
                                        <tr id="row{{$postItem->id}}" class="text-center">
                                            <td class="text-center"><input value="{{$postItem->id}}" class="checkedItem"
                                                                           name="checked" type="checkbox"></td>
                                            <td class="text-center">
                                                @if(request('page'))
                                                    {{(request('page')-1)*50 + ($key+1)}}
                                                @else
                                                    {{$key+1}}
                                                @endif
                                            </td>
                                            <td>
                                                <img class="d-block" style="width: 50px; margin: auto"
                                                     src="{{asset($postItem->image)}}"
                                                     alt=""></td>
                                            <td onclick="copyText(this)" style="cursor: pointer"
                                                title="Kopyalamaq üçün klikləyin">{{$postItem->username}}</td>
                                            <td onclick="copyText(this)" style="cursor: pointer"
                                                title="Kopyalamaq üçün klikləyin">{{$postItem->password_text}}</td>
                                            <td>{{$postItem->name}}</td>
                                            <td style="white-space: nowrap">
                                                @if($postItem->group_ids != null && $postItem->group_ids != '' && $postItem->group_ids != 'null')
                                                    @php
                                                        $categoryIds = json_decode($postItem->group_ids, true);
                                                        $thisCategories = \App\Models\Group::whereIn('id', $categoryIds)->get();
                                                    @endphp
                                                    @foreach($thisCategories as $thisCategory)
                                                        <?php
                                                        $date = \App\Models\CustomerGroupDate::where('customer_id', $postItem->id)
                                                            ->where('group_id', $thisCategory->id)
                                                            ->first();

                                                        if (!empty($date)) {
                                                            $formattedDate = $date->date ? \Carbon\Carbon::parse($date->date)->translatedFormat('d.m.Y') : '';
                                                            $formattedEndDate = $date->end_date ? ' - ' . \Carbon\Carbon::parse($date->end_date)->translatedFormat('d.m.Y') : '';
                                                        } else {
                                                            $formattedDate = '';
                                                            $formattedEndDate = '';
                                                        }
                                                        ?>
                                                        <a href="{{route('group.index', ['group_id'=>$thisCategory->id])}}">{{$thisCategory->name}} {{ !empty($date) ? '('.$formattedDate . $formattedEndDate.')' : '' }}</a>
                                                        <br>
                                                    @endforeach
                                                @endif
                                            </td>
                                            {{--                                            <td style="white-space: nowrap">--}}
                                            {{--                                                @if($postItem->blocked_subject_ids != null && $postItem->blocked_subject_ids != '' && $postItem->blocked_subject_ids != 'null')--}}
                                            {{--                                                    @php--}}
                                            {{--                                                        $categoryIds = json_decode($postItem->blocked_subject_ids, true);--}}
                                            {{--                                                        $thisCategories = \App\Models\Subject::whereIn('id', $categoryIds)->get();--}}
                                            {{--                                                    @endphp--}}
                                            {{--                                                    @foreach($thisCategories as $thisCategory)--}}
                                            {{--                                                        <a href="{{route('subject.index', ['subject_id'=>$thisCategory->id])}}">{{$thisCategory->name}}</a>--}}
                                            {{--                                                        <br>--}}
                                            {{--                                                    @endforeach--}}
                                            {{--                                                @endif--}}
                                            {{--                                            </td>--}}
                                            {{--                                            <td>{{$postItem->class}}</td>--}}
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
                                                <a data-id="{{$postItem->id}}"
                                                   class="btn btn-secondary shadow btn-xs sharp deviceItem"><i
                                                        class="fa fa-mobile-phone"></i></a>
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
                                            <button class="checkedBtn btn-primary btn mr-3" value="2">SEÇİLƏNLƏRİ BƏRPA
                                                ET
                                            </button>
                                        @else
                                            <button class="checkedBtn btn-primary btn mr-3" value="2">SEÇİLƏNLƏRİ SİL
                                            </button>
                                        @endif
                                        <button class="checkedBtn btn-success btn" value="3">ÖDƏNİŞ BİLDİRİŞİ GÖNDƏR
                                        </button>
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
                <form id="formCreate" action="{{route('customer.store')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body pb-0 pt-2">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="count">Say</label>
                                    <input class="form-control" required value="{{old('count')}}"
                                           type="number" min="1" name="count" id="count"/>
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
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="groupIds">Qrup</label>
                                    <select name="group_ids[]" multiple required class="form-control search-select"
                                            id="groupIds">
                                        @if(!empty($groups[0]))
                                            @foreach($groups as $group)
                                                <option value="{{$group->id}}">{{$group->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="date-div d-none"></div>
                                <div class="form-group">
                                    <label for="subjectIds">Bloklanan mövzular</label>
                                    <select name="blocked_subject_ids[]" multiple class="form-control search-select"
                                            id="subjectIds">
                                        @if(!empty($subjects[0]))
                                            @foreach($subjects as $subject)
                                                @php
                                                    $groupIds = ($subject->course->group_ids && $subject->course->group_ids != 'null') ? json_decode($subject->course->group_ids, true) : [];
                                                @endphp
                                                <option value="{{$subject->id}}"
                                                        data-category-id="{{ implode(',', $groupIds) }}">{{$subject->name . ' - ' . ($subject->created_at ? $subject->created_at->format('d.m.Y H:i') : '')}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">
                            Ləğv et
                        </button>
                        <button type="submit" id="createBtn" class="btn btn-primary btn-xs">Yadda
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
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="usernameEdit">İstifadəçi adı</label>
                                    <input class="form-control"
                                           type="text" readonly id="usernameEdit"/>
                                </div>
                                <div class="form-group">
                                    <label for="nameEdit">Ad və soyad</label>
                                    <input class="form-control"
                                           type="text" maxlength="100"
                                           name="name" id="nameEdit"/>
                                </div>
                                <div class="form-group">
                                    <label for="emailEdit">E-poçt</label>
                                    <input class="form-control"
                                           type="text" maxlength="100"
                                           name="email" id="emailEdit"/>
                                </div>
                                <div class="form-group">
                                    <label for="classEdit">Sinif</label>
                                    <input class="form-control"
                                           type="text" maxlength="100"
                                           name="class" id="classEdit"/>
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
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="groupIdsEdit">Qrup</label>
                                    <select name="group_ids[]" multiple required class="form-control search-select"
                                            id="groupIdsEdit">
                                        @if(!empty($groups[0]))
                                            @foreach($groups as $group)
                                                <option value="{{$group->id}}"
                                                        class="group-option">{{$group->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="date-divEdit d-none"></div>
                                <div class="form-group">
                                    <label for="subjectIdsEdit">Bloklanan mövzular</label>
                                    <select name="blocked_subject_ids[]" multiple class="form-control search-select"
                                            id="subjectIdsEdit">
                                        @if(!empty($subjects[0]))
                                            @foreach($subjects as $subject)
                                                @php
                                                    $groupIds = ($subject->course->group_ids && $subject->course->group_ids != 'null') ? json_decode($subject->course->group_ids, true) : [];
                                                @endphp
                                                <option value="{{$subject->id}}"
                                                        data-category-id="{{ implode(',', $groupIds) }}"
                                                        class="subject-option">{{$subject->name . ' - ' . ($subject->created_at ? $subject->created_at->format('d.m.Y H:i') : '')}}</option>
                                            @endforeach
                                        @endif
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Ləğv et
                        </button>
                        <button type="button" id="editPost" class="btn btn-primary">Yadda
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            const groupSelect = $('#groupIds');
            const dateDiv = $('.date-div');

            groupSelect.on('change', function () {
                dateDiv.empty();
                const selectedGroups = $(this).find(':selected');

                if (selectedGroups.length > 0) {
                    dateDiv.removeClass('d-none').addClass('d-block');
                    selectedGroups.each(function () {
                        const groupName = $(this).text();
                        const dateItem = `
                    <div class="date-item">
                        <h6>${groupName}</h6>
                        <div class="row">
                            <div class="form-group col-6">
                                <input class="form-control" type="date" name="date[]" />
                            </div>
                            <div class="form-group col-6">
                                <input class="form-control" type="date" name="end_date[]" />
                            </div>
                        </div>
                    </div>
                `;
                        dateDiv.append(dateItem);
                    });
                } else {
                    dateDiv.removeClass('d-block').addClass('d-none');
                }
            });

            groupSelect.trigger('change');

            const groupSelectEdit = $('#groupIdsEdit');
            const dateDivEdit = $('.date-divEdit');

            let existingGroupDates = {};

            groupSelectEdit.on('change', function () {
                const selectedGroupsEdit = $(this).find(':selected');
                // const selectedGroupIds = selectedGroupsEdit.map(function () {
                //     return $(this).val();
                // }).get();

                dateDivEdit.find('.date-item').each(function () {
                    const groupName = $(this).find('h6').text();
                    const date = $(this).find('input[name="date[]"]').val();
                    const endDate = $(this).find('input[name="end_date[]"]').val();
                    existingGroupDates[groupName] = {date, endDate};
                });

                dateDivEdit.empty();

                if (selectedGroupsEdit.length > 0) {
                    dateDivEdit.removeClass('d-none').addClass('d-block');

                    selectedGroupsEdit.each(function () {
                        const groupNameEdit = $(this).text();
                        // const groupId = $(this).val();

                        const savedDates = existingGroupDates[groupNameEdit] || {};

                        const dateItemEdit = `
                <div class="date-item">
                    <h6>${groupNameEdit}</h6>
                    <div class="row">
                        <div class="form-group col-6">
                            <input class="form-control" type="date" name="date[]" value="${savedDates.date || ''}" />
                        </div>
                        <div class="form-group col-6">
                            <input class="form-control" type="date" name="end_date[]" value="${savedDates.endDate || ''}" />
                        </div>
                    </div>
                </div>
            `;
                        dateDivEdit.append(dateItemEdit);
                    });
                } else {
                    dateDivEdit.removeClass('d-block').addClass('d-none');
                }

                Object.keys(existingGroupDates).forEach(groupName => {
                    if (!selectedGroupsEdit.map(function () {
                        return $(this).text();
                    }).get().includes(groupName)) {
                        delete existingGroupDates[groupName];
                    }
                });
            });
            groupSelectEdit.trigger('change');
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

        $(document).ready(function () {
            $(".search-select").select2();
        });

        function copyText(tdElement) {
            const textToCopy = tdElement.innerText;

            const tempInput = document.createElement('input');
            tempInput.value = textToCopy;
            document.body.appendChild(tempInput);

            tempInput.select();
            document.execCommand('copy');

            document.body.removeChild(tempInput);

            Swal.fire({
                title: 'Uğurlu',
                text: 'Kopyalandı',
                icon: 'success',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const groupSelect = $('#groupIds');
            const subjectSelect = $('#subjectIds');
            const allSubjects = $('#subjectIds option').clone();

            groupSelect.on('change', function () {
                const selectedGroups = groupSelect.val();
                subjectSelect.empty();

                allSubjects.each(function () {
                    const option = $(this);
                    const categoryIds = option.data('category-id') ? option.data('category-id').toString().split(',') : [];

                    const isVisible = selectedGroups.some(groupId => categoryIds.includes(groupId));

                    if (isVisible) {
                        subjectSelect.append(option.clone());
                    }
                });

                if (subjectSelect.find('option').length === 0) {
                    subjectSelect.append('<option disabled>No matching subjects</option>');
                }

                subjectSelect.trigger('change.select2');
            });

            groupSelect.trigger('change');

            const groupSelect1 = $('#groupIdsEdit');
            const subjectSelect1 = $('#subjectIdsEdit');
            const allSubjects1 = $('#subjectIdsEdit option').clone();

            groupSelect1.on('change', function () {
                const previouslySelected = subjectSelect1.val() || [];
                const selectedGroups1 = groupSelect1.val();
                subjectSelect1.empty();

                allSubjects1.each(function () {
                    const option1 = $(this);
                    const categoryIds1 = option1.data('category-id') ? option1.data('category-id').toString().split(',') : [];

                    const isVisible1 = selectedGroups1.some(groupId1 => categoryIds1.includes(groupId1));
                    if (isVisible1) {
                        if (previouslySelected.includes(option1.val())) {
                            option1.prop('selected', true);
                        }
                        subjectSelect1.append(option1.clone());
                    }
                });

                if (subjectSelect1.find('option').length === 0) {
                    subjectSelect1.append('<option disabled>No matching subjects</option>');
                }

                subjectSelect1.val(previouslySelected).trigger('change.select2');
            });

            groupSelect1.trigger('change');
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
                    let route = '{{route('customer.checked')}}';
                    let currentVal = $(this).val();

                    let text = '';
                    let resultText = '';

                    if (currentVal == '0') {
                        text = 'Seçilənləri deaktiv etmək istədiyinizə əminsiniz?';
                        resultText = 'Deaktiv edildi';
                    } else if (currentVal == '1') {
                        text = 'Seçilənləri aktiv etmək istədiyinizə əminsiniz?';
                        resultText = 'Aktiv edildi';
                    } else if (currentVal == '2') {
                        text = 'Əminsinizmi?';
                        resultText = 'Uğurlu';
                    } else {
                        text = 'Ödəniş bildirişi göndərmək istədiyinizə əminsinizmi?';
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
                                    } else if (currentVal == '2') {
                                        for (let i of checkedArr) {
                                            $('#row' + i).remove();
                                        }
                                    }

                                    $('.checkedItem').prop('checked', false);
                                    checkedArr = [];
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Xəbərdarlıq',
                                        confirmButtonColor: '#163A76',
                                        text: resultText,
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
                let route = '{{route('customer.destroy', ['customer'=>'id'])}}';
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
                                    confirmButtonColor: '#163A76',
                                    text: "Uğurlu",
                                    confirmButtonText: 'Tamam'
                                })
                            }
                        })
                    }
                })
            });

            $('.deviceItem').click(function () {
                let dataID = $(this).data('id');
                let route = '{{route('customer.device')}}';
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
                            method: 'POST',
                            data: {
                                id: dataID,
                            },
                            async: false,
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Xəbərdarlıq',
                                    confirmButtonColor: '#163A76',
                                    text: "Uğurlu",
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
                    url: '{{route('customer.changeStatus')}}',
                    method: 'POST',
                    data: {
                        id: dataID
                    },
                    async: false,
                })
            });

            $('#editPost').click(function () {
                if ($("#newPasswordEdit").val() !== $("#reNewPasswordEdit").val()) {
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
                let statusEdit = $('#statusEdit');
                let usernameEdit = $('#usernameEdit');
                let classEdit = $('#classEdit');

                let groupSelect1 = document.getElementById('groupIdsEdit');

                let route = '{{route('customer.edit', ['customer'=>'edit'])}}';
                route = route.replace('edit', dataID);
                let routeUpdate = '{{route('customer.update', ['customer' => 'update'])}}';
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
                        var dates = response.dates;

                        nameEdit.val(post.name);
                        emailEdit.val(post.email);
                        usernameEdit.val(post.username);
                        classEdit.val(post.class);

                        if (post.group_ids != 'null' && post.group_ids != '' && post.group_ids != null) {
                            let companyIds = JSON.parse(post.group_ids).map(id => id.toString());

                            $('.group-option').each(function () {
                                let checkboxValue = $(this).val().toString();
                                if (companyIds.includes(checkboxValue)) {
                                    $(this).prop('selected', true).trigger('change');
                                } else {
                                    $(this).prop('selected', false).trigger('change');
                                }
                            });

                            groupSelect1.dispatchEvent(new Event('change'));
                        }

                        if (post.blocked_subject_ids != 'null' && post.blocked_subject_ids != '' && post.blocked_subject_ids != null) {
                            let companyIds = JSON.parse(post.blocked_subject_ids).map(id => id.toString());

                            $('.subject-option').each(function () {
                                let checkboxValue = $(this).val().toString();
                                if (companyIds.includes(checkboxValue)) {
                                    $(this).prop('selected', true).trigger('change');
                                } else {
                                    $(this).prop('selected', false).trigger('change');
                                }
                            });
                        }

                        if (post.status == 1) {
                            statusEdit.attr('checked', true);
                        } else {
                            statusEdit.attr('checked', false);
                        }

                        var dateDivEdit = $('.date-divEdit');
                        dateDivEdit.empty(); // Önceki içerikleri temizle

                        if (dates.length > 0) {
                            dateDivEdit.removeClass('d-none').addClass('d-block'); // Görünür yap

                            dates.forEach(function (dateItem) {
                                let dateHtml = `
            <div class="date-item">
                <h6>${dateItem.group_name || 'Grup'}</h6> <!-- Eğer group_name yoksa 'Grup' yaz -->
                                <div class="row">
                                <div class="form-group col-6">
                                <input class="form-control" type="date" name="date[]" value="${dateItem.date || ''}" />
                                </div>
                                <div class="form-group col-6">
                                <input class="form-control" type="date" name="end_date[]" value="${dateItem.end_date || ''}" />
                                </div>
                                </div>
                                </div>
                                `
                            ;
                                    dateDivEdit.append(dateHtml);
                                });
                            } else {
                                dateDivEdit.addClass('d-none').removeClass('d-block'); // Boşsa gizle
                            }
                                                }
                                            });
                                        }

                                        let searchParams = new URLSearchParams(window.location.search)
                                        if (searchParams.has('customer_id')) {
                                            let dataId = searchParams.get('customer_id');
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
                                    })
                                    ;
</script>
@endsection
