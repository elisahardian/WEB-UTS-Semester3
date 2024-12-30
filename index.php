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
        .cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between; /* Ensure space between cards */
        }
        .cards-container .card {
            flex: 1 1 23%; /* Cards will take 23% of the width with space between */
            margin: 10px 0;
            box-sizing: border-box;
            min-width: 250px; /* Minimal width to ensure responsive */
        }
        .card-icon-wrapper {
            background-color: #f0f0f0;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-icon {
            font-size: 1.8rem;
            color: #333;
        }

        /* Styling for the full-width card */
        .full-width-card {
            width: 100%;
            margin-top: 20px;
            box-sizing: border-box;
        }

        .container-fluid {
            padding: 0 10px;
        }
    </style>

    <div class="content" id="content">
        <div class="container-fluid mt-3">
            <div class="cards-container">
                <!-- Card 1: pemasukan -->
                <div class="card card-tipe">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Total Pemasukan</h5>
                                <h4><p>Rp150.000.000,-</p></h4>
                            </div>
                            <div class="card-icon-wrapper">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: pengeluaran -->
                <div class="card card-stok">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Total Pengeluaran</h5>
                                <h4><p>Rp100.000.000,-</p></h4>
                            </div>
                            <div class="card-icon-wrapper">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: hutang -->
                <div class="card card-merek">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Total Hutang</h5>
                                <h4><p>Rp8.000.000,-</p></h4>
                            </div>
                            <div class="card-icon-wrapper">
                                <i class="fas fa-money-bill-wave card-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: piutang -->
                <div class="card card-polis">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Total Piutang</h5>
                                <h4><p>Rp10.000.000,-</p></h4>
                            </div>
                            <div class="card-icon-wrapper">
                                <i class="fas fa-file-invoice card-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Full-width card for Aplikasi Kepegawaian -->
            <div class="full-width-card">
                <div class="card w-100">
                    <div class="card-header"><strong>Aplikasi Toko Elektronik</strong></div>
                    <img src="foto/backgroundtoko.jpg" class="img-fluid" style="display:block; margin:auto;">
                </div>
            </div>
        </div>
    </div>

    <?php require 'footer.php'; ?>
</div>
</body>
</html>
