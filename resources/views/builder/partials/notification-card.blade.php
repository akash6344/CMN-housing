<article
    class="notification-card tone-{{ $item['tone'] }} {{ !empty($item['unread']) ? 'is-unread' : '' }}"
    data-notification
    data-category="{{ $item['category'] }}"
    data-unread="{{ !empty($item['unread']) ? '1' : '0' }}"
>
    <div class="notification-icon">
        {!! \App\Support\Icon::svg($item['icon']) !!}
    </div>
    <div class="notification-body">
        <div class="notification-title">{{ $item['title'] }}</div>
        <div class="notification-desc">{{ $item['desc'] }}</div>
        <div class="notification-time">{{ $item['time'] }}</div>
    </div>
    <div class="notification-actions">
        <button type="button" class="icon-ghost" data-toast="Marked as read" aria-label="Mark as read">
            {!! \App\Support\Icon::svg('checkSimple') !!}
        </button>
        <button type="button" class="icon-ghost" data-remove-notification data-toast="Notification removed" aria-label="Delete">
            {!! \App\Support\Icon::svg('trash') !!}
        </button>
    </div>
</article>
