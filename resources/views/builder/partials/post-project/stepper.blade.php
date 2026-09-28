<nav class="wizard-stepper" data-wizard-stepper aria-label="Project posting steps">
    @foreach ($wizardSteps as $step)
        <button
            type="button"
            class="wizard-step {{ $step['id'] === 1 ? 'is-active' : '' }}"
            data-goto-step="{{ $step['id'] }}"
        >
            <span class="wizard-step-index" data-step-index>{{ $step['id'] }}</span>
            <span class="wizard-step-label">{{ $step['label'] }}</span>
        </button>
        @if (!$loop->last)
            <span class="wizard-step-line" aria-hidden="true"></span>
        @endif
    @endforeach
</nav>
