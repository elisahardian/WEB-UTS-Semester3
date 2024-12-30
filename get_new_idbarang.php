<?php
include 'config.php'; // Pastikan ini sesuai dengan file koneksi kamu

// Dapatkan nomor urut terbaru untuk idbarang baru
$stmt = $conn->query("SELECT idbarang FROM tabelbarang ORDER BY idbarang DESC LIMIT 1");
$latestidbarang = $stmt->fetch_assoc();
$urut = 1;
if ($latestidbarang) {
    $latestNumber = (int) substr($latestidbarang['idbarang'], 1); // Ambil angka setelah 'B'
    $urut = $latestNumber + 1;
}
$newidbarang = 'B' . str_pad($urut, 3, '0', STR_PAD_LEFT);

echo json_encode(['new_id' => $newidbarang]);
?>
