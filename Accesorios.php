<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Cap</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #d3c9a3;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #064214;
            font-size: 50px;
            margin: 0;
        }

        .header nav a {
            font-family: cursive;
            font-size: larger;
            margin: 0 10px;
            color: #333;
            text-decoration: dashed;
            padding: 75px;
        }
        
        .oferta {
             color: #064214;
             font-weight: bold;
        }

        .header .cart-button {
            background-color: #064214;
            color: white;
            padding: 5px 15px;
            border-radius: 5px;
        }

        .product-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .product {
            background-color: white;
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .product img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .product h2 {
            font-size: 18px;
            color: black;
            margin: 10px 0;
        }

        .product p {
            font-size: 16px;
            color: green;
            font-weight: bold;
        }

        .product span {
            font-size: 14px;
            color: #555;
        }

        .product button {
            background-color: #064214;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .product button:hover {
            background-color: #043012;
        }

        .cart {
            margin: 20px;
            padding: 10px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Real Cap</h1>
        <nav>
            <a href="Gorra.php"><span class="oferta">>Gorras</span></a>
            <a href="Accesorios.html">Accesorios</a>
            <a href="proyecto.html">Quienes somos</a>
            <a href="miperfil.php">Mi perfil</a>
            <a href="sem.php">Cerrar sesión</a>
        </nav>
        <button class="cart-button" onclick="viewCart()">Carrito</button>
    </header>

    <div class="product-container" id="product-list">
        <!-- Aquí se generan dinámicamente los productos -->
    </div>

    <div class="cart" id="cart-container" style="display: none;">
        <h2>Carrito de Compras</h2>
        <ul id="cart-items"></ul>
        <p id="total-price">Total: $0.00</p>
    </div>

    <script>
        // Productos (simulación de datos)
        const products = [
            { name: "Collar de perlas", price: 2.99, image: "https://cdn1.coppel.com/images/catalog/mkp/6318/3000/63182483-1.jpg", description: "collar" },
            { name: "Golden&Black", price: 10.99, image: "https://cdn-media.glamira.com/media/product/newgeneration/view/1/sku/15008oxiniv/alloycolour/yellow/accent/black/bead/cabochon-Onyx.jpg", description: "Pulsera" },
            { name: "Esclava de oro", price: 29.99, image: "https://cdn-media.glamira.com/media/product/newgeneration/view/1/sku/denouer-b/alloycolour/yellow.jpg", description: "Pulsera" },
            { name: "Anillo gris", price: 2.99, image: "https://cdn-media.glamira.com/media/product/newgeneration/view/1/sku/MEN24/diamond/blackdiamond_AAA/alloycolour/white.jpg", description: "Anillo" },
            { name: "Carabelas", price: 4.99, image: "//www.mundodeportivo.com/elrecomendador/comparativas/wp-content/uploads/2023/04/B00CXYY81S.jpg", description: "Anillo" },
            { name: "VVS DIAMONDS", price: 1500.00, image: "https://http2.mlstatic.com/D_NQ_NP_681644-MLM74122846336_012024-O.webp", description: "Anillo" },
            { name: "Collar de diamantes", price: 2500.00, image: "https://www.clemenciaperis.com/wp-content/uploads/2023/08/collar-oro-blanco-18k-con-94-diamantes-cll013-1z.jpg", description: "Collar" },
            { name: "ICE", price: 99.99, image: "https://m.media-amazon.com/images/I/51HXIvup+WL._AC_UY1000_.jpg", description: "Collar" },
            { name: "Diamante Blanco", price: 3000.00, image: "https://cdn-media.glamira.com/media/product/newgeneration/view/1/sku/clasia-b/diamond/diamond-Brillant_AAA/alloycolour/white.jpg", description: "Pulsera" },
            { name: "Ojo turco", price: 9.99, image: "https://http2.mlstatic.com/D_NQ_NP_811939-MLM51780035751_092022-O.webp", description: "Pulsera" },
            { name: "P. madera", price: 12.99, image: "https://s.alicdn.com/@sc04/kf/Hc52edd5339ef4f3ea5e2b6c109be7e6ac.jpg_720x720q50.jpg", description: "Pulsera" },
            { name: "Vintage Love", price: 12.99, image: "https://belcorpperu.vtexassets.com/arquivos/ids/306670/210105850-fotoproductoenfondoblanco.jpg?v=638525904433500000", description: "Pulsera" },

        ];

        // Carrito
        const cart = [];

        // Cargar productos en el DOM
        const productList = document.getElementById('product-list');
        products.forEach((product, index) => {
            const productDiv = document.createElement('div');
            productDiv.classList.add('product');
            productDiv.innerHTML = `
                <img src="${product.image}" alt="${product.name}">
                <h2>${product.name}</h2>
                <p>$${product.price.toFixed(2)}</p>
                <span>${product.description}</span>
                <button onclick="addToCart(${index})">Agregar al carrito</button>
            `;
            productList.appendChild(productDiv);
        });

        // Agregar producto al carrito
        function addToCart(index) {
            cart.push(products[index]);
            alert(`${products[index].name} agregado al carrito`);
            updateCart();
        }

        // Actualizar carrito
        function updateCart() {
            const cartItems = document.getElementById('cart-items');
            const totalPrice = document.getElementById('total-price');
            cartItems.innerHTML = '';
            let total = 0;
            cart.forEach(item => {
                const li = document.createElement('li');
                li.textContent = `${item.name} - $${item.price.toFixed(2)}`;
                cartItems.appendChild(li);
                total += item.price;
            });
            totalPrice.textContent = `Total: $${total.toFixed(2)}`;
        }

        // Mostrar carrito
        function viewCart() {
            const cartContainer = document.getElementById('cart-container');
            cartContainer.style.display = cartContainer.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>