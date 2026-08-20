/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        'blue-25': '#fbfdff',
        'blue-50': '#f4faff',
        'blue-100': '#e5f3ff',
        'blue-200': '#c9e6fb',
        'blue-500': '#3588d4',
        'blue-700': '#15558e',
        ink: '#18324a',
        muted: '#6c8295',
        line: '#dce8f0',
        'green-100': '#d8f2e8',
        green: '#188a68',
        yellow: '#f2b84b',
        red: '#b45b5b',
      },
    },
  },
  plugins: [],
}