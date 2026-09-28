<section class="wizard-panel" data-wizard-panel="2" hidden>
    <article class="card wizard-section">
        <div class="wizard-section-head">
            <span class="wizard-section-num">2</span>
            <div>
                <h2>Unit Configuration & Pricing</h2>
                <p>Add unit configurations, pricing and floor plans for your project.</p>
            </div>
            <button type="button" class="btn btn-outline btn-sm" data-add-unit>
                {!! \App\Support\Icon::svg('plus') !!} Add New Unit Type
            </button>
        </div>

        <div class="unit-cards" data-unit-cards>
            <div class="card unit-card" data-unit-card>
                <div class="unit-card-head">
                    <div class="field" style="margin-bottom: 0; min-width: 220px;">
                        <input type="text" data-unit-field="unit_type" placeholder="Unit Type (e.g. 2 BHK, 3 BHK, Villa)" style="font-weight: 600; font-size: 1.05rem;">
                    </div>
                    <button type="button" class="doc-link text-danger" data-remove-unit data-toast="Unit removed">
                        {!! \App\Support\Icon::svg('trash') !!} Remove
                    </button>
                </div>
                <div class="form-grid form-grid-2">
                    <div class="field">
                        <label>Built-up Area (Sq.Ft) *</label>
                        <input type="number" step="0.01" data-unit-field="built_up_area" placeholder="e.g. 1200">
                    </div>
                    <div class="field">
                        <label>Carpet Area (Sq.Ft)</label>
                        <input type="number" step="0.01" data-unit-field="carpet_area" placeholder="e.g. 950">
                    </div>
                    <div class="field">
                        <label>Price (₹) *</label>
                        <input type="number" step="1" data-unit-field="price" placeholder="e.g. 8500000">
                    </div>
                    <div class="field">
                        <label>Price per Sq.Ft</label>
                        <input type="text" data-unit-field="price_per_sqft" placeholder="Calculated automatically" readonly>
                    </div>
                    <div class="field">
                        <label>No. of Units Available</label>
                        <input type="number" data-unit-field="available_units" placeholder="e.g. 50">
                    </div>
                    <div class="field">
                        <label>Floor Range</label>
                        <select data-unit-field="floor_range">
                            <option value="" disabled selected>Select Floor Range</option>
                            <option value="All Floors">All Floors</option>
                            <option value="1st - 10th Floor">1st - 10th Floor</option>
                            <option value="11th - 20th Floor">11th - 20th Floor</option>
                            <option value="1st - 20th Floor">1st - 20th Floor</option>
                            <option value="10th - 30th Floor">10th - 30th Floor</option>
                        </select>
                    </div>
                </div>
                <label class="wizard-check">
                    <input type="checkbox" data-unit-field="show_floor_plan">
                    <span>Show 2D/3D Floor Plan for this unit type</span>
                </label>
                <div class="unit-plan-box">
                    <div class="unit-plan-thumb"></div>
                    <div>
                        <strong>Unit Floor Plan</strong>
                        <p>Upload or select floor plan for this unit type.</p>
                        <div class="unit-plan-actions">
                            <button type="button" class="btn btn-outline btn-sm" data-toast="Upload 2D plan">Upload 2D Plan</button>
                            <button type="button" class="btn btn-outline btn-sm" data-toast="Upload 3D plan">Upload 3D Plan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>

    <article class="card wizard-section wizard-bargain-form">
        <div class="wizard-section-head">
            {!! \App\Support\Icon::svg('handshake') !!}
            <div>
                <h2>Smart Bargain Settings (Optional)</h2>
                <p>Set expected price bands so AI can suggest better offers.</p>
            </div>
        </div>
        <div class="form-grid form-grid-2">
            @include('builder.partials.form-field', ['id' => 'bargain-min', 'label' => 'Minimum Expected Price (₹)', 'value' => $smartBargain['min'] ?? '', 'placeholder' => 'e.g. 8000000'])
            @include('builder.partials.form-field', ['id' => 'bargain-target', 'label' => 'Target Price (₹)', 'value' => $smartBargain['target'] ?? '', 'placeholder' => 'e.g. 8500000'])
            @include('builder.partials.form-field', ['id' => 'bargain-max', 'label' => 'Maximum Price (₹)', 'value' => $smartBargain['max'] ?? '', 'placeholder' => 'e.g. 9000000', 'class' => 'span-2'])
        </div>
        <div class="ai-hint">
            <strong>AI Suggests Optimal Range</strong>
            <p>Based on market data and demand for similar projects nearby.</p>
        </div>
    </article>
</section>
