import 'package:flutter/material.dart';
import 'package:uuid/uuid.dart';
import '../../data/models/listing_model.dart';
import '../../data/services/vunotho_repository.dart';

class ListingProvider extends ChangeNotifier {
  final VunothoRepository _repository;
  List<ListingModel> _listings = [];
  bool _isLoading = false;
  String _selectedDistrict = 'All';

  List<ListingModel> get listings {
    if (_selectedDistrict == 'All') return _listings;
    return _listings.where((l) => l.district == _selectedDistrict).toList();
  }

  bool get isLoading => _isLoading;
  String get selectedDistrict => _selectedDistrict;

  ListingProvider(this._repository) {
    loadListings();
  }

  void filterDistrict(String district) {
    _selectedDistrict = district;
    notifyListeners();
  }

  Future<void> loadListings() async {
    _isLoading = true;
    notifyListeners();

    _listings = await _repository.getListings();
    _isLoading = false;
    notifyListeners();
  }

  Future<bool> addListing({
    required String farmerName,
    required String crop,
    required double quantityKg,
    required String quality,
    required String district,
  }) async {
    final newId = 'LIST-${DateTime.now().millisecondsSinceEpoch}-${const Uuid().v4().substring(0, 4).toUpperCase()}';
    final newListing = ListingModel(
      id: newId,
      farmerId: 'FAR-${DateTime.now().millisecondsSinceEpoch}',
      farmerName: farmerName.isEmpty ? 'Smallholder Farmer' : farmerName,
      crop: crop,
      quantityKg: quantityKg,
      quality: quality,
      lat: -18.2167,
      lng: 32.7500,
      district: district,
      syncStatus: 'Synced',
      status: 'Open',
      createdAt: DateTime.now().toIso8601String(),
    );

    final onlineSuccess = await _repository.createListing(newListing);
    await loadListings();
    return onlineSuccess;
  }

  // Price estimate based on grade & weight
  double calculateEstimatedValue(String quality, double kg) {
    double pricePerKg = 0.70;
    if (quality.contains('Grade A')) {
      pricePerKg = 0.85;
    } else if (quality.contains('Grade B')) {
      pricePerKg = 0.55;
    } else {
      pricePerKg = 0.25;
    }
    return pricePerKg * kg;
  }
}
