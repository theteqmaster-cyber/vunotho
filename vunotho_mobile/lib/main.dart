import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:supabase_flutter/supabase_flutter.dart';
import 'core/constants/supabase_config.dart';
import 'core/theme/vunotho_theme.dart';
import 'data/services/vunotho_repository.dart';
import 'logic/providers/auth_provider.dart';
import 'logic/providers/demand_provider.dart';
import 'logic/providers/listing_provider.dart';
import 'logic/providers/transport_provider.dart';
import 'presentation/auth/splash_screen.dart';
import 'presentation/main_shell.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  SupabaseClient? supabaseClient;
  try {
    await Supabase.initialize(
      url: SupabaseConfig.url,
      // ignore: deprecated_member_use
      anonKey: SupabaseConfig.anonKey,
    );
    supabaseClient = Supabase.instance.client;
  } catch (e) {
    debugPrint('Supabase offline initialization fallback: $e');
  }

  runApp(VunothoApp(supabaseClient: supabaseClient));
}

class VunothoApp extends StatelessWidget {
  final SupabaseClient? supabaseClient;

  const VunothoApp({super.key, this.supabaseClient});

  @override
  Widget build(BuildContext context) {
    final repository = VunothoRepository(supabaseClient);

    return MultiProvider(
      providers: [
        Provider<VunothoRepository>.value(value: repository),
        ChangeNotifierProvider<AuthProvider>(create: (_) => AuthProvider()),
        ChangeNotifierProvider<ListingProvider>(create: (_) => ListingProvider(repository)),
        ChangeNotifierProvider<DemandProvider>(create: (_) => DemandProvider(repository)),
        ChangeNotifierProvider<TransportProvider>(create: (_) => TransportProvider(repository)),
      ],
      child: MaterialApp(
        title: 'Vunotho - Agricultural OS',
        debugShowCheckedModeBanner: false,
        theme: VunothoTheme.lightTheme,
        home: Consumer<AuthProvider>(
          builder: (context, auth, _) {
            if (auth.isAuthenticated) {
              return const MainShell();
            }
            return const SplashScreen();
          },
        ),
      ),
    );
  }
}
