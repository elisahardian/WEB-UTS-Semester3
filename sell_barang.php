<?php
session_start();
require 'config.php';

if (!isset($_SESSION['iduser'])) {
    echo "Akses tidak sah.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idbarang = $_POST['idbarang'];
    $jumlah = $_POST['jumlah'];

    // Update stok di tabelbarang
    $stmt = $conn->prepare("UPDATE tabelbarang SET stok = stok - ? WHERE idbarang = ?");
    $stmt->bind_param("ss", $jumlah, $idbarang);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Success: Stok berhasil diperbarui.";
        } else {
            echo "Error: Tidak ada stok yang diperbarui.";
        }
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Permintaan tidak valid.";
}

$conn->close();
?>