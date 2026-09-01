import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../core/theme/vunotho_theme.dart';
import '../logic/providers/auth_provider.dart';
import 'buyer/add_demand_dialog.dart';
import 'buyer/buyer_dashboard.dart';
import 'farmer/add_listing_dialog.dart';
import 'farmer/farmer_dashboard.dart';
import 'haulier/haulier_dashboard.dart';

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
      // Harvest Lots View
      currentBody = const FarmerDashboard();
    } else {
      currentBody = _buildSettingsView(context, authProvider);
    }

    return Scaffold(
      backgroundColor: VunothoColors.lightBg,
      appBar: AppBar(
        elevation: 0,
        backgroundColor: Colors.white,
        titleSpacing: 12,
        title: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 30,
              height: 30,
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Color(0xFF15803D), Color(0xFF064E3B)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(9),
              ),
              child: const Center(
                child: Text(
                  'V',
                  style: TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.w900,
                    fontSize: 15,
                  ),
                ),
              ),
            ),
            const SizedBox(width: 7),
            Text(
              'VUNOTHO',
              style: GoogleFonts.plusJakartaSans(
                fontSize: 14,
                fontWeight: FontWeight.w900,
                letterSpacing: 0.8,
                color: VunothoColors.textDark,
              ),
            ),
          ],
        ),
        actions: [
          // Role & Sync Popup Menu Button
          PopupMenuButton<String>(
            tooltip: 'Switch Portal',
            padding: EdgeInsets.zero,
            icon: Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              decoration: BoxDecoration(
                color: const Color(0xFFDCFCE7),
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: const Color(0xFF86EFAC)),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Container(
                    width: 6,
                    height: 6,
                    decoration: const BoxDecoration(
                      color: Color(0xFF15803D),
                      shape: BoxShape.circle,
                    ),
                  ),
                  const SizedBox(width: 5),
                  Text(
                    currentRole.toUpperCase(),
                    style: GoogleFonts.jetBrainsMono(
                      fontSize: 10,
                      fontWeight: FontWeight.w800,
                      color: const Color(0xFF15803D),
                    ),
                  ),
                  const Icon(Icons.arrow_drop_down_rounded, size: 16, color: Color(0xFF15803D)),
                ],
              ),
            ),
            onSelected: (role) {
              if (role == 'logout') {
                authProvider.logout();
              } else {
                authProvider.switchRole(role);
              }
            },
            itemBuilder: (context) => [
              const PopupMenuItem(
                value: 'farmer',
                child: Row(
                  children: [
                    Icon(Icons.eco_rounded, color: Color(0xFF15803D), size: 18),
                    SizedBox(width: 10),
                    Text('Farmer Hub'),
                  ],
                ),
              ),
              const PopupMenuItem(
                value: 'buyer',
                child: Row(
                  children: [
                    Icon(Icons.storefront_rounded, color: Color(0xFF0284C7), size: 18),
                    SizedBox(width: 10),
                    Text('Buyer Hub'),
                  ],
                ),
              ),
              const PopupMenuItem(
                value: 'haulier',
                child: Row(
                  children: [
                    Icon(Icons.local_shipping_rounded, color: Color(0xFFD97706), size: 18),
                    SizedBox(width: 10),
                    Text('Haulier Hub'),
                  ],
                ),
              ),
              const PopupMenuDivider(),
              const PopupMenuItem(
                value: 'logout',
                child: Row(
                  children: [
                    Icon(Icons.logout_rounded, color: Color(0xFFDC2626), size: 18),
                    SizedBox(width: 10),
                    Text('Sign Out', style: TextStyle(color: Color(0xFFDC2626))),
                  ],
                ),
              ),
            ],
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
        backgroundColor: const Color(0xFF15803D),
        foregroundColor: Colors.white,
        elevation: 4,
        shape: const CircleBorder(),
        child: const Icon(Icons.add_rounded, size: 28),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,

      // Modern Botanical Bottom Navigation Bar
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
            const SizedBox(width: 48), // Gap for floating + button
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
              color: isSelected ? const Color(0xFF15803D) : const Color(0xFF94A3B8),
            ),
            const SizedBox(height: 2),
            Text(
              label,
              style: GoogleFonts.plusJakartaSans(
                fontSize: 10,
                fontWeight: isSelected ? FontWeight.w800 : FontWeight.w500,
                color: isSelected ? const Color(0xFF15803D) : const Color(0xFF94A3B8),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSettingsView(BuildContext context, AuthProvider auth) {
    final user = auth.user;
    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Profile Card
          Container(
            padding: const EdgeInsets.all(18),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(22),
              border: Border.all(color: VunothoColors.lightBorder),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withValues(alpha: 0.02),
                  blurRadius: 10,
                  offset: const Offset(0, 4),
                ),
              ],
            ),
            child: Row(
              children: [
                Container(
                  width: 54,
                  height: 54,
                  decoration: BoxDecoration(
                    color: const Color(0xFFDCFCE7),
                    borderRadius: BorderRadius.circular(18),
                  ),
                  child: const Center(
                    child: Icon(Icons.person_rounded, color: Color(0xFF15803D), size: 30),
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
                        '${(user?.role ?? 'farmer').toUpperCase()} • Nyanga District',
                        style: GoogleFonts.plusJakartaSans(fontSize: 11, color: VunothoColors.textMuted),
                      ),
                      const SizedBox(height: 4),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                        decoration: BoxDecoration(
                          color: const Color(0xFFDCFCE7),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          'KYC Verified • Level 1 Smallholder',
                          style: GoogleFonts.jetBrainsMono(
                            fontSize: 9,
                            fontWeight: FontWeight.w800,
                            color: const Color(0xFF15803D),
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

          Text(
            'SWITCH ACTIVE PORTAL',
            style: GoogleFonts.jetBrainsMono(
              fontSize: 11,
              fontWeight: FontWeight.w800,
              color: const Color(0xFF64748B),
              letterSpacing: 1.0,
            ),
          ),
          const SizedBox(height: 10),

          _buildPortalTile(
            'Smallholder Farmer Desk',
            'Log produce, guaranteed Net-Return pricing',
            Icons.eco_rounded,
            const Color(0xFF15803D),
            auth.currentRole == 'farmer',
            () => auth.switchRole('farmer'),
          ),
          _buildPortalTile(
            'Commercial Buyer Desk',
            'Post wholesale supermarket demand contracts',
            Icons.storefront_rounded,
            const Color(0xFF0284C7),
            auth.currentRole == 'buyer',
            () => auth.switchRole('buyer'),
          ),
          _buildPortalTile(
            'Rural Transporter Desk',
            '2.5T light truck pooled logistics manifests',
            Icons.local_shipping_rounded,
            const Color(0xFFD97706),
            auth.currentRole == 'haulier',
            () => auth.switchRole('haulier'),
          ),

          const SizedBox(height: 24),

          // Sign Out Button
          SizedBox(
            width: double.infinity,
            height: 48,
            child: OutlinedButton.icon(
              onPressed: () => auth.logout(),
              icon: const Icon(Icons.logout_rounded, size: 18, color: Color(0xFFDC2626)),
              label: Text(
                'Sign Out / Switch Account',
                style: GoogleFonts.plusJakartaSans(
                  fontSize: 13,
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

  Widget _buildPortalTile(
    String title,
    String subtitle,
    IconData icon,
    Color color,
    bool isActive,
    VoidCallback onTap,
  ) {
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      child: ListTile(
        onTap: onTap,
        tileColor: Colors.white,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
          side: BorderSide(
            color: isActive ? color : VunothoColors.lightBorder,
            width: isActive ? 2 : 1,
          ),
        ),
        leading: Container(
          width: 38,
          height: 38,
          decoration: BoxDecoration(
            color: color.withValues(alpha: 0.1),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Icon(icon, color: color, size: 20),
        ),
        title: Text(title, style: GoogleFonts.plusJakartaSans(fontSize: 13.5, fontWeight: FontWeight.bold)),
        subtitle: Text(subtitle, style: GoogleFonts.plusJakartaSans(fontSize: 10.5, color: VunothoColors.textMuted)),
        trailing: isActive ? Icon(Icons.check_circle_rounded, color: color, size: 20) : null,
      ),
    );
  }
}
