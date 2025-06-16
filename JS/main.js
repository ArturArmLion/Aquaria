document.addEventListener('DOMContentLoaded', () => {
    const categories = document.querySelectorAll('.category');

    categories.forEach(cat => {
        cat.addEventListener('click', () => {
            const id = cat.dataset.id;

            fetch(`php/get_products.php?category_id=${id}`)
                .then(res => res.json())
                .then(products => {
                    const container = document.getElementById('product-container');
                    container.innerHTML = '';

                    if (products.length === 0) {
                        container.innerHTML = '<p>Товары не найдены.</p>';
                        return;
                    }

                    products.forEach(p => {
                        const productDiv = document.createElement('div');
                        productDiv.className = 'product';

                        productDiv.innerHTML = `
                            <div class="product-image-wrapper">
                                <img src="images/products/${p.image}" alt="${escapeHtml(p.name)}">
                                <div class="product-overlay">
                                    <p>${escapeHtml(p.description)}</p>
                                </div>
                            </div>
                            <b>${escapeHtml(p.name)}</b>
                            <p>${parseFloat(p.price).toFixed(2)} руб.</p>
                        `;

                        // Кнопка "Купить"
                        const buyBtn = document.createElement('button');
                        buyBtn.className = 'buy-btn';
                        buyBtn.textContent = 'Купить';
                        buyBtn.dataset.id = p.id;
                        buyBtn.dataset.name = p.name;
                        buyBtn.dataset.price = p.price;

                        buyBtn.addEventListener('click', () => {
                            fetch('php/add_to_cart.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'product_id=' + encodeURIComponent(p.id) + '&quantity=1'
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'ok') {
                                    alert('Товар добавлен в корзину!');
                                    updateCartCount();
                                } else {
                                    alert('Ошибка при добавлении товара.');
                                }
                            });
                        });

                        // Кнопка "Избранное"
                        const favBtn = document.createElement('button');
                        favBtn.className = 'favorite-bottom';
                        favBtn.innerHTML = '❤ Избранное';
                        favBtn.dataset.id = p.id;

                        favBtn.addEventListener('click', () => {
                            fetch('php/add_to_favorites.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: 'product_id=' + encodeURIComponent(p.id)
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'ok') {
                                    alert('Товар добавлен в избранное!');
                                    favBtn.classList.add('active');
                                } else if (data.status === 'exists') {
                                    alert('Товар уже в избранном');
                                    favBtn.classList.add('active');
                                } else {
                                    alert('Ошибка при добавлении в избранное');
                                }
                            });
                        });

                        // Обёртка для кнопок
                        const buttonsWrapper = document.createElement('div');
                        buttonsWrapper.className = 'product-buttons';
                        buttonsWrapper.appendChild(buyBtn);
                        buttonsWrapper.appendChild(favBtn);

                        productDiv.appendChild(buttonsWrapper);
                        container.appendChild(productDiv);
                    });
                });
        });
    });

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function updateCartCount() {
        fetch('php/cart_count.php')
            .then(res => res.json())
            .then(data => {
                const count = data.count || 0;
                const el = document.getElementById('cart-count');
                if (el) {
                    el.textContent = count;
                    el.style.display = count > 0 ? 'inline-block' : 'none';
                }
            });
    }

    const catalogButton = document.querySelector('.feature-main button');
    if (catalogButton) {
        catalogButton.addEventListener('click', () => {
            window.scrollTo({
                top: document.querySelector('.catalog-grid').offsetTop,
                behavior: 'smooth'
            });
        });
    }

    updateCartCount();
});
