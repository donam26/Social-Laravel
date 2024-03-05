@extends('layouts.app')
@section('content')
    <script type="text/javascript">
        // Get order item info
        function editUser(event) {
            let id = $(event.target).data('orderitem-id');
            $.ajax({
                url: "/api/search/user/" + id,
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
            form.find(".name").val(data.name);
            form.find(".image").attr('src', '/storage/images/' + data.image);
            form.find('.sex').val(data.sex);
            form.find('.sex option[value="' + data.sex + '"]').prop('selected', true);
            form.find('.email').val(data.email);
            form.find('.address').val(data.address);
            form.find("#user_id").val(data.id);

        }

        // Update vendor order item
        function updateUser() {
            let id = $('#user_id').val();
            let form = $("#edit-user-form");
            $.ajax({
                url: "/api/customer/" + id,
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
                            <form id="filter-form" action="{{ route('admin.customers') }}" method="GET">
                                <!-- name -->
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="name">Tên người dùng</label>
                                    <div class="col-sm-4">
                                        <input id="name" class="form-control form-control-sm" type="text"
                                            name="name" placeholder="Nhập tên người dùng" value="{{ $filters['name'] }}">
                                    </div>
                                </div>
                                <!-- email -->
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="email">Email </label>
                                    <div class="col-sm-4">
                                        <input id="email" class="form-control form-control-sm" type="text"
                                            name="email" placeholder="Nhập email" value="{{ $filters['email'] }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-sm filter-form__submit" type="submit">Tìm
                                            kiếm</button>
                                        <a class="btn btn-secondary btn-sm filter-form__cancel"
                                            href="{{ route('admin.customers') }}">Hủy</a>
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
                        <div class="card-header"><strong>Danh sách</strong><span class="small ms-1">người dùng</span>

                        </div>
                        <div class="card-body">
                            <!-- table  -->
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Họ tên</th>
                                        <th scope="col">Hình ảnh</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Giới tính</th>
                                        <th scope="col">Địa chỉ</th>
                                        <th scope="col">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $customer)
                                        <tr>
                                            <td>{{ $customer->id }}
                                            </td>
                                            <td>{{ $customer->name }}</td>
                                            <td>
                                                <img style="width: 110px"
                                                    src="{{ asset('/storage/images/' . $customer->image) }}" alt="products"
                                                    srcset="">
                                            </td>
                                            <td>{{ $customer->email }}</td>
                                            <td>@include('components.sex-status', ['status' => $customer->sex])</td>
                                            <td>{{ $customer->address }}</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm" type="button"
                                                    data-orderitem-id="{{ $customer->id }}" data-coreui-toggle="modal"
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
                        <input id="user_id" type="text" hidden>

                        <!-- name  -->
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Họ tên</label>
                            <div class="col-sm-8">
                                <input id="name" class="form-control name" name="name"
                                    placeholder="Họ và tên..." value="" type="text">
                            </div>
                        </div>

                        <!-- image  -->
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Hình ảnh</label>
                            <div class="col-sm-8">
                                <img class="image" style="width: 110px" src="" alt="product-image"
                                    srcset="">
                            </div>
                        </div>

                        <!-- email  -->
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label" for="email">Email</label>
                            <div class="col-sm-8">
                                <input id="email" class="form-control email" name="email" placeholder="Email..."
                                    type="text">
                            </div>
                        </div>

                        <!-- sex  -->
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label " for="sex">Giới tính</label>
                            <div class="col-sm-8">
                                <select name='sex' class="form-control sex" id="gender_select">
                                    <option value="1">Nam</option>
                                    <option value="0">Nữ</option>
                                </select>
                            </div>
                            
                        </div>

                        <!-- address  -->
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label" for="address">Địa chỉ</label>
                            <div class="col-sm-8">
                                <input id="address" class="form-control address" name="address" placeholder="Địa chỉ"
                                    type="text">
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
