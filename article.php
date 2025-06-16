<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/article.css">
    <title>Aquaria</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/inputmask.min.js"></script>
</head>
<body>

    <!-- HEADER -->

    <header class="header">
    <div class="container">
        <div class="main-menu">
            <ul class="main-menu-top">
                <li class="main-menu__item"><a href="index.php"><img src="images/logo.png" width="200px" alt=""></a></li>
            </ul>
            <ul class="main-menu-top-address">
                <li class="main-menu__item"><img src="images/icon _location_.png" alt=""></li>
                <li class="main-menu__item"><p>ул.Баррикадная д.16</p></li>
            </ul>

            <ul class="main-menu-top-kontakti">
                <li class="main-menu__item"><img src="images/icon _phone_.png" alt=""></li>
                <li class="main-menu__item"><p>8 (777) 777-77-77</p></li>
            </ul>

            <ul class="main-menu-top-reg">
                <li class="main-menu__item"><a href="backend/register.php"><img src="images/icon _person_.png" alt=""></a></li>
                <li class="main-menu__item"><a href="backend/register.php"><p>Войти</p></a></li>
            </ul>

            <ul class="main-menu-top-cart">
                <li class="main-menu__item">
                    <a href="CART/unlog-cart.php" id="cart-icon">
                        <img src="images/Cart Software - iconSvg.co.png" alt="">
                        <span id="cart-count" class="cart-count">0</span>
                    </a>
                </li>
            </ul>

        </div>
        <div class="cont-2">   
            <ul class="main-menu-top2">
                <li class="main-menu__item2"><a class="main-menu-link" href="index.php">Каталог</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="service.php">Услуги</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="#">Статьи</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="kontakti.html">Контакты</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="delivery.html">Доставка</a></li>
                <li class="main-menu__item2"><a class="main-menu-link" href="kompany.html">О компании</a></li>
            </ul> 
        </div> 
    </div>
</header>



            <!-- MAIN -->


        <div class="zag-blok-1">
            <div class="cont-1300px">
                <h1 class="zagolovok-1">Статьи</h1>
            </div>
        </div> 

        <section class="main">

            <div class="article-grid">
                <div class="feature feature-main">
                    <a href="https://axolotl.ru/articles/planarii-v-akvariume-kaznit-nelzya-pomilovat/">
                        <img src="images/big-pict.png" alt="Planiriya-fish">
                    </a>
                    <div class="text-content">
                        <h1 class="zag-1">Планарии в аквариуме: казнить нельзя помиловать</h1>
                        <p>Многие аквариумисты в своей практике сталкиваются с таким неприятным обитателем аквариума как планария...</p>
                    </div>
                </div>

                <div class="feature">
                    <a href="https://axolotl.ru/articles/vodorosli-v-akvariume-chast-3/"><img src="images/vodrosli-3.png" alt=""></a>
                    <div class="text-content"><p>Водоросли в аквариуме. Часть 3</p></div>
                </div>

                <div class="feature">
                    <a href="https://axolotl.ru/articles/vodorosli-v-akvariume-chast-2/"><img src="images/vodrosli-2.png" alt=""></a>
                    <div class="text-content"><p>Водоросли в аквариуме. Часть 2</p></div>
                </div>

                <div class="feature">
                    <a href="https://axolotl.ru/articles/vodorosli-v-akvariume-chast-1/"><img src="images/vodrosli.png" alt=""></a>
                    <div class="text-content"><p>Водоросли в аквариуме. Часть 1</p></div>
                </div>

                <div class="feature">
                    <a href="https://axolotl.ru/articles/volnistye-popugai-dlya-nachinayushchikh-pervye-shagi-k-zavedeniyu-popugaya-kletka-oborudovanie-korma/"><img src="images/piranyi.png" alt=""></a>
                    <div class="text-content"><p>Хищные пираньи в аквариуме?</p></div>
                </div>
            </div>



    
            <!-- ПОДВАЛ -->


        <section class="footer">
            <div class="footer-content-1">
                <img src="images/logo-foot.png" alt="">
                <p class="data">© 2018-2024</p>
            </div>
            <div class="footer-content-2">
                <a href="index.php" class="footer-item-content-2">Главная</a>
                <a href="article.php">Статьи</a>
                <a href="kontakti.html">Контакты</a>
                <a href="delivery.html">Доставка</a>
                <a href="kompany.html">О компании</a>
            </div>
            <div class="footer-content-3">
                <p>FOLLOW</p>
                <div class="icons">
                    <img src="images/Instagram.png" alt="">
                    <img src="images/Vector.png" alt="">
                    <img src="images/Telegram.png" alt="">
                </div>
            </div>
        </section>
        <p class="footer-last-item">Информация, представленная на данном сайте не является публичной офертой или коммерческим предложением. Имеются противопоказания, необходима консультация специалиста.</p>


    </section>

        <script>
        function updateCartCounter() {
            fetch('php/cart_count.php')
                .then(res => res.json())
                .then(data => {
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        const count = data.count || 0;
                        cartCount.textContent = count;
                        cartCount.style.display = count > 0 ? 'inline-block' : 'none';
                    }
                });
        }

        document.addEventListener('DOMContentLoaded', updateCartCounter);
    </script>
                
    
</body>
</html>