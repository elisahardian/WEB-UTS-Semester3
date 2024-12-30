<?php
session_start();
require 'config.php';

if (!isset($_SESSION['iduser'])) {
    header("Location: login.php");
    exit();
}

$iduser = $_SESSION['iduser'];

// Ambil data user dari database
$stmt = $conn->prepare("SELECT iduser, email, username, alamat, notelepon, status, nik FROM login WHERE iduser = ?");
$stmt->bind_param("i", $iduser);
$stmt->execute();
$stmt->bind_result( $iduser,  $email, $username, $alamat, $notelepon, $status, $nik);
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
         .identitas-usaha {
            font-size: 22px;
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 40px;
            background-color: #fff;
            box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
            margin: 200px;
            border-radius: 8px;
        }

        .foto img {
            width: 350px;
            height: 350px;
            border: 2px;
            border-radius: 20px;
        }

        .info {
            max-width: 600px;
        }

        .box {
            padding: 10px;
            border: 1px;
            border-radius: 10px;
            background-color: #add8e6;

        }

        table {
            border-collapse: collapse;
            width: 100%;
            }

        th, td {
            padding: 11px ;
            text-align: left;
            border: 2px solid #ddd;
        }
    </style>

    <div class="container">
        
        <div class="identitas-usaha">
            <div class="foto">
            <img src="foto/admin/<?php echo htmlspecialchars($username); ?>.jpg" alt="User Photo" class="user-photo">
            </div>
            <div class="info">
                <h3 class="box"><?php echo "Hallo, " . $username . "!"; ?> </h3>
                <table>
                    <thead>
                    <tr>
                        <td><b>ID User</b></td>
                        <td><?php echo $iduser; ?></td>
                    </tr>
                    <tr>
                        <td><b>Nama</b></td>
                        <td><?php echo $username; ?></td>
                    </tr>
                    <tr>
                        <td><b>Email</b></td>
                        <td><?php echo $email; ?></td>
                    </tr>
                    
                    <tr>
                        <td><b>Alamat</b></td>
                        <td><?php echo $alamat; ?></td>
                    </tr>
                    <tr>
                        <td><b>No Telepon</b></td>
                        <td><?php echo $notelepon; ?></td>
                    </tr>
                    <tr>
                        <td><b>NIK</b></td>
                        <td><?php echo $nik; ?></td>
                    </tr>              
                    </thead>
                </table>        
            </div>
        </div>
    </div>



    <?php require 'footer.php'; ?>
</div>
</body>
</html>
