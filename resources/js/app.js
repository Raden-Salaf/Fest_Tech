import './bootstrap';

const menuToggle = document.getElementById('menu-toggle');
const navMenu = document.getElementById('nav-menu');

const line1 = document.getElementById('line-1');
const line2 = document.getElementById('line-2');
const line3 = document.getElementById('line-3');

if (menuToggle && navMenu && line1 && line2 && line3) {
    menuToggle.addEventListener('click', () => {
        // Buka atau tutup menu
        navMenu.classList.toggle('hidden');

        // Periksa kondisi menu
        const isOpen = !navMenu.classList.contains('hidden');

        // Animasi garis pertama
        line1.classList.toggle('translate-y-1.5', isOpen);
        line1.classList.toggle('rotate-45', isOpen);

        // Sembunyikan garis tengah
        line2.classList.toggle('opacity-0', isOpen);

        // Animasi garis ketiga
        line3.classList.toggle('-translate-y-1.5', isOpen);
        line3.classList.toggle('-rotate-45', isOpen);

        // Perbarui status aksesibilitas
        menuToggle.setAttribute('aria-expanded', isOpen);
    });
}
