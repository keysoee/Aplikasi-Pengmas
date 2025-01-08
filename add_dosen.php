<?php
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_dosen = $_POST['nama_dosen'];
    $email_dosen = $_POST['email_dosen'];
    $nik = $_POST['nik'];

    if (!empty($nama_dosen) && !empty($email_dosen) && !empty($nik)) {
        session_start();
        if (isset($_SESSION['id_login'])) {
            $id_login = $_SESSION['id_login'];  
        } else {
            $id_login = NULL;  
        }

        $sql = "INSERT INTO dosen (id_login, NIK, nama, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $id_login, $nik, $nama_dosen, $email_dosen);

        if ($stmt->execute()) {
            echo "<script>
                alert('Data dosen berhasil ditambahkan!');
                window.location.href = 'index.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menambahkan data dosen!');
                window.location.href = 'index.php';
            </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
            alert('Semua field harus diisi!');
            window.location.href = 'index.php';
        </script>";
    }
}

$conn->close();
?>
