<?php
session_start();
require_once 'koneksi.php'; // Ganti dengan file koneksi database Anda

if (!isset($_SESSION['id_login'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id_mentoring_eval'])) {
    $id_mentoring_eval = $_GET['id_mentoring_eval'];

    $sql = "DELETE FROM mentoring_evaluasi WHERE id_mentoring_eval = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_mentoring_eval);

    if ($stmt->execute()) {
        header('Location: index_p2m.php?content=mentoring');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ID Mentoring Eval tidak ditemukan!";
}
?>
