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
            <a href="Accesorios.php">Accesorios</a>
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
            { name: "Gorra Jean", price: 12.99, image: "https://deportesjimmy.com/wp-content/uploads/2024/09/a8ba21bf7c782e509af66219f86ae20d.jpg", description: "Levi's cap" },
            { name: "Gorra Yankees", price: 12.99, image: "https://images.footballfanatics.com/new-york-yankees/mens-47-navy-new-york-yankees-franchise-logo-fitted-hat_ss5_p-200074869+pv-1+u-smvunegrzgwatr8c2dr1+v-49rcqpgdwkhkel7sxbu0.jpg?_hv=2&w=900/250x150", description: "Yankees cap" },
            { name: "Gorra LA Dodger", price: 12.99, image: "https://2cap.com.mx/media/catalog/product/cache/869e691dd3a6f90c9125c46ae219b2a0/1/9/193234781231-1.jpg", description: "New Era LA Dodger cap" },
            { name: "Gorra Yankees 42", price: 75.00, image: "https://img.sombreroshop.es/Gorra-9Fifty-Yankees-Essential-by-New-Era.67070_pf47.jpg", description: "Tribute to Mariano Rivera" },
            { name: "Gorra Boston Red Sox", price: 13.99, image: "https://images.footballfanatics.com/boston-red-sox/boston-red-sox-new-era-authentic-on-field-59fifty-fitted-cap_ss4_p-11882896+u-1s82lt1605vw1ab0160h+v-63c5a0f54b01415e8ed0b0bdcdf47a4a.jpg?_hv=2", description: "Boston Red Sox authentic cap" },
            { name: "Gorra Chicago Bulls", price: 19.99, image: "https://neweracap.pe/181882-home_default/Gorra-9FIFTY-Chicago-Bulls-.jpg", description: "NBA Bulls snapback" },
            { name: "Gorra Golden State Warriors", price: 21.99, image: "https://newera.com.ar/media/catalog/product/cache/06cfaa02c67cf3a5c3c05d775284c631/g/o/gorra-new-era-golden-state-warriors-59fifty-citrus-pop-60288269-1_1.jpg", description: "Golden State Warriors NBA cap" },
            { name: "Gorra Superman", price: 14.99, image: "https://newera.com.ar/media/catalog/product/cache/06cfaa02c67cf3a5c3c05d775284c631/g/o/gorra-new-era-character-logo-9forty-superman-60222455-1-min_1.jpg", description: "Superman snapback" },
            { name: "Gorra Spider-Man", price: 14.99, image: "https://m.media-amazon.com/images/I/51PACsXedNL._AC_UY1000_.jpg", description: "Spider-Man cap for fans" },
            { name: "Gorra Marvel Avengers", price: 24.99, image: "https://otocaps.co/wp-content/uploads/2023/02/GORRA-AVENGER_TRES-CUARTOS.jpg", description: "Marvel's Avengers cap" },
            { name: "Gorra Los Angeles Lakers", price: 22.99, image: "https://i.ebayimg.com/thumbs/images/g/jz0AAOSwVFdj2F0M/s-l1200.jpg", description: "Official Lakers cap" },
            { name: "Gorra FC Barcelona", price: 25.99, image: "https://m.media-amazon.com/images/I/71iE7C7KJEL._AC_UY580_.jpg", description: "FC Barcelona snapback" },
            { name: "Gorra Real Madrid", price: 23.99, image: "https://totalsport.pe/wp-content/uploads/RMSB522202-GRY-1.jpg", description: "Real Madrid classic cap" },
            { name: "Gorra Iron Man", price: 15.99, image: "https://neweracap.pe/181882-home_default/Gorra-9FORTY-Iron-Man-Marvel.jpg", description: "Iron Man cap for Marvel fans" },
            { name: "Gorra Batman", price: 16.99, image: "https://kingmonster.com/cdn/shop/products/18---BATMAN_9b0b2f52-0ea2-44e3-96e5-712f81d6dbc3_1024x1024.jpg?v=1572976284", description: "Batman dark cap" },
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
