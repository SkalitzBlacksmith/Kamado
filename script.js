const galleryImage = document.querySelector('#gallery-image');
document.querySelectorAll('[data-image]').forEach((button) => {
    button.addEventListener('click', () => {
        galleryImage.src = button.dataset.image;
        galleryImage.alt = button.dataset.alt;
        document.querySelectorAll('[data-image]').forEach((item) => item.setAttribute('aria-pressed', String(item === button)));
    });
});

const menu = document.querySelector('.menu-toggle');
const nav = document.querySelector('#main-nav');
if (menu && nav) {
    menu.addEventListener('click', () => {
        const open = menu.getAttribute('aria-expanded') === 'true';
        menu.setAttribute('aria-expanded', String(!open));
        nav.classList.toggle('open', !open);
    });
}
