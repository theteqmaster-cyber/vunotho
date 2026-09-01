import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../core/theme/vunotho_theme.dart';
import '../logic/providers/auth_provider.dart';
import 'buyer/add_demand_dialog.dart';
import 'buyer/buyer_dashboard.dart';
import 'farmer/add_listing_dialog.dart';
import 'farmer/farmer_dashboard.dart';
import 'farmer/farmer_produce_screen.dart';
import 'haulier/haulier_dashboard.dart';
import 'marketplace/marketplace_screen.dart';

class MainShell extends StatefulWidget {
  const MainShell({super.key});

  @override
  State<MainShell> createState() => _MainShellState();
}

class _MainShellState extends State<MainShell> {
  int _bottomNavIndex = 0;

  @override
  Widget build(BuildContext context) {
    final authProvider = context.watch<AuthProvider>();
    final currentRole = authProvider.currentRole;

    Widget currentBody;
    if (_bottomNavIndex == 0) {
      if (currentRole == 'buyer') {
        currentBody = const BuyerDashboard();
      } else if (currentRole == 'haulier') {
        currentBody = const HaulierDashboard();
      } else {
        currentBody = const FarmerDashboard();
      }
    } else if (_bottomNavIndex == 1) {
      currentBody = const FarmerProduceScreen();
    } else if (_bottomNavIndex == 2) {
      currentBody = const MarketplaceScreen();
    } else {
      currentBody = _buildProfileView(context, authProvider);
    }

    return Scaffold(
      backgroundColor: VunothoColors.scaffoldBg,
      appBar: AppBar(
        elevation: 0,
        backgroundColor: Colors.white,
        titleSpacing: 16,
        title: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 32,
              height: 32,
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Color(0xFF143D28), Color(0xFF2E7D32)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(10),
              ),
              child: const Center(
                child: Text(
                  'V',
                  style: TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.w900,
                    fontSize: 16,
                  ),
                ),
              ),
            ),
            const SizedBox(width: 8),
            Text(
              'VUNOTHO',
              style: GoogleFonts.plusJakartaSans(
                fontSize: 15,
                fontWeight: FontWeight.w900,
                letterSpacing: 0.8,
                color: VunothoColors.textDark,
              ),
            ),
          ],
        ),
        actions: [
          // Active Role & Verified Badge
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
            decoration: BoxDecoration(
              color: const Color(0xFFE8F5E9),
              borderRadius: BorderRadius.circular(9999),
              border: Border.all(color: const Color(0xFF86EFAC)),
            ),
            child: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Container(
                  width: 6,
                  height: 6,
                  decoration: const BoxDecoration(
                    color: Color(0xFF1B5E20),
                    shape: BoxShape.circle,
                  ),
                ),
                const SizedBox(width: 6),
                Text(
                  currentRole.toUpperCase(),
                  style: GoogleFonts.jetBrainsMono(
                    fontSize: 10,
                    fontWeight: FontWeight.w800,
                    color: const Color(0xFF1B5E20),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(width: 8),

          // Secure Sign Out Action Icon
          IconButton(
            tooltip: 'Sign Out / Switch Account',
            icon: const Icon(Icons.logout_rounded, size: 20, color: VunothoColors.textMuted),
            onPressed: () => _confirmSignOut(context, authProvider),
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: currentBody,

      // Floating Action Button
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          if (currentRole == 'buyer') {
            showDialog(context: context, builder: (_) => const AddDemandDialog());
          } else {
            showDialog(context: context, builder: (_) => const AddListingDialog());
          }
        },
        backgroundColor: VunothoColors.primaryDark,
        foregroundColor: Colors.white,
        elevation: 4,
        shape: const CircleBorder(),
        child: const Icon(Icons.add_rounded, size: 28),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,

      // Botanical Floating Bottom Navigation Bar
      bottomNavigationBar: BottomAppBar(
        shape: const CircularNotchedRectangle(),
        notchMargin: 8,
        color: Colors.white,
        elevation: 8,
        surfaceTintColor: Colors.transparent,
        padding: const EdgeInsets.symmetric(horizontal: 16),
        height: 64,
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceAround,
          children: [
            _buildNavTab(0, Icons.home_rounded, 'Home'),
            _buildNavTab(1, Icons.eco_rounded, 'Produce'),
            const SizedBox(width: 48), // Space for floating button
            _buildNavTab(2, Icons.storefront_rounded, 'Market'),
            _buildNavTab(3, Icons.person_rounded, 'Profile'),
          ],
        ),
      ),
    );
  }

  Widget _buildNavTab(int index, IconData icon, String label) {
    final isSelected = _bottomNavIndex == index;
    return InkWell(
      onTap: () => setState(() => _bottomNavIndex = index),
      borderRadius: BorderRadius.circular(12),
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(
              icon,
              size: 22,
              color: isSelected ? VunothoColors.primaryDark : const Color(0xFF94A3B8),
            ),
            const SizedBox(height: 2),
            Text(
              label,
              style: GoogleFonts.plusJakartaSans(
                fontSize: 10,
                fontWeight: isSelected ? FontWeight.w800 : FontWeight.w500,
                color: isSelected ? VunothoColors.primaryDark : const Color(0xFF94A3B8),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildProfileView(BuildContext context, AuthProvider auth) {
    final user = auth.user;
    final role = auth.currentRole;

    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(),
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // 1. Authenticated Profile Card
          Container(
            padding: const EdgeInsets.all(18),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(22),
              border: Border.all(color: VunothoColors.cardBorder),
              boxShadow: VunothoTheme.softShadow,
            ),
            child: Row(
              children: [
                Container(
                  width: 56,
                  height: 56,
                  decoration: BoxDecoration(
                    color: const Color(0xFFE8F5E9),
                    borderRadius: BorderRadius.circular(18),
                  ),
                  child: const Center(
                    child: Icon(Icons.person_rounded, color: Color(0xFF1B5E20), size: 32),
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        user?.name ?? 'Simba Mukamuri',
                        style: GoogleFonts.plusJakartaSans(fontSize: 16, fontWeight: FontWeight.w800),
                      ),
                      const SizedBox(height: 2),
                      Text(
                        user?.emailOrPhone ?? '0776118117',
                        style: GoogleFonts.jetBrainsMono(fontSize: 12, color: VunothoColors.textMuted),
                      ),
                      const SizedBox(height: 5),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2.5),
                        decoration: BoxDecoration(
                          color: const Color(0xFFE8F5E9),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          '${role.toUpperCase()} • KYC Level 1 Verified',
                          style: GoogleFonts.jetBrainsMono(
                            fontSize: 9.5,
                            fontWeight: FontWeight.w800,
                            color: const Color(0xFF1B5E20),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),

          // 2. Farmgate & Settlement Credentials
          Text(
            'ACCOUNT CREDENTIALS & SETTLEMENT',
            style: GoogleFonts.jetBrainsMono(
              fontSize: 11,
              fontWeight: FontWeight.w800,
              color: VunothoColors.textMuted,
              letterSpacing: 0.8,
            ),
          ),
          const SizedBox(height: 10),

          _buildCredentialTile(
            'Registered Farm Location',
            'Nyanga Horticultural Valley, Zimbabwe',
            Icons.location_on_rounded,
            const Color(0xFF15803D),
          ),
          _buildCredentialTile(
            'EcoCash Escrow Payout Wallet',
            '+263 77 611 8117 (Active & Verified)',
            Icons.account_balance_wallet_rounded,
            const Color(0xFF0284C7),
          ),
          _buildCredentialTile(
            'Pooled Logistics Discount Tier',
            '35% Freight Subsidy Applied via 2.5T Pool',
            Icons.local_shipping_rounded,
            const Color(0xFFD97706),
          ),
          _buildCredentialTile(
            'Data Encryption & Sync Engine',
            'Offline-First SQLite / Supabase Cloud Sync',
            Icons.lock_outline_rounded,
            const Color(0xFF0D9488),
          ),

          const SizedBox(height: 24),

          // 3. Secure Sign Out / Switch Persona Button
          SizedBox(
            width: double.infinity,
            height: 50,
            child: OutlinedButton.icon(
              onPressed: () => _confirmSignOut(context, auth),
              icon: const Icon(Icons.logout_rounded, size: 18, color: Color(0xFFDC2626)),
              label: Text(
                'Sign Out / Switch to Another Account',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 13.5,
                  fontWeight: FontWeight.bold,
                  color: const Color(0xFFDC2626),
                ),
              ),
              style: OutlinedButton.styleFrom(
                side: const BorderSide(color: Color(0xFFFECACA)),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                backgroundColor: const Color(0xFFFEF2F2),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCredentialTile(String title, String subtitle, IconData icon, Color iconColor) {
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: VunothoColors.cardBorder),
        boxShadow: VunothoTheme.softShadow,
      ),
      child: Row(
        children: [
          Container(
            width: 36,
            height: 36,
            decoration: BoxDecoration(
              color: iconColor.withValues(alpha: 0.1),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(icon, color: iconColor, size: 18),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: GoogleFonts.plusJakartaSans(fontSize: 12.5, fontWeight: FontWeight.bold)),
                const SizedBox(height: 1),
                Text(subtitle, style: GoogleFonts.plusJakartaSans(fontSize: 11, color: VunothoColors.textMuted)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  void _confirmSignOut(BuildContext context, AuthProvider auth) {
    showDialog(
      context: context,
      builder: (dialogCtx) => AlertDialog(
        backgroundColor: Colors.white,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        title: Text(
          'Sign Out?',
          style: GoogleFonts.plusJakartaSans(fontSize: 18, fontWeight: FontWeight.w800),
        ),
        content: Text(
          'You will be returned to the Welcome screen where you can sign in with another role or account.',
          style: GoogleFonts.plusJakartaSans(fontSize: 13, color: VunothoColors.textMuted),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.of(dialogCtx).pop(),
            child: Text('Cancel', style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.w700, color: VunothoColors.textMuted)),
          ),
          ElevatedButton(
            onPressed: () {
              Navigator.of(dialogCtx).pop();
              auth.logout();
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: const Color(0xFFDC2626),
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            child: Text('Sign Out', style: GoogleFonts.plusJakartaSans(fontWeight: FontWeight.bold)),
          ),
        ],
      ),
    );
  }
}
