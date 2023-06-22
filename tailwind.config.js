/** @type {import('tailwindcss').Config} */
module.exports = {
  corePlugins: {
    preflight: true,
  },
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./vendor/wire-elements/modal/resources/**/*.blade.php",
    "./vendor/mediconesystems/livewire-datatables/resources/**/*.blade.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
