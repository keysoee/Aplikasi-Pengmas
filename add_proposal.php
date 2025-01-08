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

<style>
    /* Styling untuk body */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f8fb;
}

/* Styling untuk form */
form {
    max-width: 500px;
    margin: 50px auto;
    background: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #dfe7ef;
}

/* Label styling */
form label {
    display: block;
    font-weight: bold;
    margin-bottom: 8px;
    color: #34495e;
}

/* Input and file styling */
form input[type="text"],
form input[type="file"] {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #dfe7ef;
    border-radius: 5px;
    background-color: #f9fbfd;
    transition: all 0.3s ease;
}

/* Focus styling */
form input:focus {
    outline: none;
    border-color: #3498db;
    background-color: #ffffff;
}

/* Submit button styling */
form button {
    background-color: #3498db;
    color: #ffffff;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

/* Hover effect for button */
form button:hover {
    background-color: #2980b9;
}

/* Styling untuk tabel */
table {
    width: 80%;
    margin: 30px auto;
    border-collapse: collapse;
    background: #ffffff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #dfe7ef;
    border-radius: 8px;
    overflow: hidden;
}

/* Header tabel */
table th {
    background-color: #3498db;
    color: white;
    padding: 12px 15px;
    text-align: left;
    font-size: 14px;
}

/* Data tabel */
table td {
    padding: 12px 15px;
    border-bottom: 1px solid #dfe7ef;
    color: #34495e;
    font-size: 14px;
}

/* Hover effect untuk baris tabel */
table tr:hover {
    background-color: #ecf3fc;
}

/* Responsive styling untuk tabel */
@media (max-width: 768px) {
    table, table thead, table tbody, table th, table td, table tr {
        display: block;
        width: 100%;
    }

    table th {
        display: none;
    }

    table td {
        display: flex;
        justify-content: space-between;
        padding: 8px 10px;
    }
}

</style>
