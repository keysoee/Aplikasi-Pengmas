<?php
session_start();

if (!isset($_SESSION['id_login'])) {
    header('Location: login.php');
    exit();
}

$nik = $_SESSION['nik'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penelitian & Pengabdian Masyarakat</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <script>
        function switchLayout(layout) {
            document.body.className = layout; 
            showContent('profile');
        }

        function showContent(content) {
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => section.style.display = 'none'); // Sembunyikan semua konten

            document.getElementById(content).style.display = 'block'; // Tampilkan konten yang dipilih
        }

        // Open modal
        function openModal() {
            document.getElementById('addP2mModal').style.display = "block";
        }

        // Close modal
        function closeModal() {
            document.getElementById('addP2mModal').style.display = "none";
        }
    </script>
</head>
<body class="desktop-layout"> 

    <?php include('header.php'); ?>  

    <nav>
        <ul>
            <li><a href="#" onclick="showContent('profile')">Profile</a></li>
            <li><a href="#" onclick="showContent('proposal')">Tabel Proposal</a></li>
            <li><a href="#" onclick="showContent('laporan')">Tabel Laporan</a></li>
            <li><a href="#" onclick="showContent('mentoring')">Tabel Mentoring&Evaluasi</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <section>
    <div id="section-content">
    <!-- Profile -->
    <div id="profile" class="content-section">
    <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Data P2m</h2>
            <button id="addP2mBtn" onclick="openModal()">Tambah Data P2m</button>
        </div>
        <table border="1">
            <thead>
                <tr>
                    <th>ID P2m</th>
                    <th>Nama P2m</th>
                    <th>Email P2m</th>
                    <th>NIK</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php include('p2m.php'); ?>
            </tbody>
        </table>
    </div>
</div>

              <!-- Tabel Proposal -->
              <div id="proposal" class="content-section" style="display: none;">
    <h2>Tabel Proposal P2M</h2>
    <?php
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $dbname = 'peng_mas';

    // Membuat koneksi ke database
    $conn = new mysqli($host, $user, $password, $dbname);

    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Query untuk mengambil data proposal dosen
    $sql = "SELECT * FROM proposal_p2m";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'>
        <thead>
            <tr>
                <th>ID Proposal</th>
                <th>ID Dosen</th>
                <th>Judul</th>
                <th>File Proposal</th>
                <th>Tanggal Upload</th>
            </tr>
        </thead>
        <tbody>";
        
        // Loop data proposal
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . htmlspecialchars($row['id_proposal']) . "</td>
                <td>" . htmlspecialchars($row['id_dosen']) . "</td>
                <td>" . htmlspecialchars($row['judul']) . "</td>
                <td><a href='uploads/" . htmlspecialchars($row['file_proposal']) . "'>" . htmlspecialchars($row['file_proposal']) . "</a></td>
                <td>" . htmlspecialchars($row['tanggal_upload']) . "</td>

            </tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>Tidak ada data proposal dosen yang ditemukan.</p>";
    }

    // Tutup koneksi
    $conn->close();
    ?>
    <br>

</div>


<!-- Tabel Laporan P2M -->
<div id="laporan" class="content-section" style="display: none;">
    <h2>Tabel Laporan P2M</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID Laporan</th>
                <th>ID Proposal</th>
                <th>ID P2M</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Tanggal Aksi</th>
                <th>Action</th> <!-- Menambah kolom Action untuk tombol Edit -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Koneksi ke database
            $koneksi = new mysqli('localhost', 'root', '', 'peng_mas');
            // Cek koneksi
            if ($koneksi->connect_error) {
                die("Koneksi gagal: " . $koneksi->connect_error);
            }

            // Query untuk mendapatkan data laporan
            $query = "SELECT * FROM laporan_dosen";
            $result = $koneksi->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["id_laporan"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["id_proposal"] ?? '') . "</td>";

                    echo "<td>" . htmlspecialchars($row["id_p2m"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["keterangan"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["tanggal_aksi"]) . "</td>";
                    echo "<td><a href='edit_laporan.php?id_laporan=" . $row["id_laporan"] . "'>Edit</a></td>"; // Tombol Edit
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Tidak ada data laporan</td></tr>";
            }

            // Menutup koneksi
            $koneksi->close();
            ?>
        </tbody>
    </table>
</div>


<script>
    // Fungsi untuk membuka modal laporan
    function openLaporanModal() {
        document.getElementById('addLaporanModal').style.display = "block";
    }

    // Fungsi untuk menutup modal laporan
    function closeLaporanModal() {
        document.getElementById('addLaporanModal').style.display = "none";
    }
</script>

<div id="mentoring" class="content-section" style="display: none;">
    <div class="header-section">
        <h2>Tabel Mentoring & Evaluasi</h2>
        <a href="add_mentoring.php" class="button">Tambah Data Mentoring</a>
    </div>
    <table border="1">
        <thead>
        <tr>
            <th>ID Mentoring Eval</th>
            <th>ID Proposal</th>
            <th>ID Dosen</th>
            <th>ID P2M</th>
            <th>Status Mentoring Evaluasi</th>
            <th>Tanggal Mentoring Evaluasi</th>
            <th>Catatan Evaluasi</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php include('mentoring.php'); ?>
        </tbody>
    </table>
</div>


<style>
    /* Styling untuk header section yang berisi judul dan tombol */
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header-section h2 {
        margin: 0;
        font-size: 24px;
    }

    .button {
        padding: 10px 20px;
        background-color: #007BFF;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
    }

    .button:hover {
        background-color: #007BFF;
    }
</style>
</section>

<!-- Modal for Adding Mentoring Data -->
<div id="mentoring" class="content-section" style="display: none;">
    <h2>Tabel Mentoring & Evaluasi</h2>
    <table class="mentoring" border="1">
        <thead>
            <tr>
                <th>ID Mentoring Eval</th>
                <th>ID Proposal</th>
                <th>ID Dosen</th>
                <th>ID P2M</th>
                <th>Status Mentoring Evaluasi</th>
                <th>Tanggal Mentoring Evaluasi</th>
                <th>Catatan Evaluasi</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php include('mentoring.php'); ?>
        </tbody>
    </table>
    <button onclick="openModal()">Tambah Data Mentoring</button> <!-- Button to open the modal -->
</div>

<!-- Modal for Adding Mentoring Data -->
<div id="addMentoringModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Tambah Data Mentoring</h3>
        <form method="POST" action="add_mentoring.php">
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



<!-- Modal and page styles -->
<style>
    /* Style for the modal background */
    .modal {
        display: none; /* Hidden by default */
        position: fixed;
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4); /* Background with transparency */
        padding-top: 60px;
    }

    /* Modal content */
    .modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px; /* Maximum width */
    }

    /* Close button */
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Form and Input Styles */
    label {
        font-weight: bold;
        margin-top: 10px;
        display: block;
    }

    input, textarea {
        width: 100%;
        padding: 8px;
        margin: 5px 0 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button {
        background-color:#007BFF;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #007BFF;
    }
</style>



<!-- Modal for Adding Data -->
<div id="addP2mModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Tambah Data P2M</h3>
        <form method="POST" action="add_p2m.php">
            <div class="form-group">
                <label for="nama_p2m">Nama P2M:</label>
                <input type="text" name="nama_p2m" required>
            </div>
            <div class="form-group">
                <label for="email_p2m">Email P2M:</label>
                <input type="email" name="email_p2m" required>
            </div>
            <div class="form-group">
                <label for="nik">NIK:</label>
                <input type="text" name="nik" required>
            </div>
            <button type="submit" class="submit-btn">Tambah</button>
        </form>
    </div>
    <script>
    function openP2mModal() {
        document.getElementById('addP2mModal').style.display = "block";
    }

    function closeP2mModal() {
        document.getElementById('addP2mModal').style.display = "none";
    }
</script>
</div>

    <aside>
    <?php include('aside.php'); ?>
    </aside>

    
    <?php include('footer.php'); ?> 

</body>
</html>
