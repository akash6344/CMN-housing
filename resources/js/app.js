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

    const filterTab = e.target.closest('[data-filter-tab]');
    if (filterTab) {
        const group = filterTab.closest('[data-filter-group]');
        group.querySelectorAll('[data-filter-tab]').forEach((tab) => tab.classList.remove('is-active'));
        filterTab.classList.add('is-active');
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

const listingProject = document.querySelector('[data-listing-project]');
if (listingProject) {
    listingProject.addEventListener('change', filterListings);
}

const search = document.getElementById('global-search');
if (search) {
    search.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') toast(`Search: ${e.target.value || 'all records'}`);
    });
}
