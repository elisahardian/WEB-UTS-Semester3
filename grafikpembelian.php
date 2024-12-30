<?php
session_start();
require 'config.php';

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
    exit();
}

$iduser = $_SESSION['iduser'];

// Ambil data user dari database
$stmt = $conn->prepare("SELECT username, email FROM login WHERE iduser = ?");
$stmt->bind_param("i", $iduser);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();

// Ambil data nama usaha dan alamat dari database
$stmt = $conn->prepare("SELECT nama, alamat FROM namausaha LIMIT 1");
$stmt->execute();
$stmt->bind_result($namaUsaha, $alamatUsaha);
$stmt->fetch();
$stmt->close();
?>

<?php require 'head.php'; ?>
<div class="wrapper">
    <header>
        <h4><?php echo htmlspecialchars($namaUsaha); ?></h4>
        <p><?php echo htmlspecialchars($alamatUsaha); ?></p>
    </header>

    <?php include 'sidebar.php'; ?>

    <style>
        canvas { 
            width: 600px; 
            margin: auto; 
            padding-top: 30px;
        }
        h1 {
            font-weight: bold;
            font-family: Georgia;
            margin-top: 100px;
            margin-bottom: 15px;
            text-align:center;
        }
        h4 {
            font-family: Arial;
            text-align:center;
        }
    </style>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <h1>Grafik Pembelian Toko Elektronik</h1>
    <h4>Hasil Laporan Pembelian Toko Elektronik</h4>
    <canvas id="myChart"></canvas>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                datasets: [{
                    label: 'Pembelian Produk',
                    data: [40, 56, 32, 21, 29, 11, 20, 12, 28, 32, 48, 55], // Data ini bisa diubah sesuai kebutuhan
                    backgroundColor: [
                        'rgba(255, 127, 80, 1)',   // Coral
                        'rgba(100, 250, 200, 1)',  // Mint
                        'rgba(255, 245, 120, 1)',  // Lemon
                        'rgba(80, 255, 220, 1)',   // Turquoise
                        'rgba(180, 120, 255, 1)',  // Lavender
                        'rgba(255, 190, 70, 1)',   // Gold
                        'rgba(240, 240, 240, 1)',  // Light Grey
                        'rgba(130, 180, 255, 1)',  // Sky Blue
                        'rgba(220, 200, 80, 1)',   // Bright Olive
                        'rgba(100, 255, 150, 1)',  // Light Green
                        'rgba(180, 160, 255, 1)',  // Light Purple
                        'rgba(255, 230, 150, 1)'   // Light Cream

                    ],
                    borderColor: [
                        'rgba(255, 127, 80, 1)',   // Coral
                        'rgba(100, 250, 200, 1)',  // Mint
                        'rgba(255, 245, 120, 1)',  // Lemon
                        'rgba(80, 255, 220, 1)',   // Turquoise
                        'rgba(180, 120, 255, 1)',  // Lavender
                        'rgba(255, 190, 70, 1)',   // Gold
                        'rgba(240, 240, 240, 1)',  // Light Grey
                        'rgba(130, 180, 255, 1)',  // Sky Blue
                        'rgba(220, 200, 80, 1)',   // Bright Olive
                        'rgba(100, 255, 150, 1)',  // Light Green
                        'rgba(180, 160, 255, 1)',  // Light Purple
                        'rgba(255, 230, 150, 1)'   // Light Cream

                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <?php require 'footer.php'; ?>
</div>
</body>
</html>
