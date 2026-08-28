import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../core/theme/vunotho_theme.dart';
import '../logic/providers/auth_provider.dart';
import 'buyer/add_demand_dialog.dart';
import 'buyer/buyer_dashboard.dart';
import 'farmer/add_listing_dialog.dart';
import 'farmer/farmer_dashboard.dart';
import 'haulier/haulier_dashboard.dart';
import 'widgets/sync_status_badge.dart';

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
    } else {
      currentBody = _buildSettingsView(context, authProvider);
    }

    return Scaffold(
      appBar: AppBar(
        titleSpacing: 12,
        title: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 7, vertical: 4),
              decoration: BoxDecoration(
                color: VunothoColors.primary,
                borderRadius: BorderRadius.circular(8),
              ),
              child: const Text(
                'V',
                style: TextStyle(
                  color: Colors.white,
                  fontWeight: FontWeight.w900,
                  fontSize: 15,
                ),
              ),
            ),
            const SizedBox(width: 6),
            const Text(
              'VUNOTHO',
              style: TextStyle(
                fontSize: 15,
                fontWeight: FontWeight.w900,
                letterSpacing: 1.0,
                color: VunothoColors.textDark,
              ),
            ),
          ],
        ),
        actions: [
          const SyncStatusBadge(isOnline: true),
          const SizedBox(width: 4),
          PopupMenuButton<String>(
            icon: Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
              decoration: BoxDecoration(
                color: Colors.grey.shade100,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: VunothoColors.lightBorder),
              ),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(
                    currentRole.toUpperCase(),
                    style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold),
                  ),
                  const Icon(Icons.arrow_drop_down_rounded, size: 16),
                ],
              ),
            ),
            onSelected: (role) => authProvider.switchRole(role),
            itemBuilder: (context) => [
              const PopupMenuItem(
                value: 'farmer',
                child: Row(
                  children: [
                    Icon(Icons.grass_rounded, color: VunothoColors.primary, size: 18),
                    SizedBox(width: 8),
                    Text('Farmer Portal'),
                  ],
                ),
              ),
              const PopupMenuItem(
                value: 'buyer',
                child: Row(
                  children: [
                    Icon(Icons.shopping_cart_rounded, color: VunothoColors.logistics, size: 18),
                    SizedBox(width: 8),
                    Text('Buyer Portal'),
                  ],
                ),
              ),
              const PopupMenuItem(
                value: 'haulier',
                child: Row(
                  children: [
                    Icon(Icons.local_shipping_rounded, color: Color(0xFFD97706), size: 18),
                    SizedBox(width: 8),
                    Text('Haulier Portal'),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(width: 12),
        ],
      ),
      body: currentBody,
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          if (currentRole == 'buyer') {
            showDialog(context: context, builder: (_) => const AddDemandDialog());
          } else {
            showDialog(context: context, builder: (_) => const AddListingDialog());
          }
        },
        backgroundColor: currentRole == 'buyer'
            ? VunothoColors.logistics
            : (currentRole == 'haulier' ? const Color(0xFFD97706) : VunothoColors.primary),
        foregroundColor: Colors.white,
        elevation: 4,
        child: const Icon(Icons.add_rounded, size: 28),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,
      bottomNavigationBar: BottomAppBar(
        shape: const CircularNotchedRectangle(),
        notchMargin: 8,
        color: Colors.white,
        surfaceTintColor: Colors.transparent,
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceAround,
          children: [
            IconButton(
              icon: Icon(
                Icons.dashboard_rounded,
                color: _bottomNavIndex == 0 ? VunothoColors.primary : Colors.grey.shade400,
              ),
              onPressed: () => setState(() => _bottomNavIndex = 0),
            ),
            const SizedBox(width: 48), // Space for floating button
            IconButton(
              icon: Icon(
                Icons.person_rounded,
                color: _bottomNavIndex == 1 ? VunothoColors.primary : Colors.grey.shade400,
              ),
              onPressed: () => setState(() => _bottomNavIndex = 1),
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
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Row(
                children: [
                  CircleAvatar(
                    radius: 28,
                    backgroundColor: VunothoColors.primarySurface,
                    child: const Icon(Icons.person_rounded, color: VunothoColors.primary, size: 32),
                  ),
                  const SizedBox(width: 14),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        user?.name ?? 'Vunotho Member',
                        style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                      ),
                      Text(
                        '${user?.role.toUpperCase()} • ${user?.district}',
                        style: const TextStyle(fontSize: 12, color: VunothoColors.textMuted),
                      ),
                      const SizedBox(height: 4),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                        decoration: BoxDecoration(
                          color: VunothoColors.primarySurface,
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          user?.kycStatus ?? 'Verified Level 1',
                          style: const TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: VunothoColors.primaryDark),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 20),
          const Text('Switch Role Portal', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
          const SizedBox(height: 8),
          ListTile(
            tileColor: Colors.white,
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            leading: const Icon(Icons.grass_rounded, color: VunothoColors.primary),
            title: const Text('Smallholder Farmer Portal'),
            trailing: auth.currentRole == 'farmer' ? const Icon(Icons.check, color: VunothoColors.primary) : null,
            onTap: () {
              auth.switchRole('farmer');
              setState(() => _bottomNavIndex = 0);
            },
          ),
          const SizedBox(height: 8),
          ListTile(
            tileColor: Colors.white,
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            leading: const Icon(Icons.shopping_cart_rounded, color: VunothoColors.logistics),
            title: const Text('Commercial Buyer Portal'),
            trailing: auth.currentRole == 'buyer' ? const Icon(Icons.check, color: VunothoColors.logistics) : null,
            onTap: () {
              auth.switchRole('buyer');
              setState(() => _bottomNavIndex = 0);
            },
          ),
          const SizedBox(height: 8),
          ListTile(
            tileColor: Colors.white,
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            leading: const Icon(Icons.local_shipping_rounded, color: Color(0xFFD97706)),
            title: const Text('Haulier & Transporter Portal'),
            trailing: auth.currentRole == 'haulier' ? const Icon(Icons.check, color: Color(0xFFD97706)) : null,
            onTap: () {
              auth.switchRole('haulier');
              setState(() => _bottomNavIndex = 0);
            },
          ),
        ],
      ),
    );
  }
}
