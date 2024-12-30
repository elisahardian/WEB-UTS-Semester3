<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpiutang = $_POST['idpiutang'];

    $stmt = $conn->prepare("DELETE FROM tabelpiutang WHERE idpiutang = ?");
    $stmt->bind_param("s", $idpiutang);

    if ($stmt->execute()) {
        echo "Success: Data piutang berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
