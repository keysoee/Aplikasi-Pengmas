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
<link rel="stylesheet" href="styletableproposal.css">
