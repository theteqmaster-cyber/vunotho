import 'package:flutter/material.dart';
import '../../core/services/offline_storage_service.dart';
import '../../data/models/user_profile_model.dart';

class AuthProvider extends ChangeNotifier {
  UserProfileModel? _user;
  bool _isLoading = true;

  UserProfileModel? get user => _user;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _user != null;

  // Active Role ('farmer', 'buyer', 'haulier', 'admin')
  String get currentRole => _user?.role ?? 'farmer';

  AuthProvider() {
    _init();
  }

  Future<void> _init() async {
    _user = await OfflineStorageService.getUser();
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

  Future<void> login(String emailOrPhone, String role) async {
    _user = UserProfileModel(
      id: 'USR-${DateTime.now().millisecondsSinceEpoch}',
      name: emailOrPhone.split('@').first,
      emailOrPhone: emailOrPhone,
      role: role,
      district: 'Nyanga',
      createdAt: DateTime.now().toIso8601String(),
    );
    await OfflineStorageService.saveUser(_user!);
    notifyListeners();
  }

  Future<void> logout() async {
    await OfflineStorageService.clearUser();
    _user = null;
    notifyListeners();
  }
}
