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

    <h1>Grafik Penjualan Toko Elektronik</h1>
    <h4>Hasil Laporan Penjualan Toko Elektronik</h4>
    <canvas id="myChart"></canvas>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                datasets: [{
                    label: 'Penjualan Toko Elektronik',
                    data: [15, 27, 77, 54, 81, 56, 91, 88, 63, 90, 78 ], // Data ini bisa diubah sesuai kebutuhan
                    backgroundColor: [
                        'rgba(75, 192, 192, 1)', // Teal
                        'rgba(153, 102, 255, 1)', // Purple
                        'rgba(255, 159, 64, 1)', // Orange

                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)', // Teal
                        'rgba(153, 102, 255, 1)', // Purple
                        'rgba(255, 159, 64, 1)', // Orange
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
