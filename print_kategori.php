<?php

session_start();
require 'config.php';
require 'fpdf/fpdf.php';

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
    exit();
}

//ambil data nama usaha dan alamat dari database
$stmt = $conn->prepare("SELECT nama, alamat FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha);
$stmt->fetch();
$stmt->close();

//ambil data dari tabel kategori
$result = $conn->query("SELECT * FROM tabelkategori");

//buat pdf
$pdf = new FPDF();
$pdf->AddPage();


// Tambahkan logo di sisi kiri dan nama perusahaan serta alamat di sisi kanan
$logoFile = 'foto/logo.jpg'; // Path ke file logo
$logoWidth = 30; // Lebar logo
$logoHeight = 30; // Tinggi logo

// Logo
$pdf->Image($logoFile, 160, 10, $logoWidth, $logoHeight);


//tambahkan kop dokumen
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, $namaUsaha, 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->SetLineWidth(0.5);
$pdf->Cell(0, 10, $alamatUsaha, 0, 1, 'C');
$pdf->SetXY(85, $pdf->GetY());
$pdf->Line(85, $pdf->GetY(), 125, $pdf->GetY());
$pdf->Ln(15);


$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Daftar Kategori', 0, 1, 'L');
$pdf->Ln(3);


//tambahkan header table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(20, 10, 'No', 1, 0, 'C');
$pdf->Cell(85, 10, 'idkategori', 1, 0, 'C');
$pdf->Cell(85, 10, 'kategori', 1, 1, 'C');

//tambahkan data tabel
$pdf->SetFont('Arial', '', 12);
$no = 1;
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(20, 10, $no++, 1, 0, 'C');
    $pdf->Cell(85, 10, $row['idkategori'], 1, 0, 'C');
    $pdf->Cell(85, 10, $row['kategori'], 1, 1, 'C');

}


//output pdf
$pdf->Output('I', 'Daftar_kategori.pdf');


// // Tambahkan tanda tangan
// $pdf->Ln(20); // Jarak dari konten sebelumnya
// $pdf->Image('foto/tandatangan.jpeg', 10, $pdf->GetY(), 40); // Sesuaikan posisi dan ukuran gambar
// $pdf->Cell(0, 10, 'Pimpinan Toko Elektronik', 0, 1, 'L');
// $pdf->Ln(20); // Jarak sebelum output


?>