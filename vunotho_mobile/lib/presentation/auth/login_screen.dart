import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:provider/provider.dart';
import '../../core/theme/vunotho_theme.dart';
import '../../logic/providers/auth_provider.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _phoneOrEmailController = TextEditingController(text: '0776118117');
  final _pinController = TextEditingController(text: '1234');
  String _selectedRole = 'farmer';
  bool _isLoading = false;

  final List<Map<String, dynamic>> _roles = [
    {
      'id': 'farmer',
      'title': 'Smallholder Farmer',
      'icon': Icons.eco_rounded,
      'desc': 'Log produce, guaranteed Net-Return pricing',
      'color': VunothoColors.primary,
    },
    {
      'id': 'buyer',
      'title': 'Commercial Buyer',
      'icon': Icons.storefront_rounded,
      'desc': 'Wholesale supermarket orders & contracts',
      'color': const Color(0xFF0284C7),
    },
    {
      'id': 'haulier',
      'title': 'Rural Transporter',
      'icon': Icons.local_shipping_rounded,
      'desc': '2.5T load aggregation corridor routes',
      'color': VunothoColors.accent,
    },
  ];

  @override
  void dispose() {
    _phoneOrEmailController.dispose();
    _pinController.dispose();
    super.dispose();
  }

  void _handleSignIn() async {
    final identifier = _phoneOrEmailController.text.trim();
    if (identifier.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter your Phone Number or Email')),
      );
      return;
    }

    setState(() => _isLoading = true);
    await Future.delayed(const Duration(milliseconds: 300));
    if (!mounted) return;

    await context.read<AuthProvider>().login(identifier, _selectedRole);
    setState(() => _isLoading = false);
  }

  void _handleGuestExplore() async {
    setState(() => _isLoading = true);
    await context.read<AuthProvider>().login('Guest Farmer', _selectedRole);
    setState(() => _isLoading = false);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFF071726),
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
            child: ConstrainedBox(
              constraints: const BoxConstraints(maxWidth: 460),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  // 1. Official Vunotho Logo Header
                  Center(
                    child: Container(
                      width: 68,
                      height: 68,
                      padding: const EdgeInsets.all(4),
                      decoration: BoxDecoration(
                        gradient: const LinearGradient(
                          colors: [Color(0xFF15803D), Color(0xFF064E3B)],
                          begin: Alignment.topLeft,
                          end: Alignment.bottomRight,
                        ),
                        borderRadius: BorderRadius.circular(20),
                        boxShadow: [
                          BoxShadow(
                            color: const Color(0xFF15803D).withValues(alpha: 0.35),
                            blurRadius: 20,
                            offset: const Offset(0, 8),
                          ),
                        ],
                        border: Border.all(color: const Color(0xFF22C55E).withValues(alpha: 0.5), width: 1.5),
                      ),
                      child: const Center(
                        child: Text(
                          'V',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 32,
                            fontWeight: FontWeight.w900,
                            letterSpacing: -1,
                          ),
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),

                  Center(
                    child: Text(
                      'VUNOTHO',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 26,
                        fontWeight: FontWeight.w900,
                        letterSpacing: 2.0,
                        color: Colors.white,
                      ),
                    ),
                  ),
                  const SizedBox(height: 4),
                  Center(
                    child: Text(
                      'Zimbabwe Agricultural Operating System',
                      style: GoogleFonts.plusJakartaSans(
                        fontSize: 12,
                        fontWeight: FontWeight.w600,
                        color: const Color(0xFF4ADE80),
                        letterSpacing: 0.5,
                      ),
                    ),
                  ),
                  const SizedBox(height: 28),

                  // 2. Select Platform Role
                  Text(
                    'SELECT YOUR ROLE',
                    style: GoogleFonts.jetBrainsMono(
                      fontSize: 11,
                      fontWeight: FontWeight.w800,
                      color: const Color(0xFF94A3B8),
                      letterSpacing: 1.2,
                    ),
                  ),
                  const SizedBox(height: 10),

                  ..._roles.map((role) {
                    final isSelected = _selectedRole == role['id'];
                    final roleColor = role['color'] as Color;

                    return GestureDetector(
                      onTap: () => setState(() => _selectedRole = role['id']),
                      child: Container(
                        margin: const EdgeInsets.only(bottom: 10),
                        padding: const EdgeInsets.all(14),
                        decoration: BoxDecoration(
                          color: isSelected ? roleColor.withValues(alpha: 0.15) : const Color(0xFF0F2438),
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(
                            color: isSelected ? roleColor : const Color(0xFF1E3A52),
                            width: isSelected ? 2 : 1,
                          ),
                        ),
                        child: Row(
                          children: [
                            Container(
                              width: 42,
                              height: 42,
                              decoration: BoxDecoration(
                                color: isSelected ? roleColor : const Color(0xFF1E3A52),
                                borderRadius: BorderRadius.circular(12),
                              ),
                              child: Icon(role['icon'] as IconData, color: Colors.white, size: 22),
                            ),
                            const SizedBox(width: 14),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    role['title'],
                                    style: GoogleFonts.plusJakartaSans(
                                      fontSize: 14,
                                      fontWeight: FontWeight.bold,
                                      color: Colors.white,
                                    ),
                                  ),
                                  const SizedBox(height: 2),
                                  Text(
                                    role['desc'],
                                    style: GoogleFonts.plusJakartaSans(
                                      fontSize: 11,
                                      color: const Color(0xFF94A3B8),
                                    ),
                                  ),
                                ],
                              ),
                            ),
                            if (isSelected)
                              Icon(Icons.check_circle_rounded, color: roleColor, size: 20),
                          ],
                        ),
                      ),
                    );
                  }),

                  const SizedBox(height: 16),

                  // 3. Credentials Input
                  Container(
                    decoration: BoxDecoration(
                      color: const Color(0xFF0F2438),
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: const Color(0xFF1E3A52)),
                    ),
                    child: TextField(
                      controller: _phoneOrEmailController,
                      style: GoogleFonts.plusJakartaSans(color: Colors.white, fontSize: 14),
                      decoration: InputDecoration(
                        hintText: 'Mobile Phone (e.g. 0776118117)',
                        hintStyle: GoogleFonts.plusJakartaSans(color: const Color(0xFF64748B), fontSize: 13),
                        prefixIcon: const Icon(Icons.phone_iphone_rounded, color: Color(0xFF4ADE80), size: 20),
                        border: InputBorder.none,
                        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),

                  Container(
                    decoration: BoxDecoration(
                      color: const Color(0xFF0F2438),
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: const Color(0xFF1E3A52)),
                    ),
                    child: TextField(
                      controller: _pinController,
                      obscureText: true,
                      style: GoogleFonts.jetBrainsMono(color: Colors.white, fontSize: 14),
                      decoration: InputDecoration(
                        hintText: 'Security PIN / Password',
                        hintStyle: GoogleFonts.plusJakartaSans(color: const Color(0xFF64748B), fontSize: 13),
                        prefixIcon: const Icon(Icons.lock_outline_rounded, color: Color(0xFF4ADE80), size: 20),
                        border: InputBorder.none,
                        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),

                  // 4. Primary Sign In Button
                  SizedBox(
                    height: 52,
                    child: ElevatedButton(
                      onPressed: _isLoading ? null : _handleSignIn,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF15803D),
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        elevation: 4,
                      ),
                      child: _isLoading
                          ? const SizedBox(
                              width: 22,
                              height: 22,
                              child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5),
                            )
                          : Text(
                              'Sign In to Vunotho →',
                              style: GoogleFonts.plusJakartaSans(
                                fontSize: 15,
                                fontWeight: FontWeight.w800,
                              ),
                            ),
                    ),
                  ),
                  const SizedBox(height: 12),

                  // 5. Direct Guest Demo Button
                  OutlinedButton(
                    onPressed: _isLoading ? null : _handleGuestExplore,
                    style: OutlinedButton.styleFrom(
                      foregroundColor: const Color(0xFF94A3B8),
                      side: const BorderSide(color: Color(0xFF1E3A52)),
                      padding: const EdgeInsets.symmetric(vertical: 13),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                    ),
                    child: Text(
                      'Explore as Guest (Instant Access)',
                      style: GoogleFonts.plusJakartaSans(fontSize: 13, fontWeight: FontWeight.w700),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
