class UserProfileModel {
  final String id;
  final String name;
  final String organisation;
  final String emailOrPhone;
  final String role; // 'farmer', 'buyer', 'haulier', 'admin'
  final String province;
  final String district;
  final String mainProduce;
  final String vehicleType;
  final String kycStatus;
  final String createdAt;

  UserProfileModel({
    required this.id,
    required this.name,
    this.organisation = '',
    required this.emailOrPhone,
    required this.role,
    this.province = 'Manicaland',
    this.district = 'Nyanga',
    this.mainProduce = '',
    this.vehicleType = '',
    this.kycStatus = 'Verified (Level 1)',
    required this.createdAt,
  });

  factory UserProfileModel.fromJson(Map<String, dynamic> json) {
    return UserProfileModel(
      id: json['id']?.toString() ?? '',
      name: json['name']?.toString() ?? 'User',
      organisation: json['organisation']?.toString() ?? '',
      emailOrPhone: json['email_or_phone']?.toString() ?? '',
      role: json['role']?.toString() ?? 'farmer',
      province: json['province']?.toString() ?? 'Manicaland',
      district: json['district']?.toString() ?? 'Nyanga',
      mainProduce: json['main_produce']?.toString() ?? '',
      vehicleType: json['vehicle_type']?.toString() ?? '',
      kycStatus: json['kyc_status']?.toString() ?? 'Verified (Level 1)',
      createdAt: json['created_at']?.toString() ?? DateTime.now().toIso8601String(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'organisation': organisation,
      'email_or_phone': emailOrPhone,
      'role': role,
      'province': province,
      'district': district,
      'main_produce': mainProduce,
      'vehicle_type': vehicleType,
      'kyc_status': kycStatus,
      'created_at': createdAt,
    };
  }
}
