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

//ambil data dari tabel barang
$result = $conn->query("SELECT * FROM tabelbarang");

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


$pdf->Cell(0, 10, 'Daftar barang', 0, 1, 'L');
$pdf->Ln(3);

//tambahkan header table
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(15, 10, 'No', 1, 0, 'C');
$pdf->Cell(20, 10, 'idbarang', 1, 0, 'C');
$pdf->Cell(20, 10, 'idkategori', 1, 0, 'C');
$pdf->Cell(20, 10, 'idmerek', 1, 0, 'C');
$pdf->Cell(30, 10, 'nama', 1, 0, 'C');
$pdf->Cell(20, 10, 'harga', 1, 0, 'C');
$pdf->Cell(20, 10, 'stok', 1, 0, 'C');
$pdf->Cell(45, 10, 'keterangan', 1, 1, 'C');

//tambahkan data tabel
$pdf->SetFont('Arial', '', 12);
$no = 1;
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(15, 10, $no++, 1, 0, 'C');
    $pdf->Cell(20, 10, $row['idbarang'], 1, 0, 'C');
    $pdf->Cell(20, 10, $row['idkategori'], 1, 0, 'C');
    $pdf->Cell(20, 10, $row['idmerek'], 1, 0, 'C');
    $pdf->Cell(30, 10, $row['nama'], 1, 0, 'C');
    $pdf->Cell(20, 10, $row['harga'], 1, 0, 'C');
    $pdf->Cell(20, 10, $row['stok'], 1, 0, 'C');
    $pdf->Cell(45, 10, $row['keterangan'], 1, 1, 'C');
}

//output pdf
$pdf->Output('I', 'Daftar_barang.pdf');
exit();
?>
