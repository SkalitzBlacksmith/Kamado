const galleryImage = document.querySelector('#gallery-image');
document.querySelectorAll('[data-image]').forEach((button) => {
    button.addEventListener('click', () => {
        galleryImage.src = button.dataset.image;
        galleryImage.alt = button.dataset.alt;
        document.querySelectorAll('[data-image]').forEach((item) => item.setAttribute('aria-pressed', String(item === button)));
    });
});
