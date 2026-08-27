<?php
/**
 * VUNOTHO DATA PROTECTION & HANDLING POLICY (Server-Rendered PHP & Tailwind CSS)
 */
require_once __DIR__ . '/api/session.php';

$pageTitle = 'Data Protection & Handling Policy — Vunotho Agricultural Platform';
require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-4xl mx-auto space-y-10">
  
  <!-- Header Card -->
  <div class="bg-gradient-to-br from-white via-[#FAF8F5] to-[#F1F5F9] p-8 md:p-12 rounded-3xl border border-slate-200 shadow-warm-lg">
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-100 text-teal-800 text-xs font-bold font-mono mb-4">
      🔒 Enterprise Architecture • Security Standards
    </div>
    <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-4">
      Data Handling & Protection Policy
    </h1>
    <p class="text-xs md:text-sm text-slate-600 leading-relaxed max-w-2xl">
      Technical and operational protocols governing data capture, cryptographic integrity, offline-first reconciliation, and financial ledger security.
    </p>
  </div>

  <!-- Policy Deep Dive -->
  <div class="bg-white/90 backdrop-blur-md p-8 md:p-10 rounded-3xl border border-slate-200 shadow-warm-md space-y-8 text-xs md:text-sm text-slate-700 leading-relaxed">
    
    <section class="space-y-3">
      <h2 class="text-lg font-black text-slate-900">1. Architectural Principles of Data Protection</h2>
      <p>
        The Vunotho platform employs a <strong>Defense-in-Depth</strong> security architecture designed to safeguard smallholder identity, GPS farmgate coordinates, commercial buyer contracts, and financial settlement logs against tampering, interception, and unauthorized access.
      </p>
    </section>

    <section class="space-y-3">
      <h2 class="text-lg font-black text-slate-900">2. Encryption & Transmission Security</h2>
      <ul class="space-y-2 list-disc pl-5">
        <li><strong>Data in Transit:</strong> All HTTP transactions strictly enforce TLS 1.3 encryption. Security headers including <code>Content-Security-Policy</code>, <code>X-Content-Type-Options: nosniff</code>, <code>X-Frame-Options: SAMEORIGIN</code>, and <code>Strict-Transport-Security</code> are enforced on every server response.</li>
        <li><strong>Data at Rest:</strong> Database backups and cloud storage repositories in PostgreSQL use AES-256 encryption. User passwords are hashed with <code>PASSWORD_BCRYPT</code> with cost factor 12.</li>
        <li><strong>CSRF Mitigation:</strong> All mutating POST endpoints enforce cryptographically randomized anti-CSRF token verification with strict SameSite session cookies.</li>
      </ul>
    </section>

    <section class="space-y-3">
      <h2 class="text-lg font-black text-slate-900">3. Offline-First Synchronization Integrity</h2>
      <p>
        Smallholder farmers logging produce in remote farming valleys (such as Nyanga, Mutasa, and Gwanda) store operational data in local browser IndexedDB instances.
      </p>
      <ul class="space-y-2 list-disc pl-5">
        <li>Every offline record is stamped with a unique UUID v4 and local deterministic timestamp.</li>
        <li>When cellular connectivity is restored, the client sync engine batches mutations to <code>/api/sync.php</code> using idempotent upsert operations to eliminate duplicate billing or duplicate harvest registrations.</li>
      </ul>
    </section>

    <section class="space-y-3">
      <h2 class="text-lg font-black text-slate-900">4. Financial Ledger Immutability</h2>
      <p>
        All settled produce transactions, pooled transport deductions, platform surplus fees, and EcoCash remittance references are committed to an append-only relational ledger. Once a transaction receipt is digitally sealed, financial values cannot be retroactively modified without executive administrator multisig authorization and audit log generation.
      </p>
    </section>

    <section class="space-y-3">
      <h2 class="text-lg font-black text-slate-900">5. Data Retention and Archiving Lifecycle</h2>
      <ul class="space-y-2 list-disc pl-5">
        <li><strong>Active Market Orders:</strong> Demands and listings remain active until matched or cancelled by the author.</li>
        <li><strong>Financial Records:</strong> Transaction logs are preserved for a statutory duration of 7 years in accordance with national financial audit and tax compliance standards in Zimbabwe.</li>
        <li><strong>Audit Logs:</strong> Administrative authentication and KYC modifications are recorded with client IP hash and timestamp indefinitely for platform forensics.</li>
      </ul>
    </section>

  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
