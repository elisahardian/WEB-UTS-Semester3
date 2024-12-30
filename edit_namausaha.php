<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $notelepon = $_POST['notelepon'];
    $fax = $_POST['fax'];
    $email = $_POST['email'];
    $npwp = $_POST['npwp'];
    $bank = $_POST['bank'];
    $noaccount = $_POST['noaccount'];
    $atasnama = $_POST['atasnama'];
    $pimpinan = $_POST['pimpinan'];


    $stmt = $conn->prepare("UPDATE namausaha SET nama = ?, alamat = ?, notelepon = ?, fax = ?, email = ?, npwp = ?, bank = ?, noaccount = ?, atasnama = ?, pimpinan = ?");
    $stmt->bind_param("ssssssssss", $nama, $alamat, $notelepon, $fax, $email, $npwp, $bank, $noaccount, $atasnama, $pimpinan);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data namausaha berhasil diperbaharui.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbaharui data.'];
    }
    $stmt->close();
    header("Location: namausaha.php");
    exit();
} else {
    header("Location: namausaha.php");
    exit();
}
?>