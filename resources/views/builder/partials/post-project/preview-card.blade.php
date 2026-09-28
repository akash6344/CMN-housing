<article class="card wizard-preview" data-preview-card hidden>
    <div class="wizard-preview-head">Live Preview (Sample)</div>
    <div class="wizard-preview-media">
        <span class="chip chip-available">RERA Registered</span>
    </div>
    <div class="wizard-preview-body">
        <h3>{{ $preview['name'] }}</h3>
        <p class="wizard-preview-location">
            {!! \App\Support\Icon::svg('map') !!}
            {{ $preview['location'] }}
        </p>
        <div class="wizard-preview-price">{{ $preview['price'] }} <span>Onwards</span></div>
        <div class="wizard-preview-meta">
            <span class="chip chip-hold">{{ $preview['status'] }}</span>
            <span>{{ $preview['meta'] }}</span>
        </div>
        <button type="button" class="btn btn-outline" onclick="window.location='{{ route('builder.projects.preview') }}'">View Full Preview</button>
    </div>
</article>
