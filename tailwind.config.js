/** @type {import('tailwindcss').Config} */
module.exports = {
  corePlugins: {
    preflight: false,
  },
  content: [
    //"./resources/**/*.blade.php",
    //"./resources/**/*.js",
    //"./resources/**/*.vue",
    "./vendor/wire-elements/modal/resources/**/*.blade.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
