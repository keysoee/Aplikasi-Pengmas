<?php
session_start();
require_once 'koneksi.php'; // Ganti dengan file koneksi database Anda

if (!isset($_SESSION['id_login'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_mentoring_eval = $_POST['id_mentoring_eval'];
    $status_mentoring_evaluasi = $_POST['status_mentoring_evaluasi'];
    $tanggal_mentoring_evaluasi = $_POST['tanggal_mentoring_evaluasi'];
    $catatan_evaluasi = $_POST['catatan_evaluasi'];

    $sql = "UPDATE mentoring_evaluasi SET 
            status_mentoring_evaluasi = ?, 
            tanggal_mentoring_evaluasi = ?, 
            catatan_evaluasi = ? 
            WHERE id_mentoring_eval = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $status_mentoring_evaluasi, $tanggal_mentoring_evaluasi, $catatan_evaluasi, $id_mentoring_eval);

    if ($stmt->execute()) {
        header('Location: index_p2m.php?content=mentoring');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $id_mentoring_eval = $_GET['id_mentoring_eval'];

    $sql = "SELECT * FROM mentoring_evaluasi WHERE id_mentoring_eval = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_mentoring_eval);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Mentoring</title>
</head>
<body>
    <h2>Edit Data Mentoring</h2>
    <form method="POST" action="edit_mentoring.php">
        <input type="hidden" name="id_mentoring_eval" value="<?= htmlspecialchars($data['id_mentoring_eval']) ?>">
        <label>ID Proposal: <?= htmlspecialchars($data['id_proposal']) ?></label><br>
        <input type="hidden" name="id_proposal" value="<?= htmlspecialchars($data['id_proposal']) ?>">
        <label>ID Dosen: <?= htmlspecialchars($data['id_dosen']) ?></label><br>
        <input type="hidden" name="id_dosen" value="<?= htmlspecialchars($data['id_dosen']) ?>">
        <label>ID P2M: <?= htmlspecialchars($data['id_p2m']) ?></label><br>
        <input type="hidden" name="id_p2m" value="<?= htmlspecialchars($data['id_p2m']) ?>">
        <label for="status_mentoring_evaluasi">Status:</label>
        <select name="status_mentoring_evaluasi" required>
            <option value="sedang" <?= $data['status_mentoring_evaluasi'] == 'sedang' ? 'selected' : '' ?>>Sedang</option>
            <option value="selesai" <?= $data['status_mentoring_evaluasi'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
            <option value="ditunda" <?= $data['status_mentoring_evaluasi'] == 'ditunda' ? 'selected' : '' ?>>Ditunda</option>
        </select>
        <label for="tanggal_mentoring_evaluasi">Tanggal:</label>
        <input type="datetime-local" name="tanggal_mentoring_evaluasi" value="<?= htmlspecialchars($data['tanggal_mentoring_evaluasi']) ?>" required>
        <label for="catatan_evaluasi">Catatan:</label>
        <textarea name="catatan_evaluasi" required><?= htmlspecialchars($data['catatan_evaluasi']) ?></textarea>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
