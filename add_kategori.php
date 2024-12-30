<?php
session_start();
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idkategori = $_POST['idkategori'];
    $kategori = $_POST['kategori'];

    $stmt = $conn->prepare("INSERT INTO tabelkategori (idkategori, kategori) VALUES (?, ?)");
    $stmt->bind_param("ss", $idkategori, $kategori);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data kategori berhasil ditambahkan.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat menambahkan data.'];
    }
    $stmt->close();
    header("Location: kategori.php");
    exit();
} else {
    header("Location: kategori.php");
    exit();
}
?>