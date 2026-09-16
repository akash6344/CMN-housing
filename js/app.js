import { icon } from "./icons.js";
import { builder, navItems, footItems, stats, leads, activity, deals, hotUnits } from "./data.js";

const pageEl = document.getElementById("page");
const titleEl = document.getElementById("page-title");
const subEl = document.getElementById("page-sub");
const toastEl = document.getElementById("toast");
const modalEl = document.getElementById("modal");

const labels = Object.fromEntries([...navItems, ...footItems].map((item) => [item.id, item.label]));

function toast(message) {
  toastEl.textContent = message;
  toastEl.classList.add("is-on");
  clearTimeout(toastEl._t);
  toastEl._t = setTimeout(() => toastEl.classList.remove("is-on"), 2400);
}

function closeSidebar() {
  document.body.classList.remove("sidebar-open");
}

function navButton(item, active) {
  return `<button class="nav-item ${item.id === active ? "is-active" : ""}" data-route="${item.id}">
    ${icon(item.icon)}<span>${item.label}</span>
  </button>`;
}

function renderNav(active) {
  document.getElementById("nav-main").innerHTML = navItems.map((i) => navButton(i, active)).join("");
  document.getElementById("nav-foot").innerHTML = footItems.map((i) => navButton(i, active)).join("");
}

function comingSoon(id) {
  const name = labels[id] || "This section";
  return `<section class="card coming-soon">
    <div class="coming-soon-icon">${icon("clock")}</div>
    <h2>${name}</h2>
    <p>Coming soon. This screen is not part of the current dashboard design.</p>
    <button class="btn btn-primary" data-route="overview">Back to Overview</button>
  </section>`;
}

function leadCard(l) {
  return `<article class="card lead-card">
    <div class="lead-top">
      <div class="avatar" style="background:${l.color};color:${l.ink}">${l.initials}</div>
      <div>
        <div class="lead-name">${l.name}</div>
        <div class="lead-sub">${l.property}</div>
      </div>
      <span class="chip ${l.statusClass}">${l.status}</span>
    </div>
    <ul class="lead-meta">
      <li>${icon("mail")}${l.email}</li>
      <li>${icon("phone")}${l.phone}</li>
      <li>${icon("map")}${l.place}</li>
      <li>${icon("clock")}${l.when}</li>
    </ul>
    <div class="lead-actions">
      <button class="btn btn-primary btn-sm" data-toast="Calling ${l.name}…">${icon("phone")} Call</button>
      <button class="btn btn-outline btn-sm" data-toast="Email drafted for ${l.name}">${icon("mail")} Email</button>
    </div>
  </article>`;
}

function dealCard(d) {
  const offers = d.counter
    ? `<div class="offer-row">
        <div class="offer-box"><span>Buyer Offer</span><strong>${d.buyer}</strong></div>
        <div class="offer-box accent"><span>Your Counter</span><strong>${d.counter}</strong></div>
      </div>`
    : `<div class="offer-row single">
        <div class="offer-box"><span>Buyer Offer</span><strong>${d.buyer}</strong></div>
      </div>
      <div class="deal-note">${icon("clock")} ${d.expires}</div>
      <div class="deal-actions">
        <button class="btn btn-success btn-sm" data-toast="Accepted deal #${d.id}">Accept</button>
        <button class="btn btn-outline btn-sm" data-open-counter="${d.id}">Counter</button>
        <button class="btn btn-outline btn-sm" data-toast="Rejected deal #${d.id}">Reject</button>
      </div>`;

  return `<article class="card deal-card">
    <div class="deal-head">
      <span class="deal-id">Deal #${d.id}</span>
      <span class="chip ${d.statusClass}">${d.status}</span>
    </div>
    <h3 class="deal-title">${d.title}</h3>
    <div class="deal-unit">${d.unit}</div>
    ${offers}
  </article>`;
}

function overviewPage() {
  return `
    <div class="stat-grid">${stats
      .map(
        (s) => `<article class="card stat-card">
          <div class="stat-label">${s.label}</div>
          <div class="stat-icon icon-${s.tone}">${icon(s.icon)}</div>
          <div class="stat-value">${s.value}</div>
          <div class="trend ${s.dir}">${s.trend}</div>
        </article>`
      )
      .join("")}</div>
    <div class="overview-grid">
      <div class="overview-col">
        <section>
          <div class="section-head">
            <h2>Recent Leads</h2>
            <button class="link" data-route="leads">View All</button>
          </div>
          <div class="leads-row">${leads.map(leadCard).join("")}</div>
        </section>
        <section>
          <div class="section-head">
            <h2>Active Bargain Deals</h2>
            <button class="link" data-route="bargain">View All</button>
          </div>
          <div class="deals-row">${deals.map(dealCard).join("")}</div>
          <div class="banner">
            <div class="banner-icon">${icon("alert")}</div>
            <div class="banner-copy">
              <strong>2 projects pending approval</strong>
              <span>Urban Edge Phase 2 and Riverside Gardens are awaiting admin review</span>
            </div>
            <button class="btn btn-outline btn-sm" data-route="projects">View Details</button>
          </div>
        </section>
      </div>
      <div class="overview-col">
        <aside class="card activity-card">
          <div class="section-head"><h2>Recent Activity</h2></div>
          <div class="activity-list">
            ${activity
              .map(
                (a) => `<div class="activity-item">
                  <div class="activity-icon icon-${a.tone}">${icon(a.icon)}</div>
                  <div>
                    <div class="activity-title">${a.title}</div>
                    <div class="activity-desc">${a.desc}</div>
                    <div class="activity-time">${icon("clock")} ${a.time}</div>
                  </div>
                </div>`
              )
              .join("")}
          </div>
        </aside>
        <aside class="card hot-card">
          <div class="section-head"><h2>Hot Units</h2></div>
          <div class="hot-list">
            ${hotUnits
              .map(
                (u) => `<div class="hot-item">
                  <div>
                    <div class="hot-name">${u.name}</div>
                    <div class="hot-meta">${u.meta}</div>
                  </div>
                  <span class="hot-rank">#${u.rank}</span>
                </div>`
              )
              .join("")}
          </div>
        </aside>
      </div>
    </div>`;
}

function setRoute(id) {
  const route = labels[id] ? id : "overview";
  if (location.hash.replace("#", "") !== route) location.hash = route;
  renderNav(route);
  closeSidebar();

  if (route === "overview") {
    titleEl.textContent = "Dashboard Overview";
    subEl.textContent = "Welcome back, Skyline Builders";
    pageEl.innerHTML = overviewPage();
  } else {
    titleEl.textContent = labels[route];
    subEl.textContent = "This section is coming soon";
    pageEl.innerHTML = comingSoon(route);
  }

  pageEl.scrollTop = 0;
  window.scrollTo(0, 0);
}

function openProjectModal() {
  modalEl.classList.add("is-open");
  modalEl.innerHTML = `
    <div class="modal" role="dialog" aria-modal="true">
      <h3>Add Project</h3>
      <p class="hint">Submit a development for admin review.</p>
      <div class="field"><label>Project name</label><input placeholder="e.g. Lakeview Residency II" /></div>
      <div class="field"><label>City</label><input placeholder="Mumbai" /></div>
      <div class="field"><label>Total units</label><input type="number" placeholder="80" /></div>
      <div class="modal-actions">
        <button class="btn btn-outline" data-close-modal>Cancel</button>
        <button class="btn btn-primary" data-close-modal data-toast="Project submitted for review">Submit</button>
      </div>
    </div>`;
}

function openCounterModal(id) {
  modalEl.classList.add("is-open");
  modalEl.innerHTML = `
    <div class="modal" role="dialog">
      <h3>Counter offer · #${id}</h3>
      <p class="hint">Send a revised price to the buyer.</p>
      <div class="field"><label>Your counter (₹)</label><input placeholder="1.62 Cr" /></div>
      <div class="field"><label>Note</label><textarea placeholder="Optional message"></textarea></div>
      <div class="modal-actions">
        <button class="btn btn-outline" data-close-modal>Cancel</button>
        <button class="btn btn-primary" data-close-modal data-toast="Counter sent for #${id}">Send counter</button>
      </div>
    </div>`;
}

document.getElementById("brand").innerHTML = `
  <div class="brand-mark">${icon("home")}</div>
  <div>
    <div class="brand-name">CMNHousing</div>
    <div class="brand-sub">Builder Portal</div>
  </div>`;

document.getElementById("org").innerHTML = `
  <div class="org-avatar">${builder.initials}</div>
  <div class="org-meta">
    <div class="org-name">${builder.name}</div>
    <div class="org-role">${builder.role}</div>
  </div>
  <button class="org-switch" title="Switch org">${icon("switch")}</button>`;

document.getElementById("search-icon").innerHTML = icon("search");
document.getElementById("add-icon").innerHTML = icon("plus");
document.getElementById("bell-icon").innerHTML = icon("bell");
document.getElementById("menu-icon").innerHTML = icon("menu");

document.addEventListener("click", (e) => {
  const routeBtn = e.target.closest("[data-route]");
  if (routeBtn) setRoute(routeBtn.dataset.route);

  const toastBtn = e.target.closest("[data-toast]");
  if (toastBtn) toast(toastBtn.dataset.toast);

  const counter = e.target.closest("[data-open-counter]");
  if (counter) openCounterModal(counter.dataset.openCounter);

  if (e.target.closest("[data-close-modal]") || e.target === modalEl) {
    modalEl.classList.remove("is-open");
    modalEl.innerHTML = "";
  }

  if (e.target.closest("#add-project")) openProjectModal();
  if (e.target.closest("#open-bell")) setRoute("notifications");
  if (e.target.closest("#menu-toggle")) document.body.classList.toggle("sidebar-open");
  if (e.target.id === "overlay") closeSidebar();
});

document.getElementById("global-search").addEventListener("keydown", (e) => {
  if (e.key === "Enter") toast(`Search: ${e.target.value || "all records"}`);
});

window.addEventListener("hashchange", () => setRoute(location.hash.replace("#", "") || "overview"));
setRoute(location.hash.replace("#", "") || "overview");
