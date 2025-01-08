<?php
// Koneksi ke database
$koneksi = new mysqli('localhost', 'root', '', 'peng_mas');

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Proses form jika tombol submit ditekan
if (isset($_POST['submit_mentoring'])) {
    // Mengambil data dari form
    $id_proposal = $koneksi->real_escape_string($_POST['id_proposal']);
    $id_dosen = $koneksi->real_escape_string($_POST['id_dosen']);
    $id_p2m = $koneksi->real_escape_string($_POST['id_p2m']);
    $status_mentoring_eval = $koneksi->real_escape_string($_POST['status_mentoring_eval']);
    $tanggal_mentoring_eval = $koneksi->real_escape_string($_POST['tanggal_mentoring_eval']);
    $catatan_evaluasi = $koneksi->real_escape_string($_POST['catatan_evaluasi']);

    // Check if id_proposal exists in proposal_dosen table
    $check_proposal_query = $koneksi->prepare("SELECT COUNT(*) FROM proposal_dosen WHERE id_proposal = ?");
    $check_proposal_query->bind_param("s", $id_proposal);
    $check_proposal_query->execute();
    $check_proposal_query->bind_result($count);
    $check_proposal_query->fetch();
    $check_proposal_query->close();

    if ($count == 0) {
        // id_proposal does not exist
        echo "ID Proposal tidak ditemukan di tabel proposal_dosen.";
        exit();
    }

    // Query untuk menyimpan data ke database menggunakan prepared statements
    $query = $koneksi->prepare("INSERT INTO mentoring_evaluasi (id_proposal, id_dosen, id_p2m, status_mentoring_evaluasi, tanggal_mentoring_evaluasi, catatan_evaluasi) 
                                VALUES (?, ?, ?, ?, ?, ?)");
    $query->bind_param("ssssss", $id_proposal, $id_dosen, $id_p2m, $status_mentoring_eval, $tanggal_mentoring_eval, $catatan_evaluasi);

    // Execute the insert query
    if ($query->execute()) {
        // Jika berhasil, redirect ke halaman index_p2m.php
        header("Location: index_p2m.php");
        exit();
    } else {
        // Jika gagal, tampilkan error
        echo "Error: " . $query->error;
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Mentoring</title>
    <style>
        /* CSS untuk modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            float: right;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        input[type="text"], input[type="date"], textarea {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <!-- Tombol untuk menampilkan modal -->
    <button onclick="showMentoringModal()">Tambah Data Mentoring</button>

    <!-- Modal untuk Menambah Data Mentoring -->
    <div id="addMentoringModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeMentoringModal()">&times;</span>
            <h3>Tambah Data Mentoring</h3>
            <form method="POST" action="">
                <label for="id_proposal">ID Proposal:</label>
                <input type="text" name="id_proposal" required>

                <label for="id_dosen">ID Dosen:</label>
                <input type="text" name="id_dosen" required>

                <label for="id_p2m">ID P2M:</label>
                <input type="text" name="id_p2m" required>

                <label for="status_mentoring_eval">Status Mentoring Evaluasi:</label>
                <input type="text" name="status_mentoring_eval" required>

                <label for="tanggal_mentoring_eval">Tanggal Mentoring Evaluasi:</label>
                <input type="date" name="tanggal_mentoring_eval" required>

                <label for="catatan_evaluasi">Catatan Evaluasi:</label>
                <textarea name="catatan_evaluasi" required></textarea>

                <button type="submit" name="submit_mentoring">Tambah</button>
            </form>
        </div>
    </div>

    <script>
        // Menampilkan modal
        function showMentoringModal() {
            document.getElementById("addMentoringModal").style.display = "block";
        }

        // Menutup modal
        function closeMentoringModal() {
            document.getElementById("addMentoringModal").style.display = "none";
        }
    </script>
</body>
</html>
