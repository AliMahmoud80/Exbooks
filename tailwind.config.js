const colors = require('tailwindcss/colors')

module.exports = {
  mode: 'jit',
  purge: [
    './storage/framework/views/*.php',
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      colors: {
        primary: colors.blue,
        secondary: '#00dfd8',
        // 'black-primary': '#070707',
        'black-primary': '#15181c',
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
