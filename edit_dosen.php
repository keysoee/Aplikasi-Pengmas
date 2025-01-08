<?php
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_dosen = $_POST['id_dosen'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $nik = $_POST['nik'];

    $sql = "UPDATE dosen SET nama = ?, email = ?, NIK = ? WHERE id_dosen = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nama, $email, $nik, $id_dosen);

    if ($stmt->execute()) {
        echo "<script>
            alert('Data dosen berhasil diperbarui!');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal memperbarui data dosen!');
        </script>";
    }

    $stmt->close();
}

if (isset($_GET['id'])) {
    $id_dosen = $_GET['id'];

    $sql = "SELECT * FROM dosen WHERE id_dosen = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_dosen);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<script>
            alert('Data dosen tidak ditemukan!');
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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Dosen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Edit Data Dosen</h2>
    <form action="edit_dosen.php" method="POST">
        <input type="hidden" name="id_dosen" value="<?= $row['id_dosen'] ?>">
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" value="<?= $row['nama'] ?>" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= $row['email'] ?>" required><br>
        
        <label for="nik">NIK:</label>
        <input type="text" id="nik" name="nik" value="<?= $row['NIK'] ?>" required><br>
        
        <button type="submit">Simpan Perubahan</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>
