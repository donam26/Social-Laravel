<div class="mb-2 row">
    <label class="col-sm-2 col-form-label" for="customer">{{ $title ?? "Tìm kiếm user" }}</label>
    <div class="col-sm-4">
        <select id="select2-search-user-{{ uniqid() }}" class="form-control form-control-sm select2-search-user" name="{{ $name ?? 'user_id' }}" placeholder="{{ $placeholder ?? 'Nhập tên user' }}" >
        </select>
    </div>
</div>