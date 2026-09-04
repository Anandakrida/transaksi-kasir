<?php
session_start();
require_once 'config/database.php';
/** @var mysqli $conn */

$id_transaksi = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Query Transaksi & Kasir
$query_trx = mysqli_query($conn, "
    SELECT t.*, k.nama_kasir 
    FROM transaksi t 
    JOIN kasir k ON t.id_kasir = k.id_kasir 
    WHERE t.id_transaksi = '$id_transaksi'
");

$trx = mysqli_fetch_assoc($query_trx);

if (!$trx) {
    die("Data transaksi tidak ditemukan!");
}

// Gunakan nama kasir yang diedit pada halaman kasir.
$nama_kasir_struk = $_SESSION['nama_kasir'] ?? $trx['nama_kasir'];

// Query Detail Produk
$query_detail = mysqli_query($conn, "
    SELECT d.*, p.nama_produk, p.kode_produk 
    FROM detail_kasir d 
    JOIN produk p ON d.id_produk = p.id_produk 
    WHERE d.id_transaksi = '$id_transaksi'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk #<?= $trx['no_transaksi']; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="struk-wrapper">
    <div class="struk-box">
        <div class="struk-header">
            <h2>TOKO HP OFFICIAL</h2>
            <p>Jl. Gadget Store No. 88, Jakarta</p>
            <p>Telp: 0812-3456-7890</p>
        </div>

        <div class="divider"></div>

        <div>
            <p>No. Trx : <?= $trx['no_transaksi']; ?></p>
            <p>Tgl     : <?= date('d/m/Y H:i', strtotime($trx['tanggal_transaksi'])); ?></p>
            <p>Kasir   : <?= htmlspecialchars($nama_kasir_struk, ENT_QUOTES, 'UTF-8'); ?></p>
            <p>Pelanggan: <?= $trx['nama_pelanggan']; ?></p>
        </div>

        <div class="divider"></div>

        <table class="struk-table">
            <?php 
            $total_qty = 0;
            while ($item = mysqli_fetch_assoc($query_detail)): 
                $total_qty += $item['jumlah'];
            ?>
            <tr>
                <td colspan="2">
                    <b>[<?= $item['kode_produk']; ?>] <?= $item['nama_produk']; ?></b>
                </td>
            </tr>
            <tr>
                <td><?= $item['jumlah']; ?> x Rp<?= number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                <td class="text-right">Rp<?= number_format($item['subtotal'], 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <div class="divider"></div>

        <div class="flex-between">
            <span>Total Qty:</span>
            <span><?= $total_qty; ?> Pcs</span>
        </div>
        <div class="flex-between" style="font-weight: bold; font-size: 13px;">
            <span>TOTAL:</span>
            <span>Rp<?= number_format($trx['total_bayar'], 0, ',', '.'); ?></span>
        </div>
        <div class="flex-between">
            <span>Bayar (<?= $trx['metode_pembayaran']; ?>):</span>
            <span>Rp<?= number_format($trx['uang_bayar'], 0, ',', '.'); ?></span>
        </div>
        <div class="flex-between">
            <span>Kembali:</span>
            <span>Rp<?= number_format($trx['kembalian'], 0, ',', '.'); ?></span>
        </div>

        <div class="divider"></div>

        <div class="struk-footer">
            <p>--- TERIMA KASIH ---</p>
            <p>Barang yang sudah dibeli<br>tidak dapat dikembalikan.</p>
        </div>
    </div>
</div>

<div class="no-print" style="text-align: center; margin: 20px 0;">
    <button onclick="window.print()" class="btn btn-primary" style="padding: 10px 25px; font-size: 16px;">🖨️ Cetak / Print Struk</button>
    <a href="index.php" class="btn btn-secondary" style="padding: 10px 25px; font-size: 16px;">⬅️ Kembali ke Kasir</a>
</div>

<!-- AUTO PRINT TRIGGER -->
<?php if (isset($_GET['autoprint']) && $_GET['autoprint'] == 'true'): ?>
<script>
    window.onload = function() {
        window.print();
    };
</script>
<?php endif; ?>

</body>
</html>
