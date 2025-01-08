<?php
include('koneksi.php');

if (isset($_GET['id'])) {
    $id_dosen = $_GET['id'];

    $sql = "DELETE FROM dosen WHERE id_dosen = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_dosen);

    if ($stmt->execute()) {
        echo "<script>
            alert('Data dosen berhasil dihapus!');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus data dosen!');
            window.location.href = 'index.php';
        </script>";
    }

    $stmt->close();
} else {
    echo "<script>
        alert('ID dosen tidak ditemukan!');
        window.location.href = 'index.php';
    </script>";
}

$conn->close();
?>
