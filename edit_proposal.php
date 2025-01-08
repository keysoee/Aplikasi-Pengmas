<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'peng_mas';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_proposal'])) {
    $id_proposal = $_GET['id_proposal'];
    $sql = "SELECT * FROM proposal_dosen WHERE id_proposal = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_proposal);
    $stmt->execute();
    $result = $stmt->get_result();
    $proposal = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_proposal = $_POST['id_proposal'];
    $judul = $_POST['judul'];
    $file_proposal = $_FILES['file_proposal']['name'];
    $upload_dir = "uploads/";
    $upload_file = $upload_dir . basename($file_proposal);

    if (move_uploaded_file($_FILES['file_proposal']['tmp_name'], $upload_file)) {
        $sql = "UPDATE proposal_dosen SET judul = ?, file_proposal = ? WHERE id_proposal = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $judul, $file_proposal, $id_proposal);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Proposal</title>
</head>
<body>
    <h2>Edit Proposal</h2>
    <form action="edit_proposal.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_proposal" value="<?php echo $proposal['id_proposal']; ?>">
        <label>Judul:</label>
        <input type="text" name="judul" value="<?php echo $proposal['judul']; ?>" required><br>
        <label>File Proposal:</label>
        <input type="file" name="file_proposal" required><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
<style>
        /* Styling untuk body */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f8fb;
        }

        /* Container */
        form {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #dfe7ef;
        }

        /* Heading */
        h2 {
            text-align: center;
            color: #34495e;
        }

        /* Label */
        form label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #34495e;
        }

        /* Input fields */
        form input[type="text"],
        form input[type="email"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #dfe7ef;
            border-radius: 5px;
            background-color: #f9fbfd;
            transition: all 0.3s ease;
        }

        form input:focus {
            outline: none;
            border-color: #3498db;
            background-color: #ffffff;
        }

        /* Buttons */
        form button, form a {
            display: inline-block;
            padding: 10px 15px;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        form button {
            background-color: #3498db;
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        form button:hover {
            background-color: #2980b9;
        }

        form a {
            background-color: #f4f4f4;
            color: #34495e;
            border: 1px solid #dfe7ef;
        }

        form a:hover {
            background-color: #e1e5ea;
        }
    </style>