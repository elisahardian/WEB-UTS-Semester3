<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $foto = $_POST['foto'];
    $idbarang = $_POST['idbarang'];
    $idkategori = $_POST['idkategori'];
    $idmerek = $_POST['idmerek'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $keterangan = $_POST['keterangan'];
    $created_at = $_POST['created_at'];
    
    $stmt = $conn->prepare("UPDATE tabelbarang SET foto = ?, idkategori = ?, idmerek = ?, nama = ?, harga = ?, stok = ?, keterangan = ?, created_at = ? WHERE idbarang = ?");
    $stmt->bind_param("sssssssss", $foto, $idkategori, $idmerek, $nama, $harga, $stok, $keterangan, $created_at, $idbarang);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data barang berhasil diperbaharui.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbaharui data.'];
    }
    $stmt->close();
    header("Location: barang.php");
    exit();
} else {
    header("Location: barang.php");
    exit();
}
?>