import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/theme/vunotho_theme.dart';
import '../../data/models/listing_model.dart';
import '../../logic/providers/listing_provider.dart';
import 'add_listing_dialog.dart';

class FarmerDashboard extends StatelessWidget {
  const FarmerDashboard({super.key});

  @override
  Widget build(BuildContext context) {
    final listingProvider = context.watch<ListingProvider>();
    final listings = listingProvider.listings;

    final totalKg = listings.fold(0.0, (acc, l) => acc + l.quantityKg);
    final effectiveKg = totalKg > 0 ? totalKg : 4500.0;
    final totalEstValue = listings.fold(
      0.0,
      (acc, l) => acc + listingProvider.calculateEstimatedValue(l.quality, l.quantityKg),
    );
    final effectiveEstValue = totalEstValue > 0 ? totalEstValue : 3165.00;

    return Scaffold(
      backgroundColor: VunothoColors.scaffoldBg,
      body: RefreshIndicator(
        onRefresh: () => listingProvider.loadListings(),
        color: VunothoColors.primaryDark,
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // 1. GREETING & LOCATION BAR
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        children: [
                          Text(
                            'Hi, Farmer',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 22,
                              fontWeight: FontWeight.w900,
                              color: VunothoColors.textDark,
                              letterSpacing: -0.5,
                            ),
                          ),
                          const SizedBox(width: 6),
                          const Text('🌾', style: TextStyle(fontSize: 18)),
                        ],
                      ),
                      const SizedBox(height: 3),
                      Row(
                        children: [
                          const Icon(Icons.location_on_rounded, size: 13, color: VunothoColors.primary),
                          const SizedBox(width: 3),
                          Text(
                            'Bulawayo Central, Zimbabwe',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 11.5,
                              fontWeight: FontWeight.w600,
                              color: VunothoColors.textMuted,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),

                  // Soft Glass Notification Bell
                  Stack(
                    children: [
                      Container(
                        width: 44,
                        height: 44,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(color: VunothoColors.cardBorder),
                          boxShadow: VunothoTheme.softShadow,
                        ),
                        child: const Icon(Icons.notifications_none_rounded, color: VunothoColors.textDark, size: 22),
                      ),
                      Positioned(
                        top: 4,
                        right: 4,
                        child: Container(
                          width: 15,
                          height: 15,
                          decoration: BoxDecoration(
                            color: const Color(0xFFD97706),
                            shape: BoxShape.circle,
                            border: Border.all(color: Colors.white, width: 2),
                          ),
                          child: const Center(
                            child: Text(
                              '3',
                              style: TextStyle(
                                color: Colors.white,
                                fontSize: 8,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
              const SizedBox(height: 16),

              // 2. 4-DAY MICRO WEATHER FORECAST STRIP
              SizedBox(
                height: 72,
                child: ListView(
                  scrollDirection: Axis.horizontal,
                  physics: const BouncingScrollPhysics(),
                  children: [
                    _buildWeatherPill('Today', '24°C', Icons.wb_sunny_rounded, const Color(0xFFD97706), isSelected: true),
                    const SizedBox(width: 8),
                    _buildWeatherPill('Tomorrow', '23°C', Icons.wb_cloudy_rounded, const Color(0xFF0284C7)),
                    const SizedBox(width: 8),
                    _buildWeatherPill('Thursday', '25°C', Icons.wb_sunny_rounded, const Color(0xFFD97706)),
                    const SizedBox(width: 8),
                    _buildWeatherPill('Friday', '22°C', Icons.grain_rounded, const Color(0xFF0D9488)),
                  ],
                ),
              ),
              const SizedBox(height: 16),

              // 3. MODERN HERO CARD (IMAGE AS VISUAL ANCHOR + PASTEL BADGE)
              Container(
                width: double.infinity,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(24),
                  boxShadow: VunothoTheme.diffuseShadow,
                ),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(24),
                  child: Stack(
                    children: [
                      // Background Image with Rich Botanical Overlay
                      Positioned.fill(
                        child: Image.asset(
                          'assets/images/maize_hero.png',
                          fit: BoxFit.cover,
                          errorBuilder: (context, error, stackTrace) => Container(
                            color: VunothoColors.primaryDark,
                          ),
                        ),
                      ),
                      Positioned.fill(
                        child: Container(
                          decoration: BoxDecoration(
                            gradient: LinearGradient(
                              colors: [
                                const Color(0xFF071726).withValues(alpha: 0.92),
                                const Color(0xFF0A2E1D).withValues(alpha: 0.82),
                                const Color(0xFF064E3B).withValues(alpha: 0.65),
                              ],
                              begin: Alignment.centerLeft,
                              end: Alignment.centerRight,
                            ),
                          ),
                        ),
                      ),

                      // Card Content
                      Padding(
                        padding: const EdgeInsets.all(20),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                  decoration: BoxDecoration(
                                    color: const Color(0xFF15803D).withValues(alpha: 0.4),
                                    borderRadius: BorderRadius.circular(9999),
                                    border: Border.all(color: const Color(0xFF86EFAC).withValues(alpha: 0.6)),
                                  ),
                                  child: Row(
                                    children: [
                                      const Icon(Icons.shield_rounded, color: Color(0xFF86EFAC), size: 12),
                                      const SizedBox(width: 4),
                                      Text(
                                        'GUARANTEED NET RETURN',
                                        style: GoogleFonts.jetBrainsMono(
                                          color: const Color(0xFF86EFAC),
                                          fontSize: 9.5,
                                          fontWeight: FontWeight.w800,
                                          letterSpacing: 0.5,
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3.5),
                                  decoration: BoxDecoration(
                                    color: Colors.white.withValues(alpha: 0.15),
                                    borderRadius: BorderRadius.circular(8),
                                  ),
                                  child: Text(
                                    '35% Freight Saved',
                                    style: GoogleFonts.plusJakartaSans(
                                      color: const Color(0xFFFBBF24),
                                      fontSize: 10,
                                      fontWeight: FontWeight.w700,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 14),
                            Text(
                              'Grow More. Earn More.\nWaste Less.',
                              style: GoogleFonts.plusJakartaSans(
                                color: Colors.white,
                                fontSize: 19,
                                fontWeight: FontWeight.w900,
                                height: 1.2,
                                letterSpacing: -0.3,
                              ),
                            ),
                            const SizedBox(height: 6),
                            Text(
                              'Lock in transparent benchmark prices and direct EcoCash wallet payouts before dispatch.',
                              style: GoogleFonts.plusJakartaSans(
                                color: const Color(0xFFCBD5E1),
                                fontSize: 11.5,
                                height: 1.4,
                              ),
                            ),
                            const SizedBox(height: 16),
                            SizedBox(
                              height: 42,
                              child: ElevatedButton.icon(
                                onPressed: () {
                                  showDialog(
                                    context: context,
                                    builder: (_) => const AddListingDialog(),
                                  );
                                },
                                icon: const Icon(Icons.add_rounded, size: 18),
                                label: Text(
                                  'List New Produce',
                                  style: GoogleFonts.plusJakartaSans(
                                    fontSize: 13,
                                    fontWeight: FontWeight.w800,
                                  ),
                                ),
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: const Color(0xFF15803D),
                                  foregroundColor: Colors.white,
                                  elevation: 0,
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(9999)),
                                  padding: const EdgeInsets.symmetric(horizontal: 20),
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 18),

              // 4. 4 CLEAN KPI SUMMARY TILES (HAIRLINE BORDERS + DIFFUSE SHADOWS)
              GridView.count(
                crossAxisCount: 2,
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                crossAxisSpacing: 10,
                mainAxisSpacing: 10,
                childAspectRatio: 1.55,
                children: [
                  _buildKpiCard(
                    'Available Produce',
                    '${effectiveKg.toStringAsFixed(0)} kg',
                    'Ready for pickup',
                    Icons.inventory_2_rounded,
                    const Color(0xFF15803D),
                  ),
                  _buildKpiCard(
                    'Est. Net Payout',
                    '\$${effectiveEstValue.toStringAsFixed(2)}',
                    'EcoCash wallet',
                    Icons.payments_rounded,
                    const Color(0xFFD97706),
                  ),
                  _buildKpiCard(
                    'Active Buyers',
                    '12 Verified',
                    'Direct supermarkets',
                    Icons.storefront_rounded,
                    const Color(0xFF0284C7),
                  ),
                  _buildKpiCard(
                    'Pooled Transport',
                    '2 Routes',
                    'Gwanda corridor',
                    Icons.local_shipping_rounded,
                    const Color(0xFF0D9488),
                  ),
                ],
              ),
              const SizedBox(height: 22),

              // 5. REGISTERED PRODUCE LOTS (WITH REAL CROP THUMBNAILS & PASTEL PILLS)
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    'My Produce Lots',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 16,
                      fontWeight: FontWeight.w800,
                      color: VunothoColors.textDark,
                      letterSpacing: -0.3,
                    ),
                  ),
                  InkWell(
                    onTap: () {
                      showDialog(
                        context: context,
                        builder: (_) => const AddListingDialog(),
                      );
                    },
                    borderRadius: BorderRadius.circular(8),
                    child: Padding(
                      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 4),
                      child: Row(
                        children: [
                          const Icon(Icons.add, size: 15, color: VunothoColors.primaryDark),
                          const SizedBox(width: 3),
                          Text(
                            'Add Lot',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 12.5,
                              fontWeight: FontWeight.w700,
                              color: VunothoColors.primaryDark,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 10),

              if (listings.isEmpty)
                _buildDefaultProduceLots(context)
              else
                ...listings.map((l) => _buildListingTile(context, l)),

              const SizedBox(height: 22),

              // 6. DEPOT PRICE INTELLIGENCE CARD
              Container(
                padding: const EdgeInsets.all(18),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(22),
                  border: Border.all(color: VunothoColors.cardBorder),
                  boxShadow: VunothoTheme.softShadow,
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          'Depot Price Intelligence',
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 14.5,
                            fontWeight: FontWeight.w800,
                            color: VunothoColors.textDark,
                          ),
                        ),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                          decoration: BoxDecoration(
                            color: const Color(0xFFE8F5E9),
                            borderRadius: BorderRadius.circular(6),
                          ),
                          child: Text(
                            'Live Feed',
                            style: GoogleFonts.jetBrainsMono(
                              fontSize: 9.5,
                              fontWeight: FontWeight.w800,
                              color: const Color(0xFF1B5E20),
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 14),
                    _buildPriceRow('Roma Tomatoes', '\$0.42 /kg', '+12%', const Color(0xFF16A34A)),
                    const Divider(height: 18, color: Color(0xFFF1F5F9)),
                    _buildPriceRow('Brown Onions', '\$0.35 /kg', '+8%', const Color(0xFF16A34A)),
                    const Divider(height: 18, color: Color(0xFFF1F5F9)),
                    _buildPriceRow('Table Potatoes', '\$0.30 /kg', '+5%', const Color(0xFF16A34A)),
                    const Divider(height: 18, color: Color(0xFFF1F5F9)),
                    _buildPriceRow('Leafy Greens', '\$0.25 /kg', '-3%', const Color(0xFFEA580C)),
                  ],
                ),
              ),
              const SizedBox(height: 36),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildWeatherPill(String day, String temp, IconData icon, Color iconColor, {bool isSelected = false}) {
    return Container(
      width: 96,
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
      decoration: BoxDecoration(
        color: isSelected ? const Color(0xFF143D28) : Colors.white,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(
          color: isSelected ? const Color(0xFF143D28) : VunothoColors.cardBorder,
        ),
        boxShadow: VunothoTheme.softShadow,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text(
            day,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 10,
              fontWeight: FontWeight.w600,
              color: isSelected ? const Color(0xFF86EFAC) : VunothoColors.textMuted,
            ),
          ),
          const SizedBox(height: 2),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                temp,
                style: GoogleFonts.jetBrainsMono(
                  fontSize: 13.5,
                  fontWeight: FontWeight.w800,
                  color: isSelected ? Colors.white : VunothoColors.textDark,
                ),
              ),
              Icon(icon, size: 16, color: isSelected ? const Color(0xFFFBBF24) : iconColor),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildKpiCard(String title, String value, String subtext, IconData icon, Color color) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: VunothoColors.cardBorder),
        boxShadow: VunothoTheme.softShadow,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                title,
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 11,
                  fontWeight: FontWeight.w600,
                  color: VunothoColors.textMuted,
                ),
              ),
              Icon(icon, color: color, size: 16),
            ],
          ),
          Text(
            value,
            style: GoogleFonts.jetBrainsMono(
              fontSize: 16,
              fontWeight: FontWeight.w900,
              color: VunothoColors.textDark,
            ),
          ),
          Text(
            subtext,
            style: GoogleFonts.plusJakartaSans(
              fontSize: 9.5,
              fontWeight: FontWeight.w600,
              color: color,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildDefaultProduceLots(BuildContext context) {
    final lots = [
      {
        'crop': 'Butternut Squash',
        'qty': '1,450 kg',
        'grade': 'Grade A (Premium)',
        'price': '\$0.42/kg',
        'icon': Icons.eco_rounded,
        'color': const Color(0xFF15803D),
      },
      {
        'crop': 'Sugar Beans',
        'qty': '850 kg',
        'grade': 'Grade A (Export / Retail)',
        'price': '\$0.35/kg',
        'icon': Icons.grain_rounded,
        'color': const Color(0xFF0284C7),
      },
      {
        'crop': 'Tomatoes',
        'qty': '2,200 kg',
        'grade': 'Grade B (Processing / Puree)',
        'price': '\$0.30/kg',
        'icon': Icons.local_florist_rounded,
        'color': const Color(0xFFD97706),
      },
    ];

    return Column(
      children: lots.map((lot) {
        return Container(
          margin: const EdgeInsets.only(bottom: 10),
          padding: const EdgeInsets.all(14),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(20),
            border: Border.all(color: VunothoColors.cardBorder),
            boxShadow: VunothoTheme.softShadow,
          ),
          child: Row(
            children: [
              // Crop Rounded Thumbnail Badge
              Container(
                width: 44,
                height: 44,
                decoration: BoxDecoration(
                  color: (lot['color'] as Color).withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(14),
                  border: Border.all(color: (lot['color'] as Color).withValues(alpha: 0.2)),
                ),
                child: Center(
                  child: Icon(lot['icon'] as IconData, color: lot['color'] as Color, size: 22),
                ),
              ),
              const SizedBox(width: 14),

              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      lot['crop'] as String,
                      style: GoogleFonts.plusJakartaSans(fontSize: 14, fontWeight: FontWeight.w800),
                    ),
                    const SizedBox(height: 3),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 7, vertical: 2),
                      decoration: BoxDecoration(
                        color: const Color(0xFFE8F5E9),
                        borderRadius: BorderRadius.circular(6),
                      ),
                      child: Text(
                        lot['grade'] as String,
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 9.5,
                          fontWeight: FontWeight.w700,
                          color: const Color(0xFF1B5E20),
                        ),
                      ),
                    ),
                  ],
                ),
              ),

              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text(
                    lot['qty'] as String,
                    style: GoogleFonts.jetBrainsMono(
                      fontSize: 13.5,
                      fontWeight: FontWeight.w900,
                      color: VunothoColors.textDark,
                    ),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    lot['price'] as String,
                    style: GoogleFonts.jetBrainsMono(
                      fontSize: 10.5,
                      fontWeight: FontWeight.w600,
                      color: VunothoColors.textMuted,
                    ),
                  ),
                ],
              ),
            ],
          ),
        );
      }).toList(),
    );
  }

  Widget _buildListingTile(BuildContext context, ListingModel listing) {
    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: VunothoColors.cardBorder),
        boxShadow: VunothoTheme.softShadow,
      ),
      child: Row(
        children: [
          Container(
            width: 44,
            height: 44,
            decoration: BoxDecoration(
              color: const Color(0xFFE8F5E9),
              borderRadius: BorderRadius.circular(14),
            ),
            child: const Center(
              child: Icon(Icons.eco_rounded, color: Color(0xFF1B5E20), size: 22),
            ),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  listing.crop,
                  style: GoogleFonts.plusJakartaSans(fontSize: 14, fontWeight: FontWeight.w800),
                ),
                const SizedBox(height: 3),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 7, vertical: 2),
                  decoration: BoxDecoration(
                    color: const Color(0xFFE8F5E9),
                    borderRadius: BorderRadius.circular(6),
                  ),
                  child: Text(
                    listing.quality,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 9.5,
                      fontWeight: FontWeight.w700,
                      color: const Color(0xFF1B5E20),
                    ),
                  ),
                ),
              ],
            ),
          ),
          Text(
            '${listing.quantityKg.toStringAsFixed(0)} kg',
            style: GoogleFonts.jetBrainsMono(
              fontSize: 13.5,
              fontWeight: FontWeight.w900,
              color: VunothoColors.textDark,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPriceRow(String crop, String price, String change, Color changeColor) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(crop, style: GoogleFonts.plusJakartaSans(fontSize: 13, fontWeight: FontWeight.w700)),
        Row(
          children: [
            Text(price, style: GoogleFonts.jetBrainsMono(fontSize: 13, fontWeight: FontWeight.bold)),
            const SizedBox(width: 8),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
              decoration: BoxDecoration(
                color: changeColor.withValues(alpha: 0.1),
                borderRadius: BorderRadius.circular(6),
              ),
              child: Text(
                change,
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 10,
                  fontWeight: FontWeight.w800,
                  color: changeColor,
                ),
              ),
            ),
          ],
        ),
      ],
    );
  }
}
