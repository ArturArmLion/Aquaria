document.addEventListener('DOMContentLoaded', () => {
    const cartData = sessionStorage.getItem('cart');

    if (cartData) {
        fetch('cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'cartData=' + encodeURIComponent(cartData)
        }).then(res => res.json()).then(res => {
            console.log('Cart synced with PHP session');
            location.reload(); // обновим, чтобы отобразились товары
        });
    }
});
