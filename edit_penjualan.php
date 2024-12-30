<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpenjualan = $_POST['idpenjualan'];
    $idbarang = $_POST['idbarang'];
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    $dilayani_oleh = $_POST['dilayani_oleh'];
    $created_at = $_POST['created_at'];

    $stmt = $conn->prepare("UPDATE tabelpenjualan SET idbarang = ?, tanggal = ?, jumlah = ?, dilayani_oleh = ?, created_at = ? WHERE idpenjualan = ?");
    $stmt->bind_param("ssssss", $idbarang, $tanggal, $jumlah, $dilayani_oleh, $created_at, $idpenjualan);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data penjualan berhasil diperbaharui.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbaharui data.'];
    }
    $stmt->close();
    header("Location: penjualan.php");
    exit();
} else {
    header("Location: penjualan.php");
    exit();
}
?>