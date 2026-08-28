import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/constants/supabase_config.dart';
import '../../core/theme/vunotho_theme.dart';
import '../../logic/providers/listing_provider.dart';
import 'add_listing_dialog.dart';

class FarmerDashboard extends StatelessWidget {
  const FarmerDashboard({super.key});

  @override
  Widget build(BuildContext context) {
    final listingProvider = context.watch<ListingProvider>();
    final listings = listingProvider.listings;

    final totalKg = listings.fold(0.0, (acc, l) => acc + l.quantityKg);
    final totalEstValue = listings.fold(
      0.0,
      (acc, l) => acc + listingProvider.calculateEstimatedValue(l.quality, l.quantityKg),
    );

    return Scaffold(
      backgroundColor: VunothoColors.lightBg,
      body: RefreshIndicator(
        onRefresh: () => listingProvider.loadListings(),
        color: VunothoColors.primary,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Hero Callout / Action Card
              Container(
                padding: const EdgeInsets.all(18),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [VunothoColors.primaryDark, VunothoColors.primary],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  borderRadius: BorderRadius.circular(20),
                  boxShadow: [
                    BoxShadow(
                      color: VunothoColors.primary.withValues(alpha: 0.25),
                      blurRadius: 16,
                      offset: const Offset(0, 6),
                    ),
                  ],
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Expanded(
                          child: Text(
                            'FARMER HARVEST PORTAL',
                            style: TextStyle(
                              color: Color(0xFFA7F3D0),
                              fontSize: 11,
                              fontWeight: FontWeight.w800,
                              letterSpacing: 1.0,
                            ),
                          ),
                        ),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 7, vertical: 3),
                          decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.15),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: const Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Icon(Icons.shield_rounded, color: Colors.white, size: 13),
                              SizedBox(width: 3),
                              Text('Protected', style: TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.w600)),
                            ],
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),
                    const Text(
                      'Turn Your Smallholder Harvest Into Guaranteed Economic Value',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        height: 1.3,
                      ),
                    ),
                    const SizedBox(height: 16),
                    ElevatedButton.icon(
                      onPressed: () {
                        showDialog(
                          context: context,
                          builder: (_) => const AddListingDialog(),
                        );
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: VunothoColors.accent,
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 12),
                      ),
                      icon: const Icon(Icons.add_circle_outline_rounded, size: 20),
                      label: const Text('Log New Harvest Batch'),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 18),

              // Metrics Row
              Row(
                children: [
                  Expanded(
                    child: _buildMetricCard(
                      title: 'Total Logged Produce',
                      value: '${totalKg.toStringAsFixed(0)} KG',
                      subtitle: '${listings.length} Batches',
                      icon: Icons.scale_rounded,
                      color: VunothoColors.primary,
                      bgColor: VunothoColors.primarySurface,
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: _buildMetricCard(
                      title: 'Protected Value',
                      value: '\$${totalEstValue.toStringAsFixed(2)}',
                      subtitle: 'Floor Guaranteed',
                      icon: Icons.monetization_on_rounded,
                      color: VunothoColors.accent,
                      bgColor: VunothoColors.accentSurface,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 20),

              // District Filter Chips
              const Text(
                'Farming District Filter',
                style: TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.w700,
                  color: VunothoColors.textDark,
                ),
              ),
              const SizedBox(height: 8),
              SingleChildScrollView(
                scrollDirection: Axis.horizontal,
                child: Row(
                  children: [
                    _buildFilterChip(context, 'All', listingProvider),
                    ...SupabaseConfig.districts.map((d) => _buildFilterChip(context, d, listingProvider)),
                  ],
                ),
              ),
              const SizedBox(height: 18),

              // Listings Section Header
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    'Active Produce Listings (${listings.length})',
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.w800,
                      color: VunothoColors.textDark,
                    ),
                  ),
                  TextButton.icon(
                    onPressed: () => listingProvider.loadListings(),
                    icon: const Icon(Icons.refresh_rounded, size: 16, color: VunothoColors.primary),
                    label: const Text('Refresh', style: TextStyle(color: VunothoColors.primary, fontSize: 12)),
                  ),
                ],
              ),
              const SizedBox(height: 8),

              // Listings List
              if (listingProvider.isLoading)
                const Center(
                  child: Padding(
                    padding: EdgeInsets.all(32),
                    child: CircularProgressIndicator(color: VunothoColors.primary),
                  ),
                )
              else if (listings.isEmpty)
                Container(
                  width: double.infinity,
                  padding: const EdgeInsets.all(28),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: VunothoColors.lightBorder),
                  ),
                  child: Column(
                    children: [
                      Icon(Icons.inventory_2_outlined, size: 48, color: Colors.grey.shade400),
                      const SizedBox(height: 12),
                      const Text(
                        'No produce listings found in this district.',
                        style: TextStyle(fontWeight: FontWeight.bold, color: VunothoColors.textDark),
                      ),
                      const SizedBox(height: 6),
                      const Text(
                        'Tap "Log New Harvest Batch" to record produce.',
                        style: TextStyle(fontSize: 12, color: VunothoColors.textMuted),
                      ),
                    ],
                  ),
                )
              else
                ListView.separated(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  itemCount: listings.length,
                  separatorBuilder: (context, index) => const SizedBox(height: 12),
                  itemBuilder: (context, index) {
                    final item = listings[index];
                    final isSynced = item.syncStatus == 'Synced';

                    return Card(
                      child: Padding(
                        padding: const EdgeInsets.all(16),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Row(
                                  children: [
                                    Container(
                                      padding: const EdgeInsets.all(10),
                                      decoration: BoxDecoration(
                                        color: VunothoColors.primarySurface,
                                        borderRadius: BorderRadius.circular(12),
                                      ),
                                      child: const Icon(Icons.grass_rounded, color: VunothoColors.primary, size: 24),
                                    ),
                                    const SizedBox(width: 12),
                                    Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text(
                                          item.crop,
                                          style: const TextStyle(
                                            fontSize: 16,
                                            fontWeight: FontWeight.bold,
                                            color: VunothoColors.textDark,
                                          ),
                                        ),
                                        Text(
                                          item.farmerName,
                                          style: const TextStyle(fontSize: 12, color: VunothoColors.textMuted),
                                        ),
                                      ],
                                    ),
                                  ],
                                ),
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                  decoration: BoxDecoration(
                                    color: isSynced ? VunothoColors.primarySurface : const Color(0xFFFEF3C7),
                                    borderRadius: BorderRadius.circular(10),
                                  ),
                                  child: Text(
                                    item.syncStatus,
                                    style: TextStyle(
                                      fontSize: 11,
                                      fontWeight: FontWeight.w700,
                                      color: isSynced ? VunothoColors.primaryDark : const Color(0xFF92400E),
                                    ),
                                  ),
                                ),
                              ],
                            ),
                            const Divider(height: 24, color: VunothoColors.lightBorder),
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                _buildInfoPill(Icons.scale_rounded, '${item.quantityKg.toStringAsFixed(0)} KG'),
                                _buildInfoPill(Icons.verified_rounded, item.quality.split(' ').first),
                                _buildInfoPill(Icons.location_on_rounded, item.district),
                              ],
                            ),
                          ],
                        ),
                      ),
                    );
                  },
                ),
              const SizedBox(height: 32),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildMetricCard({
    required String title,
    required String value,
    required String subtitle,
    required IconData icon,
    required Color color,
    required Color bgColor,
  }) {
    return Container(
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: VunothoColors.lightBorder),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Expanded(
                child: Text(
                  title,
                  style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: VunothoColors.textMuted),
                  overflow: TextOverflow.ellipsis,
                ),
              ),
              Container(
                padding: const EdgeInsets.all(5),
                decoration: BoxDecoration(color: bgColor, borderRadius: BorderRadius.circular(8)),
                child: Icon(icon, color: color, size: 15),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Text(
            value,
            style: TextStyle(fontSize: 18, fontWeight: FontWeight.w800, color: color),
          ),
          const SizedBox(height: 2),
          Text(
            subtitle,
            style: const TextStyle(fontSize: 11, color: VunothoColors.textMuted),
          ),
        ],
      ),
    );
  }

  Widget _buildFilterChip(BuildContext context, String district, ListingProvider provider) {
    final isSelected = provider.selectedDistrict == district;
    return Padding(
      padding: const EdgeInsets.only(right: 8),
      child: ChoiceChip(
        label: Text(district),
        selected: isSelected,
        onSelected: (_) => provider.filterDistrict(district),
        selectedColor: VunothoColors.primary,
        backgroundColor: Colors.white,
        labelStyle: TextStyle(
          color: isSelected ? Colors.white : VunothoColors.textDark,
          fontWeight: FontWeight.w600,
          fontSize: 12,
        ),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(12),
          side: BorderSide(color: isSelected ? VunothoColors.primary : VunothoColors.lightBorder),
        ),
        showCheckmark: false,
      ),
    );
  }

  Widget _buildInfoPill(IconData icon, String text) {
    return Row(
      children: [
        Icon(icon, size: 14, color: VunothoColors.textMuted),
        const SizedBox(width: 4),
        Text(
          text,
          style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: VunothoColors.textDark),
        ),
      ],
    );
  }
}
