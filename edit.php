<?php
require 'config/auth.php';
require 'config/koneksi.php';

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
if ($id === false){
    header('Location: index.php');
    exit;
}

$error = '';

$stmt = mysqli_prepare($koneksi, "SELECT judul, nama_pengarang, penerbit, tahun_terbit, isbn FROM buku WHERE id_buku = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$buku = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if(!$buku) {
    header('Location: index.php');
    exit;
}

$judul = $buku['judul'];
$nama_pengarang = $buku['nama_pengarang'];
$penerbit = $buku['penerbit'];
$tahun_terbit = $buku['tahun_terbit'];
$isbn = $buku['isbn'];

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
        "UPDATE buku SET judul = ?, nama_pengarang = ?, penerbit = ?, tahun_terbit = ?, isbn = ? WHERE id_buku = ?");
        mysqli_stmt_bind_param($stmt, "sssisi", $judul, $nama_pengarang, $penerbit, $tahun_terbit, $isbn, $id);
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
        <title><?= $t['ubah_buku'] ?> - <?= $t['judul_app'] ?></title>
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