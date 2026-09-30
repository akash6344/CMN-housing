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
                <h2 id="review-hero-name">{{ !empty($projectForm['name']) ? $projectForm['name'] : 'New Project' }}</h2>
                <p id="review-hero-builder">{{ !empty($projectForm['builder']) ? 'By ' . $projectForm['builder'] : 'Builder / Developer' }}</p>
                <p class="wizard-preview-location">{!! \App\Support\Icon::svg('map') !!} <span id="review-hero-location">{{ !empty($projectForm['location']) ? $projectForm['location'] : 'Location Pending' }}</span></p>
                <div class="review-badges">
                    <span class="chip chip-available">Draft Listing</span>
                    <span class="chip chip-hold" id="review-hero-status">{{ !empty($projectForm['status']) ? $projectForm['status'] : 'Under Construction' }}</span>
                </div>
            </div>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="1">{!! \App\Support\Icon::svg('edit') !!} Edit</button>
        </div>
        <div class="review-gallery">
            <div class="review-gallery-main" id="review-cover-photo" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted,#64748b);font-size:0.88rem;background:#f8fafc;min-height:160px;border-radius:6px;overflow:hidden;">
                <span>No elevation photo uploaded</span>
            </div>
            <div class="review-gallery-side">
                <div class="review-gallery-tile" id="review-tile-video" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted,#64748b);font-size:0.8rem;">Project Video</div>
                <div class="review-gallery-tile" id="review-tile-gallery" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted,#64748b);font-size:0.8rem;">Gallery</div>
                <div class="review-gallery-tile" id="review-tile-plans" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted,#64748b);font-size:0.8rem;">Floor Layout</div>
            </div>
        </div>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>1. Project Details</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="1">Edit</button>
        </div>
        <div class="review-grid">
            <div><span>Project Name</span><strong id="review-proj-name">{{ !empty($projectForm['name']) ? $projectForm['name'] : '—' }}</strong></div>
            <div><span>Builder / Developer</span><strong id="review-proj-builder">{{ !empty($projectForm['builder']) ? $projectForm['builder'] : '—' }}</strong></div>
            <div><span>Location</span><strong id="review-proj-location">{{ !empty($projectForm['location']) ? $projectForm['location'] : '—' }}</strong></div>
            <div><span>Project Type</span><strong id="review-proj-type">{{ !empty($projectForm['type']) ? $projectForm['type'] : '—' }}</strong></div>
            <div><span>Status</span><strong><span class="chip chip-hold" id="review-proj-status">{{ !empty($projectForm['status']) ? $projectForm['status'] : '—' }}</span></strong></div>
            <div><span>Possession Date</span><strong id="review-proj-possession">{{ !empty($projectForm['possessionLabel']) ? $projectForm['possessionLabel'] : (!empty($projectForm['possession']) ? $projectForm['possession'] : '—') }}</strong></div>
            <div><span>RERA Registration No.</span><strong id="review-proj-rera">{{ !empty($projectForm['rera']) ? $projectForm['rera'] : '—' }}</strong></div>
            <div><span>No. of Towers</span><strong id="review-proj-towers">{{ !empty($projectForm['towers']) ? $projectForm['towers'] : '—' }}</strong></div>
            <div><span>Total Units</span><strong id="review-proj-units">{{ !empty($projectForm['totalUnits']) ? $projectForm['totalUnits'] : '—' }}</strong></div>
            <div><span>Land Area</span><strong id="review-proj-land">{{ !empty($projectForm['landArea']) ? $projectForm['landArea'] : '—' }}</strong></div>
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
                <tbody id="review-units-tbody">
                    @forelse ($unitTypes as $unit)
                        <tr>
                            <td><strong>{{ $unit['type'] ?? '—' }}</strong></td>
                            <td>{{ !empty($unit['builtUp']) ? $unit['builtUp'] . ' Sq.Ft' : '—' }}</td>
                            <td>{{ !empty($unit['carpet']) ? $unit['carpet'] . ' Sq.Ft' : '—' }}</td>
                            <td>{{ $unit['priceLabel'] ?? (!empty($unit['price']) ? '₹ ' . number_format($unit['price']) : '—') }}</td>
                            <td>{{ $unit['available'] ?? '—' }}</td>
                            <td>{{ $unit['floors'] ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:20px;color:var(--text-muted, #64748b);">No unit configurations added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="ai-hint" style="margin-top:12px;">Smart Bargain Settings allow buyers to negotiate within your specified range.</div>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>3. Amenities</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="3">Edit</button>
        </div>
        <div class="amenity-grid review-amenities" id="review-amenities-container">
            @php $checkedAmenities = array_filter($amenitiesList ?? [], fn($a) => !empty($a['checked'])); @endphp
            @forelse ($checkedAmenities as $amenity)
                <div class="amenity-item is-checked">
                    {!! \App\Support\Icon::svg('checkSimple') !!}
                    <span>{{ $amenity['label'] }}</span>
                </div>
            @empty
                <p style="color:var(--text-muted, #64748b);font-size:0.9rem;">No amenities selected yet.</p>
            @endforelse
        </div>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>4. Media & Floor Plans</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="4">Edit</button>
        </div>
        <div class="review-grid">
            <div><span>Project Images</span><strong id="review-media-images">{{ $mediaSummary['images'] ?? 0 }} uploaded</strong></div>
            <div><span>2D Floor Plans</span><strong id="review-plans-2d">{{ $mediaSummary['plans2d'] ?? 0 }} uploaded</strong></div>
            <div><span>3D Floor Plans</span><strong id="review-plans-3d">{{ $mediaSummary['plans3d'] ?? 0 }} uploaded</strong></div>
            <div><span>Brochure</span><strong id="review-doc-brochure">{{ !empty($mediaSummary['brochure']) ? 'Uploaded' : 'Not uploaded' }}</strong></div>
            <div><span>Price List</span><strong id="review-doc-pricelist">{{ !empty($mediaSummary['priceList']) ? 'Uploaded' : 'Not uploaded' }}</strong></div>
            <div><span>RERA Certificate</span><strong id="review-doc-rera">{{ !empty($mediaSummary['reraCert']) ? 'Uploaded' : 'Not uploaded' }}</strong></div>
            <div><span>Project Video</span><strong id="review-media-video">{{ !empty($mediaSummary['video']) ? $mediaSummary['video'] : 'Not provided' }}</strong></div>
        </div>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>5. Highlights & Description</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="1">Edit</button>
        </div>
        <div id="review-highlights-container">
            @if (!empty($highlights))
                <ul class="review-highlights">
                    @foreach ($highlights as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @else
                <p style="color:var(--text-muted, #64748b);font-size:0.9rem;margin-bottom:8px;">No highlights added.</p>
            @endif
        </div>
        <p class="review-description" id="review-description-text">{{ !empty($description) ? $description : 'No project description added yet.' }}</p>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>6. Smart Bargain</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="2">Edit</button>
        </div>
        <div class="review-grid">
            <div><span>Enable Smart Bargain</span><strong id="review-bargain-enabled">{{ !empty($smartBargain['enabled']) ? 'Yes' : 'No' }}</strong></div>
            <div><span>Min Price</span><strong id="review-bargain-min">{{ !empty($smartBargain['min']) ? '₹ ' . number_format($smartBargain['min']) : '—' }}</strong></div>
            <div><span>Target Price</span><strong id="review-bargain-target">{{ !empty($smartBargain['target']) ? '₹ ' . number_format($smartBargain['target']) : '—' }}</strong></div>
            <div><span>Max Price</span><strong id="review-bargain-max">{{ !empty($smartBargain['max']) ? '₹ ' . number_format($smartBargain['max']) : '—' }}</strong></div>
        </div>
    </article>

    <label class="wizard-check review-confirm">
        <input type="checkbox" id="confirm-submit">
        <span>I confirm that all the information provided is accurate and I agree to the Terms & Conditions of CMNHousing.</span>
    </label>
</section>
