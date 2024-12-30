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
< class="wrapper">
    <header>
        <h4><?php echo htmlspecialchars($namaUsaha); ?></h4>
        <p><?php echo htmlspecialchars($alamatUsaha); ?></p>
    </header>

    <?php include 'sidebar.php'; ?>

    <style>
        .container {
            justify-content: center;
            align-items: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            height: 60vh;
            width: 80vh;
            margin-top: 10%;
            margin-left: 30%;
            border-radius: 10px;
    
        }
        
        h1 {
            font-family: Georgia;
        }

        label {
            display: block;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        button {
            margin-left: 16%;
            padding: 10px;
            background-color: rgb(2, 120, 161);
            color: white;
            border-radius: 10px;
            width: 70%;

        }
        button:hover {
            background: rgb(100, 120, 300);;
        }

    </style>

    <div class="container">
        <h1>Form Kontak</h1>
        <form action="" method="post">
            <label for="name">Nama: </label>
            <input type="text" id="name" name="name" require>

            <label for="email">Email: </label>
            <input type="email" id="email" name="email" required>

            <label for="message">Pesan: </label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">Kirim</button>
        </form>
    </div>

    <?php require 'footer.php'; ?>
</div>
</body>
</html>
