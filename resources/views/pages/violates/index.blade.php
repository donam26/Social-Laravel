@extends('layouts.app')
@section('content')
    <script type="text/javascript">
        // Update vendor order item
        function submit(event) {
            event.preventDefault(); // Ngăn chặn hành vi mặc định của form

            let id = $('#violate_id').val();
            let form = $("#edit-user-form");
            $.ajax({
                url: "/confirmViolate/" + id,
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
                            <form id="filter-form" action="{{ route('admin.violates') }}" method="GET">
                                <!-- title -->
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label" for="title">Tiêu đề</label>
                                    <div class="col-sm-4">
                                        <input id="title" class="form-control form-control-sm" type="text"
                                            name="title" placeholder="Nhập tiêu đề bài viết"
                                            value="{{ $filters['title'] }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-sm filter-form__submit" type="submit">Tìm
                                            kiếm</button>
                                        <a class="btn btn-secondary btn-sm filter-form__cancel"
                                            href="{{ route('admin.violates') }}">Hủy</a>
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
                        <div class="card-header"><strong>Danh sách</strong><span class="small ms-1">bài viết</span>

                        </div>
                        <div class="card-body">
                            <!-- table  -->
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Người đăng</th>
                                        <th scope="col">Lý do</th>
                                        <th scope="col">Tiêu đề</th>
                                        <th scope="col">Hình ảnh</th>
                                        <th scope="col">Người tố cáo</th>
                                        <th scope="col">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($violates as $violate)
                                        <form method="post"
                                            {{-- action="{{ route('admin.violates.delete', ['id' => $violate->id]) }}"> --}} >
                                            @csrf
                                            <input id="violate_id" value="{{ $violate->id }}" type="text" hidden>

                                            <tr>
                                                <td>{{ $violate->id }}
                                                <td>{{ $violate->feel->user->name }}
                                                <td>{{ $violate->content }}
                                                </td>
                                                <td>
                                                    @if ($violate->feel->title)
                                                        {{ $violate->feel->title }}
                                                    @else
                                                        <span class="badge me-1 bg-light text-dark">Không có tiêu đề</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($violate->feel->image)
                                                        <img style="width: 110px"
                                                            src="{{ asset('/storage/images/' . $violate->feel->image) }}"
                                                            alt="products" srcset="">
                                                    @else
                                                        <span class="badge me-1 bg-light text-dark">Không có hình ảnh</span>
                                                    @endif
                                                </td>
                                                <td>{{ $violate->user->name }}
                                                <td>
                                                    <button class="btn btn-primary btn-sm" value="delete" type="submit" formaction="/violate/{{ $violate->id }}">Xóa</button>
                                                    <button class="btn btn-danger btn-sm" value="violate" type="submit" formaction="/confirmViolate/{{ $violate->id }}">Vi phạm</button>
                                                </td>
                                            </tr>
                                        </form>
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

@endsection
