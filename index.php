<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_login'])) {
    header('Location: login.php');
    exit();
}

// Ambil data pengguna dari session
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
            document.getElementById('addDosenModal').style.display = "block";
        }

        // Close modal
        function closeModal() {
            document.getElementById('addDosenModal').style.display = "none";
        }
    </script>
</head>
<body class="desktop-layout"> 

    <?php include('header.php'); ?>  <!-- Menyisipkan header.php -->

    <nav>
        <ul>
            <li><a href="#" onclick="showContent('profile')">Profile</a></li>
            <li><a href="#" onclick="showContent('proposal')">Tabel Proposal</a></li>
            <li><a href="#" onclick="showContent('laporan')">Tabel Laporan</a></li>
            <li><a href="#" onclick="showContent('history')">Tabel History</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <section>
        <div id="section-content">
            <!-- Profile -->
            <div id="profile" class="content-section">
                <h2>Data Dosen</h2>
                <table border="1">
                    <thead>
                        <tr>
                            <th>ID Dosen</th>
                            <th>Nama Dosen</th>
                            <th>Email Dosen</th>
                            <th>NIK</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include('dosen.php'); ?>
                    </tbody>
                </table>

                <br>
                <button id="addDosenBtn" onclick="openModal()">Tambah Dosen</button>
            </div>

            <!-- Tabel Proposal -->
            <div id="proposal" class="content-section" style="display: none;">
    <h2>Tabel Proposal Dosen</h2>
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
    $sql = "SELECT * FROM proposal_dosen";
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
                <th>Aksi</th>
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
                <td>
                    <a href='edit_proposal.php?id_proposal=" . htmlspecialchars($row['id_proposal']) . "'>
                        <button>Edit</button>
                    </a>
                    <a href='delete_proposal.php?id_proposal=" . htmlspecialchars($row['id_proposal']) . "' onclick=\"return confirm('Apakah Anda yakin ingin menghapus proposal ini?')\">
                        <button>Hapus</button>
                    </a>
                </td>
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
    <a href="add_proposal.php" id="addProposal" style="margin-right: 10px;">
        <button>Tambah Proposal</button>
    </a>
</div>


<!-- Tabel Proposal-------------------------------------------------------------------------------------- -->

<div id="laporan" class="content-section" style="display: none;">
    <h2>Tabel Laporan Dosen</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID Laporan</th>
                <th>ID Proposal</th>
                <th>ID P2M</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Tanggal_Aksi</th>
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
            $query = "
            SELECT 
                laporan_dosen.id_laporan,
                laporan_dosen.id_proposal,
                laporan_dosen.id_p2m,
                laporan_dosen.status,
                laporan_dosen.keterangan,
                laporan_dosen.tanggal_aksi
            FROM 
                laporan_dosen
        ";        
            $result = $koneksi->query($query);

            // Menampilkan data laporan
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id_laporan"] . "</td>";
                    echo "<td>" . $row["id_proposal"] . "</td>";
                    echo "<td>" . $row["id_p2m"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>" . $row["keterangan"] . "</td>";
                    echo "<td>" . $row["tanggal_aksi"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Tidak ada data laporan</td></tr>";
            }

            // Menutup koneksi
            $koneksi->close();
            ?>
        </tbody>
    </table>
</div>



            <!-- Tabel History -->
            <div id="history" class="content-section" style="display: none;">
    <h2>Tabel History Dosen</h2>
    <!-- Tambahkan konten tabel history di sini -->
    <table border="1">
        <thead>
            <tr>
                <th>ID History</th>
                <th>ID Proposal</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Tanggal Aksi</th>
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
            $query = "
            SELECT 
                laporan_dosen.id_laporan,
                laporan_dosen.id_proposal,
                laporan_dosen.status,
                laporan_dosen.keterangan,
                laporan_dosen.tanggal_aksi
            FROM 
                laporan_dosen
        ";        
            $result = $koneksi->query($query);

            // Menampilkan data laporan
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id_laporan"] . "</td>";
                    echo "<td>" . $row["id_proposal"] . "</td>";

                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>" . $row["keterangan"] . "</td>";
                    echo "<td>" . $row["tanggal_aksi"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Tidak ada data laporan</td></tr>";
            }

            // Menutup koneksi
            $koneksi->close();
            ?>
            <?php include('history.php'); ?> <!-- Memanggil file history.php -->
        </tbody>
    </table>
</div>


        </div>
    </section>


 <!-- Modal Add Dosen-->
    <!-- Modal Add Dosen -->
    <div id="addDosenModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Tambah Dosen</h2>
            <form action="add_dosen.php" method="POST">
                <label for="nama_dosen">Nama Dosen:</label>
                <input type="text" id="nama_dosen" name="nama_dosen" required>
            
                <label for="email_dosen">Email Dosen:</label>
                <input type="email" id="email_dosen" name="email_dosen" required>
            
                <label for="nik">NIK:</label>
                <input type="text" id="nik" name="nik" required>
            
                <button type="submit">Simpan</button>
            </form>
        </div>
    </div>

    <aside>
    <?php include('aside.php'); ?>
    </aside>

    
    <?php include('footer.php'); ?> 

</body>
</html>