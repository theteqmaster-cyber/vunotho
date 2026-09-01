import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class VunothoColors {
  // Deep Botanical Greens
  static const Color primaryDark = Color(0xFF143D28); // Deep Forest Green
  static const Color primary = Color(0xFF2E7D32); // Rich Leaf Green
  static const Color primaryLight = Color(0xFF4CAF50); // Vivid Green
  static const Color primarySurface = Color(0xFFE8F5E9); // Soft Sage Tint

  // Accent Amber / Gold
  static const Color accent = Color(0xFFD97706);
  static const Color accentLight = Color(0xFFFBBF24);
  static const Color accentSurface = Color(0xFFFFFBEB);

  // Logistics Blue
  static const Color logistics = Color(0xFF0284C7);
  static const Color logisticsSurface = Color(0xFFF0F9FF);

  // Status & Alerts
  static const Color success = Color(0xFF16A34A);
  static const Color warning = Color(0xFFEA580C);
  static const Color error = Color(0xFFDC2626);

  // Backgrounds & Surface (Calm Botanical Palette)
  static const Color scaffoldBg = Color(0xFFF6F8F5); // Warm botanical off-white
  static const Color lightBg = Color(0xFFF6F8F5); // Alias for scaffoldBg
  static const Color cardBg = Colors.white;
  static const Color cardBorder = Color(0xFFE7ECE7); // Hairline subtle border
  static const Color lightBorder = Color(0xFFE7ECE7); // Alias for cardBorder
  static const Color inputBg = Color(0xFFF8FAFC);

  // Typography Palette
  static const Color textDark = Color(0xFF0F172A);
  static const Color textBody = Color(0xFF334155);
  static const Color textMuted = Color(0xFF64748B);
  static const Color textLight = Color(0xFF94A3B8);
}

class VunothoTheme {
  static ThemeData get lightTheme {
    return ThemeData(
      useMaterial3: true,
      brightness: Brightness.light,
      primaryColor: VunothoColors.primaryDark,
      scaffoldBackgroundColor: VunothoColors.scaffoldBg,
      colorScheme: const ColorScheme.light(
        primary: VunothoColors.primaryDark,
        secondary: VunothoColors.accent,
        surface: VunothoColors.cardBg,
        error: VunothoColors.error,
        onPrimary: Colors.white,
        onSecondary: Colors.white,
      ),
      textTheme: GoogleFonts.plusJakartaSansTextTheme(
        ThemeData.light().textTheme,
      ).copyWith(
        headlineMedium: GoogleFonts.plusJakartaSans(
          fontSize: 22,
          fontWeight: FontWeight.w900,
          color: VunothoColors.textDark,
          letterSpacing: -0.5,
        ),
        titleLarge: GoogleFonts.plusJakartaSans(
          fontSize: 18,
          fontWeight: FontWeight.w800,
          color: VunothoColors.textDark,
          letterSpacing: -0.3,
        ),
        titleMedium: GoogleFonts.plusJakartaSans(
          fontSize: 15,
          fontWeight: FontWeight.w700,
          color: VunothoColors.textDark,
        ),
        bodyMedium: GoogleFonts.plusJakartaSans(
          fontSize: 13,
          fontWeight: FontWeight.w500,
          color: VunothoColors.textBody,
          height: 1.45,
        ),
        bodySmall: GoogleFonts.plusJakartaSans(
          fontSize: 11.5,
          fontWeight: FontWeight.w500,
          color: VunothoColors.textMuted,
        ),
      ),
      appBarTheme: const AppBarTheme(
        backgroundColor: Colors.white,
        elevation: 0,
        scrolledUnderElevation: 0,
        centerTitle: false,
        iconTheme: IconThemeData(color: VunothoColors.textDark),
      ),
      cardTheme: CardThemeData(
        color: VunothoColors.cardBg,
        elevation: 0,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(22),
          side: const BorderSide(color: VunothoColors.cardBorder, width: 1),
        ),
      ),
    );
  }

  // Common Box Shadows for Soft Depth
  static List<BoxShadow> get softShadow => [
    BoxShadow(
      color: Colors.black.withValues(alpha: 0.035),
      blurRadius: 16,
      offset: const Offset(0, 4),
    ),
  ];

  static List<BoxShadow> get diffuseShadow => [
    BoxShadow(
      color: const Color(0xFF143D28).withValues(alpha: 0.08),
      blurRadius: 20,
      offset: const Offset(0, 8),
    ),
  ];
}
