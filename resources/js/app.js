import '../css/styles.css';

function toast(message) {
    const toastEl = document.getElementById('toast');
    if (!toastEl) return;
    toastEl.textContent = message;
    toastEl.classList.add('is-on');
    clearTimeout(toastEl._t);
    toastEl._t = setTimeout(() => toastEl.classList.remove('is-on'), 2400);
}

function closeSidebar() {
    document.body.classList.remove('sidebar-open');
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

document.addEventListener('click', (e) => {
    const wizardSubmit = e.target.closest('[data-wizard-submit]');
    if (wizardSubmit) {
        const confirmBox = document.getElementById('confirm-submit');
        if (confirmBox && !confirmBox.checked) {
            toast('Please confirm Terms & Conditions first');
            return;
        }
    }

    const toastBtn = e.target.closest('[data-toast]');
    if (toastBtn) toast(toastBtn.dataset.toast);

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

    const wizardNext = e.target.closest('[data-wizard-next]');
    if (wizardNext) {
        const wizard = wizardNext.closest('[data-project-wizard]');
        if (wizard) setWizardStep(wizard, Number(wizard.dataset.currentStep || 1) + 1);
    }

    const wizardPrev = e.target.closest('[data-wizard-prev]');
    if (wizardPrev) {
        const wizard = wizardPrev.closest('[data-project-wizard]');
        if (wizard) setWizardStep(wizard, Number(wizard.dataset.currentStep || 1) - 1);
    }

    const gotoStep = e.target.closest('[data-goto-step]');
    if (gotoStep) {
        const wizard = document.querySelector('[data-project-wizard]');
        if (wizard) setWizardStep(wizard, Number(gotoStep.dataset.gotoStep));
    }

    const filterTab = e.target.closest('[data-filter-tab]');
    if (filterTab) {
        const group = filterTab.closest('[data-filter-group]');
        group.querySelectorAll('[data-filter-tab]').forEach((tab) => tab.classList.remove('is-active'));
        filterTab.classList.add('is-active');
    }

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
        const toggle = viewBtn.closest('[data-view-toggle]');
        toggle.querySelectorAll('[data-view]').forEach((btn) => btn.classList.remove('is-active'));
        viewBtn.classList.add('is-active');
        const grid = document.querySelector('[data-project-grid]');
        if (grid) grid.classList.toggle('is-list', viewBtn.dataset.view === 'list');
    }

    const settingsTab = e.target.closest('[data-settings-tab]');
    if (settingsTab) {
        const tabs = settingsTab.closest('[data-settings-tabs]');
        tabs.querySelectorAll('[data-settings-tab]').forEach((tab) => tab.classList.remove('is-active'));
        settingsTab.classList.add('is-active');
        const panelId = settingsTab.dataset.settingsTab;
        document.querySelectorAll('[data-settings-panel]').forEach((panel) => {
            const match = panel.dataset.settingsPanel === panelId;
            panel.classList.toggle('is-active', match);
            panel.hidden = !match;
        });
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

    if (e.target.closest('[data-listing-filter]')) {
        filterListings();
    }

    if (e.target.closest('[data-campaign-filter]')) {
        filterCampaigns();
    }

    if (e.target.closest('[data-document-filter]')) {
        filterDocuments();
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

document.addEventListener('change', (e) => {
    const amenity = e.target.closest('.amenity-item input[type="checkbox"]');
    if (amenity) {
        amenity.closest('.amenity-item')?.classList.toggle('is-checked', amenity.checked);
    }
});

const search = document.getElementById('global-search');
if (search) {
    search.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') toast(`Search: ${e.target.value || 'all records'}`);
    });
}
