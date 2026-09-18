<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-mark">{!! \App\Support\Icon::svg('home') !!}</div>
        <div>
            <div class="brand-name">CMNHousing</div>
            <div class="brand-sub">Builder Portal</div>
        </div>
    </div>

    <nav class="nav">
        @foreach ($navItems as $item)
            <a
                href="{{ route($item['route']) }}"
                class="nav-item {{ ($active ?? '') === $item['id'] ? 'is-active' : '' }}"
            >
                {!! \App\Support\Icon::svg($item['icon']) !!}
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="sidebar-foot">
        <nav>
            @foreach ($footItems as $item)
                <a
                    href="{{ route($item['route']) }}"
                    class="nav-item {{ ($active ?? '') === $item['id'] ? 'is-active' : '' }}"
                >
                    {!! \App\Support\Icon::svg($item['icon']) !!}
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
        <div class="org-card">
            <div class="org-avatar">{{ $builder['initials'] }}</div>
            <div class="org-meta">
                <div class="org-name">{{ $builder['name'] }}</div>
                <div class="org-role">{{ $builder['role'] }}</div>
            </div>
            <button class="org-switch" type="button" title="Switch org">
                {!! \App\Support\Icon::svg('switch') !!}
            </button>
        </div>
    </div>
</aside>
