<!-- perpage  -->
<div class="row mb-3">
    <div class="col-12">
        <form class="mb-0" id="perpage_form" action="{{ route(Route::currentRouteName()) }}" method="GET">
            <select id="perpage_list" name="perpage"
                class="form-select form-select-sm fit-content"
                aria-label=".form-select-sm example">
                <option value="10"
                    {{ $perpage === 10 ? 'selected' : '' }}>
                    10 / Trang
                </option>
                <option value="20"
                    {{ $perpage === 20 ? 'selected' : '' }}>
                    20 / Trang
                </option>
                <option value="50"
                    {{ $perpage === 50 ? 'selected' : '' }}>
                    50 / Trang
                </option>
                <option value="100"
                    {{ $perpage === 100 ? 'selected' : '' }}>
                    100 / Trang
                </option>
            </select>
        </form>
    </div>
</div>