<?php
session_start();
include 'veritabani.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error_msg = "";
$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username']; // Oturumdan kullanıcı adını al
    $new_password = $_POST['new_password'];

    // Yeni şifreyi güncelle
    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_sql = "UPDATE users SET password=? WHERE username=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ss", $hashed_new_password, $username);
    
    if ($update_stmt->execute()) {
        $success_msg = "Şifre başarıyla değiştirildi.";
    } else {
        $error_msg = "Şifre değiştirirken bir hata oluştu.";
    }
    
    $update_stmt->close();
}

// Tüm kullanıcıları al
$users_query = "SELECT username FROM users";
$users_result = $conn->query($users_query);

// Kullanıcı adlarını listeleme
$user_list = "";
if ($users_result->num_rows > 0) {
    while ($row = $users_result->fetch_assoc()) {
        $user_list .= "<li>" . $row["username"] . "</li>";
    }
} else {
    $user_list = "Kullanıcı bulunamadı.";
}

// Veritabanı bağlantısını kapat
$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hesap İşlemleri</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ced4da;
            border-radius: 10px;
            background-color: #EAF7FE; /* Değiştirilen: Arka plan rengi */
        }
        .form-container h2 {
            margin-bottom: 20px;
            color: #426083; /* Yazı rengi */
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-login {
            width: 100%;
            background-color: #426083; /*  Buton arka plan rengi */
            color: #ffffff;
            border: 1px solid #426083; /* Buton kenarlık rengi */
        }
        .btn-login:hover {
            background-color: #2B3A42; /*  Buton hover arka plan rengi */
            border-color: #2B3A42; /*  Buton hover kenarlık rengi */
        }
        .text-danger {
            color: #dc3545;
            margin-top: 10px;
        }
        .text-success {
            color: #28a745;
            margin-top: 10px;
        }
        .footer-custom {
            background-color: #426083; /* Footer arka plan rengi */
            color: #6c757d;
            margin-top: 50px;
            padding: 20px 0;
        }
        .footer-custom p {
            color: #ffffff; /* Footer yazı rengi */
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2>Şifre Değiştir</h2>
        <form action="hesapislemleri.php" method="post">
            <div class="form-group">
                <label for="new_password">Yeni Şifre:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-login">Şifre Değiştir</button>
            <?php
            if ($error_msg) {
                echo "<p class='text-danger'>$error_msg</p>";
            }
            if ($success_msg) {
                echo "<p class='text-success'>$success_msg</p>";
            }
            ?>
        </form>
        <?php if ($success_msg): ?>
            <p><a href="index.php">Yeniden Giriş Yap</a></p>
        <?php endif; ?>
    </div>
    
    <div class="user-list" style="background-color: #EAF7FE; padding: 20px; border-radius: 10px; margin-top: 20px;">
    <h2>Tüm Kullanıcılar</h2>
    <table class="table">
        <tbody>
            <?php echo $user_list; ?>
        </tbody>
    </table>
</div>
</div>
<footer class="footer-custom text-center py-3">
    <div class="container">
        <p>&copy; 2024 Hayvanat Bahçesi Bilgi Sitesi</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>