<article class="card wizard-preview" data-preview-card hidden>
    <div class="wizard-preview-head">Live Preview</div>
    <div class="wizard-preview-media">
        <span class="chip chip-available">New Project</span>
    </div>
    <div class="wizard-preview-body">
        <h3 data-preview-name>{{ !empty($preview['name']) ? $preview['name'] : 'Project Name' }}</h3>
        <p class="wizard-preview-location">
            {!! \App\Support\Icon::svg('map') !!}
            <span data-preview-location>{{ !empty($preview['location']) ? $preview['location'] : 'Location' }}</span>
        </p>
        <div class="wizard-preview-price" data-preview-price>{{ !empty($preview['price']) ? $preview['price'] : 'Price on Request' }}</div>
        <div class="wizard-preview-meta">
            <span class="chip chip-hold" data-preview-status>{{ !empty($preview['status']) ? $preview['status'] : 'Draft' }}</span>
            <span data-preview-meta>{{ !empty($preview['meta']) ? $preview['meta'] : 'Configurations pending' }}</span>
        </div>
        <button type="button" class="btn btn-outline" onclick="window.location='{{ route('builder.projects.preview') }}'">View Sample Preview</button>
    </div>
</article>
