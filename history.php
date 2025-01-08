<?php
require 'koneksi.php'; // Pastikan file koneksi ada dan benar

// Ambil id_proposal dari query string
$id_proposal = isset($_GET['id_proposal']) ? intval($_GET['id_proposal']) : null;

if ($id_proposal) {
    // Query untuk mengambil data laporan terkait
    $query = "SELECT id_proposal, status, judul_laporan, tanggal FROM laporan WHERE id_proposal = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_proposal);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika laporan ditemukan, tambahkan ke tabel histori_dosen
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $keterangan = "Laporan dibuat: " . $row['judul_laporan'];
            $tanggal_aksi = $row['tanggal'];
            $status = $row['status'];

            // Periksa apakah data sudah ada di histori_dosen untuk menghindari duplikasi
            $check_histori = "SELECT id_histori FROM histori_dosen WHERE id_proposal = ? AND keterangan = ?";
            $stmt_check = $conn->prepare($check_histori);
            $stmt_check->bind_param("is", $row['id_proposal'], $keterangan);
            $stmt_check->execute();
            $check_result = $stmt_check->get_result();

            if ($check_result->num_rows === 0) {
                // Insert data ke tabel histori_dosen
                $insert_histori = "INSERT INTO histori_dosen (id_proposal, keterangan, tanggal_aksi, status) 
                                   VALUES (?, ?, ?, ?)";
                $stmt_histori = $conn->prepare($insert_histori);
                $stmt_histori->bind_param("isss", $row['id_proposal'], $keterangan, $tanggal_aksi, $status);
                $stmt_histori->execute();
            }
        }
    } else {
        echo "<p>Tidak ada laporan ditemukan untuk id_proposal ini.</p>";
    }

    // Query untuk menampilkan data histori_dosen berdasarkan id_proposal
    $query_histori = "SELECT * FROM histori_dosen WHERE id_proposal = ?";
    $stmt_histori = $conn->prepare($query_histori);
    $stmt_histori->bind_param("i", $id_proposal);
    $stmt_histori->execute();
    $result_histori = $stmt_histori->get_result();

    // Tampilkan data histori
    echo "<table border='1'>
            <thead>
                <tr>
                    <th>ID Histori</th>
                    <th>ID Proposal</th>
                    <th>Keterangan</th>
                    <th>Tanggal Aksi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>";

    if ($result_histori->num_rows > 0) {
        while ($row_histori = $result_histori->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row_histori['id_histori']) . "</td>
                    <td>" . htmlspecialchars($row_histori['id_proposal']) . "</td>
                    <td>" . htmlspecialchars($row_histori['keterangan']) . "</td>
                    <td>" . htmlspecialchars($row_histori['tanggal_aksi']) . "</td>
                    <td>" . htmlspecialchars($row_histori['status']) . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Tidak ada data histori ditemukan untuk proposal ini.</td></tr>";
    }

    echo "</tbody></table>";
} 
?>
