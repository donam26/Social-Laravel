@switch($status)
    @case(App\Enums\SexStatus::MAN)
    <span class="badge me-1 bg-info">{{ App\Enums\SexStatus::getLabel(App\Enums\SexStatus::MAN) }}</span>
    @break

    @case(App\Enums\SexStatus::WOMAN)
    <span class="badge me-1 bg-warning">{{ App\Enums\SexStatus::getLabel(App\Enums\SexStatus::WOMAN) }}</span>
    @break

    {{-- @case(App\Enums\SexStatus::PROCESSING)
    <span class="badge me-1 bg-info">{{ App\Enums\SexStatus::getLabel(App\Enums\SexStatus::PROCESSING) }}</span>
    @break

    @case(App\Enums\SexStatus::COMPLETED)
    <span class="badge me-1 bg-success">{{ App\Enums\SexStatus::getLabel(App\Enums\SexStatus::COMPLETED) }}</span>
    @break --}}

    @default
@endswitch