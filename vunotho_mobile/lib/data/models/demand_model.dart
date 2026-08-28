class DemandModel {
  final String id;
  final String buyerId;
  final String buyerName;
  final String crop;
  final double targetQuantityKg;
  final double offeredPricePerKg;
  final String qualityRequired;
  final String deliveryHub;
  final String deadline;
  final String status;
  final String createdAt;

  DemandModel({
    required this.id,
    required this.buyerId,
    required this.buyerName,
    required this.crop,
    required this.targetQuantityKg,
    required this.offeredPricePerKg,
    required this.qualityRequired,
    required this.deliveryHub,
    required this.deadline,
    this.status = 'Active',
    required this.createdAt,
  });

  factory DemandModel.fromJson(Map<String, dynamic> json) {
    return DemandModel(
      id: json['id']?.toString() ?? '',
      buyerId: json['buyer_id']?.toString() ?? '',
      buyerName: json['buyer_name']?.toString() ?? 'Commercial Buyer',
      crop: json['crop']?.toString() ?? 'Produce',
      targetQuantityKg: double.tryParse(json['target_quantity_kg']?.toString() ?? '0') ?? 0.0,
      offeredPricePerKg: double.tryParse(json['offered_price_per_kg']?.toString() ?? '0') ?? 0.0,
      qualityRequired: json['quality_required']?.toString() ?? 'Grade A (Premium)',
      deliveryHub: json['delivery_hub']?.toString() ?? 'Harare Central Hub',
      deadline: json['deadline']?.toString() ?? '',
      status: json['status']?.toString() ?? 'Active',
      createdAt: json['created_at']?.toString() ?? DateTime.now().toIso8601String(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'buyer_id': buyerId,
      'buyer_name': buyerName,
      'crop': crop,
      'target_quantity_kg': targetQuantityKg,
      'offered_price_per_kg': offeredPricePerKg,
      'quality_required': qualityRequired,
      'delivery_hub': deliveryHub,
      'deadline': deadline,
      'status': status,
      'created_at': createdAt,
    };
  }
}
