<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idmerek = $_POST['idmerek'];

    $stmt = $conn->prepare("DELETE FROM tabelmerek WHERE idmerek = ?");
    $stmt->bind_param("s", $idmerek);

    if ($stmt->execute()) {
        echo "Success: Data merek berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
