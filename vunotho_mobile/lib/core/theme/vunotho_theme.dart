import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class VunothoColors {
  // Brand Agricultural Primary Palette
  static const Color primary = Color(0xFF047857); // Deep Emerald
  static const Color primaryLight = Color(0xFF10B981); // Bright Emerald
  static const Color primaryDark = Color(0xFF064E3B); // Forest Deep
  static const Color primarySurface = Color(0xFFECFDF5); // Mint Light

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

  // Neutral Backgrounds & Cards (Dark & Light)
  static const Color darkBg = Color(0xFF0F172A);
  static const Color darkCard = Color(0xFF1E293B);
  static const Color darkBorder = Color(0xFF334155);

  static const Color lightBg = Color(0xFFF8FAFC);
  static const Color lightCard = Colors.white;
  static const Color lightBorder = Color(0xFFE2E8F0);
  
  static const Color textDark = Color(0xFF0F172A);
  static const Color textMuted = Color(0xFF64748B);
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
          fontSize: 24,
          fontWeight: FontWeight.bold,
          color: VunothoColors.textDark,
        ),
        titleLarge: GoogleFonts.plusJakartaSans(
          fontSize: 18,
          fontWeight: FontWeight.w700,
          color: VunothoColors.textDark,
        ),
        bodyMedium: GoogleFonts.plusJakartaSans(
          fontSize: 14,
          color: VunothoColors.textDark,
        ),
      ),
      appBarTheme: AppBarTheme(
        elevation: 0,
        backgroundColor: Colors.white,
        surfaceTintColor: Colors.transparent,
        iconTheme: const IconThemeData(color: VunothoColors.textDark),
        titleTextStyle: GoogleFonts.plusJakartaSans(
          fontSize: 18,
          fontWeight: FontWeight.w800,
          color: VunothoColors.textDark,
        ),
      ),
      cardTheme: CardThemeData(
        color: VunothoColors.lightCard,
        elevation: 0,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
          side: const BorderSide(color: VunothoColors.lightBorder, width: 1),
        ),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: VunothoColors.primary,
          foregroundColor: Colors.white,
          elevation: 0,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
          textStyle: GoogleFonts.plusJakartaSans(
            fontSize: 15,
            fontWeight: FontWeight.w700,
          ),
        ),
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: Colors.white,
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: VunothoColors.lightBorder),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: VunothoColors.lightBorder),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: VunothoColors.primary, width: 2),
        ),
      ),
    );
  }
}
