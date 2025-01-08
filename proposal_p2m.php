<?php
// Membuat koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'peng_mas';
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data proposal dari tabel proposal_p2m dan proposal_dosen
$sql = "SELECT p2m.id_p2m, pd.id_proposal, pd.id_dosen, pd.judul, pd.file_proposal, pd.tanggal_upload 
        FROM proposal_p2m p2m
        LEFT JOIN proposal_dosen pd ON p2m.id_proposal = pd.id_proposal";
$result = $conn->query($sql);

// Menampilkan data
if ($result->num_rows > 0) {
    echo "<table border='1'>
    <thead>
        <tr>
            <th>ID Proposal</th>
            <th>ID P2M</th>
            <th>ID Dosen</th>
            <th>Judul</th>
            <th>File Proposal</th>
            <th>Tanggal Upload</th>
        </tr>
    </thead>
    <tbody>";

    // Loop untuk menampilkan data proposal
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . htmlspecialchars($row['id_proposal']) . "</td>
            <td>" . htmlspecialchars($row['id_p2m']) . "</td>
            <td>" . htmlspecialchars($row['id_dosen']) . "</td>
            <td>" . htmlspecialchars($row['judul']) . "</td>
            <td><a href='uploads/" . htmlspecialchars($row['file_proposal']) . "'>" . htmlspecialchars($row['file_proposal']) . "</a></td>
            <td>" . htmlspecialchars($row['tanggal_upload']) . "</td>
        </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p>Tidak ada data proposal P2M yang ditemukan.</p>";
}

// Tutup koneksi
$conn->close();
?>
