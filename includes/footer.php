<?php
/**
 * VUNOTHO COMMON FOOTER COMPONENT (PHP & Tailwind CSS)
 */
?>
  </main>

  <!-- Comprehensive Professional Footer -->
  <footer class="bg-white/95 backdrop-blur-md border-t border-slate-200 mt-20 pt-14 pb-10 text-xs text-slate-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
        
        <!-- Column 1: Brand & Enactus Mission -->
        <div class="lg:col-span-2 space-y-4">
          <div class="flex items-center gap-3">
            <img src="/images/vunotho_logo.jpg" alt="Vunotho Official Logo" class="w-10 h-10 rounded-xl object-cover shadow-sm border border-emerald-500/20" />
            <div>
              <span class="font-extrabold text-base tracking-tight text-slate-900 leading-none">VUNOTHO</span>
              <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Agricultural Operating System</div>
            </div>
          </div>
          <p class="text-xs text-slate-600 leading-relaxed max-w-sm">
            Vunotho is Zimbabwe's decentralized agricultural operating system developed to eliminate predatory middleman exploitation, secure transparent farmgate net returns, and divert 100% of post-harvest produce into high-value commercial channels.
          </p>
          <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-bold font-mono">
            🌱 An Enactus Zimbabwe Action Innovation
          </div>
        </div>

        <!-- Column 2: Platform Portals -->
        <div class="space-y-3">
          <h4 class="font-extrabold text-xs uppercase tracking-wider text-slate-900">Platform Desks</h4>
          <ul class="space-y-2 text-xs">
            <li><a href="/login.php?role=farmer" class="hover:text-emerald-700 font-semibold transition-all">Smallholder Produce Hub</a></li>
            <li><a href="/login.php?role=buyer" class="hover:text-amber-700 font-semibold transition-all">Commercial Procurement Desk</a></li>
            <li><a href="/login.php?role=transporter" class="hover:text-orange-700 font-semibold transition-all">Rural Freight Fleet Desk</a></li>
            <li><a href="/login.php?role=admin" class="hover:text-slate-900 font-semibold transition-all">Executive Command Center</a></li>
            <li><a href="/index.php" class="hover:text-emerald-700 font-semibold transition-all">Price Intelligence Simulator</a></li>
          </ul>
        </div>

        <!-- Column 3: Governance & Information -->
        <div class="space-y-3">
          <h4 class="font-extrabold text-xs uppercase tracking-wider text-slate-900">Knowledge & Legal</h4>
          <ul class="space-y-2 text-xs">
            <li><a href="/access.php" class="hover:text-emerald-700 font-bold text-emerald-800 transition-all flex items-center gap-1.5">📲 Download Vunotho App (Access)</a></li>
            <li><a href="/about.php" class="hover:text-slate-900 font-semibold transition-all">About Vunotho</a></li>
            <li><a href="/why-vunotho.php" class="hover:text-emerald-700 font-semibold transition-all">Why Vunotho? (Value Proposition)</a></li>
            <li><a href="/privacy.php" class="hover:text-slate-900 font-semibold transition-all">Privacy Policy</a></li>
            <li><a href="/data-policy.php" class="hover:text-slate-900 font-semibold transition-all">Data Protection & Handling Policy</a></li>
            <li><a href="/contact.php" class="hover:text-emerald-700 font-semibold transition-all">Contact Us & Support</a></li>
          </ul>
        </div>

        <!-- Column 4: Operational Corridors & Hotline -->
        <div class="space-y-3">
          <h4 class="font-extrabold text-xs uppercase tracking-wider text-slate-900">National Hubs</h4>
          <div class="space-y-2 text-xs text-slate-500">
            <div>📍 <strong>National Hub:</strong> Harare Agricultural Showgrounds, Harare</div>
            <div>📍 <strong>Eastern Hub:</strong> Nyanga Horticultural Depot, Manicaland</div>
            <div>📍 <strong>Southern Hub:</strong> Belmont Wholesale Depot, Bulawayo</div>
            <div class="pt-2 text-emerald-800 font-bold font-mono">
              📞 Hotline: +263 77 963 4613
            </div>
          </div>
        </div>

      </div>

      <!-- Bottom Bar -->
      <div class="pt-8 border-t border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-slate-500 text-xs">
        <div>
          © <?= date('Y') ?> Vunotho Agricultural Platform. All rights reserved. Registered in Zimbabwe.
        </div>
        <div class="flex items-center gap-4 font-medium">
          <a href="/privacy.php" class="hover:underline">Privacy</a>
          <a href="/data-policy.php" class="hover:underline">Data Security</a>
          <a href="/contact.php" class="hover:underline">Contact Team</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Toast Notification Stack -->
  <div id="toast-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none [&>*]:pointer-events-auto"></div>

  <!-- Interactive JavaScript Libraries -->
  <script src="/js/pricing.js"></script>
  <script src="/js/settlement.js"></script>

  <script>
    function showToast(message, type = 'info') {
      const container = document.getElementById('toast-container');
      if (!container) return;
      const toast = document.createElement('div');
      const colors = type === 'success' ? 'bg-emerald-700 text-white' : (type === 'warning' ? 'bg-amber-600 text-white' : (type === 'error' ? 'bg-rose-600 text-white' : 'bg-slate-900 text-white'));
      toast.className = `toast-item px-4 py-3 rounded-xl shadow-warm-lg text-xs font-bold flex items-center justify-between gap-3 ${colors}`;
      toast.innerHTML = `<span>${message}</span><button class="opacity-70 hover:opacity-100 font-bold" onclick="this.parentElement.remove()">✕</button>`;
      container.appendChild(toast);
      setTimeout(() => { if (toast.parentElement) { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s ease'; setTimeout(() => toast.remove(), 300); } }, 4000);
    }
  </script>
</body>
</html>
