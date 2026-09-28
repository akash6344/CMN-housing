<section class="wizard-panel" data-wizard-panel="5" hidden>
    <div class="banner documents-banner" style="background:#ecfdf3;border-color:#a7f3d0;">
        <div class="banner-icon" style="background:#d1fae5;color:#047857;">{!! \App\Support\Icon::svg('checkSimple') !!}</div>
        <div class="banner-copy">
            <strong>Ready to Submit</strong>
            <span>Review all details before submitting. You can go back and edit any section.</span>
        </div>
    </div>

    <article class="card wizard-section review-hero">
        <div class="review-hero-top">
            <div>
                <h2>{{ $projectForm['name'] }}</h2>
                <p>By {{ $projectForm['builder'] }}</p>
                <p class="wizard-preview-location">{!! \App\Support\Icon::svg('map') !!} {{ $projectForm['location'] }}</p>
                <div class="review-badges">
                    <span class="chip chip-available">RERA Registered</span>
                    <span class="chip chip-approval">Verified Builder</span>
                    <span class="chip chip-new">Bank Loan Available</span>
                    <span class="chip chip-live">Smart Bargain Enabled</span>
                </div>
            </div>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="1">{!! \App\Support\Icon::svg('edit') !!} Edit</button>
        </div>
        <div class="review-gallery">
            <div class="review-gallery-main"></div>
            <div class="review-gallery-side">
                <div class="review-gallery-tile">Watch Video</div>
                <div class="review-gallery-tile">Interior Gallery</div>
                <div class="review-gallery-tile">View Layout</div>
            </div>
        </div>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>1. Project Details</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="1">Edit</button>
        </div>
        <div class="review-grid">
            <div><span>Project Name</span><strong>{{ $projectForm['name'] }}</strong></div>
            <div><span>Builder / Developer</span><strong>{{ $projectForm['builder'] }}</strong></div>
            <div><span>Location</span><strong>{{ $projectForm['location'] }}</strong></div>
            <div><span>Project Type</span><strong>{{ $projectForm['type'] }}</strong></div>
            <div><span>Status</span><strong><span class="chip chip-hold">{{ $projectForm['status'] }}</span></strong></div>
            <div><span>Possession Date</span><strong>{{ $projectForm['possessionLabel'] }}</strong></div>
            <div><span>RERA Registration No.</span><strong>{{ $projectForm['rera'] }}</strong></div>
            <div><span>No. of Towers</span><strong>{{ $projectForm['towers'] }}</strong></div>
            <div><span>Total Units</span><strong>{{ $projectForm['totalUnits'] }}</strong></div>
            <div><span>Land Area</span><strong>{{ $projectForm['landArea'] }}</strong></div>
        </div>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>2. Unit Configuration & Pricing</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="2">Edit</button>
        </div>
        <div class="card table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Unit Type</th>
                        <th>Built-up Area</th>
                        <th>Carpet Area</th>
                        <th>Price</th>
                        <th>Units Available</th>
                        <th>Floor Range</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unitTypes as $unit)
                        <tr>
                            <td><strong>{{ $unit['type'] }}</strong></td>
                            <td>{{ $unit['builtUp'] }} Sq.Ft</td>
                            <td>{{ $unit['carpet'] }} Sq.Ft</td>
                            <td>{{ $unit['priceLabel'] }}</td>
                            <td>{{ $unit['available'] }}</td>
                            <td>{{ $unit['floors'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="ai-hint" style="margin-top:12px;">Smart Bargain Enabled — buyers can negotiate within your set range.</div>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>3. Amenities</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="3">Edit</button>
        </div>
        <div class="amenity-grid review-amenities">
            @foreach ($amenitiesList as $amenity)
                @if (!empty($amenity['checked']))
                    <div class="amenity-item is-checked">
                        {!! \App\Support\Icon::svg('checkSimple') !!}
                        <span>{{ $amenity['label'] }}</span>
                    </div>
                @endif
            @endforeach
        </div>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>4. Media & Floor Plans</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="4">Edit</button>
        </div>
        <div class="review-grid">
            <div><span>Project Images</span><strong>{{ $mediaSummary['images'] }} uploaded</strong></div>
            <div><span>2D Floor Plans</span><strong>{{ $mediaSummary['plans2d'] }} uploaded</strong></div>
            <div><span>3D Floor Plans</span><strong>{{ $mediaSummary['plans3d'] }} uploaded</strong></div>
            <div><span>Brochure</span><strong class="text-success">Uploaded</strong></div>
            <div><span>Price List</span><strong class="text-success">Uploaded</strong></div>
            <div><span>RERA Certificate</span><strong class="text-success">Uploaded</strong></div>
            <div><span>Project Video</span><strong>{{ $mediaSummary['video'] }}</strong></div>
        </div>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>5. Highlights & Description</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="1">Edit</button>
        </div>
        <ul class="review-highlights">
            @foreach ($highlights as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
        <p class="review-description">{{ $description }}</p>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>6. Smart Bargain</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="2">Edit</button>
        </div>
        <div class="review-grid">
            <div><span>Enable Smart Bargain</span><strong>{{ !empty($smartBargain['enabled']) ? 'Yes' : 'No' }}</strong></div>
            <div><span>Min Price</span><strong>{{ $smartBargain['min'] }}</strong></div>
            <div><span>Target Price</span><strong>{{ $smartBargain['target'] }}</strong></div>
            <div><span>Max Price</span><strong>{{ $smartBargain['max'] }}</strong></div>
        </div>
    </article>

    <label class="wizard-check review-confirm">
        <input type="checkbox" id="confirm-submit">
        <span>I confirm that all the information provided is accurate and I agree to the Terms & Conditions of CMNHousing.</span>
    </label>
</section>
