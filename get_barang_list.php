<?php
require 'config.php';

// Ambil data barang dari database
$barang = $conn->query("SELECT idbarang, nama FROM tabelbarang");

$options = '';
while ($row = $tabelbarang->fetch_assoc()) {
    $options .= "<option value='" . htmlspecialchars($row['idbarang']) . "'>" . htmlspecialchars($row['nama']) . "</option>";
}

echo $options;
?>
