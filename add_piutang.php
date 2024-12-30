<?php
session_start();
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idpiutang = $_POST['idpiutang'];
    $nama = $_POST['nama'];
    $tanggal= $_POST['tanggal'];
    $jumlah = $_POST['jumlah'];

    $stmt = $conn->prepare("INSERT INTO tabelpiutang (idpiutang, nama, tanggal, jumlah) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $idpiutang, $nama, $tanggal, $jumlah);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data piutang berhasil ditambahkan.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat menambahkan data.'];
    }
    $stmt->close();
    header("Location: piutang.php");
    exit();
} else {
    header("Location: piutang.php");
    exit();
}
?>