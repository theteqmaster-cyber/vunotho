/**
 * VUNOTHO FARMER DASHBOARD CLIENT CONTROLLER
 * Sidebar Collapsing | Instant Local Caching | Net-Return Calculations | Offline Mode
 */

// Price reference per kg for Net-Return Estimation
const CROP_PRICE_BENCHMARKS = {
  'Tomatoes': 0.42,
  'Table Potatoes': 0.30,
  'Onions': 0.35,
  'Leafy Greens': 0.25,
  'Butternut Squash': 0.40,
  'Green Peppers': 0.70,
  'Cabbages': 0.30
};

// 1. Sidebar Collapse Controller (Desktop & Mobile)
function toggleSidebarCollapse() {
  const sidebar = document.getElementById('portal-sidebar');
  if (!sidebar) return;

  if (window.innerWidth < 992) {
    sidebar.classList.toggle('open');
    const backdrop = document.getElementById('sidebar-backdrop');
    if (backdrop) backdrop.classList.toggle('active');
  } else {
    sidebar.classList.toggle('collapsed');
    const isCollapsed = sidebar.classList.contains('collapsed');
    try {
      localStorage.setItem('vunotho_sidebar_collapsed', isCollapsed ? 'true' : 'false');
    } catch (e) {}
  }
}

function toggleMobileSidebar(open) {
  const sidebar = document.getElementById('portal-sidebar');
  const backdrop = document.getElementById('sidebar-backdrop');
  if (!sidebar) return;

  if (open) {
    sidebar.classList.add('open');
    if (backdrop) backdrop.classList.add('active');
  } else {
    sidebar.classList.remove('open');
    if (backdrop) backdrop.classList.remove('active');
  }
}

// 2. User Profile Dropdown Toggle
function toggleUserDropdown(e) {
  if (e) {
    e.preventDefault();
    e.stopPropagation();
  }
  const menu = document.getElementById('user-dropdown-menu');
  if (menu) menu.classList.toggle('active');
}

// 3. Offline Mode Switch Controller
function handleOfflineToggle(isOffline) {
  const statusDot = document.getElementById('offline-status-dot');
  if (isOffline) {
    if (statusDot) statusDot.className = 'w-2 h-2 rounded-full bg-amber-400 animate-pulse';
    if (typeof showToast === 'function') {
      showToast('📴 Offline Mode Active — All transactions & listings cached locally in IndexedDB.', 'warning');
    }
  } else {
    if (statusDot) statusDot.className = 'w-2 h-2 rounded-full bg-emerald-400 animate-pulse';
    if (typeof showToast === 'function') {
      showToast('🌐 Online Synced — All records verified with Vunotho Master Registry.', 'success');
    }
  }
}

// 4. Produce Modal Controllers & Dynamic Net-Return Math
function openProduceModal() {
  const modal = document.getElementById('new-produce-modal');
  if (modal) {
    modal.classList.add('active');
    updateModalNetReturn();
  }
}

function closeProduceModal() {
  const modal = document.getElementById('new-produce-modal');
  if (modal) modal.classList.remove('active');
}

function updateModalNetReturn() {
  const cropSelect = document.getElementById('modal-crop-select');
  const qtyInput = document.getElementById('modal-qty-input');
  const gradeSelect = document.getElementById('modal-grade-select');
  
  if (!cropSelect || !qtyInput) return;

  const crop = cropSelect.value || 'Tomatoes';
  const qty = parseFloat(qtyInput.value) || 0;
  const basePrice = CROP_PRICE_BENCHMARKS[crop] || 0.42;
  
  let gradeMultiplier = 1.0;
  if (gradeSelect) {
    const val = gradeSelect.value;
    if (val.includes('Tier 2') || val.includes('Agro-Processing')) gradeMultiplier = 0.88;
    else if (val.includes('Tier 3') || val.includes('Livestock')) gradeMultiplier = 0.60;
    else if (val.includes('Tier 4') || val.includes('Bio-Compost')) gradeMultiplier = 0.35;
  }

  const effectivePrice = basePrice * gradeMultiplier;
  const grossTotal = qty * effectivePrice;
  const pooledFreight = qty > 0 ? (qty * 35 * 0.0015 * 0.65) : 0;
  const platformFee = grossTotal * 0.04;
  const netTakeHome = Math.max(0, grossTotal - pooledFreight - platformFee);

  const grossEl = document.getElementById('modal-calc-gross');
  const freightEl = document.getElementById('modal-calc-freight');
  const feeEl = document.getElementById('modal-calc-fee');
  const netEl = document.getElementById('modal-calc-net');
  const unitPriceEl = document.getElementById('modal-unit-price-display');

  if (grossEl) grossEl.textContent = '$' + grossTotal.toFixed(2);
  if (freightEl) freightEl.textContent = '-$' + pooledFreight.toFixed(2);
  if (feeEl) feeEl.textContent = '-$' + platformFee.toFixed(2);
  if (netEl) netEl.textContent = '$' + netTakeHome.toFixed(2);
  if (unitPriceEl) unitPriceEl.textContent = '$' + effectivePrice.toFixed(2) + '/kg';
}

// 5. Initializer & Cached State Restoration
document.addEventListener('DOMContentLoaded', () => {
  // Restore collapsed sidebar state on desktop
  try {
    if (window.innerWidth >= 992 && localStorage.getItem('vunotho_sidebar_collapsed') === 'true') {
      const sidebar = document.getElementById('portal-sidebar');
      if (sidebar) sidebar.classList.add('collapsed');
    }
  } catch (e) {}

  // Global click outside to close dropdowns
  document.addEventListener('click', (e) => {
    const menu = document.getElementById('user-dropdown-menu');
    if (menu && !e.target.closest('#user-header-pill')) {
      menu.classList.remove('active');
    }
  });

  const qtyInput = document.getElementById('modal-qty-input');
  const cropSelect = document.getElementById('modal-crop-select');
  const gradeSelect = document.getElementById('modal-grade-select');

  if (qtyInput) qtyInput.addEventListener('input', updateModalNetReturn);
  if (cropSelect) cropSelect.addEventListener('change', updateModalNetReturn);
  if (gradeSelect) gradeSelect.addEventListener('change', updateModalNetReturn);
});
