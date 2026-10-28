<?php
require 'config/auth.php';
require 'config/koneksi.php';

$hasil = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id_buku DESC");
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang']?>">
<head>
    <meta charset="UTF-8">
    <title><?= $t['judul_app']?></title>
</head>
<body>
    <h2><?= $t['judul_app']?></h2>
    <p>
        <?= $t['selamat_datang']?>, <?= htmlspecialchars($_SESSION['username']) ?> |
        <a href="logout.php"><?= $t['logout']?></a>
        <a href="ganti_bahasa.php?lang=id">ID</a> |
        <a href="ganti_bahasa.php?lang=en">EN</a>
    </p>
    <a href="tambah.php"><?= $t['tambah']?></a>

    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>No</th>
            <th><?= $t['judul']?></th>
            <th><?= $t['nama_pengarang']?></th>
            <th><?= $t['penerbit']?></th>
            <th><?= $t['tahun_terbit']?></th>
            <th><?= $t['isbn']?></th>
            <th><?= $t['aksi']?></th>
        </tr>
        <?php $no = 1; ?>
        <?php while ($row = mysqli_fetch_assoc($hasil)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['judul'])?></td>
                <td><?= htmlspecialchars($row['nama_pengarang'])?></td>
                <td><?= htmlspecialchars($row['penerbit'])?></td>
                <td><?= htmlspecialchars($row['tahun_terbit'])?></td>
                <td><?= htmlspecialchars($row['isbn'])?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id_buku'] ?>"><?= $t['edit'] ?></a> |
                    <form method="POST" action="hapus.php" style="display:inline"
                        onsubmit="return confirm('<?= $t['konfirmasi_hapus'] ?>')">
                        <input type="hidden" name="id" value="<?= $row['id_buku'] ?>">
                        <button type="submit" style="background:none; border:none; color:blue; text-decoration:underline; cursor:pointer; padding:0; font:inherit;">
                            <?= $t['hapus'] ?>
                        </button>
                    </form>
                    
                </td>
            </tr>
        <?php endwhile; ?>
        
    </table>
</body>
</html>
