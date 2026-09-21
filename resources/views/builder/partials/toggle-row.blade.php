<div class="toggle-row">
    <div>
        <div class="toggle-title">{{ $pref['label'] }}</div>
        <div class="toggle-desc">{{ $pref['desc'] }}</div>
    </div>
    <button
        type="button"
        class="toggle {{ !empty($pref['on']) ? 'is-on' : '' }}"
        role="switch"
        aria-checked="{{ !empty($pref['on']) ? 'true' : 'false' }}"
        aria-label="{{ $pref['label'] }}"
        data-toggle
    >
        <span class="toggle-knob"></span>
    </button>
</div>
