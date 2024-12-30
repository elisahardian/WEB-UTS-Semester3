<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idhutang = $_POST['idhutang'];

    $stmt = $conn->prepare("DELETE FROM tabelhutang WHERE idhutang = ?");
    $stmt->bind_param("s", $idhutang);

    if ($stmt->execute()) {
        echo "Success: Data hutang berhasil dihapus.";
    } else {
        echo "Error: Terjadi kesalahan saat menghapus data.";
    }
    $stmt->close();
} else {
    echo "Error: Invalid request.";
}
?>
