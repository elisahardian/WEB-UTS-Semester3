<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idbarang = $_POST['idbarang'];

    $stmt = $conn->prepare("SELECT * FROM tabelbarang WHERE idbarang = ?");
    $stmt->bind_param("s", $idbarang);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    echo json_encode($data);
    $stmt->close();
}
?>