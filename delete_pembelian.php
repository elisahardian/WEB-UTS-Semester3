<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpembelian = $_POST['idpembelian'];

    $stmt = $conn->prepare("DELETE FROM tabelpembelian WHERE idpembelian = ?");
    $stmt->bind_param("s", $idpembelian);

    if ($stmt->execute()) {
        echo "Success: Data pembelian berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
