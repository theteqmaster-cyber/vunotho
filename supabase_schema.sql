-- ==========================================================================
-- VUNOTHO COMPLETE SUPABASE POSTGRESQL DATABASE SCHEMA & MIGRATION
-- Run this in your Supabase SQL Editor (icon `>_` on the left navigation bar)
-- ==========================================================================

-- 1. Produce Listings Table (Smallholder Harvests)
CREATE TABLE IF NOT EXISTS public.listings (
    id VARCHAR(64) PRIMARY KEY,
    farmer_id VARCHAR(64),
    farmer_name VARCHAR(128),
    crop VARCHAR(64),
    quantity_kg NUMERIC,
    quality VARCHAR(64),
    lat NUMERIC,
    lng NUMERIC,
    district VARCHAR(64),
    sync_status VARCHAR(32) DEFAULT 'Synced',
    status VARCHAR(32) DEFAULT 'Open',
    created_at VARCHAR(64)
);

-- 2. Buyer Demands Table (Commercial Off-takers)
CREATE TABLE IF NOT EXISTS public.demands (
    id VARCHAR(64) PRIMARY KEY,
    buyer_id VARCHAR(64),
    buyer_name VARCHAR(128),
    crop VARCHAR(64),
    target_quantity_kg NUMERIC,
    offered_price_per_kg NUMERIC,
    quality_required VARCHAR(64),
    delivery_hub VARCHAR(128),
    deadline VARCHAR(64),
    status VARCHAR(32) DEFAULT 'Active',
    created_at VARCHAR(64)
);

-- 3. Transactions & Settlements Table (Digital Wallet / EcoCash / COD)
CREATE TABLE IF NOT EXISTS public.transactions (
    id VARCHAR(64) PRIMARY KEY,
    reference VARCHAR(64),
    payment_method VARCHAR(64),
    farmer_id VARCHAR(64),
    farmer_name VARCHAR(128),
    buyer_id VARCHAR(64),
    buyer_name VARCHAR(128),
    crop VARCHAR(64),
    quantity_kg NUMERIC,
    gross_total NUMERIC,
    transport_deduction NUMERIC,
    platform_fee NUMERIC,
    net_payout NUMERIC,
    status VARCHAR(32) DEFAULT 'Settled',
    created_at VARCHAR(64)
);

-- 4. Circular Value-Recovery Diversion Logs (Food Processing, Animal Feed, Bio-Compost)
CREATE TABLE IF NOT EXISTS public.value_recovery (
    id VARCHAR(64) PRIMARY KEY,
    listing_id VARCHAR(64),
    crop VARCHAR(64),
    farmer_id VARCHAR(64),
    farmer_name VARCHAR(128),
    pathway VARCHAR(128),
    kg_diverted NUMERIC,
    recovered_value_usd NUMERIC,
    facility VARCHAR(128),
    timestamp VARCHAR(64)
);

-- 5. Transporter Manifests (Pooled Multi-Farmer Routes)
CREATE TABLE IF NOT EXISTS public.manifests (
    id VARCHAR(64) PRIMARY KEY,
    cluster_id VARCHAR(64),
    transporter_id VARCHAR(64),
    crop VARCHAR(64),
    district VARCHAR(64),
    total_weight_kg NUMERIC,
    stops_count INTEGER,
    est_payout NUMERIC,
    status VARCHAR(32) DEFAULT 'Pending Dispatch',
    created_at VARCHAR(64)
);

-- 6. Registered Users Table (Farmers, Buyers, Transporters, Admins)
CREATE TABLE IF NOT EXISTS public.users (
    id VARCHAR(64) PRIMARY KEY,
    name VARCHAR(128),
    email_or_phone VARCHAR(128) UNIQUE,
    password_hash VARCHAR(255),
    role VARCHAR(32),
    district VARCHAR(64),
    kyc_status VARCHAR(32) DEFAULT 'Pending KYC',
    created_at VARCHAR(64)
);

-- 7. System Configurations Table (Global Economic Parameters)
CREATE TABLE IF NOT EXISTS public.system_configs (
    config_key VARCHAR(64) PRIMARY KEY,
    config_value TEXT,
    updated_at VARCHAR(64)
);

-- Seed Initial System Configurations
INSERT INTO public.system_configs (config_key, config_value, updated_at)
VALUES 
    ('platform_fee_pct', '4.0', NOW()),
    ('transport_per_km', '0.05', NOW()),
    ('transport_per_kg', '0.03', NOW()),
    ('grade_b_floor_usd', '0.55', NOW()),
    ('grade_c_floor_usd', '0.25', NOW()),
    ('compost_floor_usd', '0.10', NOW()),
    ('enactus_target_usd', '15000.00', NOW()),
    ('auto_dispatch_threshold_kg', '2000', NOW())
ON CONFLICT (config_key) DO NOTHING;

-- Seed Master Administrator Account
INSERT INTO public.users (id, name, email_or_phone, password_hash, role, district, kyc_status, created_at)
VALUES (
    'USR-ROOT-ADMIN',
    'System Administrator',
    'admin@vunotho@gmail.com',
    '$2y$10$0MimijvuqOyawf.iw4NTcOjPpl9Q41UrF1gymm8QMVSmSA14XoVn2', -- Hash for 'wish2026'
    'admin',
    'National Hub',
    'Super Admin',
    NOW()
)
ON CONFLICT (email_or_phone) DO NOTHING;
