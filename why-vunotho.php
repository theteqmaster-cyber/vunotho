<?php
/**
 * WHY VUNOTHO? — THE VALUE PROPOSITION & SYSTEM TRANSFORMATION (Server-Rendered PHP & Tailwind CSS)
 */
require_once __DIR__ . '/api/session.php';

$pageTitle = 'Why Vunotho? — Eliminating Inefficiencies in Zimbabwe Agriculture';
require_once __DIR__ . '/includes/header.php';
?>

<div class="vn-container py-10">
  <div class="max-w-4xl mx-auto space-y-10">
    
    <!-- Hero Section -->
    <div class="bg-white/95 backdrop-blur-md p-8 md:p-12 rounded-3xl border border-emerald-100/80 shadow-sm">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono mb-4">
        ⚡ System Transformation & Impact
      </div>
      <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-4">
        Why Zimbabwe Needs <span class="text-emerald-700">Vunotho</span>
      </h1>
      <p class="text-sm md:text-base text-slate-600 leading-relaxed max-w-2xl">
        A transparent, broker-free operating model designed for mutual economic surplus across smallholder growers, commercial buyers, and rural freight transporters.
      </p>
    </div>

    <!-- Deep Dive for Each Stakeholder: Farmers, Buyers, Hauliers -->
    <div class="space-y-6">
      
      <!-- For Smallholder Farmers -->
      <div class="bg-white/95 backdrop-blur-md p-8 rounded-3xl border border-emerald-100/80 shadow-sm border-l-8 border-l-emerald-600">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-100">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
          </div>
          <h2 class="text-xl md:text-2xl font-black text-slate-900">Why Smallholder Farmers Need Vunotho</h2>
        </div>
        <div class="space-y-3 text-xs md:text-sm text-slate-600 leading-relaxed">
          <p>
            <strong class="text-slate-900">1. Real-Time Net-Return Price Certainty:</strong> Farmers no longer guess what they will be paid at Mbare Musika or Belmont. Before harvesting, the system computes the exact net payout after transport and 4% coordination fees.
          </p>
          <p>
            <strong class="text-slate-900">2. Direct Mobile Money Settlement:</strong> Payouts disburse directly to EcoCash / OneMoney wallets upon digital verification at pickup—no promissory notes or delayed cheques.
          </p>
          <p>
            <strong class="text-slate-900">3. 100% Crop Monetization:</strong> Even off-spec Grade B and Grade C produce is routed to agro-processors and livestock feeders rather than being thrown away.
          </p>
        </div>
      </div>

      <!-- For Commercial Off-Takers & Buyers -->
      <div class="bg-white/95 backdrop-blur-md p-8 rounded-3xl border border-emerald-100/80 shadow-sm border-l-8 border-l-amber-500">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center border border-amber-100">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
          </div>
          <h2 class="text-xl md:text-2xl font-black text-slate-900">Why Commercial Buyers Need Vunotho</h2>
        </div>
        <div class="space-y-3 text-xs md:text-sm text-slate-600 leading-relaxed">
          <p>
            <strong class="text-slate-900">1. Traceable, Direct Sourcing:</strong> Supermarkets, wholesalers, and food processors source directly from verified smallholder cohorts with complete provenance data.
          </p>
          <p>
            <strong class="text-slate-900">2. Standardized 4-Tier Grading:</strong> Strict quality specifications ensure that deliveries match the ordered grade (Grade A Supermarket, Grade B Puree/Processing, etc.).
          </p>
          <p>
            <strong class="text-slate-900">3. Automated Escrow Settlements:</strong> Clean digital invoicing and automated payment release upon delivery receipt verification.
          </p>
        </div>
      </div>

      <!-- For Transporters & Logistics Hauliers -->
      <div class="bg-white/95 backdrop-blur-md p-8 rounded-3xl border border-emerald-100/80 shadow-sm border-l-8 border-l-orange-500">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-700 flex items-center justify-center border border-orange-100">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
          </div>
          <h2 class="text-xl md:text-2xl font-black text-slate-900">Why Transporters Need Vunotho</h2>
        </div>
        <div class="space-y-3 text-xs md:text-sm text-slate-600 leading-relaxed">
          <p>
            <strong class="text-slate-900">1. High-Utilization Full Truckloads (2.5T):</strong> No more returning from rural areas with half-empty beds.
          </p>
          <p>
            <strong class="text-slate-900">2. Optimized Waypoint Routing:</strong> The system clusters smallholder pickups into single corridor manifests.
          </p>
          <p>
            <strong class="text-slate-900">3. Guaranteed Freight Payouts:</strong> Logistics fees are automatically deducted from the buyer's gross deposit and remitted directly to the transporter.
          </p>
        </div>
      </div>

    </div>

  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
