<?php
// Koneksi ke database
$koneksi = new mysqli('localhost', 'root', '', 'peng_mas');

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Query untuk mendapatkan data mentoring
$query = "SELECT * FROM mentoring_evaluasi";
$result = $koneksi->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["id_mentoring_eval"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["id_proposal"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["id_dosen"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["id_p2m"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["status_mentoring_evaluasi"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["tanggal_mentoring_evaluasi"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["catatan_evaluasi"]) . "</td>";
        echo "<td>
                <form action='edit_mentoring.php' method='get'>
                    <button type='submit' name='id_mentoring_eval' value='" . $row['id_mentoring_eval'] . "'>Edit</button>
                </form>
                <form action='delete_mentoring.php' method='get' onsubmit=\"return confirm('Yakin ingin menghapus data ini?')\">
                    <button type='submit' name='id_mentoring_eval' value='" . $row['id_mentoring_eval'] . "'>Hapus</button>
                </form>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>Tidak ada data mentoring</td></tr>";
}

echo "</table>";
?>
