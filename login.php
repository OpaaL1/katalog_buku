<?php
require 'config/bahasa.php';
require 'config/koneksi.php';

if (isset($_SESSION['user_id'])){
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($username === '' || $password === ''){
    $error = $t['error_kosong'];
    } else {
        $stmt = mysqli_prepare($koneksi, "SELECT id, password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);

        $user = mysqli_fetch_assoc($hasil);

        if ($user && password_verify($password, $user['password'])){
            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $username;
            header('Location: index.php'); 
            exit;
        } else {
            $error = $t['error_login'];
        }
    }
}

?>
?>
<center>
    <!DOCTYPE html>
    <html lang="<?= $_SESSION['lang'] ?>">
    <head>
        <meta charset="UTF-8">
        <title><?= $t['login'] ?> - <?= $t['judul_app'] ?></title>
    </head>
    <body>
        <h2><?= $t['judul_app'] ?></h2>

        <form method="POST">
            <label><?= $t['username'] ?></label><br>
            <input type="text" name="username"><br>

            <label><?= $t['password'] ?></label><br>
            <input type="password" name="password"><br>

            <button type="submit"><?= $t['login'] ?></button>
        </form>

        <?php if ($error !== ''): ?>
            <p style="color:red"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <a href="ganti_bahasa.php?lang=id">ID</a> |
        <a href="ganti_bahasa.php?lang=en">EN</a>
    </body>
    </html>
</center>