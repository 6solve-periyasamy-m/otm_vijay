/** @type {import('tailwindcss').Config} */
module.exports = {
  corePlugins: {
    preflight: false,
  },
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./vendor/wire-elements/modal/resources/**/*.blade.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
