<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idkategori = $_POST['idkategori'];

    $stmt = $conn->prepare("DELETE FROM tabelkategori WHERE idkategori = ?");
    $stmt->bind_param("s", $idkategori);

    if ($stmt->execute()) {
        echo "Success: Data kategori berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
