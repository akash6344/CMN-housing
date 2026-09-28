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
                <h2>{{ !empty($projectForm['name']) ? $projectForm['name'] : 'New Project' }}</h2>
                <p>{{ !empty($projectForm['builder']) ? 'By ' . $projectForm['builder'] : 'Builder / Developer' }}</p>
                <p class="wizard-preview-location">{!! \App\Support\Icon::svg('map') !!} {{ !empty($projectForm['location']) ? $projectForm['location'] : 'Location Pending' }}</p>
                <div class="review-badges">
                    <span class="chip chip-available">Draft Listing</span>
                    @if (!empty($projectForm['status']))
                        <span class="chip chip-hold">{{ $projectForm['status'] }}</span>
                    @endif
                </div>
            </div>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="1">{!! \App\Support\Icon::svg('edit') !!} Edit</button>
        </div>
        <div class="review-gallery">
            <div class="review-gallery-main" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted,#64748b);font-size:0.88rem;">
                No elevation photo uploaded
            </div>
            <div class="review-gallery-side">
                <div class="review-gallery-tile" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted,#64748b);font-size:0.8rem;">Project Video</div>
                <div class="review-gallery-tile" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted,#64748b);font-size:0.8rem;">Gallery</div>
                <div class="review-gallery-tile" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted,#64748b);font-size:0.8rem;">Floor Layout</div>
            </div>
        </div>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>1. Project Details</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="1">Edit</button>
        </div>
        <div class="review-grid">
            <div><span>Project Name</span><strong>{{ !empty($projectForm['name']) ? $projectForm['name'] : '—' }}</strong></div>
            <div><span>Builder / Developer</span><strong>{{ !empty($projectForm['builder']) ? $projectForm['builder'] : '—' }}</strong></div>
            <div><span>Location</span><strong>{{ !empty($projectForm['location']) ? $projectForm['location'] : '—' }}</strong></div>
            <div><span>Project Type</span><strong>{{ !empty($projectForm['type']) ? $projectForm['type'] : '—' }}</strong></div>
            <div><span>Status</span><strong><span class="chip chip-hold">{{ !empty($projectForm['status']) ? $projectForm['status'] : '—' }}</span></strong></div>
            <div><span>Possession Date</span><strong>{{ !empty($projectForm['possessionLabel']) ? $projectForm['possessionLabel'] : (!empty($projectForm['possession']) ? $projectForm['possession'] : '—') }}</strong></div>
            <div><span>RERA Registration No.</span><strong>{{ !empty($projectForm['rera']) ? $projectForm['rera'] : '—' }}</strong></div>
            <div><span>No. of Towers</span><strong>{{ !empty($projectForm['towers']) ? $projectForm['towers'] : '—' }}</strong></div>
            <div><span>Total Units</span><strong>{{ !empty($projectForm['totalUnits']) ? $projectForm['totalUnits'] : '—' }}</strong></div>
            <div><span>Land Area</span><strong>{{ !empty($projectForm['landArea']) ? $projectForm['landArea'] : '—' }}</strong></div>
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
                    @forelse ($unitTypes as $unit)
                        <tr>
                            <td><strong>{{ $unit['type'] ?? '—' }}</strong></td>
                            <td>{{ !empty($unit['builtUp']) ? $unit['builtUp'] . ' Sq.Ft' : '—' }}</td>
                            <td>{{ !empty($unit['carpet']) ? $unit['carpet'] . ' Sq.Ft' : '—' }}</td>
                            <td>{{ $unit['priceLabel'] ?? (!empty($unit['price']) ? '₹ ' . $unit['price'] : '—') }}</td>
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
        <div class="amenity-grid review-amenities">
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
            <div><span>Project Images</span><strong>{{ $mediaSummary['images'] ?? 0 }} uploaded</strong></div>
            <div><span>2D Floor Plans</span><strong>{{ $mediaSummary['plans2d'] ?? 0 }} uploaded</strong></div>
            <div><span>3D Floor Plans</span><strong>{{ $mediaSummary['plans3d'] ?? 0 }} uploaded</strong></div>
            <div><span>Brochure</span><strong>{{ !empty($mediaSummary['brochure']) ? 'Uploaded' : 'Not uploaded' }}</strong></div>
            <div><span>Price List</span><strong>{{ !empty($mediaSummary['priceList']) ? 'Uploaded' : 'Not uploaded' }}</strong></div>
            <div><span>RERA Certificate</span><strong>{{ !empty($mediaSummary['reraCert']) ? 'Uploaded' : 'Not uploaded' }}</strong></div>
            <div><span>Project Video</span><strong>{{ !empty($mediaSummary['video']) ? $mediaSummary['video'] : 'Not provided' }}</strong></div>
        </div>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>5. Highlights & Description</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="1">Edit</button>
        </div>
        @if (!empty($highlights))
            <ul class="review-highlights">
                @foreach ($highlights as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        @else
            <p style="color:var(--text-muted, #64748b);font-size:0.9rem;margin-bottom:8px;">No highlights added.</p>
        @endif
        <p class="review-description">{{ !empty($description) ? $description : 'No project description added yet.' }}</p>
    </article>

    <article class="card wizard-section">
        <div class="wizard-section-head">
            <h2>6. Smart Bargain</h2>
            <button type="button" class="btn btn-outline btn-sm" data-goto-step="2">Edit</button>
        </div>
        <div class="review-grid">
            <div><span>Enable Smart Bargain</span><strong>{{ !empty($smartBargain['enabled']) ? 'Yes' : 'No' }}</strong></div>
            <div><span>Min Price</span><strong>{{ !empty($smartBargain['min']) ? $smartBargain['min'] : '—' }}</strong></div>
            <div><span>Target Price</span><strong>{{ !empty($smartBargain['target']) ? $smartBargain['target'] : '—' }}</strong></div>
            <div><span>Max Price</span><strong>{{ !empty($smartBargain['max']) ? $smartBargain['max'] : '—' }}</strong></div>
        </div>
    </article>

    <label class="wizard-check review-confirm">
        <input type="checkbox" id="confirm-submit">
        <span>I confirm that all the information provided is accurate and I agree to the Terms & Conditions of CMNHousing.</span>
    </label>
</section>
