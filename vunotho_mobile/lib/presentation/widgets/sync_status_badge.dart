import 'package:flutter/material.dart';
import '../../core/theme/vunotho_theme.dart';

class SyncStatusBadge extends StatelessWidget {
  final bool isOnline;
  final VoidCallback? onSyncTap;

  const SyncStatusBadge({
    super.key,
    this.isOnline = true,
    this.onSyncTap,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: isOnline ? VunothoColors.primarySurface : const Color(0xFFFEF3C7),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(
          color: isOnline ? const Color(0xFFA7F3D0) : const Color(0xFFFDE68A),
          width: 1,
        ),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Container(
            width: 8,
            height: 8,
            decoration: BoxDecoration(
              color: isOnline ? VunothoColors.success : VunothoColors.warning,
              shape: BoxShape.circle,
            ),
          ),
          const SizedBox(width: 6),
          Text(
            isOnline ? 'Online Synced' : 'Offline Ready',
            style: TextStyle(
              fontSize: 12,
              fontWeight: FontWeight.w700,
              color: isOnline ? VunothoColors.primaryDark : const Color(0xFF92400E),
            ),
          ),
        ],
      ),
    );
  }
}
