<?php
require 'koneksi.php'; // Pastikan file koneksi ada dan benar

// Ambil data dari query string
$id_proposal = isset($_GET['id_proposal']) ? $_GET['id_proposal'] : null;
$id_dosen = isset($_GET['id_dosen']) ? $_GET['id_dosen'] : null;

// Jika ada data id_proposal dan id_dosen di query string
if ($id_proposal && $id_dosen) {
    // Jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $judul_laporan = $_POST['judul_laporan'];
        $tanggal_aksi = date("Y-m-d H:i:s");
        $keterangan = "Laporan dibuat: $judul_laporan";

        // Simpan data ke tabel laporan
        $query_laporan = "INSERT INTO laporan (id_proposal, id_dosen, judul_laporan, tanggal) VALUES (?, ?, ?, ?)";
        $stmt_laporan = $conn->prepare($query_laporan);
        $stmt_laporan->bind_param("iiss", $id_proposal, $id_dosen, $judul_laporan, $tanggal_aksi);

        if ($stmt_laporan->execute()) {
            // Simpan data ke tabel histori_dosen
            $query_histori = "INSERT INTO histori_dosen (id_proposal, keterangan, tanggal_aksi, status) 
                              VALUES (?, ?, ?, 'Created')";
            $stmt_histori = $conn->prepare($query_histori);
            $stmt_histori->bind_param("iss", $id_proposal, $keterangan, $tanggal_aksi);

            if ($stmt_histori->execute()) {
                echo "Laporan dan histori berhasil disimpan.<br>";
            } else {
                echo "Error pada histori: " . $stmt_histori->error . "<br>";
            }
        } else {
            echo "Error pada laporan: " . $stmt_laporan->error . "<br>";
        }
    }

    // Ambil data laporan terkait untuk ditampilkan
    $query = "SELECT * FROM laporan WHERE id_proposal = ? AND id_dosen = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id_proposal, $id_dosen);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h2>Daftar Laporan</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "ID Laporan: " . $row['id_laporan'] . "<br>";
        echo "Judul Laporan: " . $row['judul_laporan'] . "<br>";
        echo "Tanggal: " . $row['tanggal'] . "<br><br>";
    }

    // Menampilkan data histori_dosen terkait
    $query_histori = "SELECT * FROM histori_dosen WHERE id_proposal = ?";
    $stmt_histori = $conn->prepare($query_histori);
    $stmt_histori->bind_param("i", $id_proposal);
    $stmt_histori->execute();
    $result_histori = $stmt_histori->get_result();

    echo "<h2>Histori Proposal</h2>";
    while ($row_histori = $result_histori->fetch_assoc()) {
        echo "ID Histori: " . $row_histori['id_histori'] . "<br>";
        echo "Keterangan: " . $row_histori['keterangan'] . "<br>";
        echo "Tanggal Aksi: " . $row_histori['tanggal_aksi'] . "<br>";
        echo "Status: " . $row_histori['status'] . "<br><br>";
    }

    // Tampilkan form untuk menambahkan laporan baru
    ?>
    <h2>Tambah Laporan Baru</h2>
    <form method="POST">
        <label for="judul_laporan">Judul Laporan:</label><br>
        <input type="text" name="judul_laporan" id="judul_laporan" required><br><br>
        <button type="submit">Simpan</button>
    </form>
    <?php
} else {
    echo "Data tidak tersedia. Pastikan id_proposal dan id_dosen dikirimkan melalui URL.";
}
?>
