<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'peng_mas';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Hapus data di laporan_dosen yang terkait dengan id_proposal
if (isset($_GET['id_proposal'])) {
    $id_proposal = intval($_GET['id_proposal']);

    // Hapus data terkait di tabel laporan_dosen
    $stmt = $conn->prepare("DELETE FROM laporan_dosen WHERE id_proposal = ?");
    $stmt->bind_param("i", $id_proposal);
    $stmt->execute();

    // Setelah menghapus data terkait, baru hapus data di tabel proposal_dosen
    $stmt = $conn->prepare("DELETE FROM proposal_dosen WHERE id_proposal = ?");
    $stmt->bind_param("i", $id_proposal);

    if ($stmt->execute()) {
        echo "Proposal berhasil dihapus!";
        header("Location: index.php");
        exit();
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }
} else {
    echo "ID Proposal tidak valid.";
}

// Tutup koneksi
$conn->close();
?>
