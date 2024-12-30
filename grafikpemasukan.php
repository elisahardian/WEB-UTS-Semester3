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
        <h4><b><?php echo htmlspecialchars($namaUsaha); ?></b></h4>
        <p><?php echo htmlspecialchars($alamatUsaha); ?></p>
    </header>

    <?php include 'sidebar.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        h1 {
            text-align: center;
            color: #333;
            padding-left: 100px;
            padding-top: 100px;
        }
        .hempat {
            text-align: center;
            color: #333;
            padding-left: 100px;
        }
        .chart-container {
            width: 42%;
            margin-left: 500px;
        }
    </style>

    
        <h1>Grafik Pemasukan Toko Elektronik</h1>
        <h4 class="hempat">Hasil Laporan Pemasukan Toko Elektronik</h4>
    <div class="chart-container">   
        <canvas id="incomePieChart"></canvas>
    </div>
    <div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('incomePieChart').getContext('2d');
                const incomePieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                        datasets: [{
                            label: 'Transaksi (dalam juta rupiah)',
                            data: [15000, 20000, 18200, 16000, 27000, 17000, 12000, 14000, 20000, 21000, 11000, 25000],
                            backgroundColor: [
                                'rgba(255, 105, 180, 1)',
                                'rgba(100, 200, 255, 1)',
                                'rgba(255, 220, 100, 1)',
                                'rgba(100, 255, 255, 1)',
                                'rgba(200, 150, 255, 1)',
                                'rgba(255, 165, 85, 1)',
                                'rgba(230, 230, 230, 1)',
                                'rgba(130, 170, 255, 1)',
                                'rgba(200, 180, 80, 1)',
                                'rgba(120, 255, 180, 1)',
                                'rgba(200, 180, 255, 1)',
                                'rgba(255, 210, 150, 1)'

                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 0.4)',
                                'rgba(54, 162, 235, 0.4)',
                                'rgba(255, 206, 86, 0.4)',
                                'rgba(75, 192, 192, 0.4)',
                                'rgba(153, 102, 255, 0.4)',
                                'rgba(255, 159, 64, 0.4)',
                                'rgba(199, 199, 199, 0.4)',
                                'rgba(83, 102, 255, 0.4)',
                                'rgba(155, 159, 64, 0.4)',
                                'rgba(75, 192, 152, 0.4)',
                                'rgba(153, 142, 255, 0.4)',
                                'rgba(255, 199, 132, 0.4)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Diagram Lingkaran Pemasukan Toko Tahunan'
                            }
                        }
                    }
                });
            });
        </script>
    </div>

    <?php require 'footer.php'; ?>
</div>
</body>
</html>
