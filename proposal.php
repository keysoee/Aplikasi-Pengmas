<?php
// Koneksi ke database
$servername = "localhost"; // Ubah dengan nama server MySQL jika perlu
$username = "root"; // Username database
$password = ""; // Password database
$dbname = "peng_mas"; // Nama database, sesuaikan dengan nama database kamu

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data proposal
$sql = "SELECT id_proposal, judul, file_proposal, tanggal_upload FROM proposal";
$result = $conn->query($sql);

// Menampilkan data proposal
if ($result->num_rows > 0) {
    // Loop untuk menampilkan setiap baris data
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id_proposal"] . "</td>";
        echo "<td>" . $row["judul"] . "</td>";
        echo "<td><a href='" . $row["file_proposal"] . "' target='_blank'>Lihat Proposal</a></td>";
        echo "<td>" . $row["tanggal_upload"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>Tidak ada data proposal</td></tr>";
}

// Menutup koneksi
$conn->close();
?>
