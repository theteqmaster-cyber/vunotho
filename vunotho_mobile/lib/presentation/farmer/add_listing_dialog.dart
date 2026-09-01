import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
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
  String _selectedCrop = 'Butternut Squash';
  double _quantityKg = 500.0;
  String _selectedQuality = 'Grade A (Export / Retail)';
  String _selectedDistrict = 'Nyanga';
  bool _isSubmitting = false;

  final List<String> _crops = [
    'Butternut Squash',
    'Sugar Beans',
    'Tomatoes',
    'Table Potatoes',
    'Cabbage & Leafy Greens',
    'Maize Grain',
  ];

  final List<String> _qualityGrades = [
    'Grade A (Export / Retail)',
    'Grade B (Processing / Puree)',
    'Grade C (Local Wholesale)',
    'Off-Spec (Animal Feed / Compost)',
  ];

  final List<String> _districts = [
    'Nyanga',
    'Mutasa',
    'Chipinge',
    'Gwanda',
    'Goromonzi',
    'Marondera',
  ];

  double _calculateEstimatedValue() {
    double baseRate = 0.50;
    if (_selectedCrop.contains('Butternut')) baseRate = 0.85;
    if (_selectedCrop.contains('Beans')) baseRate = 1.20;
    if (_selectedCrop.contains('Tomatoes')) baseRate = 0.60;
    if (_selectedCrop.contains('Potatoes')) baseRate = 0.70;

    double multiplier = 1.0;
    if (_selectedQuality.contains('Grade A')) multiplier = 1.0;
    if (_selectedQuality.contains('Grade B')) multiplier = 0.75;
    if (_selectedQuality.contains('Grade C')) multiplier = 0.55;
    if (_selectedQuality.contains('Off-Spec')) multiplier = 0.35;

    return _quantityKg * baseRate * multiplier;
  }

  void _submitForm() async {
    if (_formKey.currentState?.validate() ?? false) {
      _formKey.currentState?.save();
      setState(() => _isSubmitting = true);

      final authProvider = context.read<AuthProvider>();
      final listingProvider = context.read<ListingProvider>();

      await listingProvider.addListing(
        farmerName: authProvider.user?.name ?? 'Simba Mukamuri',
        crop: _selectedCrop,
        quantityKg: _quantityKg,
        quality: _selectedQuality,
        district: _selectedDistrict,
      );
      if (!mounted) return;

      setState(() => _isSubmitting = false);
      Navigator.of(context).pop();

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('✓ $_selectedCrop (${_quantityKg.toStringAsFixed(0)} kg) registered successfully!'),
          backgroundColor: VunothoColors.primaryDark,
          behavior: SnackBarBehavior.floating,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final estValue = _calculateEstimatedValue();

    return Dialog(
      backgroundColor: Colors.white,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
      insetPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 24),
      child: ConstrainedBox(
        constraints: const BoxConstraints(maxWidth: 440),
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(),
          padding: const EdgeInsets.all(22),
          child: Form(
            key: _formKey,
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                // Header
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: [
                        Container(
                          width: 36,
                          height: 36,
                          decoration: BoxDecoration(
                            color: const Color(0xFFE8F5E9),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: const Center(
                            child: Icon(Icons.eco_rounded, color: Color(0xFF1B5E20), size: 20),
                          ),
                        ),
                        const SizedBox(width: 10),
                        Text(
                          'Log Harvest Batch',
                          style: GoogleFonts.plusJakartaSans(
                            fontSize: 17,
                            fontWeight: FontWeight.w800,
                            color: VunothoColors.textDark,
                          ),
                        ),
                      ],
                    ),
                    IconButton(
                      icon: const Icon(Icons.close_rounded, size: 20, color: VunothoColors.textMuted),
                      onPressed: () => Navigator.of(context).pop(),
                      padding: EdgeInsets.zero,
                      constraints: const BoxConstraints(),
                    ),
                  ],
                ),
                const SizedBox(height: 18),

                // 1. Select Produce Crop
                Text(
                  'Produce Crop',
                  style: GoogleFonts.plusJakartaSans(fontSize: 12, fontWeight: FontWeight.w700, color: VunothoColors.textBody),
                ),
                const SizedBox(height: 6),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 2),
                  decoration: BoxDecoration(
                    color: VunothoColors.inputBg,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: VunothoColors.cardBorder),
                  ),
                  child: DropdownButtonHideUnderline(
                    child: DropdownButton<String>(
                      value: _selectedCrop,
                      isExpanded: true,
                      icon: const Icon(Icons.keyboard_arrow_down_rounded, color: VunothoColors.textMuted),
                      items: _crops.map((crop) {
                        return DropdownMenuItem(
                          value: crop,
                          child: Text(crop, style: GoogleFonts.plusJakartaSans(fontSize: 13.5, fontWeight: FontWeight.w600)),
                        );
                      }).toList(),
                      onChanged: (val) {
                        if (val != null) setState(() => _selectedCrop = val);
                      },
                    ),
                  ),
                ),
                const SizedBox(height: 14),

                // 2. Quantity Harvested (KG)
                Text(
                  'Quantity Harvested (KG)',
                  style: GoogleFonts.plusJakartaSans(fontSize: 12, fontWeight: FontWeight.w700, color: VunothoColors.textBody),
                ),
                const SizedBox(height: 6),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 14),
                  decoration: BoxDecoration(
                    color: VunothoColors.inputBg,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: VunothoColors.cardBorder),
                  ),
                  child: TextFormField(
                    initialValue: _quantityKg.toStringAsFixed(0),
                    keyboardType: TextInputType.number,
                    style: GoogleFonts.jetBrainsMono(fontSize: 14, fontWeight: FontWeight.w700),
                    decoration: InputDecoration(
                      border: InputBorder.none,
                      suffixText: 'KG',
                      suffixStyle: GoogleFonts.jetBrainsMono(fontSize: 12, fontWeight: FontWeight.bold, color: VunothoColors.textMuted),
                    ),
                    onChanged: (val) {
                      final parsed = double.tryParse(val);
                      if (parsed != null && parsed > 0) {
                        setState(() => _quantityKg = parsed);
                      }
                    },
                    validator: (val) {
                      if (val == null || val.isEmpty) return 'Enter quantity in KG';
                      final parsed = double.tryParse(val);
                      if (parsed == null || parsed <= 0) return 'Enter a valid positive number';
                      return null;
                    },
                  ),
                ),
                const SizedBox(height: 14),

                // 3. Quality Grade Classification
                Text(
                  'Quality Grade Classification',
                  style: GoogleFonts.plusJakartaSans(fontSize: 12, fontWeight: FontWeight.w700, color: VunothoColors.textBody),
                ),
                const SizedBox(height: 6),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 2),
                  decoration: BoxDecoration(
                    color: VunothoColors.inputBg,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: VunothoColors.cardBorder),
                  ),
                  child: DropdownButtonHideUnderline(
                    child: DropdownButton<String>(
                      value: _selectedQuality,
                      isExpanded: true,
                      icon: const Icon(Icons.keyboard_arrow_down_rounded, color: VunothoColors.textMuted),
                      items: _qualityGrades.map((grade) {
                        return DropdownMenuItem(
                          value: grade,
                          child: Text(grade, style: GoogleFonts.plusJakartaSans(fontSize: 13, fontWeight: FontWeight.w600)),
                        );
                      }).toList(),
                      onChanged: (val) {
                        if (val != null) setState(() => _selectedQuality = val);
                      },
                    ),
                  ),
                ),
                const SizedBox(height: 14),

                // 4. Farming District (Cluster)
                Text(
                  'Farming District (Cluster)',
                  style: GoogleFonts.plusJakartaSans(fontSize: 12, fontWeight: FontWeight.w700, color: VunothoColors.textBody),
                ),
                const SizedBox(height: 6),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 2),
                  decoration: BoxDecoration(
                    color: VunothoColors.inputBg,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: VunothoColors.cardBorder),
                  ),
                  child: DropdownButtonHideUnderline(
                    child: DropdownButton<String>(
                      value: _selectedDistrict,
                      isExpanded: true,
                      icon: const Icon(Icons.keyboard_arrow_down_rounded, color: VunothoColors.textMuted),
                      items: _districts.map((d) {
                        return DropdownMenuItem(
                          value: d,
                          child: Text(d, style: GoogleFonts.plusJakartaSans(fontSize: 13, fontWeight: FontWeight.w600)),
                        );
                      }).toList(),
                      onChanged: (val) {
                        if (val != null) setState(() => _selectedDistrict = val);
                      },
                    ),
                  ),
                ),
                const SizedBox(height: 18),

                // Estimated Value Card
                Container(
                  padding: const EdgeInsets.all(14),
                  decoration: BoxDecoration(
                    color: const Color(0xFFFFFBEB),
                    borderRadius: BorderRadius.circular(18),
                    border: Border.all(color: const Color(0xFFFDE68A)),
                  ),
                  child: Row(
                    children: [
                      Container(
                        width: 36,
                        height: 36,
                        decoration: BoxDecoration(
                          color: const Color(0xFFF59E0B).withValues(alpha: 0.15),
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: const Center(
                          child: Icon(Icons.attach_money_rounded, color: Color(0xFFD97706), size: 22),
                        ),
                      ),
                      const SizedBox(width: 12),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Estimated Value Floor (USD)',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 11,
                              fontWeight: FontWeight.w600,
                              color: const Color(0xFF92400E),
                            ),
                          ),
                          Text(
                            '\$${estValue.toStringAsFixed(2)}',
                            style: GoogleFonts.jetBrainsMono(
                              fontSize: 18,
                              fontWeight: FontWeight.w900,
                              color: const Color(0xFF78350F),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 18),

                // Submit Button
                SizedBox(
                  height: 48,
                  child: ElevatedButton(
                    onPressed: _isSubmitting ? null : _submitForm,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: VunothoColors.primaryDark,
                      foregroundColor: Colors.white,
                      elevation: 0,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                    ),
                    child: _isSubmitting
                        ? const SizedBox(
                            width: 20,
                            height: 20,
                            child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5),
                          )
                        : Text(
                            'Publish Produce Listing',
                            style: GoogleFonts.plusJakartaSans(
                              fontSize: 14,
                              fontWeight: FontWeight.w800,
                            ),
                          ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
