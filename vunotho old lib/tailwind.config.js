/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./*.php",
    "./includes/**/*.php",
    "./src/**/*.{js,ts,jsx,tsx}",
    "./js/**/*.{js,ts}"
  ],
  theme: {
    extend: {
      colors: {
        vunotho: {
          canvas: "#F4F7F6",
          ivory: "#FAF8F5",
          surface: "#FFFFFF",
          surfaceMuted: "#F1F5F9",
          border: "#E2E8F0",
          borderLight: "rgba(226, 232, 240, 0.8)",
          
          // Deep Slate Navy for high-contrast typography and executive badges
          navy: {
            950: "#060D17",
            900: "#0A192F",
            850: "#0F233D",
            800: "#0F2942",
            700: "#1B385C",
            600: "#2A4E7A",
            500: "#3B6D9E",
            100: "#E2E8F0",
            50: "#F8FAFC"
          },
          
          // Lush Forest & Emerald Green
          emerald: {
            900: "#064E3B",
            800: "#065F46",
            700: "#047857",
            600: "#059669",
            500: "#10B981",
            400: "#34D399",
            100: "#D1FAE5",
            50: "#ECFDF5"
          },

          // Warm Golden Amber
          gold: {
            900: "#78350F",
            800: "#92400E",
            700: "#B45309",
            600: "#D97706",
            500: "#F59E0B",
            400: "#FBBF24",
            100: "#FEF3C7",
            50: "#FFFBEB"
          },

          // Sunrise Orange (Logistics)
          orange: {
            700: "#C2410C",
            600: "#EA580C",
            500: "#F97316",
            400: "#FB923C",
            100: "#FFEDD5",
            50: "#FFF7ED"
          },

          // Connectivity Teal (Value Recovery & Infrastructure)
          teal: {
            800: "#115E59",
            700: "#0F766E",
            600: "#0D9488",
            500: "#14B8A6",
            400: "#2DD4BF",
            100: "#CCFBF1",
            50: "#F0FDFA"
          }
        }
      },
      fontFamily: {
        sans: ["'Plus Jakarta Sans'", "system-ui", "-apple-system", "BlinkMacSystemFont", "sans-serif"],
        mono: ["'JetBrains Mono'", "monospace"]
      },
      boxShadow: {
        'warm-sm': '0 1px 3px rgba(15, 41, 66, 0.04), 0 1px 2px rgba(15, 41, 66, 0.02)',
        'warm-md': '0 4px 12px -1px rgba(15, 41, 66, 0.06), 0 2px 6px -1px rgba(15, 41, 66, 0.03)',
        'warm-lg': '0 10px 25px -3px rgba(15, 41, 66, 0.08), 0 4px 10px -2px rgba(15, 41, 66, 0.04)',
        'warm-xl': '0 20px 35px -5px rgba(15, 41, 66, 0.10), 0 10px 15px -5px rgba(15, 41, 66, 0.05)',
        'glow-emerald': '0 0 20px -3px rgba(16, 185, 129, 0.35)',
        'glow-gold': '0 0 20px -3px rgba(245, 158, 11, 0.35)',
        'glow-orange': '0 0 20px -3px rgba(249, 115, 22, 0.35)'
      },
      animation: {
        'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
        'ticker-scroll': 'tickerScroll 35s linear infinite'
      },
      keyframes: {
        pulseGlow: {
          '0%, 100%': { opacity: '1', transform: 'scale(1)' },
          '50%': { opacity: '0.7', transform: 'scale(1.03)' }
        },
        tickerScroll: {
          '0%': { transform: 'translateX(0%)' },
          '100%': { transform: 'translateX(-50%)' }
        }
      }
    }
  },
  plugins: []
}
