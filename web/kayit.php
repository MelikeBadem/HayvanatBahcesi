<?php
// Oturumu başlat
session_start();

// Veritabanı bağlantısı
include 'veritabani.php';

// Başlangıçta yönlendirme yapılacak URL'yi boş bir değişkene atama
$redirect_url = "";

// Kullanıcı kayıt işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Form verilerini alın
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Şifreyi hashle

    // SQL sorgusu
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    // SQL sorgusunu hazırla ve bağlı parametreler
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password); // Parametreleri bağla

    // Sorguyu çalıştır
    if ($stmt->execute() === TRUE) {
        // Başarılı bir şekilde kaydedildiyse, yönlendirme URL'sini belirle
        $redirect_url = "index.php";
    } else {
        // Başarısız kayıt durumunda hata mesajını ayarla
        $error_msg = "Hata: " . $stmt->error;
    }

    // Bağlantıyı ve ifadeyi kapat
    $stmt->close();
    $conn->close();
}

// Eğer yönlendirme URL'si belirlenmişse, yönlendirme yap
if (!empty($redirect_url)) {
    header("Location: $redirect_url");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 100px;
        }
        .card {
            border-radius: 25px; /* Etiketlerin köşeleri oval */
            background-color: #EAF7FE; /* Arka plan rengi */
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #426083; /* Buton rengi */
            border-color: #426083; /* Buton kenar rengi */
        }
        .btn-primary:hover {
            background-color: #2B3A42; /* Buton hover arka plan rengi */
            border-color: #2B3A42; /* Buton hover kenar rengi */
        }
        .footer-custom {
            background-color: #426083;
            color: #ffffff;
            padding: 10px 0; 
        }
        .footer-custom p {
            margin: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Kayıt Ol</h2>
                </div>
                <div class="card-body">
                    <form action="kayit.php" method="post">
                        <div class="form-group">
                            <label for="username">Kullanıcı Adı:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Şifre:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Kayıt Ol</button>
                    </form>
                    <?php
                    // Eğer hata mesajı varsa
                    if (isset($error_msg)) {
                        echo "<p class='text-danger mt-3'>$error_msg</p>";
                    }
                    ?>
                    <p class="mt-3">Zaten hesabınız var mı? <a href="index.php">Buradan giriş yapın</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer-custom text-center py-2" style="background-color: #426083; color: #ffffff;"> 
    <div class="container">
        <p>&copy; 2024 Hayvanat Bahçesi Bilgi Sitesi</p> 
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


