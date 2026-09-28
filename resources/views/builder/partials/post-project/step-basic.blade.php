<section class="wizard-panel is-active" data-wizard-panel="1">
    <article class="card wizard-section">
        <div class="wizard-section-head">
            <span class="wizard-section-num">1</span>
            <div>
                <h2>Project Basic Details</h2>
                <p>Tell buyers the essentials about your project.</p>
            </div>
        </div>

        <div class="form-grid form-grid-2">
            @include('builder.partials.form-field', ['id' => 'project-name', 'label' => 'Project Name *', 'value' => $projectForm['name'], 'placeholder' => 'e.g., The Pinnacle Residences'])
            @include('builder.partials.form-field', ['id' => 'project-tagline', 'label' => 'Project Tagline (Optional)', 'value' => $projectForm['tagline'], 'placeholder' => 'e.g., Luxury Living. Smarter Prices.'])
            @include('builder.partials.form-field', ['id' => 'builder-name', 'label' => 'Builder / Developer Name *', 'value' => $projectForm['builder']])
            @include('builder.partials.form-field', ['id' => 'project-location', 'label' => 'Project Location *', 'value' => $projectForm['location'], 'placeholder' => 'Enter City, Locality, Landmark'])
            @include('builder.partials.form-field', ['id' => 'maps-link', 'label' => 'Google Maps Link (Optional)', 'value' => $projectForm['mapsLink'], 'class' => 'span-2'])

            <div class="field">
                <label for="project-type">Project Type *</label>
                <select id="project-type">
                    <option selected>{{ $projectForm['type'] }}</option>
                    <option>Villa Community</option>
                    <option>Plotting</option>
                    <option>Commercial</option>
                </select>
            </div>
            <div class="field">
                <label for="project-status">Project Status *</label>
                <select id="project-status">
                    <option selected>{{ $projectForm['status'] }}</option>
                    <option>Ready to Move</option>
                    <option>Upcoming</option>
                </select>
            </div>
            @include('builder.partials.form-field', ['id' => 'possession', 'label' => 'Possession Date *', 'type' => 'date', 'value' => $projectForm['possession']])
            <div class="field">
                <label for="rera-no">RERA Registration No.</label>
                <input id="rera-no" type="text" value="{{ $projectForm['rera'] }}">
                <div class="field-action-end">
                    <button type="button" class="link-btn" data-toast="RERA verified">Verify RERA Number</button>
                </div>
            </div>
            @include('builder.partials.form-field', ['id' => 'towers', 'label' => 'No. of Towers', 'value' => $projectForm['towers']])
            @include('builder.partials.form-field', ['id' => 'total-units', 'label' => 'Total Units', 'value' => $projectForm['totalUnits']])
            @include('builder.partials.form-field', ['id' => 'land-area', 'label' => 'Land Area', 'value' => $projectForm['landArea'], 'class' => 'span-2'])
        </div>
    </article>
</section>
