import 'package:supabase_flutter/supabase_flutter.dart';
import '../../core/services/offline_storage_service.dart';
import '../models/listing_model.dart';
import '../models/demand_model.dart';
import '../models/manifest_model.dart';

class VunothoRepository {
  final SupabaseClient? _supabase;

  VunothoRepository([this._supabase]);

  // Check if Supabase client is active
  bool get hasSupabase => _supabase != null;

  // --- Listings ---
  Future<List<ListingModel>> getListings() async {
    if (hasSupabase) {
      try {
        final response = await _supabase!
            .from('listings')
            .select()
            .order('created_at', ascending: false);
        final list = (response as List).map((e) => ListingModel.fromJson(e)).toList();
        await OfflineStorageService.saveListings(list);
        return list;
      } catch (e) {
        // Network / Supabase error -> fallback to offline cache
      }
    }
    return await OfflineStorageService.getListings();
  }

  Future<bool> createListing(ListingModel listing) async {
    bool onlineSuccess = false;
    if (hasSupabase) {
      try {
        await _supabase!.from('listings').upsert(listing.toJson());
        onlineSuccess = true;
      } catch (e) {
        onlineSuccess = false;
      }
    }

    final localModel = listing.copyWith(
      syncStatus: onlineSuccess ? 'Synced' : 'Saved Offline',
    );
    await OfflineStorageService.addListing(localModel);

    if (!onlineSuccess) {
      await OfflineStorageService.enqueueMutation('CREATE_LISTING', listing.toJson());
    }
    return onlineSuccess;
  }

  // --- Demands ---
  Future<List<DemandModel>> getDemands() async {
    if (hasSupabase) {
      try {
        final response = await _supabase!
            .from('demands')
            .select()
            .order('created_at', ascending: false);
        final list = (response as List).map((e) => DemandModel.fromJson(e)).toList();
        await OfflineStorageService.saveDemands(list);
        return list;
      } catch (e) {
        // Network error -> fallback to offline cache
      }
    }
    return await OfflineStorageService.getDemands();
  }

  Future<bool> createDemand(DemandModel demand) async {
    bool onlineSuccess = false;
    if (hasSupabase) {
      try {
        await _supabase!.from('demands').upsert(demand.toJson());
        onlineSuccess = true;
      } catch (e) {
        onlineSuccess = false;
      }
    }

    await OfflineStorageService.addDemand(demand);
    if (!onlineSuccess) {
      await OfflineStorageService.enqueueMutation('CREATE_DEMAND', demand.toJson());
    }
    return onlineSuccess;
  }

  // --- Manifests ---
  Future<List<ManifestModel>> getManifests() async {
    if (hasSupabase) {
      try {
        final response = await _supabase!
            .from('manifests')
            .select()
            .order('created_at', ascending: false);
        final list = (response as List).map((e) => ManifestModel.fromJson(e)).toList();
        await OfflineStorageService.saveManifests(list);
        return list;
      } catch (e) {
        // Fallback
      }
    }
    return await OfflineStorageService.getManifests();
  }

  // Replay offline mutations
  Future<int> syncOfflineQueue() async {
    if (!hasSupabase) return 0;
    final queue = await OfflineStorageService.getSyncQueue();
    if (queue.isEmpty) return 0;

    int syncedCount = 0;
    for (final item in queue) {
      try {
        final action = item['action'];
        final payload = item['payload'];
        if (action == 'CREATE_LISTING') {
          await _supabase!.from('listings').upsert(payload);
          syncedCount++;
        } else if (action == 'CREATE_DEMAND') {
          await _supabase!.from('demands').upsert(payload);
          syncedCount++;
        }
      } catch (_) {
        // Stop syncing on network break
        break;
      }
    }

    if (syncedCount == queue.length) {
      await OfflineStorageService.clearSyncQueue();
    }
    return syncedCount;
  }
}
