<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlikler</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .navbar-custom {
            background-color: #8BA9C6;
            color: #ffffff;
        }
        .nav-menu {
            position: fixed;
            top: 0;
            left: -250px; /* Pencereyi başlangıçta sol tarafta gizle */
            width: 250px;
            height: 100%;
            background-color:#426083;
            transition: left 0.3s ease; /* Sol kenarlık için geçiş efekti */
            z-index: 1000;
        }
        .nav-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .nav-menu li {
            padding: 10px 20px;
            border-bottom: 1px solid #8BA9C6;
        }
        .nav-menu li:last-child {
            border-bottom: none;
        }
        .nav-menu a {
            color: #ffffff;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s ease;
        }
        .nav-menu a:hover {
            background-color: #426083;
        }
        .menu-toggle {
            position: absolute;
            top: 10px;
            left: 10px; /* Pencereyi başlangıçta sol tarafta gizle */
            cursor: pointer;
            color: #ffffff;
            font-size: 24px;
        }
        .footer-custom {
            background-color: #426083; /* Alt kısım için arka plan rengi */
            color: #ffffff; /* Alt kısım için yazı rengi */
        }
        .footer-custom p {
            margin: 0; /* Alt kısımda gereksiz boşlukları kaldırma */
        }
        .info {
            background-color: #EAF7FE; /* Kutuların arka plan rengi */
            color: #2B3A42; /* Kutuların yazı rengi */
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px; /* Alt kutu ile arasına boşluk ekler */
        }
        .animal-info {
            display: flex;
            justify-content: center; /* Resmi ortalamak için */
            margin-top: 20px;
        }
        .animal-info .animal-image img {
            width: 100%;
            max-width: 600px; /*  maksimum genişlik z */
            height: 400px; 
            object-fit: cover; /* Görselin taşmasını önlemek için */
            border-radius: 10px;
            cursor: pointer; /* Mouse'un el işareti olması için */
        }
        .banner {
            background-color: #8BA9C6; /* Arka plan rengi */
            color: #ffffff; /* Metin rengi */
            padding: 20px 0; 
            margin-bottom: 25px; /* Ek mesafe */
            height: 120px; /* Yükseklik ayarı */
        }
    </style>
</head>

<body>
<header class="navbar-custom">
    <div class="container">
        <nav>
            <span class="menu-toggle" onclick="toggleMenu()">&#9776;</span>
        </nav>
    </div>
</header>
<div class="nav-menu" id="menu">
    <ul>
        <li><a href="anasayfa.php">Ana Sayfa</a></li>
        <li><a href="hayvan.php">Hayvanlar</a></li>
        <li><a href="etkinlikler.php">Etkinlikler</a></li>
        <li><a href="iletisim.php">İletişim</a></li>
        <li><a href="hesapislemleri.php">Hesap İşlemleri ve Kullancılar</a></li>
    </ul>
</div>

<section class="banner">
    <div class="container d-flex justify-content-between align-items-center text-white position-relative">
        <div>
            <h2>Etkinliklerimiz!</h2>
            <p>Hayvanat Bahçemizde Haziran Ayı Boyunca Planlanan Etkinlikler</p>
        </div>
        <div style="position: absolute; right: 195px; top: 0px;">
            <img src="zooyazi1.jpg" alt="Zoo Yazi" style="height: 80px; width: auto;">
        </div>
    </div>
</section>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="info"style="height: 460px;">
                <h2>Hayvanat Bahçemizde Yapılması Planlanan Etkinlikler </h2>
                <p><b>8 Haziran Cumartesi-</b>Eğitmenlerimiz ile birlikte hayvan besleme etkinliği yapılacaktır.</p>
                <p><b>9 Haziran Pazar -</b>Binicilik eğitimi verilecektir.</p>
                <p><b>16 Haziran Pazar-</b>Okullardan gelen öğrencilere sunum yapılacaktır.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info">
                <div class="animal-info">
                    <div class="animal-image">
                        <img id="animalImage" src="hayvanbesleme.jpg" alt="Hayvanat Bahçesi" onclick="nextImage()">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer-custom text-center py-3">
    <div class="container">
        <p>&copy; 2024 Hayvanat Bahçesi Bilgi Sitesi</p>
    </div>
</footer>

<script>
    var currentImageIndex = 0;
    var images = ["hayvanbesleme.jpg", "binicilik.jpg", "sunum.jpg"];

    function nextImage() {
        currentImageIndex++;
        if (currentImageIndex >= images.length) {
            currentImageIndex = 0;
        }
        document.getElementById('animalImage').src = images[currentImageIndex];
    }
    function toggleMenu() {
        var menu = document.getElementById("menu");
        if (menu.style.left === "-250px") {
            menu.style.left = "0";
        } else {
            menu.style.left = "-250px";
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
    var menu = document.getElementById("menu");
    var menuIsOpen = false; // Menünün açık mı kapalı mı olduğunu takip eder

    // Menüyü gizlemek için fonksiyon
    function hideMenu() {
        menu.style.left = "-250px";
        menuIsOpen = false;
    }

    // Menüyü göstermek için fonksiyon
    function showMenu() {
        menu.style.left = "0";
        menuIsOpen = true;
    }

    // Menüyü açma ve kapatma fonksiyonu
    function toggleMenu() {
        if (menuIsOpen) {
            hideMenu();
        } else {
            showMenu();
        }
    }

    // Menüyü açma ve kapatma işlevi
    var menuToggle = document.querySelector(".menu-toggle");
    menuToggle.addEventListener('click', toggleMenu);

    // Menü öğelerine tıklandığında menüyü gizle
    var menuItems = document.querySelectorAll(".nav-menu a");
    menuItems.forEach(function(item) {
        item.addEventListener('click', hideMenu);
    });

    // Ekranın herhangi bir yerine tıklandığında menüyü gizle
    document.addEventListener('click', function(event) {
        if (!menu.contains(event.target) && event.target !== menuToggle && menuIsOpen) {
            hideMenu();
        }
    });
});

</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
