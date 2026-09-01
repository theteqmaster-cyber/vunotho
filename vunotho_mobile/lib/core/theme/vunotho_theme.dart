import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class VunothoColors {
  // Brand Agricultural Primary Palette
  static const Color primary = Color(0xFF15803D); // Vivid Emerald Green
  static const Color primaryDark = Color(0xFF0A2E1D); // Deep Forest Green
  static const Color primaryDeep = Color(0xFF064E3B); // Ultra Deep Emerald
  static const Color primaryLight = Color(0xFF22C55E); // Bright Green
  static const Color primarySurface = Color(0xFFECFDF5); // Mint Light Surface

  // Accent Golden Harvest & Citrus
  static const Color accent = Color(0xFFD97706); // Golden Amber
  static const Color accentLight = Color(0xFFFBBF24); // Warm Gold
  static const Color accentSurface = Color(0xFFFFFBEB); // Warm Gold Surface

  // Secondary Sky & Logistics
  static const Color logistics = Color(0xFF0284C7); // Sky Blue
  static const Color logisticsSurface = Color(0xFFF0F9FF);

  // Status Colors
  static const Color success = Color(0xFF16A34A);
  static const Color warning = Color(0xFFEA580C);
  static const Color error = Color(0xFFDC2626);
  static const Color info = Color(0xFF2563EB);

  // Neutral Backgrounds & Cards (Botanical Theme)
  static const Color darkBg = Color(0xFF071726);
  static const Color darkCard = Color(0xFF0F2438);
  static const Color darkBorder = Color(0xFF1E3A52);

  static const Color lightBg = Color(0xFFF0FDF4); // Calm Botanical Green Canvas
  static const Color lightCard = Colors.white;
  static const Color lightBorder = Color(0xFFDCFCE7); // Soft Mint Border
  
  static const Color textDark = Color(0xFF0F172A);
  static const Color textMuted = Color(0xFF475569);
  static const Color textLight = Color(0xFF64748B);
}

class VunothoTheme {
  static ThemeData get lightTheme {
    return ThemeData(
      useMaterial3: true,
      brightness: Brightness.light,
      primaryColor: VunothoColors.primary,
      scaffoldBackgroundColor: VunothoColors.lightBg,
      colorScheme: const ColorScheme.light(
        primary: VunothoColors.primary,
        secondary: VunothoColors.accent,
        surface: VunothoColors.lightCard,
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
        ),
        titleMedium: GoogleFonts.plusJakartaSans(
          fontSize: 15,
          fontWeight: FontWeight.w700,
          color: VunothoColors.textDark,
        ),
        bodyMedium: GoogleFonts.plusJakartaSans(
          fontSize: 13,
          fontWeight: FontWeight.w500,
          color: VunothoColors.textMuted,
          height: 1.45,
        ),
      ),
      appBarTheme: const AppBarTheme(
        backgroundColor: Colors.white,
        elevation: 0,
        scrolledUnderElevation: 1,
        centerTitle: false,
        iconTheme: IconThemeData(color: VunothoColors.textDark),
      ),
      cardTheme: CardThemeData(
        color: VunothoColors.lightCard,
        elevation: 0,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(20),
          side: const BorderSide(color: VunothoColors.lightBorder, width: 1),
        ),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: VunothoColors.primaryDark,
          foregroundColor: Colors.white,
          elevation: 2,
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 14),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(9999),
          ),
          textStyle: GoogleFonts.plusJakartaSans(
            fontSize: 14,
            fontWeight: FontWeight.w800,
          ),
        ),
      ),
    );
  }
}
