<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; object-src 'none';">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Aquaria</title>
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
                        <li class="main-menu__item2"><a class="main-menu-link" href="#">Каталог</a></li>
                        <li class="main-menu__item2"><a class="main-menu-link" href="service.php">Услуги</a></li>
                        <li class="main-menu__item2"><a class="main-menu-link" href="article.php">Статьи</a></li>
                        <li class="main-menu__item2"><a class="main-menu-link" href="kontakti.html">Контакты</a></li>
                        <li class="main-menu__item2"><a class="main-menu-link" href="delivery.html">Доставка</a></li>
                        <li class="main-menu__item2"><a class="main-menu-link" href="kompany.html">О компании</a></li>
                    </ul> 
                </div> 
            </div>
        </header>



                        <!-- MAIN -->

        
        <main class="main">

            <!-- КАРУСЕЛЬ -->

            <div class="slider-container">
                <div class="slider">
                    <!-- Слайд 1 -->
                    <div class="slide">
                        <img src="images/Rybki 1.png" alt="Аквариум">
                        <div class="slide-content">
                            <h2>Создайте свой идеальный аквариум с Aquaria</h2>
                            <p>Качество, экспертиза и поддержка</p>
                        </div>
                    </div>
                    
                    <!-- Слайд 2 -->
                    <div class="slide">
                        <img src="images/Group 21.png" alt="Покупки">
                        <div class="slide-content slide2">
                            <h2>Долями – оплата покупок частями на нашем сайте!</h2>
                            <p>Оформите 4 платежа, заберите товар сразу, а платите за него постепенно</p>
                        </div>
                    </div>
                    
                    <!-- Слайд 3 -->
                    <div class="slide">
                        <img src="images/image.png" alt="Скидки">
                        <div class="slide-content">
                            <h2>+200 рублей за регистрацию карты постоянного покупателя</h2>
                            <p>Экономия с первой покупки: Скидки. Сумма 300 рублей</p>
                            <button type="button" class="btn-reg">Регистрация</button>
                        </div>
                    </div>
                </div>

                    <!-- Модальное окно -->
                <div id="login-modal" class="modal" style="display: <?= $showModal ? 'block' : 'none' ?>">
                    <div class="modal-content">
                        <span class="close-btn" onclick="closeModal()">&times;</span>
                        <p>Пожалуйста, авторизуйтесь для регистрации карты лояльности.</p>
                        <a href="backend/login.php"><button>Перейти к авторизации</button></a>
                    </div>
                </div>
                
                <!-- Кнопки навигации -->
                <button class="prev">&#10094;</button>
                <button class="next">&#10095;</button>
                
                <!-- Точки навигации -->
                <div class="dots-container">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>

            <script>
                function openModal() {
                    document.getElementById('login-modal').style.display = "block";
                }

                function closeModal() {
                    document.getElementById('login-modal').style.display = "none";
                }

                document.querySelector('.btn-reg').addEventListener('click', function(event) {
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        event.preventDefault(); // Отменяем стандартное поведение
                        openModal(); // Показываем модальное окно
                    <?php endif; ?>
                });
            </script>
        
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const slider = document.querySelector('.slider');
                    const slides = document.querySelectorAll('.slide');
                    const prevBtn = document.querySelector('.prev');
                    const nextBtn = document.querySelector('.next');
                    const dots = document.querySelectorAll('.dot');
                    
                    let currentSlide = 0;
                    const slideCount = slides.length;
                    let slideInterval;
                    const intervalTime = 10000; // 10 секунд
                    
                    // Функция для переключения слайда
                    function goToSlide(n) {
                        slider.style.transform = `translateX(-${n * 100}%)`;
                        currentSlide = n;
                        updateDots();
                    }
                    
                    // Функция для следующего слайда
                    function nextSlide() {
                        currentSlide = (currentSlide + 1) % slideCount;
                        goToSlide(currentSlide);
                    }
                    
                    // Функция для предыдущего слайда
                    function prevSlide() {
                        currentSlide = (currentSlide - 1 + slideCount) % slideCount;
                        goToSlide(currentSlide);
                    }
                    
                    // Обновление активной точки
                    function updateDots() {
                        dots.forEach((dot, index) => {
                            dot.classList.toggle('active', index === currentSlide);
                        });
                    }
                    
                    // Запуск автоматического переключения
                    function startSlideInterval() {
                        slideInterval = setInterval(nextSlide, intervalTime);
                    }
                    
                    // Остановка автоматического переключения
                    function stopSlideInterval() {
                        clearInterval(slideInterval);
                    }
                    
                    // Обработчики событий для кнопок
                    nextBtn.addEventListener('click', () => {
                        nextSlide();
                        stopSlideInterval();
                        startSlideInterval();
                    });
                    
                    prevBtn.addEventListener('click', () => {
                        prevSlide();
                        stopSlideInterval();
                        startSlideInterval();
                    });
                    
                    // Обработчики событий для точек
                    dots.forEach((dot, index) => {
                        dot.addEventListener('click', () => {
                            goToSlide(index);
                            stopSlideInterval();
                            startSlideInterval();
                        });
                    });
                    
                    // Остановка автоматического переключения при наведении на слайдер
                    document.querySelector('.slider-container').addEventListener('mouseenter', stopSlideInterval);
                    document.querySelector('.slider-container').addEventListener('mouseleave', startSlideInterval);
                    
                    // Запуск автоматического переключения при загрузке страницы
                    startSlideInterval();
                });
            </script>


            <section class="section-1">

                <h1 class="zag-1">Каталог товаров</h1>

                <div class="container-main">
                    <div class="catalog-grid">
                    <?php
                    require 'php/db.php';
                    $cats = $pdo->query("SELECT * FROM categories")->fetchAll();
                    $positions = [
                        'big',       // 1. Аквариумные рыбки
                        'small-1',   // 2. Аквариумы и тумбы
                        'small-2',   // 3. Корм для рыбок
                        'wide',      // 4. Живые растения
                        'wide-2',    // 5. Оборудование
                        'small-3',   // 6. Автокормушка
                        'small-4',   // 7. Удобрения
                    ];
                    
                    foreach ($cats as $i => $cat) {
                        $position = $positions[$i] ?? '';
                        echo "
                        <div class='category $position' data-id='{$cat['id']}'>
                            <img src='images/categories/{$cat['image']}' alt='{$cat['name']}'>
                            <span>{$cat['name']}</span>
                        </div>";
                    }
                    ?>
                        </div>
                        
                        <h2 class="tovar">Товары</h2>
                        <div id="product-container">
                            <!-- сюда будут подгружаться товары -->
                        </div>
                        
                        <script src="js/main.js"></script>
                </div>

                <div class="features-grid">
    
                    <div class="feature feature-main">
                    <h3>Хотите увидеть красивый аквариум у себя дома?</h3>
                    <p>Нажимайте на кнопку</p>
                    <button>Перейти в каталог</button>
                    </div>

                    <div class="feature">
                    <img src="images/heart.png" alt="">
                    <p>Приятные цены  и высокое качество</p>
                    </div>

                    <div class="feature">
                    <img src="images/car.png" alt="">
                    <p>Доставка по России  и не только</p>
                    </div>

                    <div class="feature">
                    <img src="images/phone-sms.png" alt="">
                    <p>Вежливые менеджеры  и быстрая помощь</p>
                    </div>

                    <div class="feature">
                    <img src="images/card.png" alt="">
                    <p>Оплата любым  удобным способом</p>
                    </div>

                </div>

                <h1 class="zag-2">Немного информации о нас</h1>

                <div class="about-wrapper">
                    <div class="about-section">
                        <img src="images/ribya-1.png" alt="Рыбка слева" class="fish fish-left">
                        
                        <div class="about-text">
                            <p>
                                Интернет-магазин «Aquaria» предлагает широкий ассортимент товаров для любителей аквариумистики.
                                У нас вы можете найти различные виды аквариумных рыбок, качественные корма, современные системы 
                                фильтрации и освещения, а также большой выбор аквариумных растений.
                            </p>
                            <p class="inf-text">
                                Наши опытные специалисты всегда готовы помочь в выборе товаров и ответить на все ваши вопросы.
                            </p>
                        </div>

                        <img src="images/ribya-2.png" alt="Рыбка справа" class="fish fish-right">
                    </div>
                </div>

                <div class="skidka">
                    <p class="promo">Прямо сейчас активируйте промокод <span class="highlight">AQUA10</span> и закрепите за собой скидку 10%!</p>
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
        </main>


            <script>
                function updateCartCounter() {
                    fetch('/php/cart_count.php')
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