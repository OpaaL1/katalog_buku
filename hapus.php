<?php
    require 'config/auth.php';
    require 'config/koneksi.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Location: index.php');
        exit;
    }

    $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

    if ($id !== false){
        $stmt = mysqli_prepare($koneksi, "DELETE FROM buku WHERE id_buku = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
    header('Location: index.php');
    exit;
?>