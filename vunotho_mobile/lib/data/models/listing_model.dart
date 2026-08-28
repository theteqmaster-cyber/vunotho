class ListingModel {
  final String id;
  final String farmerId;
  final String farmerName;
  final String crop;
  final double quantityKg;
  final String quality;
  final double lat;
  final double lng;
  final String district;
  final String syncStatus;
  final String status;
  final String createdAt;

  ListingModel({
    required this.id,
    required this.farmerId,
    required this.farmerName,
    required this.crop,
    required this.quantityKg,
    required this.quality,
    required this.lat,
    required this.lng,
    required this.district,
    this.syncStatus = 'Synced',
    this.status = 'Open',
    required this.createdAt,
  });

  factory ListingModel.fromJson(Map<String, dynamic> json) {
    return ListingModel(
      id: json['id']?.toString() ?? '',
      farmerId: json['farmer_id']?.toString() ?? '',
      farmerName: json['farmer_name']?.toString() ?? 'Smallholder Farmer',
      crop: json['crop']?.toString() ?? 'Produce',
      quantityKg: double.tryParse(json['quantity_kg']?.toString() ?? '0') ?? 0.0,
      quality: json['quality']?.toString() ?? 'Grade A (Premium)',
      lat: double.tryParse(json['lat']?.toString() ?? '-18.2167') ?? -18.2167,
      lng: double.tryParse(json['lng']?.toString() ?? '32.7500') ?? 32.7500,
      district: json['district']?.toString() ?? 'Nyanga',
      syncStatus: json['sync_status']?.toString() ?? 'Synced',
      status: json['status']?.toString() ?? 'Open',
      createdAt: json['created_at']?.toString() ?? DateTime.now().toIso8601String(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'farmer_id': farmerId,
      'farmer_name': farmerName,
      'crop': crop,
      'quantity_kg': quantityKg,
      'quality': quality,
      'lat': lat,
      'lng': lng,
      'district': district,
      'sync_status': syncStatus,
      'status': status,
      'created_at': createdAt,
    };
  }

  ListingModel copyWith({
    String? id,
    String? farmerId,
    String? farmerName,
    String? crop,
    double? quantityKg,
    String? quality,
    double? lat,
    double? lng,
    String? district,
    String? syncStatus,
    String? status,
    String? createdAt,
  }) {
    return ListingModel(
      id: id ?? this.id,
      farmerId: farmerId ?? this.farmerId,
      farmerName: farmerName ?? this.farmerName,
      crop: crop ?? this.crop,
      quantityKg: quantityKg ?? this.quantityKg,
      quality: quality ?? this.quality,
      lat: lat ?? this.lat,
      lng: lng ?? this.lng,
      district: district ?? this.district,
      syncStatus: syncStatus ?? this.syncStatus,
      status: status ?? this.status,
      createdAt: createdAt ?? this.createdAt,
    );
  }
}
