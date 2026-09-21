<div class="field {{ $class ?? '' }}">
    <label for="{{ $id ?? '' }}">{{ $label }}</label>
    <input
        id="{{ $id ?? '' }}"
        type="{{ $type ?? 'text' }}"
        value="{{ $value ?? '' }}"
        placeholder="{{ $placeholder ?? '' }}"
        @if (!empty($readonly)) readonly @endif
    >
</div>
