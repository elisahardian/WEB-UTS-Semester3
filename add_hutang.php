<?php
session_start();
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idhutang = $_POST['idhutang'];
    $nama = $_POST['nama'];
    $tanggal= $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];

    $stmt = $conn->prepare("INSERT INTO tabelhutang (idhutang, nama, tanggal, jumlah) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $idhutang, $nama, $tanggal, $jumlah);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data hutang berhasil ditambahkan.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat menambahkan data.'];
    }
    $stmt->close();
    header("Location: hutang.php");
    exit();
} else {
    header("Location: hutang.php");
    exit();
}
?>