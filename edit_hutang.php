<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idhutang = $_POST['idhutang'];
    $nama = $_POST['nama'];
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];

    $stmt = $conn->prepare("UPDATE tabelhutang SET nama = ?, tanggal = ?, jumlah = ? WHERE idhutang = ?");
    $stmt->bind_param("ssss", $nama, $tanggal, $jumlah, $idhutang);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data hutang berhasil diperbaharui.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbaharui data.'];
    }
    $stmt->close();
    header("Location: hutang.php");
    exit();
} else {
    header("Location: hutang.php");
    exit();
}
?>