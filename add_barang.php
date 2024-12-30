
<?php
session_start();
require 'config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    //ambil idbarang. UNTUK FOTO BISA
    $idbarang = $_POST['idbarang'];
    //cek apakah foto sudah di upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileSize = $_FILES['foto']['size'];
        $fileType = $_FILES['foto']['type'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        // tentukan folder tempat penyimpanann foto
        $uploadFolder = 'foto/';
        //tentukan nama filr baru sesuai idbarang
        $newFileName = $idbarang . '.jpg';
        //tentukan path lengkap untuk penyimpanan file
        $dest_path = $uploadFolder . $newFileName;
        //pindah file ke folder tujuan
        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            echo "file berhasil diupload";
        } else {
            echo "terjadi kesalahan saat mengupload file";
        } 
    } else {
        echo "tidak ada file yang di upload atau terjadi kesalahan";
    }


    $idbarang = $_POST['idbarang'];
    $idkategori = $_POST['idkategori'];
    $idmerek = $_POST['idmerek'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $keterangan = $_POST['keterangan'];

    $stmt = $conn->prepare("INSERT INTO tabelbarang (idbarang, idkategori, idmerek, nama, harga, stok, keterangan) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss", $idbarang, $idkategori, $idmerek, $nama, $harga, $stok, $keterangan);

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Data barang berhasil ditambahkan.'];
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Terjadi kesalahan saat menambahkan data.'];
    }
    $stmt->close();
    header("Location: barang.php");
    exit();
} else {
    header("Location: barang.php");
    exit();
}
?>