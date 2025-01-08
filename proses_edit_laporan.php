<?php
$koneksi = new mysqli('localhost', 'root', '', 'peng_mas');

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_laporan = intval($_POST['id_laporan']);
    $status = $koneksi->real_escape_string($_POST['status']);
    $keterangan = $koneksi->real_escape_string($_POST['keterangan']);
    $tanggal_aksi = $koneksi->real_escape_string($_POST['tanggal_aksi']);
    $id_p2m = intval($_POST['id_p2m']);

    // Validasi apakah id_p2m ada di tabel p2m
    $query_check_p2m = "SELECT id_p2m FROM p2m WHERE id_p2m = ?";
    $stmt_check_p2m = $koneksi->prepare($query_check_p2m);
    $stmt_check_p2m->bind_param("i", $id_p2m);
    $stmt_check_p2m->execute();
    $stmt_check_p2m->store_result();

    if ($stmt_check_p2m->num_rows === 0) {
        echo "Kesalahan: ID P2M tidak ditemukan di tabel p2m.";
        $stmt_check_p2m->close();
        $koneksi->close();
        exit;
    }
    $stmt_check_p2m->close();

    // Mulai proses update data
    $query_laporan_dosen = "UPDATE laporan_dosen SET status = ?, keterangan = ?, tanggal_aksi = ?, id_p2m = ? WHERE id_laporan = ?";
    $stmt_laporan_dosen = $koneksi->prepare($query_laporan_dosen);
    $stmt_laporan_dosen->bind_param("ssssi", $status, $keterangan, $tanggal_aksi, $id_p2m, $id_laporan);

    $query_laporan_p2m = "UPDATE laporan_p2m SET status = ?, keterangan = ? WHERE id_p2m = ?";
    $stmt_laporan_p2m = $koneksi->prepare($query_laporan_p2m);
    $stmt_laporan_p2m->bind_param("ssi", $status, $keterangan, $id_p2m);

    if ($stmt_laporan_dosen->execute() && $stmt_laporan_p2m->execute()) {
        echo "Laporan berhasil diperbarui di kedua tabel.";
    } else {
        echo "Terjadi kesalahan: " . $stmt_laporan_dosen->error . " | " . $stmt_laporan_p2m->error;
    }

    $stmt_laporan_dosen->close();
    $stmt_laporan_p2m->close();
    $koneksi->close();
}
?>
