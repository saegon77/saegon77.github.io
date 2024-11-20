// Espera a que el DOM esté completamente cargado antes de ejecutar el código
document.addEventListener('DOMContentLoaded', function() {
    // Selecciona la imagen principal
    const mainImage = document.querySelector('.main-image');
    // Selecciona todas las miniaturas
    const thumbnails = document.querySelectorAll('.thumbnail');

    // Itera sobre cada miniatura
    thumbnails.forEach(thumbnail => {
        // Añade un evento de clic a cada miniatura
        thumbnail.addEventListener('click', function() {
            // Guarda la URL de la imagen principal actual
            const mainSrc = mainImage.src;
            // Guarda el valor del atributo data-src de la imagen principal
            const mainDataSrc = mainImage.getAttribute('data-src');

            // Cambia la imagen principal por la miniatura clicada
            mainImage.src = this.src;
            mainImage.setAttribute('data-src', this.getAttribute('data-src'));

            // Cambia la miniatura clicada por la imagen principal anterior
            this.src = mainSrc;
            this.setAttribute('data-src', mainDataSrc);
        });
    });
});