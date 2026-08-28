class SupabaseConfig {
  static const String url = 'https://phbnfclsdkgkrmvevxtc.supabase.co';
  // Standard Supabase public anon key (or default placeholder)
  static const String anonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InBoYm5mY2xzZGtna3JtdmV2eHRjIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MDkzNDg4MDAsImV4cCI6MjAyNDkyNDgwMH0.placeholder';
  
  // Agricultural districts in Zimbabwe
  static const List<String> districts = [
    'Nyanga',
    'Mutasa',
    'Chipinge',
    'Chimanimani',
    'Makoni',
    'Mutare',
    'Buhera',
    'Goromonzi',
    'Marondera',
    'Harare',
    'Bulawayo',
  ];

  // Common crops for smallholder production
  static const List<String> crops = [
    'Butternut Squash',
    'Sugar Beans',
    'Tomatoes',
    'Maize (Green)',
    'Cabbage',
    'Onions',
    'Potatoes',
    'Avocados',
    'Macadamia Nuts',
    'Bananas',
    'Chili Peppers',
  ];

  // Quality Grades
  static const List<String> qualityGrades = [
    'Grade A (Export / Retail)',
    'Grade B (Processing / Puree)',
    'Grade C (Animal Feed / Extract)',
  ];
}
