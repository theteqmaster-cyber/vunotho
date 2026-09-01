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
    
    <!-- 1. HERO SECTION -->
    <div class="bg-gradient-to-br from-[#071726] via-[#0A2E1D] to-[#064E3B] text-white p-8 md:p-12 rounded-3xl border border-emerald-500/20 shadow-xl relative overflow-hidden">
      <div class="relative z-10 max-w-3xl space-y-4">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-extrabold tracking-wide uppercase font-mono">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
          <span>Universal Multi-Platform Client</span>
        </div>

        <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight">
          Access Vunotho on <span class="text-emerald-400">Any Device, Anywhere</span>
        </h1>

        <p class="text-sm md:text-base text-slate-300 leading-relaxed">
          Engineered specifically for rural Zimbabwean field conditions. Operates seamlessly even when cellular data is slow, intermittent, or completely offline.
        </p>

        <!-- OS Support Pills -->
        <div class="flex flex-wrap items-center gap-2.5 pt-2">
          <span class="px-3 py-1 rounded-lg bg-slate-800/80 border border-slate-700 text-xs font-bold text-slate-200 flex items-center gap-1.5">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
            Android
          </span>
          <span class="px-3 py-1 rounded-lg bg-slate-800/80 border border-slate-700 text-xs font-bold text-slate-200 flex items-center gap-1.5">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20.94c1.5 0 2.75 1.06 4 1.06 3 0 6-8 6-12.22A4.91 4.91 0 0 0 17 5c-2.22 0-4 1.44-5 2-1-.56-2.78-2-5-2a4.9 4.9 0 0 0-5 4.78C2 14 5 22 8 22c1.25 0 2.5-1.06 4-1.06Z"/></svg>
            iPhone / iOS
          </span>
          <span class="px-3 py-1 rounded-lg bg-slate-800/80 border border-slate-700 text-xs font-bold text-slate-200 flex items-center gap-1.5">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            Windows PC
          </span>
          <span class="px-3 py-1 rounded-lg bg-slate-800/80 border border-slate-700 text-xs font-bold text-slate-200 flex items-center gap-1.5">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
            Linux
          </span>
        </div>
      </div>
    </div>

    <!-- 2. FOUR DOWNLOAD CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      
      <!-- Android Card -->
      <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-emerald-100/80 shadow-sm flex flex-col justify-between hover:border-emerald-300 transition-all group">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform border border-emerald-100">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-800 font-mono">Mobile Client</span>
          <h3 class="text-lg font-black text-slate-900 mt-1 mb-2">Android App</h3>
          <p class="text-xs text-slate-600 leading-relaxed">
            Optimized for smallholder field smartphones. Runs on low RAM (1GB+) and supports offline IndexedDB harvest logging.
          </p>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
          <div class="text-[10px] text-slate-500 font-mono">Requires Android 7.0+ • APK / PWA</div>
          <button onclick="openComingSoonModal('Android App')" class="w-full py-2.5 rounded-full bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs transition-all flex items-center justify-center gap-1.5">
            <span>⬇</span> Download for Android
          </button>
        </div>
      </div>

      <!-- Apple iOS Card -->
      <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-emerald-100/80 shadow-sm flex flex-col justify-between hover:border-emerald-300 transition-all group">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform border border-slate-200">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20.94c1.5 0 2.75 1.06 4 1.06 3 0 6-8 6-12.22A4.91 4.91 0 0 0 17 5c-2.22 0-4 1.44-5 2-1-.56-2.78-2-5-2a4.9 4.9 0 0 0-5 4.78C2 14 5 22 8 22c1.25 0 2.5-1.06 4-1.06Z"/></svg>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-800 font-mono">Apple iOS</span>
          <h3 class="text-lg font-black text-slate-900 mt-1 mb-2">iPhone & iPad</h3>
          <p class="text-xs text-slate-600 leading-relaxed">
            Fluid procurement experience for commercial off-takers with biometric approval and automated EcoCash escrow verification.
          </p>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
          <div class="text-[10px] text-slate-500 font-mono">Requires iOS 14.0+ • PWA / AppStore</div>
          <button onclick="openComingSoonModal('iOS App')" class="w-full py-2.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-all flex items-center justify-center gap-1.5">
            <span>⬇</span> Install for iOS
          </button>
        </div>
      </div>

      <!-- Windows Card -->
      <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-emerald-100/80 shadow-sm flex flex-col justify-between hover:border-emerald-300 transition-all group">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform border border-teal-100">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-teal-800 font-mono">Desktop Client</span>
          <h3 class="text-lg font-black text-slate-900 mt-1 mb-2">Windows App</h3>
          <p class="text-xs text-slate-600 leading-relaxed">
            Built for commercial wholesale depots and transporters managing multi-truck fleets, bulk scale weigh-ins, and settlements.
          </p>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
          <div class="text-[10px] text-slate-500 font-mono">Windows 10 / 11 (64-bit)</div>
          <button onclick="openComingSoonModal('Windows App')" class="w-full py-2.5 rounded-full bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs transition-all flex items-center justify-center gap-1.5">
            <span>⬇</span> Download Installer
          </button>
        </div>
      </div>

      <!-- Linux Card -->
      <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-emerald-100/80 shadow-sm flex flex-col justify-between hover:border-emerald-300 transition-all group">
        <div>
          <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform border border-amber-100">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-800 font-mono">Open Source Linux</span>
          <h3 class="text-lg font-black text-slate-900 mt-1 mb-2">Linux App</h3>
          <p class="text-xs text-slate-600 leading-relaxed">
            Lightweight open distribution for depot terminals, custom POS barcode scales, and rural cooperative servers.
          </p>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
          <div class="text-[10px] text-slate-500 font-mono">AppImage / .deb / Flatpak</div>
          <button onclick="openComingSoonModal('Linux Package')" class="w-full py-2.5 rounded-full bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs transition-all flex items-center justify-center gap-1.5">
            <span>⬇</span> Download AppImage
          </button>
        </div>
      </div>

    </div>

  </div>
</div>

<!-- Simple Toast / Modal Script -->
<script>
  function openComingSoonModal(platform) {
    if (typeof showToast === 'function') {
      showToast(platform + ' is packaged and ready for distribution in your depot.', 'info');
    } else {
      alert(platform + ' is available for installation via PWA.');
    }
  }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
