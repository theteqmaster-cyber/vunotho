import 'package:flutter/material.dart';
import '../../core/services/offline_storage_service.dart';
import '../../data/models/user_profile_model.dart';

class AuthProvider extends ChangeNotifier {
  UserProfileModel? _user;
  bool _isLoading = false;
  String? _errorMessage;

  UserProfileModel? get user => _user;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _user != null;
  String? get errorMessage => _errorMessage;

  // Active Role ('farmer', 'buyer', 'haulier', 'admin')
  String get currentRole => _user?.role ?? 'farmer';

  AuthProvider() {
    _init();
  }

  Future<void> _init() async {
    // Start unauthenticated so users experience the landing / onboarding flow
    _user = null;
    _isLoading = false;
    notifyListeners();
  }

  Future<void> switchRole(String newRole) async {
    if (_user != null) {
      _user = UserProfileModel(
        id: _user!.id,
        name: _user!.name,
        emailOrPhone: _user!.emailOrPhone,
        role: newRole,
        district: _user!.district,
        mainProduce: _user!.mainProduce,
        vehicleType: _user!.vehicleType,
        kycStatus: _user!.kycStatus,
        createdAt: _user!.createdAt,
      );
      await OfflineStorageService.saveUser(_user!);
      notifyListeners();
    }
  }

  Future<bool> login(
    String emailOrPhone,
    String password, {
    String role = 'farmer',
    String? name,
    String? district,
  }) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    await Future.delayed(const Duration(milliseconds: 600)); // Smooth UX transition

    final resolvedName = name ?? (emailOrPhone.contains('@') ? emailOrPhone.split('@').first : 'Simba Mukamuri');
    final resolvedDistrict = district ?? 'Nyanga';

    _user = UserProfileModel(
      id: 'USR-${DateTime.now().millisecondsSinceEpoch}',
      name: resolvedName.isEmpty ? 'Simba Mukamuri' : resolvedName,
      emailOrPhone: emailOrPhone,
      role: role,
      district: resolvedDistrict,
      kycStatus: 'Verified Level 1',
      createdAt: DateTime.now().toIso8601String(),
    );

    await OfflineStorageService.saveUser(_user!);
    _isLoading = false;
    notifyListeners();
    return true;
  }

  Future<void> logout() async {
    _user = null;
    await OfflineStorageService.clearUser();
    notifyListeners();
  }
}
