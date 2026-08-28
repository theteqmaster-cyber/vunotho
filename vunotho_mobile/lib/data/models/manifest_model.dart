class ManifestModel {
  final String id;
  final String clusterId;
  final String transporterId;
  final String crop;
  final String district;
  final double totalWeightKg;
  final int stopsCount;
  final double estPayout;
  final String status;
  final String createdAt;

  ManifestModel({
    required this.id,
    required this.clusterId,
    required this.transporterId,
    required this.crop,
    required this.district,
    required this.totalWeightKg,
    required this.stopsCount,
    required this.estPayout,
    this.status = 'Pending Dispatch',
    required this.createdAt,
  });

  factory ManifestModel.fromJson(Map<String, dynamic> json) {
    return ManifestModel(
      id: json['id']?.toString() ?? '',
      clusterId: json['cluster_id']?.toString() ?? '',
      transporterId: json['transporter_id']?.toString() ?? '',
      crop: json['crop']?.toString() ?? 'Produce',
      district: json['district']?.toString() ?? 'Nyanga',
      totalWeightKg: double.tryParse(json['total_weight_kg']?.toString() ?? '0') ?? 0.0,
      stopsCount: int.tryParse(json['stops_count']?.toString() ?? '1') ?? 1,
      estPayout: double.tryParse(json['est_payout']?.toString() ?? '0') ?? 0.0,
      status: json['status']?.toString() ?? 'Pending Dispatch',
      createdAt: json['created_at']?.toString() ?? DateTime.now().toIso8601String(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'cluster_id': clusterId,
      'transporter_id': transporterId,
      'crop': crop,
      'district': district,
      'total_weight_kg': totalWeightKg,
      'stops_count': stopsCount,
      'est_payout': estPayout,
      'status': status,
      'created_at': createdAt,
    };
  }
}
