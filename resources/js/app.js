import '../css/styles.css';

function toast(message) {
    const toastEl = document.getElementById('toast');
    if (!toastEl) return;
    toastEl.textContent = message;
    toastEl.classList.add('is-on');
    clearTimeout(toastEl._t);
    toastEl._t = setTimeout(() => toastEl.classList.remove('is-on'), 2600);
}

function closeSidebar() {
    document.body.classList.remove('sidebar-open');
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

// Media upload state
const wizardState = {
    photos: [],
    floorPlans: [],
    documents: [],
    activeDocCat: 'brochure',
};

async function postWizardStep(url, payload) {
    const res = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
        },
        body: JSON.stringify(payload),
    });
    const data = await res.json();
    if (!res.ok) {
        const errorMsg = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Error saving step');
        throw new Error(errorMsg);
    }
    return data;
}

async function uploadWizardFile(file, category, projectId) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('category', category || 'photos');
    if (projectId) formData.append('project_id', projectId);

    const res = await fetch('/builder/projects/wizard/upload', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
        },
        body: formData,
    });
    const data = await res.json();
    if (!res.ok) {
        throw new Error(data.message || 'File upload failed');
    }
    return data.file;
}

function collectStep1Data(wizard) {
    const rawHighlights = document.getElementById('project-highlights')?.value.trim() || '';
    const highlights = rawHighlights
        ? rawHighlights.split(/[,;\n]/).map((s) => s.trim()).filter(Boolean)
        : [];

    return {
        project_id: wizard.dataset.projectId ? Number(wizard.dataset.projectId) : null,
        name: document.getElementById('project-name')?.value.trim() || '',
        tagline: document.getElementById('project-tagline')?.value.trim() || '',
        builder_name: document.getElementById('builder-name')?.value.trim() || '',
        location: document.getElementById('project-location')?.value.trim() || '',
        maps_link: document.getElementById('maps-link')?.value.trim() || '',
        project_type: document.getElementById('project-type')?.value || '',
        project_status: document.getElementById('project-status')?.value || '',
        possession_date: document.getElementById('possession')?.value || null,
        rera_number: document.getElementById('rera-no')?.value.trim() || '',
        towers: document.getElementById('towers')?.value ? Number(document.getElementById('towers')?.value) : null,
        total_units: document.getElementById('total-units')?.value ? Number(document.getElementById('total-units')?.value) : null,
        land_area: document.getElementById('land-area')?.value.trim() || '',
        description: document.getElementById('project-description')?.value.trim() || '',
        highlights: highlights,
    };
}

function collectStep2Data(wizard) {
    const cards = wizard.querySelectorAll('[data-unit-card]');
    const units = [];
    cards.forEach((card) => {
        const idVal = card.querySelector('[data-unit-field="id"]')?.value;
        const unitType = card.querySelector('[data-unit-field="unit_type"]')?.value.trim() || '';
        const builtUp = card.querySelector('[data-unit-field="built_up_area"]')?.value || '';
        const carpet = card.querySelector('[data-unit-field="carpet_area"]')?.value || '';
        const price = card.querySelector('[data-unit-field="price"]')?.value || '';
        const floorRange = card.querySelector('[data-unit-field="floor_range"]')?.value || 'All Floors';
        const available = card.querySelector('[data-unit-field="available_units"]')?.value || '1';
        const showPlan = card.querySelector('[data-unit-field="show_floor_plan"]')?.checked ?? true;

        if (unitType || builtUp || price) {
            units.push({
                id: idVal ? Number(idVal) : null,
                unit_type: unitType,
                built_up_area: Number(builtUp) || 0,
                carpet_area: Number(carpet) || Number(builtUp) || 0,
                price: Number(price) || 0,
                floor_range: floorRange,
                available_units: Number(available) || 1,
                show_floor_plan: showPlan,
            });
        }
    });

    const minPrice = document.getElementById('bargain-min')?.value;
    const targetPrice = document.getElementById('bargain-target')?.value;
    const maxPrice = document.getElementById('bargain-max')?.value;

    return {
        project_id: wizard.dataset.projectId ? Number(wizard.dataset.projectId) : null,
        smart_bargain_enabled: true,
        min_expected_price: minPrice ? Number(minPrice) : null,
        target_price: targetPrice ? Number(targetPrice) : null,
        max_price: maxPrice ? Number(maxPrice) : null,
        units: units,
    };
}

function collectStep3Data(wizard) {
    const amenities = [];
    wizard.querySelectorAll('.amenity-grid input[type="checkbox"]:checked').forEach((chk) => {
        if (chk.value) amenities.push(chk.value);
    });
    const otherAmenity = document.getElementById('other-amenity')?.value.trim() || '';

    return {
        project_id: wizard.dataset.projectId ? Number(wizard.dataset.projectId) : null,
        amenities: amenities,
        other_amenity: otherAmenity,
    };
}

function collectStep4Data(wizard) {
    return {
        project_id: wizard.dataset.projectId ? Number(wizard.dataset.projectId) : null,
        video_url: document.getElementById('project-video')?.value.trim() || '',
        virtual_tour_url: document.getElementById('virtual-tour')?.value.trim() || '',
        media_preferences: {
            show_gallery: document.getElementById('pref-show-gallery')?.checked ?? true,
            show_floor_plans: document.getElementById('pref-show-plans')?.checked ?? true,
            show_video: document.getElementById('pref-show-video')?.checked ?? true,
        },
        media_photos: wizardState.photos,
        floor_plans: wizardState.floorPlans,
        documents: wizardState.documents,
    };
}

function renderPhotoThumbnails() {
    const container = document.getElementById('media-thumbs-container');
    if (!container) return;

    // Remove existing thumbs except the add button
    container.querySelectorAll('.media-thumb-preview').forEach((el) => el.remove());

    const addBtn = document.getElementById('btn-add-more-photos');

    wizardState.photos.forEach((photo, idx) => {
        const thumb = document.createElement('div');
        thumb.className = 'media-thumb media-thumb-preview';
        thumb.style.position = 'relative';
        thumb.style.overflow = 'hidden';
        thumb.innerHTML = `
            <img src="${photo.url}" alt="${photo.name}" style="width:100%;height:100%;object-fit:cover;border-radius:6px;">
            <button type="button" class="thumb-del" data-del-photo="${idx}" style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,0.6);color:#fff;border:none;border-radius:50%;width:22px;height:22px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;">✕</button>
        `;
        if (addBtn) {
            container.insertBefore(thumb, addBtn);
        } else {
            container.appendChild(thumb);
        }
    });

    const coverPhotoEl = document.getElementById('review-cover-photo');
    if (coverPhotoEl && wizardState.photos.length > 0) {
        coverPhotoEl.innerHTML = `<img src="${wizardState.photos[0].url}" alt="Cover" style="width:100%;height:100%;object-fit:cover;">`;
    }
}

function renderFloorPlanList() {
    const container = document.getElementById('floor-plans-container');
    if (!container) return;

    if (wizardState.floorPlans.length === 0) {
        container.innerHTML = `
            <div class="card" id="empty-plans-notice" style="padding: 28px; text-align: center; color: var(--text-muted, #64748b); border: 1px dashed var(--border-color, #e2e8f0); border-radius: 8px;">
                <p style="margin-bottom: 6px; font-weight: 500;">No floor plans uploaded yet</p>
                <p style="font-size: 0.85rem;">Click the "+ Add Floor Plan" button above to upload 2D or 3D floor plans for your unit configurations.</p>
            </div>
        `;
        return;
    }

    let html = '<div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));gap:12px;">';
    wizardState.floorPlans.forEach((plan, idx) => {
        html += `
            <div class="card" style="padding:12px;position:relative;border:1px solid var(--border-color,#e2e8f0);border-radius:8px;">
                <div style="font-size:0.85rem;font-weight:600;margin-bottom:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${plan.name}</div>
                <div style="font-size:0.75rem;color:var(--text-muted,#64748b);">${(plan.size / 1024).toFixed(1)} KB</div>
                <button type="button" data-del-plan="${idx}" style="position:absolute;top:8px;right:8px;background:none;border:none;color:#ef4444;cursor:pointer;font-size:14px;">✕</button>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

function renderDocList() {
    const container = document.getElementById('docs-list-container');
    if (!container) return;

    if (wizardState.documents.length === 0) {
        container.innerHTML = '';
        return;
    }

    let html = '';
    wizardState.documents.forEach((doc, idx) => {
        html += `
            <div class="card" style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border:1px solid var(--border-color,#e2e8f0);border-radius:6px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="chip chip-available" style="text-transform:capitalize;">${doc.category || 'Doc'}</span>
                    <strong style="font-size:0.88rem;">${doc.name}</strong>
                    <span style="font-size:0.78rem;color:var(--text-muted,#64748b);">${(doc.size / 1024).toFixed(1)} KB</span>
                </div>
                <button type="button" data-del-doc="${idx}" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:15px;padding:4px 8px;">✕</button>
            </div>
        `;
    });
    container.innerHTML = html;
}

function updateReviewScreen(wizard) {
    const s1 = collectStep1Data(wizard);
    const s2 = collectStep2Data(wizard);
    const s3 = collectStep3Data(wizard);
    const s4 = collectStep4Data(wizard);

    // Hero top
    const heroName = document.getElementById('review-hero-name');
    const heroBuilder = document.getElementById('review-hero-builder');
    const heroLoc = document.getElementById('review-hero-location');
    const heroStatus = document.getElementById('review-hero-status');

    if (heroName) heroName.textContent = s1.name || 'New Project';
    if (heroBuilder) heroBuilder.textContent = s1.builder_name ? `By ${s1.builder_name}` : 'Builder / Developer';
    if (heroLoc) heroLoc.textContent = s1.location || 'Location Pending';
    if (heroStatus) heroStatus.textContent = s1.project_status || 'Under Construction';

    // Section 1: Basic
    const projName = document.getElementById('review-proj-name');
    const projBuilder = document.getElementById('review-proj-builder');
    const projLoc = document.getElementById('review-proj-location');
    const projType = document.getElementById('review-proj-type');
    const projStatus = document.getElementById('review-proj-status');
    const projPoss = document.getElementById('review-proj-possession');
    const projRera = document.getElementById('review-proj-rera');
    const projTowers = document.getElementById('review-proj-towers');
    const projUnits = document.getElementById('review-proj-units');
    const projLand = document.getElementById('review-proj-land');

    if (projName) projName.textContent = s1.name || '—';
    if (projBuilder) projBuilder.textContent = s1.builder_name || '—';
    if (projLoc) projLoc.textContent = s1.location || '—';
    if (projType) projType.textContent = s1.project_type || '—';
    if (projStatus) projStatus.textContent = s1.project_status || '—';
    if (projPoss) projPoss.textContent = s1.possession_date || '—';
    if (projRera) projRera.textContent = s1.rera_number || '—';
    if (projTowers) projTowers.textContent = s1.towers || '—';
    if (projUnits) projUnits.textContent = s1.total_units || '—';
    if (projLand) projLand.textContent = s1.land_area || '—';

    // Section 2: Units table
    const unitsTbody = document.getElementById('review-units-tbody');
    if (unitsTbody) {
        if (s2.units.length === 0) {
            unitsTbody.innerHTML = `<tr><td colspan="6" style="text-align:center;padding:20px;color:var(--text-muted,#64748b);">No unit configurations added yet.</td></tr>`;
        } else {
            let trs = '';
            s2.units.forEach((u) => {
                const formattedPrice = u.price >= 10000000
                    ? `₹ ${(u.price / 10000000).toFixed(2)} Cr`
                    : (u.price >= 100000 ? `₹ ${(u.price / 100000).toFixed(2)} L` : `₹ ${Number(u.price).toLocaleString()}`);
                trs += `
                    <tr>
                        <td><strong>${u.unit_type || '—'}</strong></td>
                        <td>${u.built_up_area ? `${u.built_up_area} Sq.Ft` : '—'}</td>
                        <td>${u.carpet_area ? `${u.carpet_area} Sq.Ft` : '—'}</td>
                        <td>${formattedPrice}</td>
                        <td>${u.available_units || '—'}</td>
                        <td>${u.floor_range || '—'}</td>
                    </tr>
                `;
            });
            unitsTbody.innerHTML = trs;
        }
    }

    // Section 3: Amenities
    const amenitiesCont = document.getElementById('review-amenities-container');
    if (amenitiesCont) {
        if (s3.amenities.length === 0) {
            amenitiesCont.innerHTML = '<p style="color:var(--text-muted,#64748b);font-size:0.9rem;">No amenities selected yet.</p>';
        } else {
            let chips = '';
            s3.amenities.forEach((a) => {
                chips += `
                    <div class="amenity-item is-checked">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                        <span>${a}</span>
                    </div>
                `;
            });
            amenitiesCont.innerHTML = chips;
        }
    }

    // Section 4: Media
    const mediaImages = document.getElementById('review-media-images');
    const plans2d = document.getElementById('review-plans-2d');
    const plans3d = document.getElementById('review-plans-3d');
    const docBrochure = document.getElementById('review-doc-brochure');
    const docPrice = document.getElementById('review-doc-pricelist');
    const docRera = document.getElementById('review-doc-rera');
    const mediaVideo = document.getElementById('review-media-video');

    if (mediaImages) mediaImages.textContent = `${wizardState.photos.length} uploaded`;
    if (plans2d) plans2d.textContent = `${wizardState.floorPlans.length} uploaded`;
    if (plans3d) plans3d.textContent = '0 uploaded';
    if (docBrochure) docBrochure.textContent = wizardState.documents.some((d) => d.category === 'brochure') ? 'Uploaded' : 'Not uploaded';
    if (docPrice) docPrice.textContent = wizardState.documents.some((d) => d.category === 'price_list') ? 'Uploaded' : 'Not uploaded';
    if (docRera) docRera.textContent = wizardState.documents.some((d) => d.category === 'rera') ? 'Uploaded' : 'Not uploaded';
    if (mediaVideo) mediaVideo.textContent = s4.video_url || 'Not provided';

    // Section 5: Highlights & Description
    const highlightsCont = document.getElementById('review-highlights-container');
    if (highlightsCont) {
        if (s1.highlights.length === 0) {
            highlightsCont.innerHTML = '<p style="color:var(--text-muted,#64748b);font-size:0.9rem;margin-bottom:8px;">No highlights added.</p>';
        } else {
            let lis = '<ul class="review-highlights">';
            s1.highlights.forEach((h) => {
                lis += `<li>${h}</li>`;
            });
            lis += '</ul>';
            highlightsCont.innerHTML = lis;
        }
    }
    const descText = document.getElementById('review-description-text');
    if (descText) descText.textContent = s1.description || 'No project description added yet.';

    // Section 6: Smart Bargain
    const bMin = document.getElementById('review-bargain-min');
    const bTarget = document.getElementById('review-bargain-target');
    const bMax = document.getElementById('review-bargain-max');
    if (bMin) bMin.textContent = s2.min_expected_price ? `₹ ${Number(s2.min_expected_price).toLocaleString()}` : '—';
    if (bTarget) bTarget.textContent = s2.target_price ? `₹ ${Number(s2.target_price).toLocaleString()}` : '—';
    if (bMax) bMax.textContent = s2.max_price ? `₹ ${Number(s2.max_price).toLocaleString()}` : '—';
}

function openProjectModal() {
    const modalEl = document.getElementById('modal');
    if (!modalEl) return;
    modalEl.classList.add('is-open');
    modalEl.innerHTML = `
    <div class="modal" role="dialog" aria-modal="true">
      <h3>Add Project</h3>
      <p class="hint">Submit a development for admin review.</p>
      <div class="field"><label>Project name</label><input placeholder="e.g. Lakeview Residency II" /></div>
      <div class="field"><label>City</label><input placeholder="Mumbai" /></div>
      <div class="field"><label>Total units</label><input type="number" placeholder="80" /></div>
      <div class="modal-actions">
        <button class="btn btn-outline" type="button" data-close-modal>Cancel</button>
        <button class="btn btn-primary" type="button" data-close-modal data-toast="Project submitted for review">Submit</button>
      </div>
    </div>`;
}

function openCounterModal(id) {
    const modalEl = document.getElementById('modal');
    if (!modalEl) return;
    modalEl.classList.add('is-open');
    modalEl.innerHTML = `
    <div class="modal" role="dialog">
      <h3>Counter offer · #${id}</h3>
      <p class="hint">Send a revised price to the buyer.</p>
      <div class="field"><label>Your counter (₹)</label><input placeholder="1.62 Cr" /></div>
      <div class="field"><label>Note</label><textarea placeholder="Optional message"></textarea></div>
      <div class="modal-actions">
        <button class="btn btn-outline" type="button" data-close-modal>Cancel</button>
        <button class="btn btn-primary" type="button" data-close-modal data-toast="Counter sent for #${id}">Send counter</button>
      </div>
    </div>`;
}

// Global Click Handlers
document.addEventListener('click', (e) => {
    // Submit Project Final Action
    const wizardSubmit = e.target.closest('[data-wizard-submit]');
    if (wizardSubmit) {
        const wizard = document.querySelector('[data-project-wizard]');
        if (!wizard) return;

        const confirmBox = document.getElementById('confirm-submit');
        if (!confirmBox || !confirmBox.checked) {
            toast('Please confirm Terms & Conditions before submitting');
            confirmBox?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        const projectId = wizard.dataset.projectId ? Number(wizard.dataset.projectId) : null;
        if (!projectId) {
            toast('Please complete project details in Step 1 first');
            setWizardStep(wizard, 1);
            return;
        }

        wizardSubmit.disabled = true;
        const originalText = wizardSubmit.innerHTML;
        wizardSubmit.innerHTML = 'Submitting project...';

        postWizardStep('/builder/projects/wizard/submit', {
            project_id: projectId,
            terms_accepted: true,
        })
            .then((res) => {
                toast(res.message || 'Project submitted for review successfully!');
                setTimeout(() => {
                    window.location.href = res.redirect_url || '/projects';
                }, 1400);
            })
            .catch((err) => {
                toast(err.message || 'Submission failed');
                wizardSubmit.disabled = false;
                wizardSubmit.innerHTML = originalText;
            });
        return;
    }

    // Generic toast buttons
    const toastBtn = e.target.closest('[data-toast]');
    if (toastBtn && !toastBtn.closest('[data-wizard-submit]')) {
        toast(toastBtn.dataset.toast);
    }

    const counter = e.target.closest('[data-open-counter]');
    if (counter) openCounterModal(counter.dataset.openCounter);

    const modalEl = document.getElementById('modal');
    if (e.target.closest('[data-close-modal]') || e.target === modalEl) {
        if (modalEl) {
            modalEl.classList.remove('is-open');
            modalEl.innerHTML = '';
        }
    }

    if (e.target.closest('#add-project') || e.target.closest('#add-project-page')) openProjectModal();
    if (e.target.closest('#menu-toggle')) document.body.classList.toggle('sidebar-open');
    if (e.target.id === 'overlay') closeSidebar();

    // Wizard Next Step handler
    const wizardNext = e.target.closest('[data-wizard-next]');
    if (wizardNext) {
        const wizard = wizardNext.closest('[data-project-wizard]');
        if (!wizard) return;
        const currentStep = Number(wizard.dataset.currentStep || 1);

        // STEP 1 -> 2
        if (currentStep === 1) {
            const data = collectStep1Data(wizard);
            if (!data.name || !data.builder_name || !data.location || !data.project_type || !data.project_status) {
                toast('Please fill all required project fields (*) in Step 1');
                return;
            }
            wizardNext.disabled = true;
            postWizardStep('/builder/projects/wizard/basic', data)
                .then((res) => {
                    wizard.dataset.projectId = res.project_id;
                    toast(res.message || 'Basic details saved');
                    setWizardStep(wizard, 2);
                })
                .catch((err) => toast(err.message))
                .finally(() => { wizardNext.disabled = false; });
            return;
        }

        // STEP 2 -> 3
        if (currentStep === 2) {
            const data = collectStep2Data(wizard);
            if (!wizard.dataset.projectId) {
                toast('Please complete Step 1 first');
                setWizardStep(wizard, 1);
                return;
            }
            wizardNext.disabled = true;
            postWizardStep('/builder/projects/wizard/units', data)
                .then((res) => {
                    toast(res.message || 'Units & pricing saved');
                    setWizardStep(wizard, 3);
                })
                .catch((err) => toast(err.message))
                .finally(() => { wizardNext.disabled = false; });
            return;
        }

        // STEP 3 -> 4
        if (currentStep === 3) {
            const data = collectStep3Data(wizard);
            if (!wizard.dataset.projectId) {
                toast('Please complete Step 1 first');
                setWizardStep(wizard, 1);
                return;
            }
            wizardNext.disabled = true;
            postWizardStep('/builder/projects/wizard/amenities', data)
                .then((res) => {
                    toast(res.message || 'Amenities saved');
                    setWizardStep(wizard, 4);
                })
                .catch((err) => toast(err.message))
                .finally(() => { wizardNext.disabled = false; });
            return;
        }

        // STEP 4 -> 5 (Media to Review)
        if (currentStep === 4) {
            const data = collectStep4Data(wizard);
            if (!wizard.dataset.projectId) {
                toast('Please complete Step 1 first');
                setWizardStep(wizard, 1);
                return;
            }
            wizardNext.disabled = true;
            postWizardStep('/builder/projects/wizard/media', data)
                .then((res) => {
                    toast(res.message || 'Media saved');
                    setWizardStep(wizard, 5);
                    updateReviewScreen(wizard);
                })
                .catch((err) => toast(err.message))
                .finally(() => { wizardNext.disabled = false; });
            return;
        }

        setWizardStep(wizard, currentStep + 1);
        if (currentStep + 1 === 5) updateReviewScreen(wizard);
        return;
    }

    // Save Draft button
    const saveDraftBtn = e.target.closest('[data-wizard-save-draft]');
    if (saveDraftBtn) {
        const wizard = document.querySelector('[data-project-wizard]');
        if (wizard) {
            const step1 = collectStep1Data(wizard);
            const step2 = collectStep2Data(wizard);
            const step3 = collectStep3Data(wizard);
            const step4 = collectStep4Data(wizard);
            const draftPayload = {
                ...step1,
                ...step2,
                ...step3,
                ...step4,
                project_id: wizard.dataset.projectId ? Number(wizard.dataset.projectId) : null,
            };
            saveDraftBtn.disabled = true;
            postWizardStep('/builder/projects/wizard/draft', draftPayload)
                .then((res) => {
                    wizard.dataset.projectId = res.project_id;
                    toast(res.message || 'Draft saved successfully');
                })
                .catch((err) => toast(err.message))
                .finally(() => { saveDraftBtn.disabled = false; });
        }
        return;
    }

    // Stepper navigation / Previous
    const wizardPrev = e.target.closest('[data-wizard-prev]');
    if (wizardPrev) {
        const wizard = wizardPrev.closest('[data-project-wizard]');
        if (wizard) setWizardStep(wizard, Number(wizard.dataset.currentStep || 1) - 1);
        return;
    }

    const gotoStep = e.target.closest('[data-goto-step]');
    if (gotoStep) {
        const wizard = document.querySelector('[data-project-wizard]');
        if (wizard) {
            const targetStep = Number(gotoStep.dataset.gotoStep);
            setWizardStep(wizard, targetStep);
            if (targetStep === 5) updateReviewScreen(wizard);
        }
        return;
    }

    // Add unit card
    const addUnit = e.target.closest('[data-add-unit]');
    if (addUnit) {
        const container = document.querySelector('[data-unit-cards]');
        const firstCard = container?.querySelector('[data-unit-card]');
        if (container && firstCard) {
            const clone = firstCard.cloneNode(true);
            clone.querySelectorAll('input').forEach((inp) => {
                if (inp.type === 'checkbox') inp.checked = false;
                else inp.value = '';
            });
            clone.querySelectorAll('select').forEach((sel) => sel.selectedIndex = 0);
            container.appendChild(clone);
            toast('New unit type added');
        }
        return;
    }

    // Remove unit card
    const removeUnit = e.target.closest('[data-remove-unit]');
    if (removeUnit) {
        const card = removeUnit.closest('[data-unit-card]');
        const container = document.querySelector('[data-unit-cards]');
        if (card && container) {
            if (container.querySelectorAll('[data-unit-card]').length > 1) {
                card.remove();
                toast('Unit removed');
            } else {
                card.querySelectorAll('input').forEach((inp) => {
                    if (inp.type === 'checkbox') inp.checked = false;
                    else inp.value = '';
                });
                card.querySelectorAll('select').forEach((sel) => sel.selectedIndex = 0);
                toast('Unit cleared');
            }
        }
        return;
    }

    // Delete photo thumbnail
    const delPhotoBtn = e.target.closest('[data-del-photo]');
    if (delPhotoBtn) {
        const idx = Number(delPhotoBtn.dataset.delPhoto);
        wizardState.photos.splice(idx, 1);
        renderPhotoThumbnails();
        toast('Photo removed');
        return;
    }

    // Delete floor plan
    const delPlanBtn = e.target.closest('[data-del-plan]');
    if (delPlanBtn) {
        const idx = Number(delPlanBtn.dataset.delPlan);
        wizardState.floorPlans.splice(idx, 1);
        renderFloorPlanList();
        toast('Floor plan removed');
        return;
    }

    // Delete document
    const delDocBtn = e.target.closest('[data-del-doc]');
    if (delDocBtn) {
        const idx = Number(delDocBtn.dataset.delDoc);
        wizardState.documents.splice(idx, 1);
        renderDocList();
        toast('Document removed');
        return;
    }

    // Photo file input trigger
    if (e.target.closest('#media-dropzone') || e.target.closest('#btn-browse-photos') || e.target.closest('#btn-add-more-photos')) {
        const input = document.getElementById('media-photos-input');
        if (input && e.target !== input) input.click();
        return;
    }

    // Floor plan file input trigger
    if (e.target.closest('#btn-browse-plans')) {
        const input = document.getElementById('floor-plans-input');
        if (input && e.target !== input) input.click();
        return;
    }

    // Doc category tabs
    const docCatBtn = e.target.closest('[data-doc-cat]');
    if (docCatBtn) {
        wizardState.activeDocCat = docCatBtn.dataset.docCat;
        const label = document.getElementById('active-doc-cat-label');
        if (label) label.textContent = docCatBtn.textContent;
    }

    // Docs file input trigger
    if (e.target.closest('#docs-dropzone')) {
        const input = document.getElementById('docs-file-input');
        if (input && e.target !== input) input.click();
        return;
    }

    // Filter tab
    const filterTab = e.target.closest('[data-filter-tab]');
    if (filterTab) {
        const group = filterTab.closest('[data-filter-group]');
        if (group) {
            group.querySelectorAll('[data-filter-tab]').forEach((tab) => tab.classList.remove('is-active'));
            filterTab.classList.add('is-active');
        }
    }

    // Project preview interactive controls
    const ppageTab = e.target.closest('[data-ppage-tab]');
    if (ppageTab) {
        const tabs = ppageTab.closest('.ppage-tabs');
        tabs.querySelectorAll('[data-ppage-tab]').forEach((tab) => tab.classList.remove('is-active'));
        ppageTab.classList.add('is-active');
    }

    const bhkTab = e.target.closest('[data-bhk-tab]');
    if (bhkTab) {
        const preview = bhkTab.closest('[data-project-preview]');
        const index = bhkTab.dataset.bhkTab;
        preview.querySelectorAll('[data-bhk-tab]').forEach((tab) => tab.classList.remove('is-active'));
        bhkTab.classList.add('is-active');
        preview.querySelectorAll('[data-bhk-panel]').forEach((panel) => {
            panel.hidden = panel.dataset.bhkPanel !== index;
        });
    }

    const sizeCard = e.target.closest('[data-size-card]');
    if (sizeCard) {
        const row = sizeCard.closest('.ppage-sizes');
        row.querySelectorAll('[data-size-card]').forEach((card) => card.classList.remove('is-active'));
        sizeCard.classList.add('is-active');
    }

    const planBtn = e.target.closest('[data-plan-view]');
    if (planBtn) {
        const wrap = planBtn.closest('.ppage-plan');
        wrap.querySelectorAll('[data-plan-view]').forEach((btn) => btn.classList.remove('is-active'));
        planBtn.classList.add('is-active');
        const visual = wrap.querySelector('[data-plan-visual]');
        if (visual) {
            visual.classList.toggle('is-2d', planBtn.dataset.planView === '2d');
            visual.classList.toggle('is-3d', planBtn.dataset.planView === '3d');
        }
    }

    const viewBtn = e.target.closest('[data-view]');
    if (viewBtn) {
        const wrap = viewBtn.closest('[data-view-toggle]');
        wrap?.querySelectorAll('[data-view]').forEach((btn) => btn.classList.remove('is-active'));
        viewBtn.classList.add('is-active');
        const grid = document.querySelector('[data-project-grid]');
        if (grid) grid.classList.toggle('is-list-view', viewBtn.dataset.view === 'list');
    }

    const toggleBtn = e.target.closest('[data-toggle]');
    if (toggleBtn) {
        toggleBtn.classList.toggle('is-on');
        const on = toggleBtn.classList.contains('is-on');
        toggleBtn.setAttribute('aria-checked', on ? 'true' : 'false');
        toast(on ? 'Preference enabled' : 'Preference disabled');
    }

    if (e.target.closest('[data-mark-all-read]')) {
        document.querySelectorAll('[data-notification]').forEach((card) => {
            card.classList.remove('is-unread');
            card.dataset.unread = '0';
        });
        toast('All notifications marked as read');
    }

    const removeBtn = e.target.closest('[data-remove-notification]');
    if (removeBtn) {
        const card = removeBtn.closest('[data-notification]');
        if (card) card.remove();
    }

    const notifyFilter = e.target.closest('[data-notify-filter]');
    if (notifyFilter) {
        const filter = notifyFilter.dataset.notifyFilter;
        document.querySelectorAll('[data-notification]').forEach((card) => {
            const category = card.dataset.category;
            const unread = card.dataset.unread === '1';
            let show = true;
            if (filter === 'unread') show = unread;
            else if (filter === 'leads') show = category === 'leads';
            else if (filter === 'bargains') show = category === 'bargains';
            card.hidden = !show;
        });
    }

    if (e.target.closest('[data-listing-filter]')) filterListings();
    if (e.target.closest('[data-campaign-filter]')) filterCampaigns();
    if (e.target.closest('[data-document-filter]')) filterDocuments();
});

// File Upload Event Listeners
document.addEventListener('change', async (e) => {
    const wizard = document.querySelector('[data-project-wizard]');
    const projectId = wizard?.dataset.projectId || null;

    // Photos upload
    if (e.target.id === 'media-photos-input') {
        const files = Array.from(e.target.files || []);
        if (files.length === 0) return;
        toast(`Uploading ${files.length} image(s)...`);
        for (const file of files) {
            try {
                const uploaded = await uploadWizardFile(file, 'photos', projectId);
                wizardState.photos.push(uploaded);
            } catch (err) {
                toast(`Failed to upload ${file.name}: ${err.message}`);
            }
        }
        renderPhotoThumbnails();
        toast('Images uploaded successfully');
        e.target.value = '';
    }

    // Floor plans upload
    if (e.target.id === 'floor-plans-input') {
        const files = Array.from(e.target.files || []);
        if (files.length === 0) return;
        toast(`Uploading ${files.length} floor plan(s)...`);
        for (const file of files) {
            try {
                const uploaded = await uploadWizardFile(file, 'floor_plans', projectId);
                wizardState.floorPlans.push(uploaded);
            } catch (err) {
                toast(`Failed to upload ${file.name}: ${err.message}`);
            }
        }
        renderFloorPlanList();
        toast('Floor plans uploaded');
        e.target.value = '';
    }

    // Documents upload
    if (e.target.id === 'docs-file-input') {
        const files = Array.from(e.target.files || []);
        if (files.length === 0) return;
        const category = wizardState.activeDocCat || 'brochure';
        toast(`Uploading ${files.length} document(s)...`);
        for (const file of files) {
            try {
                const uploaded = await uploadWizardFile(file, category, projectId);
                wizardState.documents.push(uploaded);
            } catch (err) {
                toast(`Failed to upload ${file.name}: ${err.message}`);
            }
        }
        renderDocList();
        toast('Document uploaded');
        e.target.value = '';
    }

    const amenity = e.target.closest('.amenity-item input[type="checkbox"]');
    if (amenity) {
        amenity.closest('.amenity-item')?.classList.toggle('is-checked', amenity.checked);
    }

    if (e.target.id === 'project-status') {
        const previewStatus = document.querySelector('[data-preview-status]');
        if (previewStatus) previewStatus.textContent = e.target.value || 'Draft';
    }
});

// Calculate price per sqft dynamically when price or built-up area changes
document.addEventListener('input', (e) => {
    if (e.target.id === 'project-name') {
        const previewName = document.querySelector('[data-preview-name]');
        if (previewName) previewName.textContent = e.target.value || 'Project Name';
    }
    if (e.target.id === 'project-location') {
        const previewLoc = document.querySelector('[data-preview-location]');
        if (previewLoc) previewLoc.textContent = e.target.value || 'Location';
    }

    const card = e.target.closest('[data-unit-card]');
    if (card) {
        const fieldName = e.target.dataset.unitField;
        if (fieldName === 'price' || fieldName === 'built_up_area') {
            const priceVal = parseFloat(card.querySelector('[data-unit-field="price"]')?.value) || 0;
            const areaVal = parseFloat(card.querySelector('[data-unit-field="built_up_area"]')?.value) || 0;
            const sqftInput = card.querySelector('[data-unit-field="price_per_sqft"]');
            if (sqftInput) {
                if (priceVal > 0 && areaVal > 0) {
                    const perSqft = Math.round(priceVal / areaVal);
                    sqftInput.value = `₹ ${perSqft.toLocaleString()}`;
                } else {
                    sqftInput.value = '';
                }
            }
        }
    }
});

function filterListings() {
    const active = document.querySelector('[data-filter-group="listings"] [data-listing-filter].is-active');
    const statusFilter = active ? active.dataset.listingFilter : 'all';
    const projectSelect = document.querySelector('[data-listing-project]');
    const projectFilter = projectSelect ? projectSelect.value : 'All Projects';

    document.querySelectorAll('[data-listing-row]').forEach((row) => {
        const status = row.dataset.status || '';
        const project = row.dataset.project || '';
        const statusOk = statusFilter === 'all' || status === statusFilter;
        const projectOk = projectFilter === 'All Projects' || project === projectFilter;
        row.hidden = !(statusOk && projectOk);
    });
}

function filterCampaigns() {
    const active = document.querySelector('[data-filter-group="campaigns"] [data-campaign-filter].is-active');
    const filter = active ? active.dataset.campaignFilter : 'all';

    document.querySelectorAll('[data-campaign-card]').forEach((card) => {
        const status = card.dataset.status || '';
        card.hidden = !(filter === 'all' || status === filter);
    });
}

function filterDocuments() {
    const active = document.querySelector('[data-filter-group="documents"] [data-document-filter].is-active');
    const filter = active ? active.dataset.documentFilter : 'rera';

    document.querySelectorAll('[data-document-card]').forEach((card) => {
        card.hidden = card.dataset.category !== filter;
    });
}

filterDocuments();

const listingProject = document.querySelector('[data-listing-project]');
if (listingProject) {
    listingProject.addEventListener('change', filterListings);
}

function setWizardStep(wizard, step) {
    const max = wizard.querySelectorAll('[data-wizard-panel]').length;
    const next = Math.min(Math.max(step, 1), max);
    wizard.dataset.currentStep = String(next);

    wizard.querySelectorAll('[data-wizard-panel]').forEach((panel) => {
        const match = Number(panel.dataset.wizardPanel) === next;
        panel.hidden = !match;
        panel.classList.toggle('is-active', match);
    });

    wizard.querySelectorAll('[data-wizard-stepper] [data-goto-step]').forEach((btn) => {
        const id = Number(btn.dataset.gotoStep);
        btn.classList.toggle('is-active', id === next);
        btn.classList.toggle('is-done', id < next);
        const index = btn.querySelector('[data-step-index]');
        if (index) index.textContent = id < next ? '✓' : String(id);
    });

    const prevBtn = wizard.querySelector('[data-wizard-prev]');
    const nextBtn = wizard.querySelector('[data-wizard-next]');
    const submitBtn = wizard.querySelector('.wizard-footer [data-wizard-submit]');
    const readyCard = wizard.querySelector('[data-ready-card]');
    const previewCard = wizard.querySelector('[data-preview-card]');
    const cancelBtn = wizard.querySelector('.wizard-footer-right .btn-ghost');

    if (prevBtn) prevBtn.hidden = next === 1;
    if (nextBtn) nextBtn.hidden = next === max;
    if (submitBtn) submitBtn.hidden = next !== max;
    if (readyCard) readyCard.hidden = next !== max;
    if (previewCard) previewCard.hidden = next === 1;
    if (cancelBtn) cancelBtn.hidden = next !== 1;

    const nextLabels = {
        1: 'Next: Units & Pricing',
        2: 'Next: Amenities',
        3: 'Next: Media & Plans',
        4: 'Next: Review & Submit',
    };
    if (nextBtn && nextLabels[next]) {
        nextBtn.innerHTML = `${nextLabels[next]} <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>`;
    }

    const titles = {
        1: 'List your project and reach thousands of verified buyers on CMNHousing.',
        2: 'Add unit configurations, pricing and floor plans for your project.',
        3: 'Select amenities and features available in your project.',
        4: 'Upload images, floor plans, documents and video.',
        5: 'Review all details before submitting. You can go back and edit any section.',
    };
    const sub = document.querySelector('.topbar-title p');
    if (sub && titles[next]) sub.textContent = titles[next];

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Initial fetch if editing existing project
const wizardEl = document.querySelector('[data-project-wizard]');
if (wizardEl && wizardEl.dataset.projectId) {
    const pid = wizardEl.dataset.projectId;
    fetch(`/builder/projects/wizard/${pid}`)
        .then((r) => r.json())
        .then((res) => {
            if (res.project) {
                if (res.project.media_photos) {
                    wizardState.photos = res.project.media_photos;
                    renderPhotoThumbnails();
                }
                if (res.project.floor_plans) {
                    wizardState.floorPlans = res.project.floor_plans;
                    renderFloorPlanList();
                }
                if (res.project.documents) {
                    wizardState.documents = res.project.documents;
                    renderDocList();
                }
            }
        })
        .catch(() => {});
}

const search = document.getElementById('global-search');
if (search) {
    search.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') toast(`Search: ${e.target.value || 'all records'}`);
    });
}
