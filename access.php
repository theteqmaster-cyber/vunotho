<?php
/**
 * VUNOTHO MULTI-PLATFORM ACCESS & DOWNLOADS
 */
require_once __DIR__ . '/api/session.php';

$pageTitle = 'Access & Download Vunotho App — Android, iOS, Windows, Linux';
require_once __DIR__ . '/includes/header.php';
?>

<div class="vn-container py-10">
  <div class="max-w-5xl mx-auto space-y-10">
    
    <!-- 1. HIGH-CONTRAST BOTANICAL HERO SECTION -->
    <div style="background: linear-gradient(135deg, #071726 0%, #0A2E1D 60%, #064E3B 100%); color: #ffffff;" class="p-8 md:p-12 rounded-3xl border border-emerald-500/30 shadow-2xl relative overflow-hidden">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
        
        <div class="lg:col-span-7 space-y-4">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/25 text-emerald-300 border border-emerald-400/30 text-xs font-extrabold tracking-wide uppercase font-mono">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
            <span>Universal Multi-Platform Client</span>
          </div>

          <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight text-white">
            Access Vunotho on <span class="text-emerald-400">Any Device, Anywhere</span>
          </h1>

          <p class="text-sm md:text-base text-slate-200 leading-relaxed font-normal">
            Engineered specifically for rural Zimbabwean field conditions. Operates seamlessly offline, recovers 100% produce value across all grades, and delivers direct EcoCash mobile settlements.
          </p>

          <!-- OS Support Pills -->
          <div class="flex flex-wrap items-center gap-2.5 pt-2">
            <span class="px-3 py-1.5 rounded-xl bg-white/15 border border-white/20 text-xs font-bold text-white flex items-center gap-2">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
              Android (APK)
            </span>
            <span class="px-3 py-1.5 rounded-xl bg-white/15 border border-white/20 text-xs font-bold text-white flex items-center gap-2">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20.94c1.5 0 2.75 1.06 4 1.06 3 0 6-8 6-12.22A4.91 4.91 0 0 0 17 5c-2.22 0-4 1.44-5 2-1-.56-2.78-2-5-2a4.9 4.9 0 0 0-5 4.78C2 14 5 22 8 22c1.25 0 2.5-1.06 4-1.06Z"/></svg>
              iPhone (PWA)
            </span>
            <span class="px-3 py-1.5 rounded-xl bg-white/15 border border-white/20 text-xs font-bold text-white flex items-center gap-2">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
              Windows PC
            </span>
            <span class="px-3 py-1.5 rounded-xl bg-white/15 border border-white/20 text-xs font-bold text-white flex items-center gap-2">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
              Linux Desktop
            </span>
          </div>
        </div>

        <!-- Right Column: Visual Device Illustration Card -->
        <div class="lg:col-span-5 relative">
          <div class="relative rounded-2xl overflow-hidden border border-emerald-500/30 shadow-2xl bg-slate-900 group">
            <img src="/images/farmland.png" alt="Vunotho Farmland Overview" class="w-full h-56 object-cover object-center transform group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-[#071726]/95 via-[#071726]/40 to-transparent"></div>
            
            <!-- Floating Mobile Screen Chip -->
            <div class="absolute bottom-3 left-3 right-3 bg-[#071726]/95 backdrop-blur-md border border-white/20 p-3 rounded-xl flex items-center justify-between text-xs">
              <div class="flex items-center gap-2.5">
                <img src="/images/vunotho_logo.png" alt="Vunotho Logo" class="w-8 h-8 rounded-lg object-contain bg-white p-1" />
                <div>
                  <div class="font-extrabold text-white leading-tight">Vunotho Mobile App</div>
                  <div class="text-[10px] text-emerald-400 font-mono">100% Value Recovery Engine</div>
                </div>
              </div>
              <span class="px-2 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 font-bold font-mono text-[10px]">v1.0 Live</span>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- 2. FOUR DOWNLOAD CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      
      <!-- Android Card -->
      <div class="bg-white p-6 rounded-3xl border border-emerald-200/80 shadow-md flex flex-col justify-between hover:border-emerald-500 hover:shadow-lg transition-all group">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform border border-emerald-200">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-800 font-mono">Mobile Client</span>
          <h3 class="text-lg font-black text-slate-900 mt-1 mb-2">Android App</h3>
          <p class="text-xs text-slate-600 leading-relaxed">
            Optimized for Zimbabwean smallholders. Runs smoothly on all smartphones with offline-first harvest logging and price benchmarking.
          </p>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
          <div class="text-[10px] text-slate-500 font-mono">Android 7.0+ • Direct APK (42 MB)</div>
          <a href="/downloads/vunotho-mobile.apk" download="vunotho-mobile.apk" class="w-full py-2.5 rounded-full bg-emerald-800 hover:bg-emerald-900 text-white font-extrabold text-xs transition-all flex items-center justify-center gap-1.5 shadow-md">
            <span>⬇</span> Download APK Directly
          </a>
        </div>
      </div>

      <!-- Apple iOS Card -->
      <div class="bg-white p-6 rounded-3xl border border-emerald-200/80 shadow-md flex flex-col justify-between hover:border-emerald-500 hover:shadow-lg transition-all group">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform border border-slate-200">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20.94c1.5 0 2.75 1.06 4 1.06 3 0 6-8 6-12.22A4.91 4.91 0 0 0 17 5c-2.22 0-4 1.44-5 2-1-.56-2.78-2-5-2a4.9 4.9 0 0 0-5 4.78C2 14 5 22 8 22c1.25 0 2.5-1.06 4-1.06Z"/></svg>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-800 font-mono">Apple iOS</span>
          <h3 class="text-lg font-black text-slate-900 mt-1 mb-2">iPhone & iPad</h3>
          <p class="text-xs text-slate-600 leading-relaxed">
            Fluid procurement experience for commercial off-takers and farm managers with automated EcoCash escrow verification.
          </p>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
          <div class="text-[10px] text-emerald-800 font-bold font-mono">Available via Web App (PWA)</div>
          <button onclick="openIosPwaModal()" class="w-full py-2.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs transition-all flex items-center justify-center gap-1.5 shadow-md">
            <span>📲</span> Install via Web App
          </button>
        </div>
      </div>

      <!-- Windows Card -->
      <div class="bg-white p-6 rounded-3xl border border-emerald-200/80 shadow-md flex flex-col justify-between hover:border-emerald-500 hover:shadow-lg transition-all group">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform border border-teal-100">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-teal-800 font-mono">Desktop Client</span>
          <h3 class="text-lg font-black text-slate-900 mt-1 mb-2">Windows PC</h3>
          <p class="text-xs text-slate-600 leading-relaxed">
            Built for commercial wholesale depots and transporters managing multi-truck fleets, bulk weigh-ins, and settlements.
          </p>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
          <div class="text-[10px] text-slate-500 font-mono">Windows 10 / 11 (64-bit)</div>
          <button onclick="openDesktopComingSoonModal('Windows Native Client')" class="w-full py-2.5 rounded-full bg-teal-700 hover:bg-teal-800 text-white font-extrabold text-xs transition-all flex items-center justify-center gap-1.5 shadow-md">
            <span>💻</span> Get Windows Client
          </button>
        </div>
      </div>

      <!-- Linux Card -->
      <div class="bg-white p-6 rounded-3xl border border-emerald-200/80 shadow-md flex flex-col justify-between hover:border-emerald-500 hover:shadow-lg transition-all group">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform border border-amber-100">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-800 font-mono">Open Source Linux</span>
          <h3 class="text-lg font-black text-slate-900 mt-1 mb-2">Linux Desktop</h3>
          <p class="text-xs text-slate-600 leading-relaxed">
            Lightweight distribution for depot terminals, custom POS barcode scales, and rural cooperative servers.
          </p>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
          <div class="text-[10px] text-slate-500 font-mono">AppImage / .deb / Flatpak</div>
          <button onclick="openDesktopComingSoonModal('Linux Native Client')" class="w-full py-2.5 rounded-full bg-amber-700 hover:bg-amber-800 text-white font-extrabold text-xs transition-all flex items-center justify-center gap-1.5 shadow-md">
            <span>🐧</span> Get Linux Client
          </button>
        </div>
      </div>

    </div>

  </div>
</div>

<!-- ==================== MODAL DIALOGS ==================== -->

<!-- iOS PWA Installation Modal -->
<div id="ios-pwa-modal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
  <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-5 animate-in fade-in zoom-in duration-200">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2.5">
        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-900 font-bold">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20.94c1.5 0 2.75 1.06 4 1.06 3 0 6-8 6-12.22A4.91 4.91 0 0 0 17 5c-2.22 0-4 1.44-5 2-1-.56-2.78-2-5-2a4.9 4.9 0 0 0-5 4.78C2 14 5 22 8 22c1.25 0 2.5-1.06 4-1.06Z"/></svg>
        </div>
        <div>
          <h3 class="text-base font-black text-slate-900">Install Vunotho on iOS</h3>
          <p class="text-[11px] text-slate-500 font-mono">Safari Web App (PWA)</p>
        </div>
      </div>
      <button onclick="closeModals()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold">✕</button>
    </div>

    <div class="space-y-3 bg-slate-50 p-4 rounded-2xl border border-slate-200/80 text-xs text-slate-700">
      <div class="flex items-start gap-2.5">
        <span class="w-5 h-5 rounded-full bg-emerald-800 text-white font-bold flex items-center justify-center text-[10px] shrink-0">1</span>
        <span>Open <strong>vunotho.co.zw</strong> in <strong>Safari</strong> on your iPhone or iPad.</span>
      </div>
      <div class="flex items-start gap-2.5">
        <span class="w-5 h-5 rounded-full bg-emerald-800 text-white font-bold flex items-center justify-center text-[10px] shrink-0">2</span>
        <span>Tap the <strong>Share button [ ⎋ ]</strong> in Safari's bottom toolbar.</span>
      </div>
      <div class="flex items-start gap-2.5">
        <span class="w-5 h-5 rounded-full bg-emerald-800 text-white font-bold flex items-center justify-center text-[10px] shrink-0">3</span>
        <span>Select <strong>"Add to Home Screen"</strong> and tap <strong>Add</strong>.</span>
      </div>
    </div>

    <div class="flex gap-2.5">
      <a href="/farmer.php" class="flex-1 py-3 rounded-full bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs text-center transition-all shadow-md">
        Launch Web App Now 🌿
      </a>
      <button onclick="closeModals()" class="px-4 py-3 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all">
        Close
      </button>
    </div>
  </div>
</div>

<!-- Desktop Native Client Coming Soon Modal -->
<div id="desktop-modal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
  <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-5 animate-in fade-in zoom-in duration-200">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2.5">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
        </div>
        <div>
          <h3 id="desktop-modal-title" class="text-base font-black text-slate-900">Desktop Native Client</h3>
          <p class="text-[11px] text-emerald-700 font-mono font-bold">Phase 2 Pilot Rollout</p>
        </div>
      </div>
      <button onclick="closeModals()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold">✕</button>
    </div>

    <p id="desktop-modal-desc" class="text-xs text-slate-600 leading-relaxed">
      Native desktop packages for Windows and Linux are currently in closed testing with regional aggregation depots and transport fleet managers.
    </p>

    <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 space-y-1">
      <div class="font-bold flex items-center gap-1.5">
        <span>💡</span> Instant Access via Web Desk
      </div>
      <p class="text-[11px] text-emerald-800 leading-tight">
        You can access the full desktop experience immediately through your browser with full offline caching and EcoCash escrow tools.
      </p>
    </div>

    <div class="flex gap-2.5">
      <a href="/farmer.php" class="flex-1 py-3 rounded-full bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs text-center transition-all shadow-md">
        Launch Web Desk 🌿
      </a>
      <button onclick="closeModals()" class="px-4 py-3 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all">
        Got It
      </button>
    </div>
  </div>
</div>

<script>
  function openIosPwaModal() {
    document.getElementById('ios-pwa-modal').classList.remove('hidden');
  }

  function openDesktopComingSoonModal(platformName) {
    document.getElementById('desktop-modal-title').innerText = platformName;
    document.getElementById('desktop-modal-desc').innerText = 
      platformName + ' native package is currently in closed testing for regional commercial depots. You can use the fully featured Web Desk immediately.';
    document.getElementById('desktop-modal').classList.remove('hidden');
  }

  function closeModals() {
    document.getElementById('ios-pwa-modal').classList.add('hidden');
    document.getElementById('desktop-modal').classList.add('hidden');
  }

  // Close when clicking backdrop
  window.addEventListener('click', (e) => {
    if (e.target.id === 'ios-pwa-modal' || e.target.id === 'desktop-modal') {
      closeModals();
    }
  });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
