import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/theme/vunotho_theme.dart';
import '../../data/models/listing_model.dart';
import '../../logic/providers/listing_provider.dart';
import 'add_listing_dialog.dart';

class FarmerProduceScreen extends StatefulWidget {
  const FarmerProduceScreen({super.key});

  @override
  State<FarmerProduceScreen> createState() => _FarmerProduceScreenState();
}

class _FarmerProduceScreenState extends State<FarmerProduceScreen> {
  String _selectedFilter = 'All';

  final List<String> _filterOptions = [
    'All',
    'Grade A',
    'Grade B',
    'Ready for Pickup',
    'In 2.5T Pool',
  ];

  @override
  Widget build(BuildContext context) {
    final listingProvider = context.watch<ListingProvider>();
    final allListings = listingProvider.listings;

    List<ListingModel> filteredListings = allListings;
    if (_selectedFilter == 'Grade A') {
      filteredListings = allListings.where((l) => l.quality.contains('Grade A')).toList();
    } else if (_selectedFilter == 'Grade B') {
      filteredListings = allListings.where((l) => l.quality.contains('Grade B')).toList();
    } else if (_selectedFilter == 'In 2.5T Pool') {
      filteredListings = allListings.where((l) => l.status.contains('Pool') || l.status.contains('Assigned')).toList();
    }

    final totalKg = allListings.fold(0.0, (acc, l) => acc + l.quantityKg);
    final effectiveKg = totalKg > 0 ? totalKg : 4500.0;
    final totalValue = allListings.fold(
      0.0,
      (acc, l) => acc + listingProvider.calculateEstimatedValue(l.quality, l.quantityKg),
    );
    final effectiveValue = totalValue > 0 ? totalValue : 3165.00;

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
              // 1. TOP PRODUCE HEADER & SUMMARY BANNER
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
                        Expanded(
                          child: Row(
                            children: [
                              Container(
                                width: 38,
                                height: 38,
                                decoration: BoxDecoration(
                                  color: const Color(0xFFE8F5E9),
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                child: const Center(
                                  child: Icon(Icons.eco_rounded, color: Color(0xFF1B5E20), size: 22),
                                ),
                              ),
                              const SizedBox(width: 10),
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      'My Harvest Batches',
                                      style: GoogleFonts.plusJakartaSans(
                                        fontSize: 15,
                                        fontWeight: FontWeight.w900,
                                        color: VunothoColors.textDark,
                                      ),
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                    Text(
                                      'Registered Farmgate Produce',
                                      style: GoogleFonts.plusJakartaSans(
                                        fontSize: 10.5,
                                        color: VunothoColors.textMuted,
                                      ),
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                  ],
                                ),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(width: 8),
                        ElevatedButton.icon(
                          onPressed: () {
                            showDialog(
                              context: context,
                              builder: (_) => const AddListingDialog(),
                            );
                          },
                          icon: const Icon(Icons.add, size: 15),
                          label: Text(
                            'Log Batch',
                            style: GoogleFonts.plusJakartaSans(fontSize: 11.5, fontWeight: FontWeight.w800),
                          ),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: VunothoColors.primaryDark,
                            foregroundColor: Colors.white,
                            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
                            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                            elevation: 0,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),
                    const Divider(height: 1, color: Color(0xFFF1F5F9)),
                    const SizedBox(height: 14),

                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceAround,
                      children: [
                        _buildStatItem('Total Produce', '${effectiveKg.toStringAsFixed(0)} KG', const Color(0xFF15803D)),
                        Container(width: 1, height: 32, color: const Color(0xFFE2E8F0)),
                        _buildStatItem('Est. Net Return', '\$${effectiveValue.toStringAsFixed(2)}', const Color(0xFFD97706)),
                        Container(width: 1, height: 32, color: const Color(0xFFE2E8F0)),
                        _buildStatItem('Active Lots', '${allListings.isEmpty ? 3 : allListings.length} Lots', const Color(0xFF0284C7)),
                      ],
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 16),

              // 2. HORIZONTAL FILTER PILLS
              SizedBox(
                height: 38,
                child: ListView.separated(
                  scrollDirection: Axis.horizontal,
                  physics: const BouncingScrollPhysics(),
                  itemCount: _filterOptions.length,
                  separatorBuilder: (context, index) => const SizedBox(width: 8),
                  itemBuilder: (context, index) {
                    final filter = _filterOptions[index];
                    final isSelected = _selectedFilter == filter;
                    return InkWell(
                      onTap: () => setState(() => _selectedFilter = filter),
                      borderRadius: BorderRadius.circular(12),
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                        decoration: BoxDecoration(
                          color: isSelected ? VunothoColors.primaryDark : Colors.white,
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(
                            color: isSelected ? VunothoColors.primaryDark : VunothoColors.cardBorder,
                          ),
                        ),
                        child: Text(
                          filter,
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 11.5,
                            fontWeight: isSelected ? FontWeight.w800 : FontWeight.w600,
                            color: isSelected ? Colors.white : VunothoColors.textBody,
                          ),
                        ),
                      ),
                    );
                  },
                ),
              ),
              const SizedBox(height: 14),

              // 3. PRODUCE LOT CARDS
              if (allListings.isEmpty)
                ..._buildDemoProduceCards(context)
              else
                ...filteredListings.map((listing) => _buildProduceCard(context, listing)),

              const SizedBox(height: 30),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildStatItem(String label, String value, Color color) {
    return Column(
      children: [
        Text(
          value,
          style: GoogleFonts.jetBrainsMono(
            fontSize: 14.5,
            fontWeight: FontWeight.w900,
            color: color,
          ),
        ),
        const SizedBox(height: 2),
        Text(
          label,
          style: GoogleFonts.plusJakartaSans(
            fontSize: 10.5,
            fontWeight: FontWeight.w500,
            color: VunothoColors.textMuted,
          ),
        ),
      ],
    );
  }

  List<Widget> _buildDemoProduceCards(BuildContext context) {
    final demoItems = [
      {
        'crop': 'Butternut Squash',
        'qty': 1450.0,
        'grade': 'Grade A (Supermarket Spec)',
        'district': 'Nyanga Horticultural Depot',
        'floorPrice': '\$0.42 /kg',
        'estTotal': '\$609.00',
        'status': 'Ready at Farmgate',
        'statusColor': const Color(0xFF15803D),
        'icon': Icons.eco_rounded,
      },
      {
        'crop': 'Sugar Beans',
        'qty': 850.0,
        'grade': 'Grade A (Export / Retail)',
        'district': 'Mutasa Cooperative Cluster',
        'floorPrice': '\$0.35 /kg',
        'estTotal': '\$297.50',
        'status': 'Assigned to 2.5T Truck',
        'statusColor': const Color(0xFFD97706),
        'icon': Icons.grain_rounded,
      },
      {
        'crop': 'Roma Tomatoes',
        'qty': 2200.0,
        'grade': 'Grade B (Processing / Puree)',
        'district': 'Chipinge Horticultural Hub',
        'floorPrice': '\$0.30 /kg',
        'estTotal': '\$660.00',
        'status': 'Matched Cairns Foods',
        'statusColor': const Color(0xFF0284C7),
        'icon': Icons.local_florist_rounded,
      },
    ];

    return demoItems.map((item) {
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
                      width: 42,
                      height: 42,
                      decoration: BoxDecoration(
                        color: (item['statusColor'] as Color).withValues(alpha: 0.1),
                        borderRadius: BorderRadius.circular(14),
                      ),
                      child: Icon(item['icon'] as IconData, color: item['statusColor'] as Color, size: 22),
                    ),
                    const SizedBox(width: 12),
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          item['crop'] as String,
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 15,
                            fontWeight: FontWeight.w800,
                            color: VunothoColors.textDark,
                          ),
                        ),
                        Text(
                          item['district'] as String,
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
                    color: (item['statusColor'] as Color).withValues(alpha: 0.1),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Text(
                    item['status'] as String,
                    style: GoogleFonts.plusJakartaSans(
                      fontSize: 10,
                      fontWeight: FontWeight.w800,
                      color: item['statusColor'] as Color,
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 12),

            // Grade Badge
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
              decoration: BoxDecoration(
                color: const Color(0xFFE8F5E9),
                borderRadius: BorderRadius.circular(6),
              ),
              child: Text(
                item['grade'] as String,
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 10,
                  fontWeight: FontWeight.w700,
                  color: const Color(0xFF1B5E20),
                ),
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
                    Text('Volume', style: GoogleFonts.plusJakartaSans(fontSize: 10.5, color: VunothoColors.textMuted)),
                    Text('${(item['qty'] as double).toStringAsFixed(0)} KG', style: GoogleFonts.jetBrainsMono(fontSize: 14, fontWeight: FontWeight.w900)),
                  ],
                ),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('Floor Price', style: GoogleFonts.plusJakartaSans(fontSize: 10.5, color: VunothoColors.textMuted)),
                    Text(item['floorPrice'] as String, style: GoogleFonts.jetBrainsMono(fontSize: 13, fontWeight: FontWeight.w800)),
                  ],
                ),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.end,
                  children: [
                    Text('Est. Net Payout', style: GoogleFonts.plusJakartaSans(fontSize: 10.5, color: VunothoColors.textMuted)),
                    Text(item['estTotal'] as String, style: GoogleFonts.jetBrainsMono(fontSize: 14, fontWeight: FontWeight.w900, color: const Color(0xFF15803D))),
                  ],
                ),
              ],
            ),
          ],
        ),
      );
    }).toList();
  }

  Widget _buildProduceCard(BuildContext context, ListingModel listing) {
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
                    width: 42,
                    height: 42,
                    decoration: BoxDecoration(
                      color: const Color(0xFFE8F5E9),
                      borderRadius: BorderRadius.circular(14),
                    ),
                    child: const Center(
                      child: Icon(Icons.eco_rounded, color: Color(0xFF1B5E20), size: 22),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        listing.crop,
                        style: GoogleFonts.plusJakartaSans(
                          fontSize: 15,
                          fontWeight: FontWeight.w800,
                          color: VunothoColors.textDark,
                        ),
                      ),
                      Text(
                        '${listing.district} District',
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
                  listing.status,
                  style: GoogleFonts.plusJakartaSans(
                    fontSize: 10,
                    fontWeight: FontWeight.w800,
                    color: const Color(0xFF1B5E20),
                  ),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),

          // Grade Badge
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
            decoration: BoxDecoration(
              color: const Color(0xFFE8F5E9),
              borderRadius: BorderRadius.circular(6),
            ),
            child: Text(
              listing.quality,
              style: GoogleFonts.plusJakartaSans(
                fontSize: 10,
                fontWeight: FontWeight.w700,
                color: const Color(0xFF1B5E20),
              ),
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
                  Text('Volume', style: GoogleFonts.plusJakartaSans(fontSize: 10.5, color: VunothoColors.textMuted)),
                  Text('${listing.quantityKg.toStringAsFixed(0)} KG', style: GoogleFonts.jetBrainsMono(fontSize: 14, fontWeight: FontWeight.w900)),
                ],
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  Text('Status', style: GoogleFonts.plusJakartaSans(fontSize: 10.5, color: VunothoColors.textMuted)),
                  Text(listing.syncStatus, style: GoogleFonts.jetBrainsMono(fontSize: 12, fontWeight: FontWeight.w700, color: const Color(0xFF15803D))),
                ],
              ),
            ],
          ),
        ],
      ),
    );
  }
}
