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

//ambil data dari tabel hutang
$result = $conn->query("SELECT * FROM tabelhutang");

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


$pdf->Cell(0, 10, 'Daftar hutang', 0, 1, 'L');
$pdf->Ln(3);

//tambahkan header table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(15, 10, 'No', 1, 0, 'C');
$pdf->Cell(25, 10, 'idhutang', 1, 0, 'C');
$pdf->Cell(50, 10, 'nama', 1, 0, 'C');
$pdf->Cell(65, 10, 'tanggal', 1, 0, 'C');
$pdf->Cell(30, 10, 'jumlah', 1, 1, 'C');

//tambahkan data tabel
$pdf->SetFont('Arial', '', 12);
$no = 1;
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(15, 10, $no++, 1, 0, 'C');
    $pdf->Cell(25, 10, $row['idhutang'], 1, 0, 'C');
    $pdf->Cell(50, 10, $row['nama'], 1, 0, 'C');
    $pdf->Cell(65, 10, $row['tanggal'], 1, 0, 'C');
    $pdf->Cell(30, 10, $row['jumlah'], 1, 1, 'C');
}

//output pdf
$pdf->Output('I', 'Daftar_hutang.pdf');
?>