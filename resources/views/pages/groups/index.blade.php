@extends('layouts.app')
@section('content')
    <script type="text/javascript">
        // Get order item info
        function editUser(event) {
            let id = $(event.target).data('orderitem-id');
            $.ajax({
                url: "/api/search/group/" + id,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        loadUserInfo(response.data);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText);
                }
            });
        }

        // Load order item info
        function loadUserInfo(data) {
            let form = $("#edit-user-form");
            form.find(".name").text(data.user.name);
            form.find(".title").val(data.title);
            form.find(".image").attr('src', '/storage/images/' + data.image);
            form.find('.status').val(data.status);
            form.find('.status option[value="' + data.status + '"]').prop('selected', true);
            form.find("#group_id").val(data.id);
        }

        // Update vendor order item
        function updateUser() {
            let id = $('#group_id').val();
            let form = $("#edit-user-form");
            $.ajax({
                url: "/api/group/" + id,
                type: "POST",
                data: form.serialize(),
                dataType: "json",
                success: function(response) {
                    console.log(response)
                    if (response.status === 'success') {
                        location.reload();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText);
                }
            });
        }
    </script>
    <div class="body flex-grow-1 px-3">
        <div class="container-lg">
            @include('components.alert')

            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header"><strong>Tìm kiếm</strong></div>
                        <div class="card-body">
                            <form id="filter-form" action="{{ route('admin.groups') }}" method="GET">
                                <!-- name -->
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="name">Tên nhóm</label>
                                    <div class="col-sm-4">
                                        <input id="name" class="form-control form-control-sm" type="text"
                                            name="name" placeholder="Nhập tên nhóm"
                                            value="{{ $filters['name'] }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-sm filter-form__submit" type="submit">Tìm
                                            kiếm</button>
                                        <a class="btn btn-secondary btn-sm filter-form__cancel"
                                            href="{{ route('admin.groups') }}">Hủy</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header"><strong>Danh sách</strong><span class="small ms-1">nhóm</span>

                        </div>
                        <div class="card-body">
                            <!-- table  -->
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Tên nhóm</th>
                                        <th scope="col">Quản trị viên</th>
                                        <th scope="col">Hình ảnh</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groups as $group)
                                        <tr>
                                            <td>{{ $group->id }}
                                            </td>
                                            <td>{{ $group->name }}</td>
                                            <td>{{ $group->createdUser->name }}</td>
                                            <td>
                                                @if ($group->image)
                                                    <img style="width: 110px"
                                                        src="{{ asset('/storage/images/' . $group->image) }}" alt="products"
                                                        srcset="">
                                                @else
                                                    <span class="badge me-1 bg-light text-dark">Không có hình ảnh</span>
                                                @endif
                                            </td>
                                            <td>@include('components.feel-status', ['status' => $group->status])</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" type="button"
                                                    data-orderitem-id="{{ $group->id }}" data-coreui-toggle="modal"
                                                    data-coreui-target="#edit-user-modal" onclick="editUser(event)">Cập
                                                    nhật</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @include('components.pagination')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit vendor order item popup  -->
    <div class="modal fade" id="edit-user-modal" tabindex="-1" aria-labelledby="edit-user-modal-title"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="edit-user-modal-title" class="modal-title">Cập nhật user</h5>
                    <button class="btn-close" type="button" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-user-form" action="POST">
                        @csrf
                        <input id="group_id" type="text" hidden>
                        <!-- name  -->
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Người đăng</label>
                            <div class="col-sm-8">
                                <span id="name" class="form-control name"></span>
                            </div>
                        </div>
                        
                        <!-- title  -->
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Tiêu đề</label>
                            <div class="col-sm-8">
                                <input id="title" class="form-control title" name="title"
                                    placeholder="Tiêu đề nhóm..." value="" type="text">
                            </div>
                        </div>

                        <!-- image  -->
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Hình ảnh</label>
                            <div class="col-sm-8">
                                <img class="image" style="width: 110px" src="" alt="product-image" srcset="">
                            </div>
                        </div>

                        <!-- status  -->
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label " for="status">Giới tính</label>
                            <div class="col-sm-8">
                                <select name='status' class="form-control status" id="gender_select">
                                    <option value="1">Công khai</option>
                                    <option value="0">Riêng tư</option>
                                </select>
                            </div>

                        </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm" type="button" onclick="updateUser()">Cập nhật</button>
                    <button class="btn btn-secondary btn-sm" type="button" data-coreui-dismiss="modal">Đóng</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
