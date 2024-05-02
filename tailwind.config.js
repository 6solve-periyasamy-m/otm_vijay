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
    extend: {
      colors: {
        'modal-overlay-bg': 'rgba(128, 128, 128, 0.4)',
      },
    },
  },
  plugins: [],
}
