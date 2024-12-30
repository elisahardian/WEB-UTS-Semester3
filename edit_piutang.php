<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpiutang = $_POST['idpiutang'];
    $nama = $_POST['nama'];
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];

    $stmt = $conn->prepare("UPDATE tabelpiutang SET nama = ?, tanggal = ?, jumlah = ? WHERE idpiutang = ?");
    $stmt->bind_param("ssss", $nama, $tanggal, $jumlah, $idpiutang);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data piutang berhasil diperbaharui.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbaharui data.'];
    }
    $stmt->close();
    header("Location: piutang.php");
    exit();
} else {
    header("Location: piutang.php");
    exit();
}
?>