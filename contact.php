<?php
/**
 * CONTACT US & ENQUIRIES HUB (Server-Rendered PHP & Tailwind CSS)
 */
require_once __DIR__ . '/api/session.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Security session expired. Please refresh and try again.';
    } else {
        $name = sanitize_string($_POST['name'] ?? '');
        $email_or_phone = sanitize_email_or_phone($_POST['email_or_phone'] ?? '');
        $role = sanitize_string($_POST['role'] ?? 'Farmer');
        $district = sanitize_string($_POST['district'] ?? 'Harare');
        $enquiry = sanitize_string($_POST['message'] ?? '', 1000);

        if (empty($name) || empty($email_or_phone) || empty($enquiry)) {
            $error = 'Please fill in all required fields.';
        } else {
            $message = "Thank you, {$name}! Your enquiry has been routed to our regional field operations desk in {$district}. We will contact you shortly.";
        }
    }
}

$pageTitle = 'Contact Us & Field Support — Vunotho Agricultural Platform';
require_once __DIR__ . '/includes/header.php';
?>

<div class="max-w-4xl mx-auto space-y-12">
  
  <!-- Header Card -->
  <div class="bg-gradient-to-br from-white via-[#FAF8F5] to-[#F1F5F9] p-8 md:p-12 rounded-3xl border border-slate-200 shadow-warm-lg">
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold font-mono mb-4">
      📞 National Field Support & Partnerships
    </div>
    <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-4">
      Get in Touch with <span class="text-emerald-600">Vunotho</span>
    </h1>
    <p class="text-xs md:text-sm text-slate-600 leading-relaxed max-w-2xl">
      Reach our agricultural coordination team across Harare, Nyanga, Gwanda, Mutasa, and Bulawayo for farmer onboarding, bulk buyer procurement, or haulier fleet integration.
    </p>
  </div>

  <!-- Status Alert -->
  <?php if (!empty($message)): ?>
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-warm-sm">
      <span>✓ <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></span>
      <button onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">✕</button>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center justify-between shadow-warm-sm">
      <span>⚠️ <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
      <button onclick="this.parentElement.remove()" class="text-rose-700 font-bold">✕</button>
    </div>
  <?php endif; ?>

  <!-- 2-Column Contact Section: Form + Hub Coordinates -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Left: Contact Form -->
    <div class="lg:col-span-7 bg-white/90 backdrop-blur-md p-8 rounded-3xl border border-slate-200 shadow-warm-md space-y-6">
      <div>
        <h2 class="text-xl font-black text-slate-900">Send an Enquiry / Request Onboarding</h2>
        <p class="text-xs text-slate-500 mt-1">Our field agents respond within 2 business hours.</p>
      </div>

      <form method="POST" action="/contact.php" class="space-y-4">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>" />

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Full Name / Trading Entity</label>
          <input type="text" name="name" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" placeholder="e.g. Sipho Moyo / Fresh Express Ltd" required />
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number (WhatsApp Preferred) or Email</label>
          <input type="text" name="email_or_phone" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" placeholder="e.g. 0773878836" required />
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">I am a...</label>
            <select name="role" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold">
              <option value="Smallholder Farmer" selected>Smallholder Farmer</option>
              <option value="Commercial Buyer">Commercial Off-Taker / Buyer</option>
              <option value="Agro-Processor">Agro-Processor</option>
              <option value="Transporter">Freight Haulier / Fleet</option>
              <option value="Partner">Development / Enactus Partner</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Nearest District</label>
            <select name="district" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold">
              <option value="Nyanga" selected>Nyanga</option>
              <option value="Harare">Harare Hub</option>
              <option value="Gwanda">Gwanda</option>
              <option value="Mutasa">Mutasa</option>
              <option value="Mutare">Mutare</option>
              <option value="Bulawayo">Bulawayo</option>
              <option value="Goromonzi">Goromonzi</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 mb-1">Your Message / Requirement</label>
          <textarea name="message" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" placeholder="Describe your produce volumes, procurement demands, or freight route inquiries..." required></textarea>
        </div>

        <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-glow-emerald transition-all mt-2">
          Submit Enquiry to Field Desk →
        </button>
      </form>
    </div>

    <!-- Right: Regional Hub Details & WhatsApp -->
    <div class="lg:col-span-5 space-y-6">
      
      <!-- WhatsApp Direct Card -->
      <div class="bg-gradient-to-br from-emerald-800 to-teal-900 text-white p-6 rounded-3xl shadow-warm-md space-y-3">
        <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/30 text-emerald-200 text-[10px] font-bold font-mono uppercase">Direct Hotline</span>
        <h3 class="text-lg font-black">WhatsApp Field Desk</h3>
        <p class="text-xs text-emerald-100 leading-relaxed">
          Need instant guidance on harvest logging or truck dispatch? Chat directly with our field coordination desk.
        </p>
        <a href="https://wa.me/263779634613" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-emerald-900 font-extrabold text-xs shadow-sm hover:bg-emerald-50 transition-all mt-2">
          💬 Chat on WhatsApp (+263 77 963 4613)
        </a>
      </div>

      <!-- Regional Hub Addresses -->
      <div class="bg-white/90 backdrop-blur-md p-6 rounded-3xl border border-slate-200 shadow-warm-md space-y-4 text-xs text-slate-600">
        <h3 class="font-black text-slate-900 text-sm">Regional Coordination Hubs</h3>
        
        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
          <strong class="text-slate-900">National Headquarters (Harare)</strong>
          <div>Harare Agricultural Showgrounds, Exhibition Park, Harare</div>
          <div class="font-mono text-emerald-700 font-bold">harare@vunotho.co.zw</div>
        </div>

        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
          <strong class="text-slate-900">Eastern Highlands Hub (Nyanga & Mutasa)</strong>
          <div>Nyanga Horticultural Consolidation Center, Manicaland</div>
          <div class="font-mono text-emerald-700 font-bold">nyanga@vunotho.co.zw</div>
        </div>

        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
          <strong class="text-slate-900">Southern Hub (Bulawayo & Gwanda)</strong>
          <div>Belmont Agro-Industrial Wholesale Depot, Bulawayo</div>
          <div class="font-mono text-emerald-700 font-bold">bulawayo@vunotho.co.zw</div>
        </div>
      </div>

    </div>

  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
