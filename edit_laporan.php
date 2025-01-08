<?php
$koneksi = new mysqli('localhost', 'root', '', 'peng_mas');

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if (isset($_GET['id_laporan'])) {
    $id_laporan = $_GET['id_laporan'];

    // Ambil data laporan berdasarkan ID
    $query = "SELECT * FROM laporan_dosen WHERE id_laporan = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_laporan);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        die("Data tidak ditemukan.");
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laporan P2M</title>
</head>
<body>

<h3>Edit Laporan P2M</h3>

<form method="POST" action="proses_edit_laporan.php">
    <input type="hidden" name="id_laporan" value="<?= $data['id_laporan'] ?>">

    <label for="id_proposal">ID Proposal:</label>
    <input type="text" name="id_proposal" value="<?= $data['id_proposal'] ?>" disabled><br><br>

    <label for="id_p2m">ID P2M:</label>
    <input type="text" name="id_p2m" value="<?= $data['id_p2m'] ?>" required><br><br>

    <!-- Status Dropdown -->
    <label for="status">Status:</label>
    <select name="status" required>
        <option value="disetujui" <?= $data['status'] == 'disetujui' ? 'selected' : ''; ?>>Disetujui</option>
        <option value="ditolak" <?= $data['status'] == 'ditolak' ? 'selected' : ''; ?>>Ditolak</option>
    </select><br><br>

    <label for="keterangan">Keterangan:</label>
    <textarea name="keterangan" required><?= $data['keterangan'] ?></textarea><br><br>

    <label for="tanggal_aksi">Tanggal Aksi:</label>
    <input type="date" name="tanggal_aksi" value="<?= $data['tanggal_aksi'] ?>" required><br><br>

    <button type="submit">Update</button>
</form>

</body>
</html>
<style>
    /* Reset dasar */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    color: #333;
    line-height: 1.6;
}

/* Gaya untuk header */
h3 {
    text-align: center;
    color: #4CAF50;
    margin-top: 20px;
}

/* Gaya untuk form */
form {
    max-width: 600px;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Gaya untuk label */
label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

/* Gaya untuk input, textarea, dan select */
input[type="text"],
input[type="date"],
textarea,
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

/* Gaya untuk tombol */
button {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #45a049;
}

/* Fokus pada input */
input:focus,
textarea:focus,
select:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
}

/* Gaya untuk form yang lebih responsif */
@media (max-width: 768px) {
    form {
        padding: 15px;
    }
}

</style>