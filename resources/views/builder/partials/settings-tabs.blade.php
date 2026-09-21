<div class="settings-tabs" data-settings-tabs>
    @foreach ($settingsTabs as $index => $tab)
        <button
            type="button"
            class="settings-tab {{ $index === 0 ? 'is-active' : '' }}"
            data-settings-tab="{{ $tab['id'] }}"
        >
            {!! \App\Support\Icon::svg($tab['icon']) !!}
            <span>{{ $tab['label'] }}</span>
        </button>
    @endforeach
</div>
