<section class="wizard-panel" data-wizard-panel="4" hidden>
    <article class="card wizard-section">
        <div class="wizard-section-head">
            <span class="wizard-section-num">4</span>
            <div>
                <h2>Project Images & Media</h2>
                <p>Upload high-quality photos to attract more buyers.</p>
            </div>
        </div>

        <div class="media-upload-row">
            <div class="upload-dropzone" data-toast="Image upload coming soon">
                {!! \App\Support\Icon::svg('upload') !!}
                <strong>Drag & drop images or click to upload</strong>
                <span>JPG, PNG up to 10MB each</span>
                <button type="button" class="btn btn-outline btn-sm">Upload Images</button>
            </div>
            <ul class="media-checklist">
                <li>{!! \App\Support\Icon::svg('checkSimple') !!} Project Elevation</li>
                <li>{!! \App\Support\Icon::svg('checkSimple') !!} Amenities</li>
                <li>{!! \App\Support\Icon::svg('checkSimple') !!} Sample Flats</li>
                <li>{!! \App\Support\Icon::svg('checkSimple') !!} Site Photos</li>
                <li>{!! \App\Support\Icon::svg('checkSimple') !!} Brochure</li>
            </ul>
        </div>

        <div class="media-thumbs">
            <button type="button" class="media-thumb media-thumb-add" data-toast="Upload images">
                {!! \App\Support\Icon::svg('plus') !!}
                <span>Upload Images</span>
            </button>
        </div>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <span class="wizard-section-num">5</span>
            <div>
                <h2>Floor Plans</h2>
                <p>Add 2D and 3D plans for each configuration.</p>
            </div>
            <button type="button" class="btn btn-outline btn-sm" data-toast="Add floor plan">+ Add Floor Plan</button>
        </div>

        <div class="card" style="padding: 28px; text-align: center; color: var(--text-muted, #64748b); border: 1px dashed var(--border-color, #e2e8f0); border-radius: 8px;">
            <p style="margin-bottom: 6px; font-weight: 500;">No floor plans uploaded yet</p>
            <p style="font-size: 0.85rem;">Click the "+ Add Floor Plan" button above to upload 2D or 3D floor plans for your unit configurations.</p>
        </div>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <span class="wizard-section-num">6</span>
            <div>
                <h2>Brochure, Documents & Video</h2>
            </div>
        </div>

        <div class="status-tabs" data-filter-group="doc-tabs">
            <button type="button" class="status-tab is-active" data-filter-tab>Brochure</button>
            <button type="button" class="status-tab" data-filter-tab>Price List</button>
            <button type="button" class="status-tab" data-filter-tab>RERA Certificate</button>
            <button type="button" class="status-tab" data-filter-tab>Other Documents</button>
        </div>

        <div class="upload-dropzone upload-dropzone-sm" data-toast="PDF upload coming soon">
            {!! \App\Support\Icon::svg('file') !!}
            <strong>Upload PDF</strong>
            <span>Max 20 MB</span>
        </div>

        @include('builder.partials.form-field', [
            'id' => 'project-video',
            'label' => 'Project Video (YouTube or Vimeo Link)',
            'value' => $mediaSummary['video'] ?? '',
            'placeholder' => 'Paste YouTube / Vimeo video URL',
        ])

        @include('builder.partials.form-field', [
            'id' => 'virtual-tour',
            'label' => 'Virtual Tour (Optional)',
            'placeholder' => 'Matterport / 360° walkthrough link',
        ])

        <div class="media-prefs">
            <label class="wizard-check"><input type="checkbox"> <span>Show photos in gallery</span></label>
            <label class="wizard-check"><input type="checkbox"> <span>Enable 2D/3D floor plans on listing page</span></label>
            <label class="wizard-check"><input type="checkbox"> <span>Show video / virtual tour</span></label>
        </div>
    </article>
</section>
