<?php
// Oturumu başlat
session_start();

include 'veritabani.php'; // veritabani.php dosyamızın yolu

// Düzenlenecek hayvanın bilgilerini almak için değişkenler
$edit_mode = false;
$edit_hayvan_id = "";
$tur = "";
$yas = "";
$tehlikeli_davranis = "";
$saglik_durumu = "";

// Kullanıcı hayvan düzenleme işlemi başlattıgında
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_hayvan_id = $_GET['edit'];

    // Düzenlenecek hayvanın bilgilerini almak için SQL sorgusu
    $sql = "SELECT * FROM hayvantakibi WHERE hayvan_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $edit_hayvan_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Eğer sorgu başarılıysa ve veri bulunuyorsa
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tur = $row['tur'];
        $yas = $row['yas'];
        $tehlikeli_davranis = $row['tehlikeli_davranis'];
        $saglik_durumu = $row['saglik_durumu'];
    } else {
        echo "Veri bulunamadı veya bir hata oluştu.";
        exit();
    }
}

// Kullanıcı hayvan eklemesi işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ekle'])) {
    // Form verileri
    $tur = $_POST['tur'];
    $yas = $_POST['yas'];
    $tehlikeli_davranis = $_POST['tehlikeli_davranis'];
    $saglik_durumu = $_POST['saglik_durumu'];

    // SQL sorgusu
    $sql = "INSERT INTO hayvantakibi (tur, yas, tehlikeli_davranis, saglik_durumu) VALUES (?, ?, ?, ?)";
    
    // SQL sorgusunu hazırlanması ve bağlı parametreleri belirtilmesi
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $tur, $yas, $tehlikeli_davranis, $saglik_durumu); // Parametreler

    // Sorguyu çalıştır
    if ($stmt->execute() === TRUE) {
        echo "Başarıyla Eklendi.";
    } else {
        echo "Hata: " . $stmt->error;
    }

    // Bağlantıyı kapat
    $stmt->close();
}

// Kullanıcı hayvan düzenleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['duzenle'])) {
    // Form verilerini alınması
    $edit_hayvan_id = $_POST['hayvan_id'];
    $tur = $_POST['tur'];
    $yas = $_POST['yas'];
    $tehlikeli_davranis = $_POST['tehlikeli_davranis'];
    $saglik_durumu = $_POST['saglik_durumu'];

    // SQL sorgusunu hazırlanması
    $sql = "UPDATE hayvantakibi SET tur=?, yas=?, tehlikeli_davranis=?, saglik_durumu=? WHERE hayvan_id=?";
    
    // SQL sorgusunu hazırla ve bağlı parametrelerin belirlenmesi
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissi", $tur, $yas, $tehlikeli_davranis, $saglik_durumu, $edit_hayvan_id); 

    // Sorguyu çalıştır
    if ($stmt->execute() === TRUE) {
        echo "Başarıyla Güncellendi.";
    } else {
        echo "Hata: " . $stmt->error;
    }

    // Bağlantıyı kapat
    $stmt->close();
}

// Kullanıcı hayvan silme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sil'])) {
    // Form verilerini alın
    $hayvan_id = $_POST['hayvan_id'];

    // SQL sorgusunu hazırla
    $sql = "DELETE FROM hayvantakibi WHERE hayvan_id=?";
    
    // SQL sorgusunu hazırla ve bağlı parametreleri belirt
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hayvan_id); // Parametreleri bağla

    // Sorguyu çalıştır
    if ($stmt->execute() === TRUE) {
        echo "Başarıyla Silindi.";
    } else {
        echo "Hata: " . $stmt->error;
    }

    // Bağlantıyı ve ifadeyi kapat
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hayvanlar</title>
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
            margin-bottom: 100px; /* Alt kutu ile arasına boşluk ekler */
        
        }
        .animal-info {
            display: flex;
            justify-content: center; /* Resmi ortalamak için */
            margin-top: 20px;
           
        }
        .animal-info .animal-image img {
            width: 100%;
            max-width: 600px; /* maksimum genişlik*/
            height: 400px; /* Sabit yükseklik */
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
            <span class="menu-toggle" onclick="toggleMenu()">☰ Menü</span>
            <div class="nav-menu" id="menu">
                <ul>
                    <li><a href="anasayfa.php">Ana Sayfa</a></li>
                    <li><a href="hayvan.php">Hayvanlar</a></li>
                    <li><a href="etkinlikler.php">Etkinlikler</a></li>
                    <li><a href="iletisim.php">İletişim</a></li>
                    <li><a href="hesapislemleri.php">Hesap İşlemleri ve Kullanıcılar</a></li>
                </ul>
            </div>
        </nav>
        
    </div>
</header>

<section class="banner">
    <div class="container d-flex justify-content-between align-items-center text-white position-relative">
        <div>
            <h2>Animals</h2>
            <p>Hayvanat bahçemizde bulunan hayvanlar</p>
        </div>
        <div style="position: absolute; right: 195px; top: 0px;">
            <img src="zooyazi1.jpg" alt="Zoo Yazi" style="height: 80px; width: auto;">
        </div>
    </div>
</section>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="info" style="height: 600px;">
                <h2>Hayvan <?php echo $edit_mode ? 'Düzenle' : 'Ekle'; ?></h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input type="hidden" name="hayvan_id" value="<?php echo $edit_hayvan_id; ?>">
                    <label for="tur">Tür:</label>
                    <input type="text" id="tur" name="tur" value="<?php echo $tur; ?>" required><br><br>
                    <label for="yas">Yaş:</label>
                    <input type="number" id="yas" name="yas" value="<?php echo $yas; ?>" required><br><br>
                    <label for="tehlikeli_davranis">Tehlikeli Davranış:</label>
                    <input type="text" id="tehlikeli_davranis" name="tehlikeli_davranis" value="<?php echo $tehlikeli_davranis; ?>"><br><br>
                    <label for="saglik_durumu">Sağlık Durumu:</label>
                    <input type="text" id="saglik_durumu" name="saglik_durumu" value="<?php echo $saglik_durumu; ?>" required><br><br>
                    <input type="submit" name="<?php echo $edit_mode ? 'duzenle' : 'ekle'; ?>" value="<?php echo $edit_mode ? 'Düzenle' : 'Ekle'; ?>">
                </form>

                <?php
                // Verileri almak için SQL sorgusu
                $sql = "SELECT * FROM hayvantakibi";

                // Sorguyu çalıştır
                $result = $conn->query($sql);

                // Eğer sorgu başarılıysa
                if ($result && $result->num_rows > 0) {
                    echo "<table border='1'>";
                    echo "<tr><th>ID</th><th>Tür</th><th>Yaş</th><th>Tehlikeli Davranış</th><th>Sağlık Durumu</th><th>İşlemler</th></tr>";
                    // Her bir sonuç için verileri tabloya ekle
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row['hayvan_id']."</td>";
                        echo "<td>".$row['tur']."</td>";
                        echo "<td>".$row['yas']."</td>";
                        echo "<td>".$row['tehlikeli_davranis']."</td>";
                        echo "<td>".$row['saglik_durumu']."</td>";
                        echo "<td>
                                <form style='display:inline;' action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='POST'>
                                    <input type='hidden' name='hayvan_id' value='".$row['hayvan_id']."'>
                                    <input type='submit' name='sil' value='Sil'>
                                </form>
                                <form style='display:inline;' action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='GET'>
                                    <input type='hidden' name='edit' value='".$row['hayvan_id']."'>
                                    <input type='submit' value='Düzenle'>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "Veri bulunamadı veya bir hata oluştu.";
                }

                // Veritabanı bağlantısı kapatma
                $conn->close();
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info">
                <div class="animal-info">
                    <div class="animal-image">
                        <img id="animalImage" src="panda.jpg" alt="Hayvanat Bahçesi" onclick="nextImage()">
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
    var images = ["panda.jpg","susamuru.jpg", "ayi.jpg", "yilan.jpg","zurafa.jpg"];

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

        function hideMenu() {
            menu.style.left = "-250px";
            menuIsOpen = false;
        }

        function showMenu() {
            menu.style.left = "0";
            menuIsOpen = true;
        }

        function toggleMenu() {
            if (menuIsOpen) {
                hideMenu();
            } else {
                showMenu();
            }
        }

        var menuToggle = document.querySelector(".menu-toggle");
        menuToggle.addEventListener('click', toggleMenu);

        var menuItems = document.querySelectorAll(".nav-menu a");
        menuItems.forEach(function(item) {
            item.addEventListener('click', hideMenu);
        });

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
