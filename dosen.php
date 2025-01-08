<?php
include('koneksi.php');

$sql = "SELECT id_dosen, nama, email, NIK FROM dosen";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['id_dosen']}</td>
            <td>{$row['nama']}</td>
            <td>{$row['email']}</td>
            <td>{$row['NIK']}</td>
            <td>
                <button onclick=\"window.location.href='edit_dosen.php?id={$row['id_dosen']}'\">Edit</button>
                <button onclick=\"if(confirm('Yakin ingin menghapus data ini?')) { window.location.href='delete_dosen.php?id={$row['id_dosen']}' }\">Hapus</button>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='5'>Tidak ada data dosen</td></tr>";
}
?>
