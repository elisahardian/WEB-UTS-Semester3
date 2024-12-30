<?php
include 'config.php'; // Pastikan ini sesuai dengan file koneksi kamu

// Dapatkan nomor urut terbaru untuk idpembelian baru
$stmt = $conn->query("SELECT idpembelian FROM tabelpembelian ORDER BY idpembelian DESC LIMIT 1");
$latestidpembelian = $stmt->fetch_assoc();
$urut = 1;
if ($latestidpembelian) {
    $latestNumber = (int) substr($latestidpembelian['idpembelian'], 3); // Ambil angka setelah 'BUY'
    $urut = $latestNumber + 1;
}
$newidpembelian = 'BUY' . str_pad($urut, 3, '0', STR_PAD_LEFT);

echo json_encode(['new_id' => $newidpembelian]);
?>
