<div class="status-tabs" data-filter-group="{{ $group ?? 'default' }}">
    @foreach ($tabs as $index => $tab)
        <button
            type="button"
            class="status-tab {{ $index === 0 ? 'is-active' : '' }}"
            data-filter-tab
        >{{ $tab }}</button>
    @endforeach
</div>
