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
            @include('builder.partials.form-field', ['id' => 'project-name', 'label' => 'Project Name *', 'value' => $projectForm['name'] ?? '', 'placeholder' => 'e.g., The Pinnacle Residences'])
            @include('builder.partials.form-field', ['id' => 'project-tagline', 'label' => 'Project Tagline (Optional)', 'value' => $projectForm['tagline'] ?? '', 'placeholder' => 'e.g., Luxury Living. Smarter Prices.'])
            @include('builder.partials.form-field', ['id' => 'builder-name', 'label' => 'Builder / Developer Name *', 'value' => $projectForm['builder'] ?? '', 'placeholder' => 'e.g., ABC Builders & Developers'])
            @include('builder.partials.form-field', ['id' => 'project-location', 'label' => 'Project Location *', 'value' => $projectForm['location'] ?? '', 'placeholder' => 'Enter City, Locality, Landmark'])
            @include('builder.partials.form-field', ['id' => 'maps-link', 'label' => 'Google Maps Link (Optional)', 'value' => $projectForm['mapsLink'] ?? '', 'placeholder' => 'https://maps.google.com/?q=...', 'class' => 'span-2'])

            <div class="field">
                <label for="project-type">Project Type *</label>
                <select id="project-type">
                    <option value="" disabled {{ empty($projectForm['type']) ? 'selected' : '' }}>Select Project Type</option>
                    <option value="Residential Apartment" {{ ($projectForm['type'] ?? '') === 'Residential Apartment' ? 'selected' : '' }}>Residential Apartment</option>
                    <option value="Villa Community" {{ ($projectForm['type'] ?? '') === 'Villa Community' ? 'selected' : '' }}>Villa Community</option>
                    <option value="Plotting" {{ ($projectForm['type'] ?? '') === 'Plotting' ? 'selected' : '' }}>Plotting</option>
                    <option value="Commercial" {{ ($projectForm['type'] ?? '') === 'Commercial' ? 'selected' : '' }}>Commercial</option>
                </select>
            </div>
            <div class="field">
                <label for="project-status">Project Status *</label>
                <select id="project-status">
                    <option value="" disabled {{ empty($projectForm['status']) ? 'selected' : '' }}>Select Project Status</option>
                    <option value="Under Construction" {{ ($projectForm['status'] ?? '') === 'Under Construction' ? 'selected' : '' }}>Under Construction</option>
                    <option value="Ready to Move" {{ ($projectForm['status'] ?? '') === 'Ready to Move' ? 'selected' : '' }}>Ready to Move</option>
                    <option value="Upcoming" {{ ($projectForm['status'] ?? '') === 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                </select>
            </div>
            @include('builder.partials.form-field', ['id' => 'possession', 'label' => 'Possession Date *', 'type' => 'date', 'value' => $projectForm['possession'] ?? ''])
            <div class="field">
                <label for="rera-no">RERA Registration No.</label>
                <input id="rera-no" type="text" value="{{ $projectForm['rera'] ?? '' }}" placeholder="e.g. P02400012345">
                <div class="field-action-end">
                    <button type="button" class="link-btn" data-toast="RERA verified">Verify RERA Number</button>
                </div>
            </div>
            @include('builder.partials.form-field', ['id' => 'towers', 'label' => 'No. of Towers', 'value' => $projectForm['towers'] ?? '', 'placeholder' => 'e.g. 5'])
            @include('builder.partials.form-field', ['id' => 'total-units', 'label' => 'Total Units', 'value' => $projectForm['totalUnits'] ?? '', 'placeholder' => 'e.g. 250'])
            @include('builder.partials.form-field', ['id' => 'land-area', 'label' => 'Land Area', 'value' => $projectForm['landArea'] ?? '', 'placeholder' => 'e.g. 10 Acres', 'class' => 'span-2'])

            <div class="field span-2">
                <label for="project-description">Project Description</label>
                <textarea id="project-description" rows="3" placeholder="Provide a brief overview of the project, architecture, location benefits, etc.">{{ $projectForm['description'] ?? '' }}</textarea>
            </div>
            <div class="field span-2">
                <label for="project-highlights">Key Highlights (Comma-separated)</label>
                <input id="project-highlights" type="text" value="{{ !empty($highlights) ? implode(', ', $highlights) : '' }}" placeholder="e.g. 5 mins from Metro, 70% Open Space, Premium Clubhouse">
            </div>
        </div>
    </article>
</section>
