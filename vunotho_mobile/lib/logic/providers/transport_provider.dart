import 'package:flutter/material.dart';
import '../../data/models/manifest_model.dart';
import '../../data/services/vunotho_repository.dart';

class TransportProvider extends ChangeNotifier {
  final VunothoRepository _repository;
  List<ManifestModel> _manifests = [];
  bool _isLoading = false;

  List<ManifestModel> get manifests => _manifests;
  bool get isLoading => _isLoading;

  TransportProvider(this._repository) {
    loadManifests();
  }

  Future<void> loadManifests() async {
    _isLoading = true;
    notifyListeners();

    _manifests = await _repository.getManifests();
    _isLoading = false;
    notifyListeners();
  }

  double calculateTotalLogisticsCapacityKg() {
    return _manifests.fold(0.0, (acc, m) => acc + m.totalWeightKg);
  }

  double calculateTotalLogisticsRevenue() {
    return _manifests.fold(0.0, (acc, m) => acc + m.estPayout);
  }
}
