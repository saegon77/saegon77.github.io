// Seleccionar elementos del DOM
const btnCart = document.querySelector('.container-cart-icon');
const containerCartProducts = document.querySelector('.container-cart-products');

// Evento para mostrar/ocultar el carrito al hacer clic en el icono
btnCart.addEventListener('click', () => {
    containerCartProducts.classList.toggle('hidden-cart');
});

// Seleccionar más elementos del DOM
const cartInfo = document.querySelector('.cart-product');
const rowProduct = document.querySelector('.row-product');
const productsList = document.querySelector('.container-items');
const valorTotal = document.querySelector('.total-pagar');
const countProducts = document.querySelector('#contador-productos');
const cartEmpty = document.querySelector('.cart-empty');
const cartTotal = document.querySelector('.cart-total');

// Array para almacenar todos los productos en el carrito
let allProducts = [];

// Evento para añadir productos al carrito
productsList.addEventListener('click', e => {
    if (e.target.classList.contains('btn-add-cart')) {
        const product = e.target.parentElement;

        // Crear objeto con la información del producto
        const infoProduct = {
            quantity: 1,
            title: product.querySelector('h2').textContent,
            price: product.querySelector('.price').textContent,
        };

        // Verificar si el producto ya existe en el carrito
        const exists = allProducts.some(
            product => product.title === infoProduct.title
        );

        if (exists) {
            // Si existe, aumentar la cantidad
            const products = allProducts.map(product => {
                if (product.title === infoProduct.title) {
                    product.quantity++;
                    return product;
                } else {
                    return product;
                }
            });
            allProducts = [...products];
        } else {
            // Si no existe, añadir el nuevo producto
            allProducts = [...allProducts, infoProduct];
        }

        // Actualizar la visualización del carrito
        showHTML();
    }
});

// Evento para eliminar productos del carrito
rowProduct.addEventListener('click', e => {
    if (e.target.classList.contains('icon-close')) {
        const product = e.target.closest('.cart-product');
        const title = product.querySelector('.titulo-producto-carrito').textContent;

        // Filtrar el producto del array o reducir su cantidad
        allProducts = allProducts.filter(product => {
            if (product.title === title) {
                if (product.quantity > 1) {
                    product.quantity--;
                    return true;
                }
                return false;
            }
            return true;
        });

        // Actualizar la visualización del carrito
        showHTML();
    }
});

// Función para mostrar los productos en el carrito
const showHTML = () => {
    // Mostrar/ocultar elementos según si hay productos en el carrito
    if (!allProducts.length) {
        cartEmpty.classList.remove('hidden');
        rowProduct.classList.add('hidden');
        cartTotal.classList.add('hidden');
    } else {
        cartEmpty.classList.add('hidden');
        rowProduct.classList.remove('hidden');
        cartTotal.classList.remove('hidden');
    }

    // Limpiar el contenido anterior
    rowProduct.innerHTML = '';

    let total = 0;
    let totalOfProducts = 0;

    // Iterar sobre todos los productos en el carrito
    allProducts.forEach(product => {
        const containerProduct = document.createElement('div');
        containerProduct.classList.add('cart-product');

        // Calcular el precio total para este producto
        const price = parseFloat(product.price.replace('$', '').replace(/\./g, '').replace(',', '.'));
        const productTotal = price * product.quantity;

        // Crear el HTML para cada producto
        containerProduct.innerHTML = `
            <div class="info-cart-product">
                <span class="cantidad-producto-carrito">${product.quantity}</span>
                <p class="titulo-producto-carrito">${product.title}</p>
                <span class="precio-producto-carrito">${formatCurrency(productTotal)}</span>
            </div>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="icon-close"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        `;

        // Añadir el producto al contenedor del carrito
        rowProduct.append(containerProduct);

        // Actualizar totales
        total += productTotal;
        totalOfProducts += product.quantity;
    });

    // Actualizar el total y el contador de productos en el DOM
    valorTotal.innerText = formatCurrency(total);
    countProducts.innerText = totalOfProducts;
};

// Función para formatear el precio en pesos colombianos
const formatCurrency = (value) => {
    return value.toLocaleString('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    });
};

// Inicializar el carrito como vacío
showHTML();