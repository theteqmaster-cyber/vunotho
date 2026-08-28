import 'package:flutter_test/flutter_test.dart';
import 'package:vunotho_mobile/main.dart';

void main() {
  testWidgets('VunothoApp smoke test', (WidgetTester tester) async {
    await tester.pumpWidget(const VunothoApp());
    expect(find.text('VUNOTHO'), findsOneWidget);
  });
}
