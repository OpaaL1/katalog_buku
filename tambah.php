<?php
require 'config/auth.php';
require 'config/koneksi.php';

$error = '';
$judul = $nama_pengarang = $penerbit = $tahun_terbit = $isbn = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $judul = trim($_POST['judul'] ?? '');
    $nama_pengarang = trim($_POST['nama_pengarang'] ?? '');
    $penerbit = trim($_POST['penerbit'] ?? '');
    $tahun_terbit = trim($_POST['tahun_terbit'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    if($judul === '' || $nama_pengarang === '' || $penerbit === '' || $tahun_terbit === '' || $isbn === ''){
        $error = $t['error_kosong_form'];
    }else if (filter_var($tahun_terbit, FILTER_VALIDATE_INT) === false){
        $error = $t['error_tahun'];
    }else if (strlen($judul) > 150){
        $error = $t['error_judul_panjang'];
    }else {
        $stmt = mysqli_prepare($koneksi,
        "INSERT INTO buku (judul, nama_pengarang, penerbit, tahun_terbit, isbn) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssis", $judul, $nama_pengarang, $penerbit, $tahun_terbit, $isbn);
        try {
            mysqli_stmt_execute($stmt);
            header('Location: index.php');
            exit;
        }catch (mysqli_sql_exception $e) {
            if ($e ->getCode() == 1062) {
                $error = $t['error_isbn_duplikat'];
            }else {
                $error = $t['error_umum'];
            }
        }
    }
}
?>

 <!DOCTYPE html>
    <html lang="<?= $_SESSION['lang'] ?>">
    <head>
        <meta charset="UTF-8">
        <title><?= $t['tambah'] ?> - <?= $t['judul_app'] ?></title>
    </head>
    <body>
        <h2><?= $t['judul_app'] ?></h2>
        <form method="POST">
            <label><?= $t['judul'] ?></label><br>
            <input type="text" name="judul" value="<?= htmlspecialchars($judul)?>"><br>
            
            <label><?= $t['nama_pengarang'] ?></label><br>
            <input type="text" name="nama_pengarang" value="<?= htmlspecialchars($nama_pengarang)?>"><br>

            <label><?= $t['penerbit'] ?></label><br>
            <input type="text" name="penerbit" value="<?= htmlspecialchars($penerbit)?>"><br>

            <label><?= $t['tahun_terbit'] ?></label><br>
            <input type="number" name="tahun_terbit" value="<?= htmlspecialchars($tahun_terbit)?>"><br>

            <label><?= $t['isbn'] ?></label><br>
            <input type="text" name="isbn" value="<?= htmlspecialchars($isbn)?>"><br>

            <button type="submit"><?= $t['simpan'] ?></button>
        </form>

         <?php if ($error !== ''): ?>
            <p style="color:red"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <a href="index.php"><?=$t['kembali']?></a>

        <a href="ganti_bahasa.php?lang=id">ID</a> |
        <a href="ganti_bahasa.php?lang=en">EN</a>
    </body>
    </html>