<?php
require_once 'config/database.php';
/** @var mysqli $conn */

// 1. Tambahkan kolom gambar secara otomatis jika belum ada di tabel produk
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM produk LIKE 'gambar'");
if (mysqli_num_rows($check_column) == 0) {
    mysqli_query($conn, "ALTER TABLE produk ADD COLUMN gambar VARCHAR(255) DEFAULT 'default.png'");
}

if (isset($_POST['simpan'])) {
    $kode  = mysqli_real_escape_string($conn, $_POST['kode_produk']);
    $nama  = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $merk  = mysqli_real_escape_string($conn, $_POST['merk']);
    $harga = (float)$_POST['harga'];
    $stok  = (int)$_POST['stok'];

    // Proses gambar jika ada gambar baru yang diunggah
    $new_name = 'default.png';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed_ext, true) && getimagesize($_FILES['gambar']['tmp_name'])) {
            $new_name = time() . '_' . rand(100, 999) . '.' . $ext;
            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/' . $new_name)) {
                $new_name = 'default.png';
            }
        }
    }

    // 2. Jika kode produk sudah ada, tambahkan stok ke produk tersebut
    $check_kode = mysqli_query($conn, "SELECT id_produk FROM produk WHERE kode_produk = '$kode'");
    if (mysqli_num_rows($check_kode) > 0) {
        $gambar_sql = $new_name !== 'default.png' ? ", gambar = '" . mysqli_real_escape_string($conn, $new_name) . "'" : '';
        $update_stok = mysqli_query($conn, "UPDATE produk SET stok = stok + $stok$gambar_sql, updated_at = NOW() WHERE kode_produk = '$kode'");

        if ($update_stok) {
            $success_msg = "Stok produk dengan kode <b>'$kode'</b> berhasil ditambahkan sebanyak $stok.";
        } else {
            $error_msg = "Gagal menambahkan stok: " . mysqli_error($conn);
        }
    } else {
        // 3. Simpan ke database
        $query = "INSERT INTO produk (kode_produk, nama_produk, merk, harga, stok, gambar, created_at, updated_at) 
                  VALUES ('$kode', '$nama', '$merk', '$harga', '$stok', '$new_name', NOW(), NOW())";

        if (mysqli_query($conn, $query)) {
            $success_msg = "Produk baru berhasil disimpan!";
        } else {
            $error_msg = "Gagal menambah produk: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - POS HP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>POS Penjualan HP</h1>
    <nav>
        <a href="index.php">Transaksi / Kasir</a>
        <a href="tambah_produk.php" class="active">Kelola Produk</a>
    </nav>
</header>

<div class="container">
    <div class="grid">
        <div class="card">
            <h2>Tambah Produk Baru</h2>

            <?php if (isset($success_msg)): ?>
                <div style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 14px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 0.875rem; font-weight: 500;">
                    <?= $success_msg; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_msg)): ?>
                <div style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 14px 16px; border-radius: var(--radius-sm); margin-bottom: 20px; font-size: 0.875rem; font-weight: 500;">
                    <?= $error_msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Kode Barang / Kode Produk</label>
                    <input type="text" name="kode_produk" placeholder="Contoh: HP006" required>
                </div>
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" placeholder="Contoh: iPhone 16 Pro Max" required>
                </div>
                <div class="form-group">
                    <label>Merk / Brand</label>
                    <input type="text" name="merk" placeholder="Contoh: Apple" required>
                </div>
                <div class="form-group">
                    <label>Harga Jual (Rp)</label>
                    <input type="number" name="harga" placeholder="25000000" required>
                </div>
                <div class="form-group">
                    <label>Stok Awal</label>
                    <input type="number" name="stok" value="10" min="1" required>
                </div>
                <div class="form-group">
                    <label>Foto Produk (Format JPG, PNG)</label>
                    <input type="file" name="gambar" accept="image/*" style="padding: 8px 12px; background: #ffffff;">
                </div>

                <button type="submit" name="simpan" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Simpan Produk</button>
            </form>
        </div>

        <div class="card">
            <h2>Daftar Produk Terdaftar</h2>
            <div class="table-responsive" style="max-height: 540px; overflow-y: auto;">
                <table>
                    <thead style="position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th style="width: 60px; text-align: center;">Foto</th>
                            <th>Kode & Nama</th>
                            <th>Harga</th>
                            <th style="text-align: center;">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $list_produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC");
                        while ($p = mysqli_fetch_assoc($list_produk)):
                            $img = !empty($p['gambar']) ? $p['gambar'] : 'default.png';
                        ?>
                        <tr>
                            <td style="text-align: center;">
                                <img src="uploads/<?= htmlspecialchars($img); ?>" class="img-thumb" style="margin: 0 auto;" onerror="this.src='https://via.placeholder.com/45?text=No+Img'">
                            </td>
                            <td>
                                <b style="color: var(--text-main); font-size: 0.875rem;"><?= htmlspecialchars($p['nama_produk']); ?></b><br>
                                <small style="color: var(--text-muted); font-size: 0.75rem; font-weight: 500;"><?= htmlspecialchars($p['kode_produk']); ?> (<?= htmlspecialchars($p['merk']); ?>)</small>
                            </td>
                            <td style="font-weight: 600; color: var(--text-main);">Rp<?= number_format($p['harga'], 0, ',', '.'); ?></td>
                            <td style="text-align: center;">
                                <span style="background: #f1f5f9; color: var(--text-main); padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 0.75rem; border: 1px solid var(--border);">
                                    <?= $p['stok']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>