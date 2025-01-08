<?php
include('koneksi.php'); // File untuk koneksi database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_proposal = $_POST['id_proposal'] ?? null;
    $id_dosen = $_POST['id_dosen'] ?? null;
    $judul = $_POST['judul'] ?? null;
    $tanggal_upload = date('Y-m-d');
    
    // Validasi input
    if (empty($id_proposal) || empty($id_dosen) || empty($judul)) {
        echo "Semua data harus diisi.";
        exit;
    }

    // Proses upload file
    if (isset($_FILES['file_proposal']) && $_FILES['file_proposal']['error'] == 0) {
        $file_proposal = basename($_FILES['file_proposal']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $file_proposal;

        // Periksa apakah direktori tujuan ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['file_proposal']['tmp_name'], $target_file)) {
            // Query untuk insert data ke tabel proposal_dosen
            $sql_proposal = "INSERT INTO proposal_dosen (id_proposal, id_dosen, judul, file_proposal, tanggal_upload)
                             VALUES (?, ?, ?, ?, ?)";
            $stmt_proposal = $conn->prepare($sql_proposal);
            $stmt_proposal->bind_param("iisss", $id_proposal, $id_dosen, $judul, $file_proposal, $tanggal_upload);

            if ($stmt_proposal->execute()) {
                // Query untuk menyalin data ke tabel proposal_p2m
                $sql_p2m = "INSERT INTO proposal_p2m (id_proposal, id_dosen, judul, file_proposal, tanggal_upload)
                            VALUES (?, ?, ?, ?, ?)";
                $stmt_p2m = $conn->prepare($sql_p2m);
                $stmt_p2m->bind_param("iisss", $id_proposal, $id_dosen, $judul, $file_proposal, $tanggal_upload);

                if ($stmt_p2m->execute()) {
                    // Query untuk insert data ke tabel laporan_dosen
                    $sql_laporan = "INSERT INTO laporan_dosen (id_proposal, status, keterangan, id_p2m)
                                    VALUES (?, 'masih menunggu', '', NULL)";
                    $stmt_laporan = $conn->prepare($sql_laporan);
                    $stmt_laporan->bind_param("i", $id_proposal);

                    if ($stmt_laporan->execute()) {
                        echo "Proposal berhasil ditambahkan dan laporan diperbarui!";
                    } else {
                        echo "Error pada tabel laporan: " . $conn->error;
                    }
                } else {
                    echo "Error pada tabel proposal_p2m: " . $conn->error;
                }
            } else {
                echo "Error pada tabel proposal_dosen: " . $conn->error;
            }
        } else {
            echo "Terjadi kesalahan saat mengupload file.";
        }
    } else {
        echo "File tidak valid atau terjadi kesalahan saat upload.";
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <label for="id_proposal">ID Proposal:</label>
    <input type="text" name="id_proposal" id="id_proposal" required><br>
    <label for="id_dosen">ID Dosen:</label>
    <input type="text" name="id_dosen" id="id_dosen" required><br>
    <label for="judul">Judul:</label>
    <input type="text" name="judul" id="judul" required><br>
    <label for="file_proposal">Upload Proposal:</label>
    <input type="file" name="file_proposal" id="file_proposal" required><br>
    <button type="submit">Tambah Proposal</button>
</form>

<link rel="stylesheet" href="styletableproposal.css">
