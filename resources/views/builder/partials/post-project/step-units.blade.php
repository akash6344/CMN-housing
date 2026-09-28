<section class="wizard-panel" data-wizard-panel="2" hidden>
    <article class="card wizard-section">
        <div class="wizard-section-head">
            <span class="wizard-section-num">2</span>
            <div>
                <h2>Unit Configuration & Pricing</h2>
                <p>Add unit configurations, pricing and floor plans for your project.</p>
            </div>
            <button type="button" class="btn btn-outline btn-sm" data-toast="Add unit type coming soon">
                {!! \App\Support\Icon::svg('plus') !!} Add New Unit Type
            </button>
        </div>

        <div class="unit-cards">
            @foreach ($unitTypes as $unit)
                <div class="card unit-card">
                    <div class="unit-card-head">
                        <h3>{{ $unit['type'] }}</h3>
                        <button type="button" class="doc-link text-danger" data-toast="Unit removed">
                            {!! \App\Support\Icon::svg('trash') !!} Remove
                        </button>
                    </div>
                    <div class="form-grid form-grid-2">
                        @include('builder.partials.form-field', ['label' => 'Built-up Area (Sq.Ft)', 'value' => $unit['builtUp']])
                        @include('builder.partials.form-field', ['label' => 'Carpet Area (Sq.Ft)', 'value' => $unit['carpet']])
                        @include('builder.partials.form-field', ['label' => 'Price (₹)', 'value' => $unit['price']])
                        @include('builder.partials.form-field', ['label' => 'Price per Sq.Ft', 'value' => $unit['perSqft'], 'readonly' => true])
                        @include('builder.partials.form-field', ['label' => 'No. of Units Available', 'value' => $unit['available']])
                        <div class="field">
                            <label>Floor Range</label>
                            <select>
                                <option selected>{{ $unit['floors'] }}</option>
                                <option>All Floors</option>
                            </select>
                        </div>
                    </div>
                    <label class="wizard-check">
                        <input type="checkbox" checked>
                        <span>Show 2D/3D Floor Plan for this unit type</span>
                    </label>
                    <div class="unit-plan-box">
                        <div class="unit-plan-thumb"></div>
                        <div>
                            <strong>{{ $unit['type'] }} Floor Plan</strong>
                            <p>Upload or select floor plan for this unit type.</p>
                            <div class="unit-plan-actions">
                                <button type="button" class="btn btn-outline btn-sm" data-toast="Upload 2D plan">Upload 2D Plan</button>
                                <button type="button" class="btn btn-outline btn-sm" data-toast="Upload 3D plan">Upload 3D Plan</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
            @include('builder.partials.form-field', ['label' => 'Minimum Expected Price (₹)', 'value' => $smartBargain['min']])
            @include('builder.partials.form-field', ['label' => 'Target Price (₹)', 'value' => $smartBargain['target']])
            @include('builder.partials.form-field', ['label' => 'Maximum Price (₹)', 'value' => $smartBargain['max'], 'class' => 'span-2'])
        </div>
        <div class="ai-hint">
            <strong>AI Suggests Optimal Range</strong>
            <p>Based on market data and demand for similar projects nearby.</p>
        </div>
    </article>
</section>
