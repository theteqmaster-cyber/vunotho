import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../../data/models/listing_model.dart';
import '../../data/models/demand_model.dart';
import '../../data/models/manifest_model.dart';
import '../../data/models/user_profile_model.dart';

class OfflineStorageService {
  static const String _listingsKey = 'vunotho_cached_listings';
  static const String _demandsKey = 'vunotho_cached_demands';
  static const String _manifestsKey = 'vunotho_cached_manifests';
  static const String _userKey = 'vunotho_active_user';
  static const String _syncQueueKey = 'vunotho_sync_queue';

  // Seed default sample listings if storage is empty
  static final List<ListingModel> _defaultSeedListings = [
    ListingModel(
      id: 'LIST-NYA-001',
      farmerId: 'FAR-01',
      farmerName: 'Simba Mukamuri',
      crop: 'Butternut Squash',
      quantityKg: 1450,
      quality: 'Grade A (Premium)',
      lat: -18.2167,
      lng: 32.7500,
      district: 'Nyanga',
      syncStatus: 'Synced',
      status: 'Open',
      createdAt: DateTime.now().subtract(const Duration(hours: 3)).toIso8601String(),
    ),
    ListingModel(
      id: 'LIST-MUT-002',
      farmerId: 'FAR-02',
      farmerName: 'Tariro Chitauro',
      crop: 'Sugar Beans',
      quantityKg: 850,
      quality: 'Grade A (Export / Retail)',
      lat: -18.6000,
      lng: 32.6500,
      district: 'Mutasa',
      syncStatus: 'Synced',
      status: 'Open',
      createdAt: DateTime.now().subtract(const Duration(hours: 6)).toIso8601String(),
    ),
    ListingModel(
      id: 'LIST-CHP-003',
      farmerId: 'FAR-03',
      farmerName: 'Farai Dube',
      crop: 'Tomatoes',
      quantityKg: 2200,
      quality: 'Grade B (Processing / Puree)',
      lat: -20.2000,
      lng: 32.6167,
      district: 'Chipinge',
      syncStatus: 'Synced',
      status: 'Open',
      createdAt: DateTime.now().subtract(const Duration(hours: 12)).toIso8601String(),
    ),
  ];

  static final List<DemandModel> _defaultSeedDemands = [
    DemandModel(
      id: 'DEM-HRE-01',
      buyerId: 'BUY-01',
      buyerName: 'Freshmark Zimbabwe',
      crop: 'Butternut Squash',
      targetQuantityKg: 5000,
      offeredPricePerKg: 0.75,
      qualityRequired: 'Grade A (Export / Retail)',
      deliveryHub: 'Harare Fresh Distribution Hub',
      deadline: '2026-09-05',
      createdAt: DateTime.now().toIso8601String(),
    ),
    DemandModel(
      id: 'DEM-BYO-02',
      buyerId: 'BUY-02',
      buyerName: 'Cairns Foods Agro-Processing',
      crop: 'Tomatoes',
      targetQuantityKg: 8000,
      offeredPricePerKg: 0.55,
      qualityRequired: 'Grade B (Processing / Puree)',
      deliveryHub: 'Mutare Canning Depot',
      deadline: '2026-09-10',
      createdAt: DateTime.now().toIso8601String(),
    ),
  ];

  static final List<ManifestModel> _defaultSeedManifests = [
    ManifestModel(
      id: 'MAN-NYA-101',
      clusterId: 'CLUSTER-NYANGA-NORTH',
      transporterId: 'TRP-01',
      crop: 'Butternut Squash & Beans',
      district: 'Nyanga',
      totalWeightKg: 2300,
      stopsCount: 4,
      estPayout: 185.00,
      status: 'En Route to Hub',
      createdAt: DateTime.now().toIso8601String(),
    ),
    ManifestModel(
      id: 'MAN-MUT-102',
      clusterId: 'CLUSTER-HONDE-VALLEY',
      transporterId: 'TRP-02',
      crop: 'Bananas & Avocados',
      district: 'Mutasa',
      totalWeightKg: 4100,
      stopsCount: 6,
      estPayout: 320.00,
      status: 'Pending Dispatch',
      createdAt: DateTime.now().toIso8601String(),
    ),
  ];

  // User Profile
  static Future<void> saveUser(UserProfileModel user) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_userKey, jsonEncode(user.toJson()));
  }

  static Future<UserProfileModel?> getUser() async {
    final prefs = await SharedPreferences.getInstance();
    final raw = prefs.getString(_userKey);
    if (raw == null) return null;
    try {
      return UserProfileModel.fromJson(jsonDecode(raw));
    } catch (_) {
      return null;
    }
  }

  static Future<void> clearUser() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_userKey);
  }

  // Listings
  static Future<List<ListingModel>> getListings() async {
    final prefs = await SharedPreferences.getInstance();
    final raw = prefs.getString(_listingsKey);
    if (raw == null) {
      await saveListings(_defaultSeedListings);
      return _defaultSeedListings;
    }
    try {
      final List decoded = jsonDecode(raw);
      return decoded.map((e) => ListingModel.fromJson(e)).toList();
    } catch (_) {
      return _defaultSeedListings;
    }
  }

  static Future<void> saveListings(List<ListingModel> listings) async {
    final prefs = await SharedPreferences.getInstance();
    final raw = jsonEncode(listings.map((e) => e.toJson()).toList());
    await prefs.setString(_listingsKey, raw);
  }

  static Future<void> addListing(ListingModel listing) async {
    final list = await getListings();
    final index = list.indexWhere((e) => e.id == listing.id);
    if (index >= 0) {
      list[index] = listing;
    } else {
      list.insert(0, listing);
    }
    await saveListings(list);
  }

  // Demands
  static Future<List<DemandModel>> getDemands() async {
    final prefs = await SharedPreferences.getInstance();
    final raw = prefs.getString(_demandsKey);
    if (raw == null) {
      await saveDemands(_defaultSeedDemands);
      return _defaultSeedDemands;
    }
    try {
      final List decoded = jsonDecode(raw);
      return decoded.map((e) => DemandModel.fromJson(e)).toList();
    } catch (_) {
      return _defaultSeedDemands;
    }
  }

  static Future<void> saveDemands(List<DemandModel> demands) async {
    final prefs = await SharedPreferences.getInstance();
    final raw = jsonEncode(demands.map((e) => e.toJson()).toList());
    await prefs.setString(_demandsKey, raw);
  }

  static Future<void> addDemand(DemandModel demand) async {
    final list = await getDemands();
    list.insert(0, demand);
    await saveDemands(list);
  }

  // Manifests
  static Future<List<ManifestModel>> getManifests() async {
    final prefs = await SharedPreferences.getInstance();
    final raw = prefs.getString(_manifestsKey);
    if (raw == null) {
      await saveManifests(_defaultSeedManifests);
      return _defaultSeedManifests;
    }
    try {
      final List decoded = jsonDecode(raw);
      return decoded.map((e) => ManifestModel.fromJson(e)).toList();
    } catch (_) {
      return _defaultSeedManifests;
    }
  }

  static Future<void> saveManifests(List<ManifestModel> manifests) async {
    final prefs = await SharedPreferences.getInstance();
    final raw = jsonEncode(manifests.map((e) => e.toJson()).toList());
    await prefs.setString(_manifestsKey, raw);
  }

  // Sync Queue (Mutations waiting for internet connection)
  static Future<void> enqueueMutation(String action, Map<String, dynamic> payload) async {
    final prefs = await SharedPreferences.getInstance();
    final raw = prefs.getString(_syncQueueKey);
    List queue = [];
    if (raw != null) {
      try {
        queue = jsonDecode(raw);
      } catch (_) {}
    }
    queue.add({
      'action': action,
      'payload': payload,
      'timestamp': DateTime.now().toIso8601String(),
    });
    await prefs.setString(_syncQueueKey, jsonEncode(queue));
  }

  static Future<List<Map<String, dynamic>>> getSyncQueue() async {
    final prefs = await SharedPreferences.getInstance();
    final raw = prefs.getString(_syncQueueKey);
    if (raw == null) return [];
    try {
      final List decoded = jsonDecode(raw);
      return decoded.cast<Map<String, dynamic>>();
    } catch (_) {
      return [];
    }
  }

  static Future<void> clearSyncQueue() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_syncQueueKey);
  }
}
