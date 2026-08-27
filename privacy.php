<?php
/**
 * VUNOTHO PRIVACY POLICY (Server-Rendered PHP & Tailwind CSS)
 */
require_once __DIR__ . '/api/session.php';

$pageTitle = 'Privacy Policy — Vunotho Agricultural Platform';
require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-4xl mx-auto space-y-10">
  
  <!-- Header Card -->
  <div class="bg-gradient-to-br from-white via-[#FAF8F5] to-[#F1F5F9] p-8 md:p-12 rounded-3xl border border-slate-200 shadow-warm-lg">
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono mb-4">
      🛡️ Legal & Compliance • Zimbabwe Data Protection Act [Chapter 12:07]
    </div>
    <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-4">
      Privacy Policy
    </h1>
    <p class="text-xs md:text-sm text-slate-600 leading-relaxed max-w-2xl">
      Last Updated: August 27, 2026. This Privacy Policy details how the Vunotho Agricultural Operating System collects, protects, processes, and respects user and farmgate data.
    </p>
  </div>

  <!-- Content Sections -->
  <div class="bg-white/90 backdrop-blur-md p-8 md:p-10 rounded-3xl border border-slate-200 shadow-warm-md space-y-8 text-xs md:text-sm text-slate-700 leading-relaxed">
    
    <section class="space-y-3">
      <h2 class="text-lg font-black text-slate-900">1. Commitment to Smallholder Data Sovereignty</h2>
      <p>
        Vunotho was developed under the principle that smallholder farmers own their data. We do not monetize, sell, or rent your personal, harvest, yield, or location data to commercial advertisers or third-party brokers. All data collected serves exclusively to facilitate market matching, transparent net pricing, freight clustering, and verified mobile money settlements.
      </p>
    </section>

    <section class="space-y-3">
      <h2 class="text-lg font-black text-slate-900">2. Categories of Information We Collect</h2>
      <ul class="space-y-2 list-disc pl-5">
        <li><strong>User Profile Data:</strong> Full name, phone number, email address, district, province, farming entity name, and KYC verification status.</li>
        <li><strong>Agricultural Harvest Data:</strong> Commodity type, estimated harvest volume (kg), quality grading tier (Grade A, B, C, Compost), and farmgate GPS coordinates.</li>
        <li><strong>Commercial Demand Data:</strong> Required crops, purchase target quantities, offered prices, and wholesale delivery depots.</li>
        <li><strong>Financial Transaction Logs:</strong> Settlement timestamps, gross revenues, pooled transport deductions, platform coordination fees (4%), and EcoCash/mobile money transaction references.</li>
      </ul>
    </section>

    <section class="space-y-3">
      <h2 class="text-lg font-black text-slate-900">3. Purpose and Legal Basis of Processing</h2>
      <p>We process your data strictly to execute the following core platform operations:</p>
      <ul class="space-y-2 list-disc pl-5">
        <li>Matching smallholder produce supply lots with verified commercial buyers.</li>
        <li>Computing 2.5-Tonne light truck pooled routes to reduce rural transportation fees.</li>
        <li>Disbursing instant digital payouts directly to farmer mobile wallets upon produce handover.</li>
        <li>Tracking circular post-harvest food waste diversion in compliance with UN SDG 12.3.</li>
      </ul>
    </section>

    <section class="space-y-3">
      <h2 class="text-lg font-black text-slate-900">4. Data Storage, Security & Offline Encryption</h2>
      <p>
        All sensitive user credentials and transaction records are encrypted in transit via SSL/TLS and hashed at rest using industry-standard Bcrypt algorithms. For rural operations in low-connectivity areas, cached data stored in local browser IndexedDB is protected and synchronized securely using idempotent tokens once internet connectivity is restored.
      </p>
    </section>

    <section class="space-y-3">
      <h2 class="text-lg font-black text-slate-900">5. Your Rights as a Data Subject</h2>
      <p>Under Zimbabwe's Cyber and Data Protection Act, you have the right to:</p>
      <ul class="space-y-2 list-disc pl-5">
        <li>Access a complete digital copy of your registered produce and transaction history.</li>
        <li>Request correction or updating of any inaccurate KYC profile details.</li>
        <li>Request complete account deletion and erasure of your operational profile from our central registry upon settling all active contracts.</li>
      </ul>
    </section>

    <section class="space-y-3 pt-6 border-t border-slate-100">
      <h2 class="text-lg font-black text-slate-900">6. Contact Our Data Protection Officer</h2>
      <p>
        If you have questions regarding this policy or wish to exercise your data rights, please contact our Governance team at:
        <br />
        <strong>Email:</strong> <span class="font-mono text-emerald-700 font-bold">privacy@vunotho.co.zw</span>
        <br />
        <strong>Physical Address:</strong> Vunotho Headquarters, Harare Agricultural Showgrounds, Harare, Zimbabwe.
      </p>
    </section>

  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
