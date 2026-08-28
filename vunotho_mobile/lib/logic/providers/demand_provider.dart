import 'package:flutter/material.dart';
import 'package:uuid/uuid.dart';
import '../../data/models/demand_model.dart';
import '../../data/services/vunotho_repository.dart';

class DemandProvider extends ChangeNotifier {
  final VunothoRepository _repository;
  List<DemandModel> _demands = [];
  bool _isLoading = false;

  List<DemandModel> get demands => _demands;
  bool get isLoading => _isLoading;

  DemandProvider(this._repository) {
    loadDemands();
  }

  Future<void> loadDemands() async {
    _isLoading = true;
    notifyListeners();

    _demands = await _repository.getDemands();
    _isLoading = false;
    notifyListeners();
  }

  Future<bool> addDemand({
    required String buyerName,
    required String crop,
    required double targetQuantityKg,
    required double offeredPricePerKg,
    required String qualityRequired,
    required String deliveryHub,
    required String deadline,
  }) async {
    final newId = 'DEM-${DateTime.now().millisecondsSinceEpoch}-${const Uuid().v4().substring(0, 4).toUpperCase()}';
    final newDemand = DemandModel(
      id: newId,
      buyerId: 'BUY-${DateTime.now().millisecondsSinceEpoch}',
      buyerName: buyerName.isEmpty ? 'Commercial Off-taker' : buyerName,
      crop: crop,
      targetQuantityKg: targetQuantityKg,
      offeredPricePerKg: offeredPricePerKg,
      qualityRequired: qualityRequired,
      deliveryHub: deliveryHub,
      deadline: deadline,
      status: 'Active',
      createdAt: DateTime.now().toIso8601String(),
    );

    final onlineSuccess = await _repository.createDemand(newDemand);
    await loadDemands();
    return onlineSuccess;
  }
}
