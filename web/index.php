<?php
session_start();
include 'veritabani.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: anasayfa.php"); 
            exit();
        } else {
            $error_msg = "Geçersiz şifre.";
        }
    } else {
        $error_msg = "Kullanıcı bulunamadı.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .navbar-custom {
            background-color: #8BA9C6;
            color: #ffffff;
        }
        .nav-menu {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100%;
            background-color: #426083;
            transition: left 0.3s ease;
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
            left: 10px;
            cursor: pointer;
            color: #ffffff;
            font-size: 24px;
        }
        .footer-custom {
            background-color: #426083;
            color: #ffffff;
        }
        .footer-custom p {
            margin: 0;
        }
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #EAF7FE;
        }
        .form-container h2 {
            margin-bottom: 20px;
            color: #2B3A42;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-login {
            width: 100%;
            background-color: #426083;
            color: #ffffff;
        }
        .btn-login:hover {
            background-color: #2B3A42;
        }
        .create-account {
            margin-top: 20px;
            text-align: center;
        }
        .create-account a {
            color: #007bff;
            text-decoration: none;
        }
        .create-account a:hover {
            text-decoration: underline;
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

<div class="container">
    <div class="form-container">
        <h2>Giriş Yap</h2>
        <form action="index.php" method="post">
            <div class="form-group">
                <label for="username">Kullanıcı Adı:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Şifre:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-login">Giriş Yap</button>
            <?php if(isset($error_msg)) echo "<p class='text-danger'>$error_msg</p>"; ?>
        </form>
        <div class="create-account">
            Hesabınız yok mu? <a href="kayit.php">Kayıt ol</a>
        </div>
    
    
    </div>
</div>
<footer class="footer-custom text-center py-3">
    <div class="container">
        <p>&copy; 2024 Hayvanat Bahçesi Bilgi Sitesi</p>
    </div>
</footer>

<script>
    function toggleMenu() {
        var menu = document.getElementById("menu");
        if (menu.style.left === "-250px") {
            menu.style.left = "0";
        } else {
            menu.style.left = "-250px";
        }
    }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
