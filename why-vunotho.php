<?php
/**
 * WHY VUNOTHO? — THE VALUE PROPOSITION & SYSTEM TRANSFORMATION (Server-Rendered PHP & Tailwind CSS)
 */
require_once __DIR__ . '/api/session.php';

$pageTitle = 'Why Vunotho? — Eliminating Inefficiencies in Zimbabwe Agriculture';
require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-4xl mx-auto space-y-12">
  
  <!-- Hero Section -->
  <div class="bg-gradient-to-br from-white via-[#FAF8F5] to-[#F1F5F9] p-8 md:p-12 rounded-3xl border border-slate-200 shadow-warm-lg">
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold font-mono mb-4">
      ⚡ System Transformation & Impact
    </div>
    <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-4">
      Why Zimbabwe Needs <span class="text-amber-600">Vunotho</span>
    </h1>
    <p class="text-sm md:text-base text-slate-600 leading-relaxed max-w-2xl">
      A transparent, broker-free operating model designed for mutual economic surplus across smallholder growers, commercial buyers, and rural freight transporters.
    </p>
  </div>

  <!-- Deep Dive for Each Stakeholder: Farmers, Buyers, Hauliers -->
  <div class="space-y-8">
    
    <!-- For Smallholder Farmers -->
    <div class="bg-white/90 backdrop-blur-md p-8 rounded-3xl border border-slate-200 shadow-warm-md border-l-8 border-l-emerald-500">
      <div class="flex items-center gap-3 mb-4">
        <span class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl font-black">🌱</span>
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
    <div class="bg-white/90 backdrop-blur-md p-8 rounded-3xl border border-slate-200 shadow-warm-md border-l-8 border-l-amber-500">
      <div class="flex items-center gap-3 mb-4">
        <span class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl font-black">🏢</span>
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
          <strong class="text-slate-900">3. Aggregated Bulk Deliveries:</strong> Instead of negotiating with 30 individual farmers, buyers receive consolidated 2.5-tonne shipments at their designated wholesale depot.
        </p>
      </div>
    </div>

    <!-- For Rural Freight Transporters & Hauliers -->
    <div class="bg-white/90 backdrop-blur-md p-8 rounded-3xl border border-slate-200 shadow-warm-md border-l-8 border-l-orange-500">
      <div class="flex items-center gap-3 mb-4">
        <span class="w-10 h-10 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center text-xl font-black">🚚</span>
        <h2 class="text-xl md:text-2xl font-black text-slate-900">Why Logistics Hauliers Need Vunotho</h2>
      </div>
      <div class="space-y-3 text-xs md:text-sm text-slate-600 leading-relaxed">
        <p>
          <strong class="text-slate-900">1. Guaranteed Full 2.5-Tonne Truckloads:</strong> Hauliers no longer travel with empty capacity. The algorithm aggregates neighboring farm stops to maximize payload.
        </p>
        <p>
          <strong class="text-slate-900">2. Escrowed Diesel Remittances:</strong> Transport fees are deducted automatically from gross produce proceeds and remitted directly upon terminal delivery.
        </p>
        <p>
          <strong class="text-slate-900">3. Optimized Waypoint Routing:</strong> Drivers receive sequenced GPS waypoint manifests that minimize fuel consumption across rural feeder roads.
        </p>
      </div>
    </div>

  </div>

  <!-- What Vunotho Seeks to Improve (The Before vs After) -->
  <div class="bg-white/90 backdrop-blur-md p-8 md:p-10 rounded-3xl border border-slate-200 shadow-warm-md">
    <h2 class="text-2xl font-black text-slate-900 tracking-tight mb-6">What Vunotho Seeks to Improve</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="p-6 rounded-2xl bg-rose-50 border border-rose-200 space-y-2">
        <span class="text-xs font-bold uppercase tracking-wider text-rose-700 font-mono">Traditional Status Quo</span>
        <h4 class="font-bold text-rose-900 text-base">Exploitative Middlemen System</h4>
        <ul class="space-y-1.5 text-xs text-rose-800 list-disc pl-4">
          <li>Roadside brokers take 35%–55% margins</li>
          <li>Delayed or defaulted cash payments</li>
          <li>30%+ post-harvest cosmetic produce dump waste</li>
          <li>Empty, expensive backhaul truck trips</li>
        </ul>
      </div>

      <div class="p-6 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-2">
        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 font-mono">Vunotho Transformation</span>
        <h4 class="font-bold text-emerald-900 text-base">Guaranteed Direct Value Pipeline</h4>
        <ul class="space-y-1.5 text-xs text-emerald-800 list-disc pl-4">
          <li>Fixed 4% transparent platform coordination fee</li>
          <li>Instant EcoCash mobile money on pickup</li>
          <li>100% circular post-harvest valorization (SDG 12.3)</li>
          <li>Clustered 2.5T load manifests with 35% savings</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Big Call-To-Action: Contact Us Now -->
  <div class="bg-gradient-to-br from-[#0F2942] via-[#0A192F] to-[#060D17] text-white p-8 md:p-12 rounded-3xl border border-slate-700 shadow-warm-xl text-center space-y-6">
    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold font-mono">
      Ready to Collaborate?
    </span>
    <h2 class="text-2xl md:text-4xl font-black tracking-tight">
      Join the Agricultural Operating System Today
    </h2>
    <p class="text-xs md:text-sm text-slate-300 max-w-xl mx-auto leading-relaxed">
      Whether you are a smallholder grower, an off-taker supermarket, an agro-processor, or a fleet operator, our team is ready to onboard you.
    </p>
    <div class="flex flex-wrap items-center justify-center gap-4">
      <a href="/contact.php" class="px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs shadow-glow-emerald transition-all">
        Contact Us Now →
      </a>
      <a href="/login.php?mode=register" class="px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition-all">
        Register Verified Account
      </a>
    </div>
  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
