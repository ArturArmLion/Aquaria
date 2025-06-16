<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/service.css">
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
                <li class="main-menu__item2"><a class="main-menu-link" href="article.php">Статьи</a></li>
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
            <h1 class="zagolovok-1">Услуги</h1>
        </div>
    </div> 


<main class="main">

    <div class="img-sertificate"><img class="sertificate" src="images/sertificate.png" alt="sertificate" width="1000px"></div>

    <div class="osnovnoy-text">
        <p>АРГ, АкваПлюс, Акватех - производят аквариумы уже более 20 лет в г.Санкт-Петербурге и зарекомендовали себя как надежные производители качественных аквариумов, как со слов их клиентов, так и по нашему опыту.</p>
        <p>
            На нашем сайте в разделе аквариумы от 100 литров представлен большой выбор аквариумных комплексов по низким ценам. Вам достаточно выбрать понравившейся аквариум с тумбой и оставить заявку на сайте. <br>
            • Сроки изготовления до 20 рабочих дней, но как правило в течении 14 календарных дней аквариум уже будет готов. <br>
            • Аквариумы изготавливаются только при 100% предоплате и заключении договора на поставку. В договоре прописаны все условия и сроки. <br>
            • Мы не делаем скидки на аквариумы под заказ поскольку наценка минимальна. <br>
            • Перед заключением договора желательно заполнить заявку на изготовление в любом из наших магазинов, что бы итоговый результат вас не разочаровал.
        </p>
    </div>


        <div class="forma-obrat-svyaz">
            <p class="zag">Оставьте заявку и менеджер <br>
                перезвонит вам через 5 минут</p>
            
            <p class="inf">И предложит специальные условия для клиентов, <br> 
                которые обращаются к нам в первый раз</p>

            <form class="forma" action="php/submit.php" method="POST">
                <input class="inf-2" name="name" type="text" placeholder="Ваше имя" id="name" required>
                <input id="phone" class="inf-2" name="phone" type="tel" placeholder="Ваш телефон" required>
                <button class="knopka">Отправить</button>
            </form>
        </div>


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const nameInput = document.getElementById("name");
        
                if (nameInput) {
                    nameInput.addEventListener("input", function(event) {
                        let value = event.target.value;
        
                        // Удаляем цифры и спецсимволы, кроме дефиса и пробела
                        value = value.replace(/[^a-zA-Zа-яА-ЯёЁ\s-]/g, "");
        
                        // Убираем пробелы в начале
                        value = value.replace(/^\s+/, "");
        
                        // Делаем первую букву заглавной
                        if (value.length > 0) {
                            value = value.charAt(0).toUpperCase() + value.slice(1);
                        }
        
                        event.target.value = value;
                    });
                } else {
                    console.error("Ошибка: Поле name не найдено!");
                }
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const phoneInput = document.getElementById("phone");
        
                if (!phoneInput) {
                    console.error("Ошибка: Элемент #phone не найден!");
                    return;
                }
        
                phoneInput.addEventListener("input", function(event) {
                    let value = event.target.value.replace(/\D/g, ""); // Удаляем все нецифровые символы
        
                    // Если пользователь начал ввод без "+7", добавляем
                    if (!value.startsWith("7")) {
                        value = "7" + value;
                    }
        
                    // Ограничиваем количество цифр
                    value = value.substring(0, 11);
        
                    // Форматируем в "+7 (XXX) XXX-XX-XX"
                    let formattedValue = "+7";
                    if (value.length > 1) formattedValue += " (" + value.substring(1, 4);
                    if (value.length > 4) formattedValue += ") " + value.substring(4, 7);
                    if (value.length > 7) formattedValue += "-" + value.substring(7, 9);
                    if (value.length > 9) formattedValue += "-" + value.substring(9, 11);
        
                    event.target.value = formattedValue;
                });
        
                phoneInput.addEventListener("keydown", function(event) {
                    // Разрешаем только цифры, клавиши управления и Backspace
                    if (!/[0-9]/.test(event.key) && 
                        !["Backspace", "Delete", "ArrowLeft", "ArrowRight"].includes(event.key)) {
                        event.preventDefault();
                    }
                });
            });
        </script>

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




    
            <!-- ПОДВАЛ -->


        <section class="footer">
            <div class="footer-content-1">
                <img src="images/logo-foot.png" alt="">
                <p class="data">© 2018-2024</p>
            </div>
            <div class="footer-content-2">
                <a href="index.php" class="footer-item-content-2">Главная</a>
                <a href="#">Каталог</a>
                <a href="#">Статьи</a>
                <a href="#">Контакты</a>
                <a href="#">Доставка</a>
                <a href="#">О компании</a>
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
                
    
</body>
</html>