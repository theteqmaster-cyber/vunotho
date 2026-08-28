/**
 * VUNOTHO ENTERPRISE DATA TYPE DEFINITIONS (TypeScript)
 */

export interface Listing {
  id: string;
  farmer_id: string;
  farmer_name: string;
  crop: string;
  quantity_kg: number;
  quality: string;
  lat: number;
  lng: number;
  district: string;
  province?: string;
  user_id?: string;
  sync_status?: 'Synced' | 'Saved Offline' | 'Needs Attention';
  status?: 'Open' | 'Locked' | 'Fulfilled';
  created_at?: string;
}

export interface Demand {
  id: string;
  buyer_id: string;
  buyer_name: string;
  crop: string;
  target_quantity_kg: number;
  offered_price_per_kg: number;
  quality_required?: string;
  quality_tier?: string;
  delivery_hub: string;
  deadline?: string;
  district?: string;
  province?: string;
  user_id?: string;
  status?: 'Active' | 'Fulfilled' | 'Closed';
  created_at?: string;
}

export interface Transaction {
  id: string;
  reference: string;
  receipt_reference?: string;
  payment_method: string;
  farmer_id: string;
  farmer_name: string;
  buyer_id: string;
  buyer_name: string;
  crop: string;
  quantity_kg: number;
  gross_total: number;
  transport_deduction: number;
  transport_cost?: number;
  platform_fee: number;
  net_payout: number;
  status: 'Settled' | 'Pending' | 'Escrow';
  created_at: string;
}

export interface ValueRecoveryLog {
  id: string;
  listing_id: string;
  crop: string;
  farmer_id: string;
  farmer_name: string;
  pathway: string;
  kg_diverted: number;
  recovered_value_usd: number;
  facility: string;
  timestamp: string;
}

export interface ManifestStop {
  farmerName: string;
  crop: string;
  weightKg: number;
  lat: number;
  lng: number;
  district: string;
}

export interface RouteManifest {
  id: string;
  clusterId: string;
  crop: string;
  district: string;
  originDistrict: string;
  destination: string;
  totalWeightKg: number;
  stopsCount: number;
  stops: ManifestStop[];
  loadUtilizationPct: number;
  estTotalDistance: number;
  estimatedDistanceKm: number;
  estTransporterPayout: number;
  status: 'Pending Dispatch' | 'Dispatched' | 'In Transit' | 'Completed';
  created_at?: string;
}

export interface UserProfile {
  id: string;
  name: string;
  organisation?: string;
  email_or_phone: string;
  role: 'farmer' | 'buyer' | 'transporter' | 'admin';
  province?: string;
  district?: string;
  main_produce?: string;
  vehicle_type?: string;
  kyc_status?: string;
  kycStatus?: string;
  created_at?: string;
}

export interface SystemConfig {
  platform_fee_pct: string;
  transport_per_km: string;
  transport_per_kg: string;
  grade_a_multiplier?: string;
  grade_b_floor_usd: string;
  grade_c_floor_usd: string;
  compost_floor_usd: string;
  enactus_target_usd: string;
  auto_dispatch_threshold_kg: string;
}

export interface NetReturnBreakdown {
  grossPricePerKg: number;
  grossTotal: number;
  transportPerKg: number;
  transportTotal: number;
  platformFeePerKg: number;
  platformFeeTotal: number;
  netReturnPerKg: number;
  netTotal: number;
  quantityKg: number;
  distanceKm: number;
  isAggregated: boolean;
  transportSavings: number;
  currency: string;
}

export interface SyncMutation {
  id?: number;
  action: 'CREATE_LISTING' | 'CREATE_DEMAND' | 'SETTLE_TRANSACTION' | 'LOG_VALUE_RECOVERY' | 'REGISTER_USER';
  payload: any;
  created_at: string;
  attempts?: number;
}
