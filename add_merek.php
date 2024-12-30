<?php
session_start();
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idmerek = $_POST['idmerek'];
    $merek = $_POST['merek'];

    $stmt = $conn->prepare("INSERT INTO tabelmerek (idmerek, merek) VALUES (?, ?)");
    $stmt->bind_param("ss", $idmerek, $merek);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data merek berhasil ditambahkan.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat menambahkan data.'];
    }
    $stmt->close();
    header("Location: merek.php");
    exit();
} else {
    header("Location: merek.php");
    exit();
}
?>