<?php
include 'config.php'; // Pastikan ini sesuai dengan file koneksi kamu

// Dapatkan nomor urut terbaru untuk idpenjualan baru
$stmt = $conn->query("SELECT idpenjualan FROM tabelpenjualan ORDER BY idpenjualan DESC LIMIT 1");
$latestidpenjualan = $stmt->fetch_assoc();
$urut = 1;
if ($latestidpenjualan) {
    $latestNumber = (int) substr($latestidpenjualan['idpenjualan'], 4); // Ambil angka setelah 'SELL'
    $urut = $latestNumber + 1;
}
$newidpenjualan = 'SELL' . str_pad($urut, 3, '0', STR_PAD_LEFT);

echo json_encode(['new_id' => $newidpenjualan]);
?>
