<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpembelian = $_POST['idpembelian'];
    $idbarang = $_POST['idbarang'];
    $tanggal = $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    $dilayani_oleh = $_POST['dilayani_oleh'];
    $created_at = $_POST['created_at'];

    $stmt = $conn->prepare("UPDATE tabelpembelian SET idbarang = ?, tanggal = ?, jumlah = ?, dilayani_oleh = ?, created_at = ? WHERE idpembelian = ?");
    $stmt->bind_param("ssssss", $idbarang, $tanggal, $jumlah, $dilayani_oleh, $created_at, $idpembelian);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data pembelian berhasil diperbaharui.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbaharui data.'];
    }
    $stmt->close();
    header("Location: pembelian.php");
    exit();
} else {
    header("Location: pembelian.php");
    exit();
}
?>