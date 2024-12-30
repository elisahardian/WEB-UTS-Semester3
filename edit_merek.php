<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idmerek = $_POST['idmerek'];
    $merek = $_POST['merek'];

    $stmt = $conn->prepare("UPDATE tabelmerek SET merek = ? WHERE idmerek = ?");
    $stmt->bind_param("ss", $merek, $idmerek);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data merek berhasil diperbaharui.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbaharui data.'];
    }
    $stmt->close();
    header("Location: merek.php");
    exit();
} else {
    header("Location: merek.php");
    exit();
}
?>