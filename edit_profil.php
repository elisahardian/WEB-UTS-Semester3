<?php
session_start();
require 'config.php';

// Cek apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $iduser= isset($_POST['iduser']) ? $_POST['iduser'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : null;
    $notelepon = isset($_POST['notelepon']) ? $_POST['notelepon'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;
    $nik = isset($_POST['nik']) ? $_POST['nik'] : null;


    // Validasi jika semua data ada
    if ($iduser && $email && $username && $alamat&& $notelepon && $status && $nik) {
        // Query untuk memperbarui data peringatan
        $stmt = $conn->prepare("UPDATE login SET iduser = ?, email = ?, username = ?, alamat = ?, notelepon = ?, status = ?, nik = ?,  WHERE iduser = ?");
        $stmt->bind_param("sssssss", $iduse, $email, $username, $alamat, $notelepon, $status, $nik);

        // Mengeksekusi query dan memberikan feedback kepada user
        if ($stmt->execute()) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Data profil admin berhasil diperbarui.'];
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat memperbarui data profil admin.'];
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Data yang dikirim tidak lengkap.'];
    }

    // Redirect kembali ke halaman peringatan setelah proses selesai
    header("Location: profiladmin.php");
    exit();
} else {
    // Jika request bukan POST, redirect ke halaman peringatan
    header("Location: profiladmin.php");
    exit();
}
?>
