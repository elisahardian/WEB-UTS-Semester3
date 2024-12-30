<?php
session_start();
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpenjualan = $_POST['idpenjualan'];
    $idbarang = $_POST['idbarang'];
    $tanggal= $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];
    $dilayani_oleh = $_POST['dilayani_oleh'];
    $created_at = $_POST['created_at'];

    $stmt = $conn->prepare("INSERT INTO tabelpenjualan (idpenjualan, idbarang, tanggal, jumlah, dilayani_oleh, created_at) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $idpenjualan, $idbarang, $tanggal, $jumlah, $dilayani_oleh, $created_at);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data penjualan berhasil ditambahkan.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat menambahkan data.'];
    }
    $stmt->close();
    header("Location: penjualan.php");
    exit();
} else {
    header("Location: penjualan.php");
    exit();
}
?>