document.addEventListener('DOMContentLoaded', () => {
    // Pøidání funkcionality pro karusel
    let index = 0;
    const slides = document.querySelectorAll('.carousel img');
    const showSlide = () => {
        slides.forEach((slide, i) => {
            slide.style.display = i === index ? 'block' : 'none';
        });
        index = (index + 1) % slides.length;
    };
    setInterval(showSlide, 3000);

    // Pøidání dalších interaktivních funkcí
});
