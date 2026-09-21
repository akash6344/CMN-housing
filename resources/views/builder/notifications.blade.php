@extends('layouts.builder')

@section('content')
    <div class="notifications-toolbar">
        <div class="status-tabs notifications-filters" data-filter-group="notifications">
            @foreach ($notificationFilters as $index => $tab)
                <button
                    type="button"
                    class="status-tab {{ $index === 0 ? 'is-active' : '' }}"
                    data-filter-tab
                    data-notify-filter="{{ strtolower($tab['label']) }}"
                >
                    {{ $tab['label'] }}
                    @if (!empty($tab['badge']))
                        <span class="filter-badge">{{ $tab['badge'] }}</span>
                    @endif
                </button>
            @endforeach
        </div>

        <button type="button" class="btn btn-outline" data-mark-all-read>
            {!! \App\Support\Icon::svg('checkSimple') !!} Mark All as Read
        </button>
    </div>

    <div class="notifications-list" data-notifications-list>
        @foreach ($notifications as $item)
            @include('builder.partials.notification-card', ['item' => $item])
        @endforeach
    </div>
@endsection
