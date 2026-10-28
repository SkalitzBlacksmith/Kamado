const galleryImage = document.querySelector('#gallery-image');
const galleryButtons = [...document.querySelectorAll('[data-image]')];
const galleryStatus = document.querySelector('#gallery-status');

function selectImage(button) {
    galleryImage.src = button.dataset.image;
    galleryImage.alt = button.dataset.alt;
    galleryButtons.forEach((item) => item.setAttribute('aria-pressed', String(item === button)));
    galleryStatus.textContent = (galleryButtons.indexOf(button) + 1) + ' / ' + galleryButtons.length + ' · ' + button.dataset.alt;
}

if (galleryImage) {
    galleryButtons.forEach((button, index) => {
        button.addEventListener('click', () => selectImage(button));
        button.addEventListener('keydown', (event) => {
            if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
            event.preventDefault();
            let next = index;
            if (event.key === 'ArrowLeft') next = (index - 1 + galleryButtons.length) % galleryButtons.length;
            if (event.key === 'ArrowRight') next = (index + 1) % galleryButtons.length;
            if (event.key === 'Home') next = 0;
            if (event.key === 'End') next = galleryButtons.length - 1;
            galleryButtons[next].focus();
            selectImage(galleryButtons[next]);
        });
    });
}

const menu = document.querySelector('.menu-toggle');
const nav = document.querySelector('#main-nav');
if (menu && nav) {
    function closeMenu() {
        menu.setAttribute('aria-expanded', 'false');
        nav.classList.remove('open');
    }

    menu.addEventListener('click', () => {
        const open = menu.getAttribute('aria-expanded') === 'true';
        menu.setAttribute('aria-expanded', String(!open));
        nav.classList.toggle('open', !open);
    });
    nav.addEventListener('click', (event) => {
        if (event.target.closest('a')) closeMenu();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && nav.classList.contains('open')) {
            closeMenu();
            menu.focus();
        }
    });
    window.matchMedia('(min-width: 701px)').addEventListener('change', closeMenu);
}
