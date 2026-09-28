<section class="wizard-panel" data-wizard-panel="3" hidden>
    <article class="card wizard-section">
        <div class="wizard-section-head">
            <span class="wizard-section-num">3</span>
            <div>
                <h2>Amenities & Features</h2>
                <p>Select amenities available in your project.</p>
            </div>
        </div>

        <div class="amenity-grid">
            @foreach ($amenitiesList as $amenity)
                <label class="amenity-item {{ !empty($amenity['checked']) ? 'is-checked' : '' }}">
                    <input type="checkbox" {{ !empty($amenity['checked']) ? 'checked' : '' }}>
                    <span>{{ $amenity['label'] }}</span>
                </label>
            @endforeach
        </div>

        <div class="field" style="margin-top: 14px; max-width: 420px;">
            <label for="other-amenity">Other (Specify)</label>
            <input id="other-amenity" type="text" placeholder="Add custom amenity">
        </div>
    </article>
</section>
