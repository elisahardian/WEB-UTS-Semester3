<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpenjualan = $_POST['idpenjualan'];

    $stmt = $conn->prepare("DELETE FROM tabelpenjualan WHERE idpenjualan = ?");
    $stmt->bind_param("s", $idpenjualan);

    if ($stmt->execute()) {
        echo "Success: Data penjualan berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
