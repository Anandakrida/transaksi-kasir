<?php
session_start();
require_once 'config/database.php';
/** @var mysqli $conn */

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$error_msg = "";

// Nama kasir dapat diedit saat memproses pembayaran.
if (isset($_POST['nama_kasir'])) {
    $nama_kasir_input = trim($_POST['nama_kasir']);
    $_SESSION['nama_kasir'] = $nama_kasir_input !== '' ? $nama_kasir_input : 'Admin';
}
$nama_kasir = $_SESSION['nama_kasir'] ?? 'Admin';

// =====================================================
// TAMBAH PRODUK KE KERANJANG
// =====================================================
if (isset($_POST['tambah_keranjang'])) {
    $id_produk = (int) $_POST['id_produk'];
    $jumlah = (int) $_POST['jumlah'];

    if ($id_produk > 0 && $jumlah > 0) {
        $stmt = $conn->prepare("SELECT * FROM produk WHERE id_produk = ?");
        $stmt->bind_param("i", $id_produk);
        $stmt->execute();
        $result = $stmt->get_result();
        $produk = $result->fetch_assoc();

        if ($produk) {
            $gambar_nama = !empty($produk['gambar']) ? $produk['gambar'] : 'default.png';

            if ($jumlah > $produk['stok']) {
                $error_msg = "Stok tidak mencukupi! Stok tersisa: " . $produk['stok'];
            } else {
                if (isset($_SESSION['cart'][$id_produk])) {
                    $total_qty = $_SESSION['cart'][$id_produk]['jumlah'] + $jumlah;

                    if ($total_qty > $produk['stok']) {
                        $error_msg = "Total jumlah melebihi batas stok tersedia (" . $produk['stok'] . ")";
                    } else {
                        $_SESSION['cart'][$id_produk]['jumlah'] = $total_qty;
                    }
                } else {
                    $_SESSION['cart'][$id_produk] = [
                        'id_produk'   => $produk['id_produk'],
                        'kode_produk' => $produk['kode_produk'],
                        'nama_produk' => $produk['nama_produk'],
                        'harga'       => $produk['harga'],
                        'jumlah'      => $jumlah,
                        'gambar'      => $gambar_nama
                    ];
                }
            }
        }
        $stmt->close();
    }
}

// =====================================================
// HAPUS PRODUK DARI KERANJANG
// =====================================================
if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    unset($_SESSION['cart'][$id_hapus]);
    header("Location: index.php");
    exit();
}

// =====================================================
// RESET KERANJANG
// =====================================================
if (isset($_GET['reset'])) {
    unset($_SESSION['cart']);
    header("Location: index.php");
    exit();
}

// =====================================================
// PROSES PEMBAYARAN
// =====================================================
if (isset($_POST['proses_bayar'])) {
    if (!empty($_SESSION['cart'])) {
        $no_transaksi = 'TRX-' . date('YmdHis');
        $id_kasir = 1; // Default admin kasir
        $nama_pelanggan = !empty($_POST['nama_pelanggan']) ? trim($_POST['nama_pelanggan']) : 'customer';
        $metode_pembayaran = trim($_POST['metode_pembayaran']);
        $uang_bayar = (float) $_POST['uang_bayar'];

        $subtotal = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['harga'] * $item['jumlah'];
        }

        $total_bayar = $subtotal;
        $kembalian = $uang_bayar - $total_bayar;

        if ($uang_bayar >= $total_bayar) {
            // Prepared Statement untuk Insert Transaksi
            $stmt_trx = $conn->prepare("
                INSERT INTO transaksi 
                (no_transaksi, id_kasir, nama_pelanggan, tanggal_transaksi, subtotal, total_bayar, uang_bayar, kembalian, metode_pembayaran, created_at, updated_at) 
                VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, NOW(), NOW())
            ");
            $stmt_trx->bind_param("sisdddds", $no_transaksi, $id_kasir, $nama_pelanggan, $subtotal, $total_bayar, $uang_bayar, $kembalian, $metode_pembayaran);

            if ($stmt_trx->execute()) {
                $id_transaksi = $conn->insert_id;
                $stmt_trx->close();

                // Prepared Statements untuk Detail & Update Stok
                $stmt_detail = $conn->prepare("INSERT INTO detail_kasir (id_transaksi, id_produk, jumlah, harga_satuan, subtotal) VALUES (?, ?, ?, ?, ?)");
                $stmt_stok   = $conn->prepare("UPDATE produk SET stok = stok - ? WHERE id_produk = ?");

                foreach ($_SESSION['cart'] as $item) {
                    $id_p = (int) $item['id_produk'];
                    $qty  = (int) $item['jumlah'];
                    $hrg  = (float) $item['harga'];
                    $sub  = $qty * $hrg;

                    $stmt_detail->bind_param("iiidd", $id_transaksi, $id_p, $qty, $hrg, $sub);
                    $stmt_detail->execute();

                    $stmt_stok->bind_param("ii", $qty, $id_p);
                    $stmt_stok->execute();
                }

                $stmt_detail->close();
                $stmt_stok->close();

                unset($_SESSION['cart']);
                header("Location: cetak_struk.php?id=" . $id_transaksi . "&autoprint=true");
                exit();
            } else {
                $error_msg = "Gagal menyimpan transaksi: " . $conn->error;
            }
        } else {
            $error_msg = "Uang pembayaran kurang dari total tagihan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Penjualan HP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>POS Penjualan HP</h1>
    <nav>
        <a href="index.php" class="active">Transaksi / Kasir</a>
        <a href="tambah_produk.php">Kelola Produk</a>
    </nav>
</header>

<div class="container">
    <?php if (!empty($error_msg)): ?>
        <div style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 16px; font-weight: 500; font-size: 0.875rem;">
            <?= htmlspecialchars($error_msg); ?>
        </div>
    <?php endif; ?>

    <div class="grid">
        <!-- KATALOG PRODUK -->
        <div class="card">
            <h2 style="margin-bottom: 16px;">Pilih Produk</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; max-height: 620px; overflow-y: auto; padding-right: 4px;">
                <?php
                $res = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0 ORDER BY nama_produk ASC");

                if (mysqli_num_rows($res) > 0):
                    while ($row = mysqli_fetch_assoc($res)):
                        $img_file = !empty($row['gambar']) ? $row['gambar'] : 'default.png';
                ?>
                    <div style="background: #ffffff; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 10px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="width: 100%; height: 100px; background: #f8fafc; border-radius: 6px; overflow: hidden; margin-bottom: 8px; display: flex; align-items: center; justify-content: center;">
                                <img src="uploads/<?= htmlspecialchars($img_file); ?>" alt="<?= htmlspecialchars($row['nama_produk']); ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;" onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
                            </div>
                            <small style="color: var(--text-muted); font-size: 0.7rem; font-weight: 600; text-transform: uppercase;"><?= htmlspecialchars($row['kode_produk']); ?></small>
                            <h3 style="font-size: 0.85rem; font-weight: 600; margin: 2px 0 4px 0; color: var(--text-main); height: 2.4em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.2;"><?= htmlspecialchars($row['nama_produk']); ?></h3>
                            <div style="font-size: 0.95rem; font-weight: 700; color: var(--primary); margin-bottom: 4px;">Rp<?= number_format($row['harga'], 0, ',', '.'); ?></div>
                            <div style="font-size: 0.7rem; color: #065f46; font-weight: 600; margin-bottom: 8px;">Stok: <?= $row['stok']; ?></div>
                        </div>

                        <form method="POST" action="" style="display: flex; gap: 6px;">
                            <input type="hidden" name="id_produk" value="<?= $row['id_produk']; ?>">
                            <input type="number" name="jumlah" value="1" min="1" max="<?= $row['stok']; ?>" style="width: 45px; height: 32px; padding: 4px; text-align: center; font-size: 0.8rem;" required>
                            <button type="submit" name="tambah_keranjang" class="btn btn-primary" style="flex: 1; padding: 4px 8px; font-size: 0.75rem;">+ Tambah</button>
                        </form>
                    </div>
                <?php 
                    endwhile;
                else: 
                ?>
                    <div style="grid-column: 1 / -1; padding: 32px; text-align: center; color: var(--text-muted);">
                        Produk belum tersedia.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- KERANJANG & FORM PEMBAYARAN KOMPAK -->
        <div class="card" style="display: flex; flex-direction: column;">
            <h2>Keranjang Pembelian</h2>
            
            <!-- Tabel Keranjang (Batas Tinggi Disesuaikan) -->
            <div class="table-responsive" style="max-height: 220px; overflow-y: auto; margin-bottom: 12px;">
                <table>
                    <thead style="position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th>Barang</th>
                            <th>Harga</th>
                            <th style="text-align: center;">Qty</th>
                            <th>Subtotal</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grand_total = 0;
                        $total_items = 0;
                        ?>

                        <?php if (!empty($_SESSION['cart'])): ?>
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                                <?php
                                $sub = $item['harga'] * $item['jumlah'];
                                $grand_total += $sub;
                                $total_items += $item['jumlah'];
                                ?>
                                <tr>
                                    <td>
                                        <b style="color: var(--text-main); font-size: 0.8rem;"><?= htmlspecialchars($item['nama_produk']); ?></b><br>
                                        <small style="color: var(--text-muted); font-size: 0.7rem;"><?= htmlspecialchars($item['kode_produk']); ?></small>
                                    </td>
                                    <td style="font-size: 0.8rem;">Rp<?= number_format($item['harga'], 0, ',', '.'); ?></td>
                                    <td style="text-align: center; font-weight: 600; font-size: 0.8rem;"><?= $item['jumlah']; ?></td>
                                    <td style="font-weight: 600; font-size: 0.8rem; color: var(--text-main);">Rp<?= number_format($sub, 0, ',', '.'); ?></td>
                                    <td style="text-align: center;">
                                        <a href="index.php?hapus=<?= $item['id_produk']; ?>" class="btn btn-danger" style="padding: 2px 6px; font-size: 0.7rem; border-radius: 4px;">✕</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 24px 12px; font-size: 0.85rem;">
                                    Belum ada barang di keranjang.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Ringkasan Total & Form Pembayaran Ringkas -->
            <?php if (!empty($_SESSION['cart'])): ?>
                <form method="POST" action="" style="border-top: 1px solid var(--border); padding-top: 12px;">
                    <!-- Ringkasan Total -->
                    <div style="background: #f8fafc; border: 1px solid var(--border); padding: 10px 12px; border-radius: 6px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Total (<?= $total_items; ?> item):</span>
                        <span style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">Rp<?= number_format($grand_total, 0, ',', '.'); ?></span>
                    </div>

                    <!-- Input Grid 2 Kolom (Bikin hemat tempat) -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 8px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 0.7rem; margin-bottom: 2px;">Kasir</label>
                            <input type="text" name="nama_kasir" value="<?= htmlspecialchars($nama_kasir); ?>" placeholder="Nama Kasir" required style="padding: 6px 10px; font-size: 0.8rem;">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 0.7rem; margin-bottom: 2px;">Pelanggan</label>
                            <input type="text" name="nama_pelanggan" value="customer" placeholder="Nama Pelanggan" style="padding: 6px 10px; font-size: 0.8rem;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 12px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 0.7rem; margin-bottom: 2px;">Metode Pembayaran</label>
                            <select name="metode_pembayaran" required style="padding: 6px 10px; font-size: 0.8rem;">
                                <option value="Cash">Cash / Tunai</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label style="font-size: 0.7rem; margin-bottom: 2px;">Uang Bayar (Rp)</label>
                            <input type="number" id="uang_bayar" name="uang_bayar" min="<?= $grand_total; ?>" value="<?= $grand_total; ?>" required style="padding: 6px 10px; font-size: 0.85rem; font-weight: 700; color: var(--primary);">
                        </div>
                    </div>

                    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; padding: 8px 12px; border-radius: 6px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.8rem; color: #065f46;">Kembalian:</span>
                        <strong id="kembalian_display" style="font-size: 1rem; color: #047857;">Rp0</strong>
                    </div>

                    <!-- Tombol Aksi -->
                    <div style="display: flex; gap: 8px;">
                        <button type="submit" name="proses_bayar" class="btn btn-primary" style="flex: 3; padding: 10px; font-size: 0.85rem; justify-content: center; background: #059669; border-color: #059669;">
                            Bayar & Print Struk
                        </button>
                        <a href="index.php?reset=1" class="btn btn-secondary" onclick="return confirm('Kosongkan keranjang?')" style="flex: 1; padding: 10px; font-size: 0.85rem; justify-content: center; background: #f1f5f9; color: var(--text-main); border: 1px solid var(--border);">
                            Reset
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    const uangBayar = document.getElementById('uang_bayar');
    const kembalianDisplay = document.getElementById('kembalian_display');
    const totalBayar = <?= (float) $grand_total; ?>;

    function tampilkanKembalian() {
        const kembalian = Math.max(0, (parseFloat(uangBayar.value) || 0) - totalBayar);
        kembalianDisplay.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(kembalian);
    }

    uangBayar.addEventListener('input', tampilkanKembalian);
    tampilkanKembalian();
</script>

</body>
</html>