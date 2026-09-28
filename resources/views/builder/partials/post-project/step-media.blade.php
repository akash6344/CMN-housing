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
            @for ($i = 1; $i <= 4; $i++)
                <div class="media-thumb">
                    <button type="button" class="media-thumb-remove" aria-label="Remove" data-toast="Image removed">
                        {!! \App\Support\Icon::svg('x') !!}
                    </button>
                </div>
            @endfor
            <button type="button" class="media-thumb media-thumb-add" data-toast="Add more images">
                {!! \App\Support\Icon::svg('plus') !!}
                <span>Add More</span>
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

        <div class="status-tabs floor-plan-tabs" data-filter-group="floor-plans">
            <button type="button" class="status-tab is-active" data-filter-tab>2 BHK (2)</button>
            <button type="button" class="status-tab" data-filter-tab>3 BHK (2)</button>
            <button type="button" class="status-tab" data-filter-tab>4 BHK (0)</button>
        </div>

        <div class="floor-plan-cards">
            <div class="card floor-plan-card">
                <div class="floor-plan-preview is-2d"></div>
                <div>
                    <strong>2D Floor Plan</strong>
                    <p>3 BHK - 1380 Sq.Ft</p>
                    <div class="unit-plan-actions">
                        <button type="button" class="btn btn-outline btn-sm" data-toast="Replace plan">Replace</button>
                        <button type="button" class="btn btn-outline btn-sm" data-toast="View plan">View</button>
                    </div>
                </div>
            </div>
            <div class="card floor-plan-card">
                <div class="floor-plan-preview is-3d"></div>
                <div>
                    <strong>3D Floor Plan</strong>
                    <p>3 BHK - 1380 Sq.Ft</p>
                    <div class="unit-plan-actions">
                        <button type="button" class="btn btn-outline btn-sm" data-toast="Replace plan">Replace</button>
                        <button type="button" class="btn btn-outline btn-sm" data-toast="View plan">View</button>
                    </div>
                </div>
            </div>
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
            'value' => 'https://youtube.com/watch?v=example',
            'placeholder' => 'Paste video URL',
        ])

        @include('builder.partials.form-field', [
            'id' => 'virtual-tour',
            'label' => 'Virtual Tour (Optional)',
            'placeholder' => 'Matterport / 360° walkthrough link',
        ])

        <div class="media-prefs">
            <label class="wizard-check"><input type="checkbox" checked> <span>Show photos in gallery</span></label>
            <label class="wizard-check"><input type="checkbox" checked> <span>Enable 2D/3D floor plans on listing page</span></label>
            <label class="wizard-check"><input type="checkbox" checked> <span>Show video / virtual tour</span></label>
        </div>
    </article>
</section>
