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

    <h1>Grafik Pengeluaran Toko Elektronik</h1>
    <h4 class="hempat">Hasil Laporan Pengeluaran Toko Elektronik</h4>
    <!-- <canvas id="myChart"></canvas> -->
    <div class="chart-container">
        <canvas id="expensePieChart"></canvas>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('expensePieChart').getContext('2d');
            const expensePieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    datasets: [{
                        label: 'Pengeluaran (dalam juta rupiah)',
                        data: [23000, 21000, 12000, 12000, 20000, 21000, 8000, 10000, 15000, 30000, 26000, 14000],
                        backgroundColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(199, 199, 199, 1)',
                            'rgba(83, 102, 255, 1)',
                            'rgba(155, 159, 64, 1)',
                            'rgba(75, 192, 152, 1)',
                            'rgba(153, 142, 255, 1)',
                            'rgba(255, 199, 132, 1)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(199, 199, 199, 1)',
                            'rgba(83, 102, 255, 1)',
                            'rgba(155, 159, 64, 1)',
                            'rgba(75, 192, 152, 1)',
                            'rgba(153, 142, 255, 1)',
                            'rgba(255, 199, 132, 1)'
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
                            text: 'Diagram Lingkaran Pengeluaran Toko Tahunan'
                        }
                    }
                }
            });
        });
    </script>

    <?php require 'footer.php'; ?>
</div>
</body>
</html>
