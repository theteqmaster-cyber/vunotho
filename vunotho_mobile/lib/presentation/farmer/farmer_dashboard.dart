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
    final effectiveKg = totalKg > 0 ? totalKg : 420.0;
    final totalEstValue = listings.fold(
      0.0,
      (acc, l) => acc + listingProvider.calculateEstimatedValue(l.quality, l.quantityKg),
    );
    final effectiveEstValue = totalEstValue > 0 ? totalEstValue : 186.40;

    return Scaffold(
      backgroundColor: VunothoColors.lightBg,
      body: RefreshIndicator(
        onRefresh: () => listingProvider.loadListings(),
        color: VunothoColors.primary,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // 1. TOP GREETING & LOCATION HEADER
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
                      const SizedBox(height: 2),
                      Row(
                        children: [
                          const Icon(Icons.location_on_rounded, size: 13, color: Color(0xFF15803D)),
                          const SizedBox(width: 3),
                          Text(
                            'Bulawayo Central, Zimbabwe',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 11,
                              fontWeight: FontWeight.w600,
                              color: VunothoColors.textMuted,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),

                  // Notification Bell with Badge
                  Stack(
                    children: [
                      Container(
                        width: 44,
                        height: 44,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(14),
                          border: Border.all(color: VunothoColors.lightBorder),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withValues(alpha: 0.03),
                              blurRadius: 8,
                              offset: const Offset(0, 2),
                            ),
                          ],
                        ),
                        child: const Icon(Icons.notifications_none_rounded, color: VunothoColors.textDark, size: 22),
                      ),
                      Positioned(
                        top: 2,
                        right: 2,
                        child: Container(
                          width: 16,
                          height: 16,
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

              // 2. 4-DAY WEATHER MICRO-FORECAST BAR
              SizedBox(
                height: 74,
                child: ListView(
                  scrollDirection: Axis.horizontal,
                  children: [
                    _buildWeatherPill('Today', '24°C', Icons.wb_sunny_rounded, const Color(0xFFF59E0B), isHighlighted: true),
                    const SizedBox(width: 8),
                    _buildWeatherPill('Tomorrow', '23°C', Icons.wb_cloudy_rounded, const Color(0xFF0284C7)),
                    const SizedBox(width: 8),
                    _buildWeatherPill('Thursday', '25°C', Icons.wb_sunny_rounded, const Color(0xFFF59E0B)),
                    const SizedBox(width: 8),
                    _buildWeatherPill('Friday', '22°C', Icons.grain_rounded, const Color(0xFF0D9488)),
                  ],
                ),
              ),
              const SizedBox(height: 16),

              // 3. PRIMARY HERO ACTION BANNER (PAY AFTER SELL)
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(18),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [Color(0xFF071726), Color(0xFF0A2E1D), Color(0xFF064E3B)],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  borderRadius: BorderRadius.circular(22),
                  boxShadow: [
                    BoxShadow(
                      color: const Color(0xFF071726).withValues(alpha: 0.15),
                      blurRadius: 16,
                      offset: const Offset(0, 6),
                    ),
                  ],
                  border: Border.all(color: const Color(0xFF22C55E).withValues(alpha: 0.3)),
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3.5),
                          decoration: BoxDecoration(
                            color: const Color(0xFF15803D).withValues(alpha: 0.35),
                            borderRadius: BorderRadius.circular(9999),
                            border: Border.all(color: const Color(0xFF4ADE80).withValues(alpha: 0.4)),
                          ),
                          child: Row(
                            children: [
                              const Icon(Icons.shield_rounded, color: Color(0xFF4ADE80), size: 12),
                              const SizedBox(width: 4),
                              Text(
                                'GUARANTEED NET RETURN',
                                style: GoogleFonts.jetBrainsMono(
                                  color: const Color(0xFF4ADE80),
                                  fontSize: 9.5,
                                  fontWeight: FontWeight.w800,
                                  letterSpacing: 0.5,
                                ),
                              ),
                            ],
                          ),
                        ),
                        Text(
                          '● 35% Freight Saved',
                          style: GoogleFonts.plusJakartaSans(
                            color: const Color(0xFFFBBF24),
                            fontSize: 10,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),
                    Text(
                      'Grow More. Earn More. Waste Less.',
                      style: GoogleFonts.plusJakartaSans(
                        color: Colors.white,
                        fontSize: 17,
                        fontWeight: FontWeight.w900,
                        height: 1.25,
                      ),
                    ),
                    const SizedBox(height: 4),
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
                          elevation: 2,
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(9999)),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 18),

              // 4. 4 KPI SUMMARY METRIC CARDS
              GridView.count(
                crossAxisCount: 2,
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                crossAxisSpacing: 10,
                mainAxisSpacing: 10,
                childAspectRatio: 1.6,
                children: [
                  _buildKpiTile(
                    'Available Produce',
                    '${effectiveKg.toStringAsFixed(0)} kg',
                    'Ready for pickup',
                    Icons.inventory_2_rounded,
                    const Color(0xFF15803D),
                  ),
                  _buildKpiTile(
                    'Est. Net Payout',
                    '\$${effectiveEstValue.toStringAsFixed(2)}',
                    'EcoCash wallet',
                    Icons.payments_rounded,
                    const Color(0xFFD97706),
                  ),
                  _buildKpiTile(
                    'Active Buyers',
                    '12 Verified',
                    'Direct supermarkets',
                    Icons.storefront_rounded,
                    const Color(0xFF0284C7),
                  ),
                  _buildKpiTile(
                    'Pooled Transport',
                    '2 Routes',
                    'Gwanda corridor',
                    Icons.local_shipping_rounded,
                    const Color(0xFF0D9488),
                  ),
                ],
              ),
              const SizedBox(height: 20),

              // 5. REGISTERED PRODUCE LOTS SECTION
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    'My Produce Lots',
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 16,
                      fontWeight: FontWeight.w800,
                      color: VunothoColors.textDark,
                    ),
                  ),
                  TextButton.icon(
                    onPressed: () {
                      showDialog(
                        context: context,
                        builder: (_) => const AddListingDialog(),
                      );
                    },
                    icon: const Icon(Icons.add, size: 15, color: Color(0xFF15803D)),
                    label: Text(
                      'Add Lot',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 12,
                        fontWeight: FontWeight.w700,
                        color: const Color(0xFF15803D),
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 8),

              if (listings.isEmpty)
                _buildDemoProduceList(context)
              else
                ...listings.map((l) => _buildListingCard(context, l)),

              const SizedBox(height: 20),

              // 6. BENCHMARK COMMODITY PRICE INTELLIGENCE
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: VunothoColors.lightBorder),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withValues(alpha: 0.02),
                      blurRadius: 8,
                      offset: const Offset(0, 2),
                    ),
                  ],
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
                            fontSize: 14,
                            fontWeight: FontWeight.w800,
                            color: VunothoColors.textDark,
                          ),
                        ),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                          decoration: BoxDecoration(
                            color: const Color(0xFFDCFCE7),
                            borderRadius: BorderRadius.circular(6),
                          ),
                          child: Text(
                            'Live Feed',
                            style: GoogleFonts.jetBrainsMono(
                              fontSize: 9,
                              fontWeight: FontWeight.w800,
                              color: const Color(0xFF15803D),
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                    _buildPriceRow('Roma Tomatoes', '\$0.42 /kg', '+12% vs last week', const Color(0xFF16A34A)),
                    const Divider(height: 16, color: Color(0xFFF1F5F9)),
                    _buildPriceRow('Brown Onions', '\$0.35 /kg', '+8% vs last week', const Color(0xFF16A34A)),
                    const Divider(height: 16, color: Color(0xFFF1F5F9)),
                    _buildPriceRow('Table Potatoes', '\$0.30 /kg', '+5% vs last week', const Color(0xFF16A34A)),
                    const Divider(height: 16, color: Color(0xFFF1F5F9)),
                    _buildPriceRow('Leafy Greens', '\$0.25 /kg', '-3% vs last week', const Color(0xFFEA580C)),
                  ],
                ),
              ),
              const SizedBox(height: 40),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildWeatherPill(String day, String temp, IconData icon, Color iconColor, {bool isHighlighted = false}) {
    return Container(
      width: 96,
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
      decoration: BoxDecoration(
        color: isHighlighted ? const Color(0xFF15803D) : Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(
          color: isHighlighted ? const Color(0xFF15803D) : VunothoColors.lightBorder,
        ),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.02),
            blurRadius: 6,
            offset: const Offset(0, 2),
          ),
        ],
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
              color: isHighlighted ? const Color(0xFFDCFCE7) : VunothoColors.textMuted,
            ),
          ),
          const SizedBox(height: 2),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                temp,
                style: GoogleFonts.jetBrainsMono(
                  fontSize: 14,
                  fontWeight: FontWeight.w800,
                  color: isHighlighted ? Colors.white : VunothoColors.textDark,
                ),
              ),
              Icon(icon, size: 16, color: isHighlighted ? Colors.white : iconColor),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildKpiTile(String title, String value, String subtext, IconData icon, Color color) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: VunothoColors.lightBorder),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.02),
            blurRadius: 6,
            offset: const Offset(0, 2),
          ),
        ],
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
                  fontSize: 10.5,
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
              fontWeight: FontWeight.w500,
              color: color,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildDemoProduceList(BuildContext context) {
    final demoLots = [
      {'crop': 'Roma Tomatoes', 'qty': '180 kg', 'quality': 'Grade A (Supermarket)', 'price': '\$0.42 /kg', 'color': const Color(0xFF15803D)},
      {'crop': 'Brown Onions', 'qty': '120 kg', 'quality': 'Grade A (10kg Pocket)', 'price': '\$0.35 /kg', 'color': const Color(0xFF0284C7)},
      {'crop': 'Table Potatoes', 'qty': '80 kg', 'quality': 'Grade A (15kg Mesh)', 'price': '\$0.30 /kg', 'color': const Color(0xFFD97706)},
    ];

    return Column(
      children: demoLots.map((lot) {
        return Container(
          margin: const EdgeInsets.only(bottom: 8),
          padding: const EdgeInsets.all(14),
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(18),
            border: Border.all(color: VunothoColors.lightBorder),
          ),
          child: Row(
            children: [
              Container(
                width: 38,
                height: 38,
                decoration: BoxDecoration(
                  color: (lot['color'] as Color).withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(Icons.eco_rounded, color: lot['color'] as Color, size: 20),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      lot['crop'] as String,
                      style: GoogleFonts.plusJakartaSans(fontSize: 13.5, fontWeight: FontWeight.bold),
                    ),
                    Text(
                      lot['quality'] as String,
                      style: GoogleFonts.plusJakartaSans(fontSize: 10.5, color: VunothoColors.textMuted),
                    ),
                  ],
                ),
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text(
                    lot['qty'] as String,
                    style: GoogleFonts.jetBrainsMono(fontSize: 13, fontWeight: FontWeight.w800, color: const Color(0xFF15803D)),
                  ),
                  Text(
                    lot['price'] as String,
                    style: GoogleFonts.jetBrainsMono(fontSize: 10, color: VunothoColors.textMuted),
                  ),
                ],
              ),
            ],
          ),
        );
      }).toList(),
    );
  }

  Widget _buildListingCard(BuildContext context, ListingModel listing) {
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: VunothoColors.lightBorder),
      ),
      child: Row(
        children: [
          Container(
            width: 38,
            height: 38,
            decoration: BoxDecoration(
              color: const Color(0xFF15803D).withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(12),
            ),
            child: const Icon(Icons.eco_rounded, color: Color(0xFF15803D), size: 20),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  listing.crop,
                  style: GoogleFonts.plusJakartaSans(fontSize: 13.5, fontWeight: FontWeight.bold),
                ),
                Text(
                  listing.quality,
                  style: GoogleFonts.plusJakartaSans(fontSize: 10.5, color: VunothoColors.textMuted),
                ),
              ],
            ),
          ),
          Text(
            '${listing.quantityKg.toStringAsFixed(0)} kg',
            style: GoogleFonts.jetBrainsMono(fontSize: 13, fontWeight: FontWeight.w800, color: const Color(0xFF15803D)),
          ),
        ],
      ),
    );
  }

  Widget _buildPriceRow(String crop, String price, String change, Color changeColor) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(crop, style: GoogleFonts.plusJakartaSans(fontSize: 12.5, fontWeight: FontWeight.w600)),
        Row(
          children: [
            Text(price, style: GoogleFonts.jetBrainsMono(fontSize: 12.5, fontWeight: FontWeight.bold)),
            const SizedBox(width: 8),
            Text(change, style: GoogleFonts.plusJakartaSans(fontSize: 10, fontWeight: FontWeight.w700, color: changeColor)),
          ],
        ),
      ],
    );
  }
}
