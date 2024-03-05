@switch($status)
    @case(App\Enums\FriendStatus::ACCEPTED)
    <span class="badge me-1 bg-info">{{ App\Enums\FriendStatus::getLabel(App\Enums\FriendStatus::ACCEPTED) }}</span>
    @break

    @case(App\Enums\FriendStatus::SENDED)
    <span class="badge me-1 bg-warning">{{ App\Enums\FriendStatus::getLabel(App\Enums\FriendStatus::SENDED) }}</span>
    @break
    @default
@endswitch