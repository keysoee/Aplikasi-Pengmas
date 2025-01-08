<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'pengmasyarakat';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id_p2m = isset($_GET['id_p2m']) ? intval($_GET['id_p2m']) : 0;

if ($id_p2m > 0) {
    $sql = "SELECT * FROM p2m WHERE id_p2m = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die('Gagal mempersiapkan query: ' . $conn->error);
    }

    $stmt->bind_param("i", $id_p2m);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "Data tidak ditemukan.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $nik = $_POST['nik'];

        $update_sql = "UPDATE p2m SET nama = ?, email = ?, NIK = ? WHERE id_p2m = ?";
        $update_stmt = $conn->prepare($update_sql);
        
        if ($update_stmt === false) {
            die('Gagal mempersiapkan query: ' . $conn->error);
        }

        $update_stmt->bind_param("sssi", $nama, $email, $nik, $id_p2m);

        if ($update_stmt->execute()) {
            header('Location: index_p2m.php');
            exit();
        } else {
            echo "Error: " . $update_stmt->error;
        }

        $update_stmt->close();
    }
} else {
    echo "ID P2M tidak valid.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data P2M</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Edit Data P2M</h2>
    <form action="edit_p2m.php?id_p2m=<?= $id_p2m ?>" method="POST">
        <input type="hidden" name="id_p2m" value="<?= $row['id_p2m'] ?>">

        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" value="<?= isset($row['nama']) ? $row['nama'] : '' ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= isset($row['email']) ? $row['email'] : '' ?>" required><br>

        <label for="nik">NIK:</label>
        <input type="text" id="nik" name="nik" value="<?= isset($row['NIK']) ? $row['NIK'] : '' ?>" required><br>

        <button type="submit">Simpan Perubahan</button>
        <a href="index_p2m.php">Batal</a>
    </form>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            color: #333;
            margin-top: 20px;
        }

        form {
            background-color: white;
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
            color: #333;
        }

        input[type="text"], input[type="email"], input[type="number"], select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color:#007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color:#007BFF;
        }

        .form-container {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
    </style>
</body>
</html>
