import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/theme/vunotho_theme.dart';
import '../../data/models/demand_model.dart';
import '../../logic/providers/demand_provider.dart';
import '../../logic/providers/listing_provider.dart';

class MarketplaceScreen extends StatefulWidget {
  const MarketplaceScreen({super.key});

  @override
  State<MarketplaceScreen> createState() => _MarketplaceScreenState();
}

class _MarketplaceScreenState extends State<MarketplaceScreen> {
  int _activeSegment = 0; // 0 = Buyer Demands, 1 = Farmgate Supply

  @override
  Widget build(BuildContext context) {
    final demandProvider = context.watch<DemandProvider>();
    final listingProvider = context.watch<ListingProvider>();
    final demands = demandProvider.demands;
    final listings = listingProvider.listings;

    return Scaffold(
      backgroundColor: VunothoColors.scaffoldBg,
      body: RefreshIndicator(
        onRefresh: () async {
          await demandProvider.loadDemands();
          await listingProvider.loadListings();
        },
        color: VunothoColors.primaryDark,
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // 1. TOP SEGMENTED TOGGLE (BUYER DEMANDS vs FARMGATE SUPPLY)
              Container(
                padding: const EdgeInsets.all(4),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: VunothoColors.cardBorder),
                  boxShadow: VunothoTheme.softShadow,
                ),
                child: Row(
                  children: [
                    Expanded(
                      child: InkWell(
                        onTap: () => setState(() => _activeSegment = 0),
                        borderRadius: BorderRadius.circular(12),
                        child: Container(
                          padding: const EdgeInsets.symmetric(vertical: 10),
                          decoration: BoxDecoration(
                            color: _activeSegment == 0 ? VunothoColors.primaryDark : Colors.transparent,
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Center(
                            child: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                Icon(
                                  Icons.storefront_rounded,
                                  size: 16,
                                  color: _activeSegment == 0 ? Colors.white : VunothoColors.textMuted,
                                ),
                                const SizedBox(width: 6),
                                Text(
                                  'Buyer Contracts',
                                  style: GoogleFonts.plusJakartaSans(
                                    fontSize: 12.5,
                                    fontWeight: _activeSegment == 0 ? FontWeight.w800 : FontWeight.w600,
                                    color: _activeSegment == 0 ? Colors.white : VunothoColors.textBody,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ),
                      ),
                    ),
                    Expanded(
                      child: InkWell(
                        onTap: () => setState(() => _activeSegment = 1),
                        borderRadius: BorderRadius.circular(12),
                        child: Container(
                          padding: const EdgeInsets.symmetric(vertical: 10),
                          decoration: BoxDecoration(
                            color: _activeSegment == 1 ? VunothoColors.primaryDark : Colors.transparent,
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Center(
                            child: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                Icon(
                                  Icons.eco_rounded,
                                  size: 16,
                                  color: _activeSegment == 1 ? Colors.white : VunothoColors.textMuted,
                                ),
                                const SizedBox(width: 6),
                                Text(
                                  'Farmgate Supply',
                                  style: GoogleFonts.plusJakartaSans(
                                    fontSize: 12.5,
                                    fontWeight: _activeSegment == 1 ? FontWeight.w800 : FontWeight.w600,
                                    color: _activeSegment == 1 ? Colors.white : VunothoColors.textBody,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 16),

              // 2. ACTIVE VIEW BODY
              if (_activeSegment == 0)
                _buildBuyerDemandsSection(context, demands)
              else
                _buildFarmgateSupplySection(context, listings),

              const SizedBox(height: 30),
            ],
          ),
        ),
      ),
    );
  }

  // --- BUYER DEMANDS STREAM ---
  Widget _buildBuyerDemandsSection(BuildContext context, List<DemandModel> demands) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Verified Wholesale Demands',
                  style: GoogleFonts.plusJakartaSans(fontSize: 15, fontWeight: FontWeight.w800),
                ),
                Text(
                  'Supermarkets & Agro-processors with escrow deposits',
                  style: GoogleFonts.plusJakartaSans(fontSize: 11, color: VunothoColors.textMuted),
                ),
              ],
            ),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
              decoration: BoxDecoration(
                color: const Color(0xFFDCFCE7),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Text(
                'Instant Match',
                style: GoogleFonts.jetBrainsMono(
                  fontSize: 9.5,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFF15803D),
                ),
              ),
            ),
          ],
        ),
        const SizedBox(height: 14),

        if (demands.isEmpty)
          ..._buildDefaultDemandCards(context)
        else
          ...demands.map((d) => _buildLiveDemandCard(context, d)),
      ],
    );
  }

  List<Widget> _buildDefaultDemandCards(BuildContext context) {
    final demoDemands = [
      {
        'buyer': 'Freshmark Zimbabwe',
        'crop': 'Butternut Squash',
        'qty': 5000.0,
        'price': 0.75,
        'grade': 'Grade A (Supermarket Retail)',
        'hub': 'Harare Fresh Distribution Depot',
        'deadline': 'Sep 05, 2026',
        'badgeColor': const Color(0xFF15803D),
      },
      {
        'buyer': 'Cairns Foods Agro-Processing',
        'crop': 'Roma Tomatoes',
        'qty': 8000.0,
        'price': 0.55,
        'grade': 'Grade B (Processing / Puree)',
        'hub': 'Mutare Industrial Cannery Hub',
        'deadline': 'Sep 10, 2026',
        'badgeColor': const Color(0xFF0284C7),
      },
      {
        'buyer': 'OK Zimbabwe Supermarkets',
        'crop': 'Brown Onions & Potatoes',
        'qty': 3500.0,
        'price': 0.65,
        'grade': 'Grade A (Export / Retail)',
        'hub': 'Belmont Wholesale Depot, Bulawayo',
        'deadline': 'Sep 12, 2026',
        'badgeColor': const Color(0xFFD97706),
      },
    ];

    return demoDemands.map((d) {
      final totalContractValue = (d['qty'] as double) * (d['price'] as double);

      return Container(
        margin: const EdgeInsets.only(bottom: 12),
        padding: const EdgeInsets.all(16),
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
                Row(
                  children: [
                    Container(
                      width: 40,
                      height: 40,
                      decoration: BoxDecoration(
                        color: (d['badgeColor'] as Color).withValues(alpha: 0.1),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Icon(Icons.storefront_rounded, color: d['badgeColor'] as Color, size: 20),
                    ),
                    const SizedBox(width: 12),
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          d['buyer'] as String,
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 14,
                            fontWeight: FontWeight.w800,
                            color: VunothoColors.textDark,
                          ),
                        ),
                        Text(
                          'Deadline: ${d['deadline']}',
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 11,
                            color: VunothoColors.textMuted,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3.5),
                  decoration: BoxDecoration(
                    color: const Color(0xFFE8F5E9),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Text(
                    '\$${(d['price'] as double).toStringAsFixed(2)} /kg',
                    style: GoogleFonts.jetBrainsMono(
                      fontSize: 12,
                      fontWeight: FontWeight.w900,
                      color: const Color(0xFF1B5E20),
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),

            Row(
              children: [
                Text(
                  'Order: ',
                  style: GoogleFonts.plusJakartaSans(fontSize: 12, color: VunothoColors.textMuted),
                ),
                Text(
                  '${(d['qty'] as double).toStringAsFixed(0)} KG of ${d['crop']}',
                  style: GoogleFonts.plusJakartaSans(fontSize: 12.5, fontWeight: FontWeight.w800),
                ),
              ],
            ),
            const SizedBox(height: 4),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
              decoration: BoxDecoration(
                color: const Color(0xFFF1F5F9),
                borderRadius: BorderRadius.circular(6),
              ),
              child: Text(
                d['grade'] as String,
                style: GoogleFonts.plusJakartaSans(fontSize: 10, fontWeight: FontWeight.w600, color: const Color(0xFF334155)),
              ),
            ),
            const SizedBox(height: 12),
            const Divider(height: 1, color: Color(0xFFF1F5F9)),
            const SizedBox(height: 12),

            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('Contract Value', style: GoogleFonts.plusJakartaSans(fontSize: 10.5, color: VunothoColors.textMuted)),
                    Text('\$${totalContractValue.toStringAsFixed(2)}', style: GoogleFonts.jetBrainsMono(fontSize: 14, fontWeight: FontWeight.w900, color: const Color(0xFF15803D))),
                  ],
                ),
                ElevatedButton.icon(
                  onPressed: () => _openFulfillSheet(context, d['buyer'] as String, d['crop'] as String),
                  icon: const Icon(Icons.handshake_rounded, size: 16),
                  label: Text(
                    'Fulfill Order',
                    style: GoogleFonts.plusJakartaSans(fontSize: 12, fontWeight: FontWeight.w800),
                  ),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: VunothoColors.primaryDark,
                    foregroundColor: Colors.white,
                    elevation: 0,
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                ),
              ],
            ),
          ],
        ),
      );
    }).toList();
  }

  Widget _buildLiveDemandCard(BuildContext context, DemandModel demand) {
    final totalVal = demand.targetQuantityKg * demand.offeredPricePerKg;

    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(16),
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
              Text(demand.buyerName, style: GoogleFonts.plusJakartaSans(fontSize: 14, fontWeight: FontWeight.w800)),
              Text('\$${demand.offeredPricePerKg.toStringAsFixed(2)} /kg', style: GoogleFonts.jetBrainsMono(fontSize: 12, fontWeight: FontWeight.w900, color: const Color(0xFF15803D))),
            ],
          ),
          const SizedBox(height: 6),
          Text('${demand.targetQuantityKg.toStringAsFixed(0)} KG of ${demand.crop}', style: GoogleFonts.plusJakartaSans(fontSize: 13, fontWeight: FontWeight.w700)),
          const SizedBox(height: 12),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text('Value: \$${totalVal.toStringAsFixed(2)}', style: GoogleFonts.jetBrainsMono(fontSize: 13, fontWeight: FontWeight.bold)),
              ElevatedButton(
                onPressed: () => _openFulfillSheet(context, demand.buyerName, demand.crop),
                style: ElevatedButton.styleFrom(
                  backgroundColor: VunothoColors.primaryDark,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                ),
                child: const Text('Fulfill Order', style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold)),
              ),
            ],
          ),
        ],
      ),
    );
  }

  // --- FARMGATE SUPPLY STREAM ---
  Widget _buildFarmgateSupplySection(BuildContext context, dynamic listings) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'Smallholder Produce Stream',
                  style: GoogleFonts.plusJakartaSans(fontSize: 15, fontWeight: FontWeight.w800),
                ),
                Text(
                  'Harvest lots ready for 2.5T load aggregation',
                  style: GoogleFonts.plusJakartaSans(fontSize: 11, color: VunothoColors.textMuted),
                ),
              ],
            ),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
              decoration: BoxDecoration(
                color: const Color(0xFFFFFBEB),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Text(
                '2.5T Pooled',
                style: GoogleFonts.jetBrainsMono(
                  fontSize: 9.5,
                  fontWeight: FontWeight.w800,
                  color: const Color(0xFFB45309),
                ),
              ),
            ),
          ],
        ),
        const SizedBox(height: 14),

        _buildSupplyStreamCards(),
      ],
    );
  }

  Widget _buildSupplyStreamCards() {
    final supplyLots = [
      {'farmer': 'Simba Mukamuri', 'crop': 'Butternut Squash', 'qty': '1,450 KG', 'district': 'Nyanga Valley', 'grade': 'Grade A', 'color': const Color(0xFF15803D)},
      {'farmer': 'Tariro Chitauro', 'crop': 'Sugar Beans', 'qty': '850 KG', 'district': 'Mutasa North', 'grade': 'Grade A', 'color': const Color(0xFF0284C7)},
      {'farmer': 'Farai Dube', 'crop': 'Roma Tomatoes', 'qty': '2,200 KG', 'district': 'Chipinge Hub', 'grade': 'Grade B', 'color': const Color(0xFFD97706)},
      {'farmer': 'Chiedza Moyo', 'crop': 'Table Potatoes', 'qty': '3,100 KG', 'district': 'Gwanda Central', 'grade': 'Grade A', 'color': const Color(0xFF0D9488)},
    ];

    return Column(
      children: supplyLots.map((lot) {
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
                width: 42,
                height: 42,
                decoration: BoxDecoration(
                  color: (lot['color'] as Color).withValues(alpha: 0.1),
                  borderRadius: BorderRadius.circular(14),
                ),
                child: Icon(Icons.agriculture_rounded, color: lot['color'] as Color, size: 22),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      lot['crop'] as String,
                      style: GoogleFonts.plusJakartaSans(fontSize: 14, fontWeight: FontWeight.w800),
                    ),
                    Text(
                      '${lot['farmer']} • ${lot['district']}',
                      style: GoogleFonts.plusJakartaSans(fontSize: 11, color: VunothoColors.textMuted),
                    ),
                  ],
                ),
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text(
                    lot['qty'] as String,
                    style: GoogleFonts.jetBrainsMono(fontSize: 13.5, fontWeight: FontWeight.w900, color: const Color(0xFF15803D)),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 1.5),
                    decoration: BoxDecoration(
                      color: const Color(0xFFE8F5E9),
                      borderRadius: BorderRadius.circular(4),
                    ),
                    child: Text(
                      lot['grade'] as String,
                      style: GoogleFonts.plusJakartaSans(fontSize: 9.5, fontWeight: FontWeight.w700, color: const Color(0xFF1B5E20)),
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

  void _openFulfillSheet(BuildContext context, String buyer, String crop) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.white,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (sheetCtx) => Padding(
        padding: const EdgeInsets.all(22),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Row(
              children: [
                const Icon(Icons.handshake_rounded, color: Color(0xFF15803D), size: 24),
                const SizedBox(width: 10),
                Text(
                  'Fulfill Buyer Order',
                  style: GoogleFonts.plusJakartaSans(fontSize: 17, fontWeight: FontWeight.w800),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Text(
              'Match your harvest lot with $buyer for $crop. Produce will be allocated into the next 2.5T transport manifest and payouts will disburse instantly to your EcoCash wallet.',
              style: GoogleFonts.plusJakartaSans(fontSize: 12.5, color: VunothoColors.textBody, height: 1.45),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: () {
                Navigator.of(sheetCtx).pop();
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(
                    content: Text('✓ Successfully linked harvest lot to $buyer order!'),
                    backgroundColor: VunothoColors.primaryDark,
                    behavior: SnackBarBehavior.floating,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                );
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: VunothoColors.primaryDark,
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 14),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
              ),
              child: Text('Confirm Order Fulfillment', style: GoogleFonts.plusJakartaSans(fontSize: 14, fontWeight: FontWeight.w800)),
            ),
          ],
        ),
      ),
    );
  }
}
