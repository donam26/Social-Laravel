@switch($status)
    @case(App\Enums\FeelStatus::PUBLIC)
    <span class="badge me-1 bg-info">{{ App\Enums\FeelStatus::getLabel(App\Enums\FeelStatus::PUBLIC) }}</span>
    @break

    @case(App\Enums\FeelStatus::PRIVATE)
    <span class="badge me-1 bg-warning">{{ App\Enums\FeelStatus::getLabel(App\Enums\FeelStatus::PRIVATE) }}</span>
    @break
    @default
@endswitch