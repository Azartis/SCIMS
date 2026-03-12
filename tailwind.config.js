import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    
    darkMode: 'class',

    theme: {
        extend: {
            colors: {
                // Navy & Gold Brand Colors
                navy: {
                    50: '#f0f4f8',
                    100: '#e0e9f0',
                    200: '#c1d3e0',
                    300: '#a2bcd0',
                    400: '#5390b9',
                    500: '#003366',
                    600: '#001f3f',
                    700: '#000d1a',
                    800: '#000a12',
                    900: '#000608',
                },
                gold: {
                    50: '#fef9f0',
                    100: '#fef3e0',
                    200: '#fde8c1',
                    300: '#fcdca3',
                    400: '#d4af37',
                    500: '#c9a227',
                    600: '#b8941f',
                    700: '#9d7d1a',
                    800: '#826613',
                    900: '#66520d',
                },
                // Semantic Colors
                success: '#27AE60',
                warning: '#F39C12',
                danger: '#E74C3C',
                info: '#2980B9',
                'deceased': '#C0392B',
            },
            spacing: {
                xs: '4px',
                sm: '8px',
                md: '16px',
                lg: '24px',
                xl: '32px',
                '2xl': '48px',
                '3xl': '64px',
            },
            fontSize: {
                'display': '48px',
                'xl': '24px',
                'lg': '18px',
                'base': '16px',
                'sm': '14px',
                'xs': '12px',
                'caption': '11px',
            },
            lineHeight: {
                'tight': '1.2',
                'normal': '1.6',
                'code': '1.4',
            },
            borderRadius: {
                'none': '0',
                'sm': '4px',
                'DEFAULT': '8px',
                'md': '8px',
                'lg': '12px',
                'xl': '16px',
                'full': '9999px',
            },
            boxShadow: {
                'xs': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                'sm': '0 2px 4px rgba(0,0,0,0.08)',
                'md': '0 4px 12px rgba(0,0,0,0.1)',
                'lg': '0 8px 24px rgba(0,0,0,0.15)',
                'xl': '0 10px 32px rgba(0,0,0,0.2)',
                'focus': '0 0 0 3px rgba(0,31,63,0.1)',
            },
            backgroundImage: {
                'gradient-navy-gold': 'linear-gradient(135deg, #001F3F 0%, #003366 50%, #D4AF37 100%)',
                'gradient-navy': 'linear-gradient(135deg, #001F3F 0%, #003366 100%)',
                'gradient-gold': 'linear-gradient(135deg, #D4AF37 0%, #c9a227 100%)',
            },
            fontFamily: {
                sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', ...defaultTheme.fontFamily.sans],
            },
            animation: {
                'pulse-subtle': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'slide-in': 'slideIn 0.3s ease-out',
                'fade-in': 'fadeIn 0.3s ease-out',
            },
            keyframes: {
                slideIn: {
                    '0%': { transform: 'translateY(-10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
            },
        },
    },

    plugins: [forms],
};
