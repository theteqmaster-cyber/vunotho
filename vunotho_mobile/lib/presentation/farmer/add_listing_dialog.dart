import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/constants/supabase_config.dart';
import '../../core/theme/vunotho_theme.dart';
import '../../logic/providers/auth_provider.dart';
import '../../logic/providers/listing_provider.dart';

class AddListingDialog extends StatefulWidget {
  const AddListingDialog({super.key});

  @override
  State<AddListingDialog> createState() => _AddListingDialogState();
}

class _AddListingDialogState extends State<AddListingDialog> {
  final _formKey = GlobalKey<FormState>();
  String _selectedCrop = SupabaseConfig.crops.first;
  String _selectedGrade = SupabaseConfig.qualityGrades.first;
  String _selectedDistrict = SupabaseConfig.districts.first;
  final TextEditingController _kgController = TextEditingController(text: '500');
  bool _isSubmitting = false;

  double get _currentKg => double.tryParse(_kgController.text) ?? 0.0;

  @override
  void dispose() {
    _kgController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final listingProvider = context.read<ListingProvider>();
    final authProvider = context.read<AuthProvider>();
    final estValue = listingProvider.calculateEstimatedValue(_selectedGrade, _currentKg);

    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      backgroundColor: Colors.white,
      insetPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 24),
      child: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(
                          color: VunothoColors.primarySurface,
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: const Icon(Icons.eco_rounded, color: VunothoColors.primary, size: 22),
                      ),
                      const SizedBox(width: 10),
                      const Text(
                        'Log Harvest Batch',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                          color: VunothoColors.textDark,
                        ),
                      ),
                    ],
                  ),
                  IconButton(
                    icon: const Icon(Icons.close_rounded),
                    onPressed: () => Navigator.of(context).pop(),
                  ),
                ],
              ),
              const SizedBox(height: 16),

              // Crop Dropdown
              const Text('Produce Crop', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
              const SizedBox(height: 6),
              DropdownButtonFormField<String>(
                initialValue: _selectedCrop,
                items: SupabaseConfig.crops.map((c) => DropdownMenuItem(value: c, child: Text(c))).toList(),
                onChanged: (v) => setState(() => _selectedCrop = v!),
                decoration: const InputDecoration(prefixIcon: Icon(Icons.agriculture_rounded)),
              ),
              const SizedBox(height: 14),

              // Quantity in KG
              const Text('Quantity Harvested (KG)', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
              const SizedBox(height: 6),
              TextFormField(
                controller: _kgController,
                keyboardType: TextInputType.number,
                decoration: const InputDecoration(
                  prefixIcon: Icon(Icons.scale_rounded),
                  suffixText: 'KG',
                ),
                onChanged: (_) => setState(() {}),
                validator: (val) {
                  if (val == null || val.isEmpty || double.tryParse(val) == null || double.parse(val) <= 0) {
                    return 'Please enter a valid harvest weight';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 14),

              // Quality Grade
              const Text('Quality Grade Classification', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
              const SizedBox(height: 6),
              DropdownButtonFormField<String>(
                initialValue: _selectedGrade,
                items: SupabaseConfig.qualityGrades.map((g) => DropdownMenuItem(value: g, child: Text(g))).toList(),
                onChanged: (v) => setState(() => _selectedGrade = v!),
                decoration: const InputDecoration(prefixIcon: Icon(Icons.verified_rounded)),
              ),
              const SizedBox(height: 14),

              // Farming District
              const Text('Farming District (Cluster)', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
              const SizedBox(height: 6),
              DropdownButtonFormField<String>(
                initialValue: _selectedDistrict,
                items: SupabaseConfig.districts.map((d) => DropdownMenuItem(value: d, child: Text(d))).toList(),
                onChanged: (v) => setState(() => _selectedDistrict = v!),
                decoration: const InputDecoration(prefixIcon: Icon(Icons.location_on_rounded)),
              ),
              const SizedBox(height: 18),

              // Dynamic Floor Price Estimate Card
              Container(
                padding: const EdgeInsets.all(14),
                decoration: BoxDecoration(
                  color: VunothoColors.accentSurface,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: const Color(0xFFFDE68A)),
                ),
                child: Row(
                  children: [
                    const Icon(Icons.monetization_on_rounded, color: VunothoColors.accent, size: 28),
                    const SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          const Text(
                            'Estimated Value Floor (USD)',
                            style: TextStyle(fontSize: 12, color: Color(0xFF92400E), fontWeight: FontWeight.w600),
                          ),
                          Text(
                            '\$${estValue.toStringAsFixed(2)}',
                            style: const TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.w800,
                              color: Color(0xFF78350F),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 20),

              // Submit Button
              ElevatedButton(
                onPressed: _isSubmitting
                    ? null
                    : () async {
                        if (!_formKey.currentState!.validate()) return;
                        setState(() => _isSubmitting = true);

                        final farmerName = authProvider.user?.name ?? 'Smallholder Farmer';
                        final success = await listingProvider.addListing(
                          farmerName: farmerName,
                          crop: _selectedCrop,
                          quantityKg: _currentKg,
                          quality: _selectedGrade,
                          district: _selectedDistrict,
                        );

                        if (context.mounted) {
                          Navigator.of(context).pop();
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(
                              backgroundColor: VunothoColors.primaryDark,
                              content: Text(
                                success
                                    ? '✅ Listing synced to Vunotho marketplace!'
                                    : '💾 Harvest saved offline! Will auto-sync when online.',
                              ),
                            ),
                          );
                        }
                      },
                child: _isSubmitting
                    ? const SizedBox(
                        height: 20,
                        width: 20,
                        child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white),
                      )
                    : const Text('Publish Produce Listing'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
