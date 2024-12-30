<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idkategori = $_POST['idkategori'];
    $kategori = $_POST['kategori'];

    $stmt = $conn->prepare("UPDATE tabelkategori SET kategori = ? WHERE idkategori = ?");
    $stmt->bind_param("ss", $kategori, $idkategori);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data kategori berhasil diperbaharui.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbaharui data.'];
    }
    $stmt->close();
    header("Location: kategori.php");
    exit();
} else {
    header("Location: kategori.php");
    exit();
}
?>