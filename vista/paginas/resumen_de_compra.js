// Obtener los productos del carrito desde el localStorage
let cartProducts = JSON.parse(localStorage.getItem('cartProducts')) || [];

// Seleccionar elementos del DOM donde mostrar los productos
const rowProductList = document.querySelector('.row-product-list');
const totalCompra = document.querySelector('.total-compra');

// Mostrar los productos en la página
const showProductsInSummary = () => {
    rowProductList.innerHTML = ''; // Limpiar el contenedor antes de volver a mostrar los productos
    if (!cartProducts.length) {
        rowProductList.innerHTML = '<p>No hay productos en el carrito.</p>';
        totalCompra.innerText = formatCurrency(0);
        return;
    }

    let total = 0;

    // Iterar sobre los productos del carrito y crear el HTML
    cartProducts.forEach((product, index) => {
        const price = parseFloat(product.price.replace('$', '').replace(/\./g, '').replace(',', '.'));
        const productTotal = price * product.quantity;

        // Crear un contenedor para cada producto
        const productElement = document.createElement('div');
        productElement.classList.add('cart-product');

        productElement.innerHTML = `
            <div class="info-cart-product">
                <span class="cantidad-producto-carrito">${product.quantity}</span>
                <p class="titulo-producto-carrito">${product.title}</p>
                <span class="precio-producto-carrito">${formatCurrency(productTotal)}</span>
            </div>
            <button class="delete-product" data-index="${index}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </button>
        `;

        rowProductList.appendChild(productElement);
        total += productTotal;
    });

    // Mostrar el total de la compra
    totalCompra.innerText = formatCurrency(total);
};

// Formatear el precio en pesos colombianos
const formatCurrency = (value) => {
    return value.toLocaleString('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
};

// Función para eliminar un producto del carrito
const removeProduct = (index) => {
    cartProducts.splice(index, 1);
    localStorage.setItem('cartProducts', JSON.stringify(cartProducts));
    showProductsInSummary();
};

// Inicializar la vista de productos
showProductsInSummary();

// Agregar event listener para los botones de eliminar
rowProductList.addEventListener('click', (e) => {
    if (e.target.closest('.delete-product')) {
        const index = e.target.closest('.delete-product').getAttribute('data-index');
        removeProduct(parseInt(index));
    }
});

// Funcionalidad del sistema de calificación por estrellas
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    let selectedRating = 0;

    stars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const hoverValue = this.getAttribute('data-value');
            highlightStars(hoverValue);
        });

        star.addEventListener('mouseout', function() {
            highlightStars(selectedRating);
        });

        star.addEventListener('click', function() {
            selectedRating = this.getAttribute('data-value');
            highlightStars(selectedRating);
            
            // Aquí puedes añadir código para guardar la calificación si lo necesitas
            console.log(`Calificación seleccionada: ${selectedRating}`);
        });
    });

    function highlightStars(rating) {
        stars.forEach(s => {
            s.classList.toggle('active', s.getAttribute('data-value') <= rating);
        });
    }

    // Funcionalidad para seleccionar método de pago
    const paymentOptions = document.querySelectorAll('.payment-option');
    let selectedPaymentMethod = null;

    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Deseleccionar la opción previamente seleccionada
            if (selectedPaymentMethod) {
                selectedPaymentMethod.classList.remove('selected');
            }

            // Seleccionar la nueva opción
            this.classList.add('selected');
            selectedPaymentMethod = this;

            // Aquí puedes añadir código adicional para manejar la selección del método de pago
            console.log(`Método de pago seleccionado: ${this.getAttribute('data-method')}`);
        });
    });
});