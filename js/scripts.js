document.addEventListener('DOMContentLoaded', () => {
    // P�id�n� funkcionality pro karusel
    let index = 0;
    const slides = document.querySelectorAll('.carousel img');
    const showSlide = () => {
        slides.forEach((slide, i) => {
            slide.style.display = i === index ? 'block' : 'none';
        });
        index = (index + 1) % slides.length;
    };
    setInterval(showSlide, 3000);

    // P�id�n� dal��ch interaktivn�ch funkc�
});
