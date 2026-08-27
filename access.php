<?php
/**
 * VUNOTHO MULTI-PLATFORM ACCESS & DOWNLOADS (Server-Rendered PHP & Tailwind CSS)
 */
require_once __DIR__ . '/api/session.php';

$pageTitle = 'Access & Download Vunotho App — Android, iOS, Windows, Linux';
require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-5xl mx-auto space-y-12">
  
  <!-- 1. HERO SECTION -->
  <div class="bg-gradient-to-br from-[#0F2942] via-[#0A192F] to-[#060D17] text-white p-8 md:p-14 rounded-3xl border border-slate-700 shadow-warm-xl relative overflow-hidden">
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-emerald-500/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-amber-500/15 blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-3xl space-y-4">
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-extrabold tracking-wide uppercase font-mono">
        📲 Universal Multi-Platform Client
      </div>

      <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight">
        Access Vunotho on <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-amber-300 to-teal-300">Any Device, Anywhere</span>
      </h1>

      <p class="text-sm md:text-base text-slate-300 leading-relaxed">
        Engineered specifically for rural Zimbabwean field conditions. Operates seamlessly even when cellular data is slow, intermittent, or completely offline.
      </p>

      <!-- OS Support Pills -->
      <div class="flex flex-wrap items-center gap-2.5 pt-2">
        <span class="px-3 py-1 rounded-lg bg-slate-800/80 border border-slate-600 text-xs font-bold text-slate-200 flex items-center gap-1.5">
          <span>🤖</span> Android
        </span>
        <span class="px-3 py-1 rounded-lg bg-slate-800/80 border border-slate-600 text-xs font-bold text-slate-200 flex items-center gap-1.5">
          <span>🍏</span> iPhone / iOS
        </span>
        <span class="px-3 py-1 rounded-lg bg-slate-800/80 border border-slate-600 text-xs font-bold text-slate-200 flex items-center gap-1.5">
          <span>🪟</span> Windows
        </span>
        <span class="px-3 py-1 rounded-lg bg-slate-800/80 border border-slate-600 text-xs font-bold text-slate-200 flex items-center gap-1.5">
          <span>🐧</span> Linux
        </span>
      </div>
    </div>
  </div>

  <!-- 2. FOUR DOWNLOAD CARDS -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    
    <!-- Android Card -->
    <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-slate-200 shadow-warm-md flex flex-col justify-between hover:border-emerald-500 transition-all group">
      <div>
        <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-3xl font-black mb-4 group-hover:scale-110 transition-all">
          🤖
        </div>
        <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-800 font-mono">Mobile Client</span>
        <h3 class="text-xl font-black text-slate-900 mt-1 mb-2">Android App</h3>
        <p class="text-xs text-slate-600 leading-relaxed">
          Optimized for smallholder field smartphones. Runs on low RAM (1GB+) and supports offline IndexedDB harvest logging.
        </p>
      </div>

      <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
        <div class="text-[10px] text-slate-500 font-mono">Requires Android 7.0+ • APK / Play</div>
        <button onclick="openComingSoonModal('Android App')" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-glow-emerald transition-all flex items-center justify-center gap-1.5">
          <span>⬇</span> Download for Android
        </button>
      </div>
    </div>

    <!-- Apple iOS Card -->
    <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-slate-200 shadow-warm-md flex flex-col justify-between hover:border-slate-800 transition-all group">
      <div>
        <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center text-3xl font-black mb-4 group-hover:scale-110 transition-all">
          🍏
        </div>
        <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-800 font-mono">Apple iOS</span>
        <h3 class="text-xl font-black text-slate-900 mt-1 mb-2">iPhone & iPad</h3>
        <p class="text-xs text-slate-600 leading-relaxed">
          Fluid procurement experience for commercial off-takers and managers. Instant push alerts for incoming produce batches.
        </p>
      </div>

      <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
        <div class="text-[10px] text-slate-500 font-mono">Requires iOS 14.0+ • App Store</div>
        <button onclick="openComingSoonModal('iPhone / iOS App')" class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-sm transition-all flex items-center justify-center gap-1.5">
          <span>⬇</span> Download for iOS
        </button>
      </div>
    </div>

    <!-- Windows Card -->
    <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-slate-200 shadow-warm-md flex flex-col justify-between hover:border-amber-500 transition-all group">
      <div>
        <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-3xl font-black mb-4 group-hover:scale-110 transition-all">
          🪟
        </div>
        <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-800 font-mono">Desktop Client</span>
        <h3 class="text-xl font-black text-slate-900 mt-1 mb-2">Windows App</h3>
        <p class="text-xs text-slate-600 leading-relaxed">
          Built for commercial wholesale depots, grain elevators, and freight fleet dispatch centers across Zimbabwe.
        </p>
      </div>

      <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
        <div class="text-[10px] text-slate-500 font-mono">Win 10/11 • 64-bit Installer (.exe)</div>
        <button onclick="openComingSoonModal('Windows Desktop App')" class="w-full py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow-glow-amber transition-all flex items-center justify-center gap-1.5">
          <span>⬇</span> Download for Windows
        </button>
      </div>
    </div>

    <!-- Linux Card -->
    <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-slate-200 shadow-warm-md flex flex-col justify-between hover:border-orange-500 transition-all group">
      <div>
        <div class="w-14 h-14 rounded-2xl bg-orange-100 text-orange-700 flex items-center justify-center text-3xl font-black mb-4 group-hover:scale-110 transition-all">
          🐧
        </div>
        <span class="text-[10px] font-extrabold uppercase tracking-widest text-orange-800 font-mono">Open Source Linux</span>
        <h3 class="text-xl font-black text-slate-900 mt-1 mb-2">Linux App</h3>
        <p class="text-xs text-slate-600 leading-relaxed">
          Lightweight, open distribution package for Ubuntu, Debian, Fedora, Arch, and rural cooperative terminal kiosks.
        </p>
      </div>

      <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
        <div class="text-[10px] text-slate-500 font-mono">.deb / AppImage / Flatpak</div>
        <button onclick="openComingSoonModal('Linux Native App')" class="w-full py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-bold text-xs shadow-glow-orange transition-all flex items-center justify-center gap-1.5">
          <span>⬇</span> Download for Linux
        </button>
      </div>
    </div>

  </div>

  <!-- 3. USE VUNOTHO EVEN WHEN DATA IS SLOW / OFFLINE HIGHLIGHT -->
  <div class="bg-white/90 backdrop-blur-md p-8 md:p-10 rounded-3xl border border-slate-200 shadow-warm-md space-y-6">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl font-black">
        📡
      </div>
      <div>
        <h2 class="text-xl md:text-2xl font-black text-slate-900">Always Connected — Even in Low-Signal Rural Areas</h2>
        <p class="text-xs text-slate-500">How Vunotho overcomes poor connectivity across Zimbabwe's farming districts.</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
      <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
        <span class="text-emerald-700 font-extrabold text-xs font-mono">1. Instant Offline Storage</span>
        <h4 class="font-bold text-slate-900 text-sm">Log Harvests With 0KB Data</h4>
        <p class="text-xs text-slate-600 leading-relaxed">
          Smallholders in deep valleys (Nyanga, Mutasa, Gwanda) can log produce grades and quantities completely offline. Data saves locally in secure storage.
        </p>
      </div>

      <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
        <span class="text-amber-700 font-extrabold text-xs font-mono">2. Smart Auto-Sync</span>
        <h4 class="font-bold text-slate-900 text-sm">Automatic Cloud Reconnection</h4>
        <p class="text-xs text-slate-600 leading-relaxed">
          The moment your handset catches even a 2G / EDGE signal, pending records are idempotently synchronized to the central PostgreSQL registry.
        </p>
      </div>

      <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
        <span class="text-teal-700 font-extrabold text-xs font-mono">3. Lightweight Payload</span>
        <h4 class="font-bold text-slate-900 text-sm">Under 2MB Data Per Week</h4>
        <p class="text-xs text-slate-600 leading-relaxed">
          Ultra-compact JSON sync payload means smallholder farmers do not burn expensive mobile data bundles to trade on the platform.
        </p>
      </div>
    </div>

    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
      <div class="flex items-center gap-2 text-emerald-900 font-bold">
        <span>💡</span>
        <span>Tip: You can use the web portal today on any browser without installing anything!</span>
      </div>
      <a href="/login.php" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold transition-all shadow-sm whitespace-nowrap">
        Launch Web Portal Now →
      </a>
    </div>
  </div>

</div>

<!-- 4. COMING SOON MODAL WITH BRANDING & ENACTUS SIGNATURE -->
<div id="coming-soon-modal" class="vunotho-modal-backdrop">
  <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-warm-xl max-w-md w-full relative text-center space-y-5">
    
    <!-- Modal Close Button -->
    <button onclick="closeComingSoonModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 font-bold text-lg">
      ✕
    </button>

    <!-- Floating Emblem -->
    <img src="/images/vunotho_logo.jpg" alt="Vunotho Official Logo" class="w-16 h-16 rounded-2xl object-cover mx-auto shadow-glow-emerald border border-emerald-500/30" />

    <div>
      <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-[11px] font-extrabold font-mono mb-2">
        🚀 Field Testing Phase
      </div>
      <h3 class="text-xl font-black text-slate-900" id="modal-platform-title">Native App — Coming Soon</h3>
      <p class="text-xs text-slate-600 mt-2 leading-relaxed" id="modal-platform-desc">
        The dedicated binary application is currently undergoing final multi-district offline stress testing across Manicaland and Matabeleland farming cooperatives.
      </p>
    </div>

    <!-- Recommendation Box -->
    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 text-left space-y-1.5">
      <div class="font-bold text-slate-900 flex items-center gap-1">
        <span>✓</span> <strong>Fully Functional Web App Available Today:</strong>
      </div>
      <p class="text-[11px]">
        You can access all smallholder harvest logging, price intelligence calculations, and commercial demand matching immediately via our live web portal.
      </p>
    </div>

    <!-- Official Enactus Signage -->
    <div class="pt-4 border-t border-slate-100 flex items-center justify-center gap-2 text-xs font-mono text-emerald-800 font-extrabold">
      <span>🌱</span>
      <span>Vunotho Innovation Team • Enactus Zimbabwe</span>
    </div>

    <div class="flex gap-2">
      <a href="/login.php" class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-glow-emerald transition-all">
        Open Web Portal →
      </a>
      <button onclick="closeComingSoonModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all border border-slate-200">
        Got It
      </button>
    </div>

  </div>
</div>

<script>
  function openComingSoonModal(platform) {
    const modal = document.getElementById('coming-soon-modal');
    const title = document.getElementById('modal-platform-title');
    const desc = document.getElementById('modal-platform-desc');
    if (modal && title && desc) {
      title.textContent = `${platform} — Coming Soon`;
      desc.textContent = `The dedicated native client for ${platform} is currently in final field validation with Enactus smallholder farming cohorts. The web application is 100% operational today!`;
      modal.classList.add('active');
    }
  }

  function closeComingSoonModal() {
    const modal = document.getElementById('coming-soon-modal');
    if (modal) {
      modal.classList.remove('active');
    }
  }
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
