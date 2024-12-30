<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idbarang = $_POST['idbarang'];

    $stmt = $conn->prepare("DELETE FROM tabelbarang WHERE idbarang = ?");
    $stmt->bind_param("s", $idbarang);

    if ($stmt->execute()) {
        echo "Success: Data berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>