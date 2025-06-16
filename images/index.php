<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        <li class="main-menu__item"><img src="images/🦆 icon _location_.png" alt=""></li>
                        <li class="main-menu__item"><p>ул.Баррикадная д.16</p></li>
                    </ul>

                    <ul class="main-menu-top-kontakti">
                        <li class="main-menu__item"><img src="images/🦆 icon _phone_.png" alt=""></li>
                        <li class="main-menu__item"><p>8 (777) 777-77-77</p></li>
                    </ul>

                    <ul class="main-menu-top-reg">
                        <li class="main-menu__item"><a href="backend/register.php"><img src="images/🦆 icon _person_.png" alt=""></a></li>
                        <li class="main-menu__item"><a href="backend/register.php"><p>Войти</p></a></li>
                    </ul>

                    <ul class="main-menu-top-cart">
                        <li class="main-menu__item"><a href="#"><img src="images/Cart Software - iconSvg.co.png" alt=""></a></li>
                    </ul>

                </div>
                <div class="cont-2">   
                    <ul class="main-menu-top2">
                        <li class="main-menu__item2"><a class="main-menu-link" href="#">Каталог</a></li>
                        <li class="main-menu__item2"><a class="main-menu-link" href="#">Услуги</a></li>
                        <li class="main-menu__item2"><a class="main-menu-link" href="#">Статьи</a></li>
                        <li class="main-menu__item2"><a class="main-menu-link" href="#">Контакты</a></li>
                        <li class="main-menu__item2"><a class="main-menu-link" href="#">Доставка</a></li>
                        <li class="main-menu__item2"><a class="main-menu-link" href="#">О компании</a></li>
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
                    <p>Приятные цены <br> и высокое качество</p>
                    </div>

                    <div class="feature">
                    <img src="images/car.png" alt="">
                    <p>Доставка по России <br> и не только</p>
                    </div>

                    <div class="feature">
                    <img src="images/phone-sms.png" alt="">
                    <p>Вежливые менеджеры <br> и быстрая помощь</p>
                    </div>

                    <div class="feature">
                    <img src="images/card.png" alt="">
                    <p>Оплата любым <br> удобным способом</p>
                    </div>

                </div>

            </section>
        
            
        </main>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>