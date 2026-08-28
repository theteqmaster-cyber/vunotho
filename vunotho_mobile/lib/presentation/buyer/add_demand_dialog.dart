import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../core/constants/supabase_config.dart';
import '../../core/theme/vunotho_theme.dart';
import '../../logic/providers/auth_provider.dart';
import '../../logic/providers/demand_provider.dart';

class AddDemandDialog extends StatefulWidget {
  const AddDemandDialog({super.key});

  @override
  State<AddDemandDialog> createState() => _AddDemandDialogState();
}

class _AddDemandDialogState extends State<AddDemandDialog> {
  final _formKey = GlobalKey<FormState>();
  String _selectedCrop = SupabaseConfig.crops.first;
  String _selectedGrade = SupabaseConfig.qualityGrades.first;
  final TextEditingController _kgController = TextEditingController(text: '3000');
  final TextEditingController _priceController = TextEditingController(text: '0.75');
  final TextEditingController _hubController = TextEditingController(text: 'Harare Fresh Distribution Hub');
  bool _isSubmitting = false;

  @override
  void dispose() {
    _kgController.dispose();
    _priceController.dispose();
    _hubController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final demandProvider = context.read<DemandProvider>();
    final authProvider = context.read<AuthProvider>();

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
                          color: VunothoColors.logisticsSurface,
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: const Icon(Icons.shopping_cart_rounded, color: VunothoColors.logistics, size: 22),
                      ),
                      const SizedBox(width: 10),
                      const Text(
                        'Post Buyer Demand',
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

              // Crop
              const Text('Required Crop Produce', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
              const SizedBox(height: 6),
              DropdownButtonFormField<String>(
                initialValue: _selectedCrop,
                items: SupabaseConfig.crops.map((c) => DropdownMenuItem(value: c, child: Text(c))).toList(),
                onChanged: (v) => setState(() => _selectedCrop = v!),
                decoration: const InputDecoration(prefixIcon: Icon(Icons.agriculture_rounded)),
              ),
              const SizedBox(height: 14),

              // Target Quantity (KG)
              const Text('Target Order Quantity (KG)', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
              const SizedBox(height: 6),
              TextFormField(
                controller: _kgController,
                keyboardType: TextInputType.number,
                decoration: const InputDecoration(
                  prefixIcon: Icon(Icons.scale_rounded),
                  suffixText: 'KG',
                ),
                validator: (val) => (val == null || val.isEmpty) ? 'Please enter target quantity' : null,
              ),
              const SizedBox(height: 14),

              // Offered Price Per KG
              const Text('Offered Price Per KG (USD)', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
              const SizedBox(height: 6),
              TextFormField(
                controller: _priceController,
                keyboardType: const TextInputType.numberWithOptions(decimal: true),
                decoration: const InputDecoration(
                  prefixIcon: Icon(Icons.attach_money_rounded),
                  prefixText: '\$ ',
                ),
                validator: (val) => (val == null || val.isEmpty) ? 'Please enter offered price' : null,
              ),
              const SizedBox(height: 14),

              // Quality Grade
              const Text('Minimum Quality Required', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
              const SizedBox(height: 6),
              DropdownButtonFormField<String>(
                initialValue: _selectedGrade,
                items: SupabaseConfig.qualityGrades.map((g) => DropdownMenuItem(value: g, child: Text(g))).toList(),
                onChanged: (v) => setState(() => _selectedGrade = v!),
                decoration: const InputDecoration(prefixIcon: Icon(Icons.verified_rounded)),
              ),
              const SizedBox(height: 14),

              // Delivery Hub
              const Text('Delivery Hub Depot', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
              const SizedBox(height: 6),
              TextFormField(
                controller: _hubController,
                decoration: const InputDecoration(prefixIcon: Icon(Icons.storefront_rounded)),
                validator: (val) => (val == null || val.isEmpty) ? 'Please enter delivery hub' : null,
              ),
              const SizedBox(height: 20),

              // Submit Button
              ElevatedButton(
                onPressed: _isSubmitting
                    ? null
                    : () async {
                        if (!_formKey.currentState!.validate()) return;
                        setState(() => _isSubmitting = true);

                        final buyerName = authProvider.user?.name ?? 'Commercial Off-taker';
                        final success = await demandProvider.addDemand(
                          buyerName: buyerName,
                          crop: _selectedCrop,
                          targetQuantityKg: double.tryParse(_kgController.text) ?? 1000,
                          offeredPricePerKg: double.tryParse(_priceController.text) ?? 0.5,
                          qualityRequired: _selectedGrade,
                          deliveryHub: _hubController.text,
                          deadline: DateTime.now().add(const Duration(days: 14)).toIso8601String().split('T').first,
                        );

                        if (context.mounted) {
                          Navigator.of(context).pop();
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(
                              backgroundColor: VunothoColors.logistics,
                              content: Text(
                                success
                                    ? '✅ Commercial Demand published to network!'
                                    : '💾 Demand saved offline! Will sync when connected.',
                              ),
                            ),
                          );
                        }
                      },
                style: ElevatedButton.styleFrom(backgroundColor: VunothoColors.logistics),
                child: _isSubmitting
                    ? const SizedBox(
                        height: 20,
                        width: 20,
                        child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white),
                      )
                    : const Text('Post Commercial Demand'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
