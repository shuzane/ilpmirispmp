<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'logout') {
        header("Location: index.php");
		session_destroy();
        exit();
    } 
}

$host = "localhost:3307";
$dbname = "ilp";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['users_email'])) {
    die("User not logged in.");
}

$email = $_SESSION['users_email'];

// Fetch student details for display
$query = $conn->prepare("SELECT first_name, last_name, profile_picture FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$query->bind_result($first_name, $last_name, $profile_picture);
$query->fetch();
$query->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - ILP Miri</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-container">
    <div class="logo">
        <img src="logo.png" alt="Logo">
    </div>

    <div class="student-section">
        <h2>Welcome, <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></h2>
    </div>

    <form method="post">
        <input type="hidden" name="action" value="home">
        <input type="submit" class="button" value="Home">
    </form>

    <form method="post">
        <input type="hidden" name="action" value="logout">
        <input type="submit" class="button" value="Logout">
    </form>
</div>

<div class="resources">
	<span class="highlight">Dokumen Cetakan Pelajar</span>
		<ul class="menuhep">
			<li><a href="BORANG JAWAPAN INDUSTRI.pdf" download>Borang Jawapan Industri (Reply Form)</a></li>
			<li><a href="SURAT PENEMPATAN SYARIKAT.pdf" download>Surat Penempatan Syarikat</a></li>
		</ul>
	<br/> <br/>
</div>


<div class="resources">
	<span class="highlight">Borang Latihan Industri </span>
		<ul class="menuhep">
			<li><a href="BORANG PENGESAHAN LAPOR DIRI.pdf" download>Borang Pengesahan Lapor Diri</a></li>
			<li><a href="BORANG DAFTAR MAKLUMAT SYARIKAT.pdf" download>Borang Daftar Maklumat Syarikat</a></li>
			<li><a href="BORANG LAWATAN PENYELIAAN DAN PENILAIAN MAJIKAN LATIHAN INDUSTRI.pdf" download>Borang Lawatan Penyeliaan dan Penilaian Majikan Latihan Industri</a></li>
			<li><a href="BORANG KEHADIRAN PELAJAR LATIHAN INDUSTRI.pdf" download>Borang Kehadiran Pelajar Latihan Industri</a></li>
		</ul>
		<br/> <br/>
</div>
	

</body>
</html>