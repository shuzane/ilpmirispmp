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

$users_id = $_SESSION['users_email'];

// Fetch student details for display
$query = $conn->prepare("SELECT first_name, last_name FROM users WHERE email = ?");
$query->bind_param("s", $users_email);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

// Ensure values exist before echoing
$first_name = isset($row['first_name']) ? $row['first_name'] : "";
$last_name = isset($row['last_name']) ? $row['last_name'] : "";

// Fetch student details
$query = $conn->prepare("SELECT * FROM student_info WHERE student_id = ?");
$query->bind_param("s", $users_id);
$query->execute();
$result = $query->get_result();
$student = $result->fetch_assoc();

$student_id = $student['student_id'] ?? "";
$student_matrix = $student['student_matrix'] ?? "";
$name = $student['name'] ?? "";
$intern_place = $student['intern_place'] ?? "";
$course = $student['course'] ?? "";
$birth_date = $student['birth_date'] ?? "";
$phone_number = $student['phone_number'] ?? "";
$address = $student['address'] ?? "";
$religion = $student['religion'] ?? "";
$ethnicity = $student['ethnicity'] ?? "";
$bank_name = $student['bank_name'] ?? "";
$bank_number = $student['bank_number'] ?? "";
$disability = $student['disability'] ?? "";
$mailing_address = $student['mailing_address'] ?? "";
$postcode = $student['postcode'] ?? "";
$district_city = $student['district_city'] ?? "";

// Fetch father details
$query = $conn->prepare("SELECT * FROM father_info WHERE father_id = ?");
$query->bind_param("s", $users_id);
$query->execute();
$result = $query->get_result();
$father = $result->fetch_assoc();

$father_name = $father['father_name'] ?? "";
$father_phone = $father['father_phone'] ?? "";
$father_occupation = $father['father_occupation'] ?? "";
$father_address = $father['father_address'] ?? "";
$family_income = $father['family_income'] ?? "";

// Fetch mother details
$query = $conn->prepare("SELECT * FROM mother_info WHERE mother_id = ?");
$query->bind_param("s", $users_id);
$query->execute();
$result = $query->get_result();
$mother = $result->fetch_assoc();

$mother_name = $mother['mother_name'] ?? "";
$mother_phone = $mother['mother_phone'] ?? "";
$mother_occupation = $mother['mother_occupation'] ?? "";
$mother_address = $mother['mother_address'] ?? "";

// Fetch guardian details
$query = $conn->prepare("SELECT * FROM guardian_info WHERE guardian_id = ?");
$query->bind_param("s", $users_id);
$query->execute();
$result = $query->get_result();
$guardian = $result->fetch_assoc();

$guardian_name = $guardian['guardian_name'] ?? "";
$guardian_phone = $guardian['guardian_phone'] ?? "";
$guardian_occupation = $guardian['guardian_occupation'] ?? "";
$guardian_address = $guardian['guardian_address'] ?? "";
$relationship = $guardian['relationship'] ?? "";

$query = $conn->prepare("SELECT * FROM social_media WHERE fb_account = ?");
$query->bind_param("s", $users_id);
$query->execute();
$result = $query->get_result();
$guardian = $result->fetch_assoc();

$fb_account = $guardian['fb_account'] ?? "";
$ig_account = $guardian['ig_account'] ?? "";
$tiktok_account = $guardian['tiktok_account'] ?? "";

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
        <h2>Welcome, <?php echo $first_name . ' ' . $last_name; ?></h2>
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

<div class="biodata">
    <span class="profile">Biodata Pelajar</span>
    <form method="post">
        <ul class="biodata-pelajar">
            <li><strong>Maklumat Pelajar</strong></li>
            <li>No. Kad Pengenalan: <?php echo $student_id; ?></li>
			
			<li>No. Matrix: <?php echo $student_matrix; ?> </li>
			
			<li>Kursus: <?php echo $course; ?> </li>
            
			<li>No. Telefon: <?php echo $phone_number; ?></li>
            
			<li>Tarikh Lahir: <?php echo $birth_date; ?></li>
            
			<li>Alamat Rumah: <?php echo $address; ?></li>
            
			<li>Agama: <?php echo $religion; ?></li>
            
			<li>Bangsa: <?php echo $ethnicity; ?></li>
            
			<li>Nama Bank: <?php echo $bank_name; ?></li>
            
			<li>No. Akaun Bank: <?php echo $bank_number; ?></li>
            
			<li>OKU (Kecacatan): <?php echo $disability; ?></li>
            
			<li>Alamat Surat Menyurat: <?php echo $mailing_address; ?></li>
            
			<li>Poskod: <?php echo $postcode; ?></li>
            
			<li>Bandar/Daerah: <?php echo $district_city; ?></li>
            
			<!-- ********** -->
           <li><strong>Maklumat Penjaga *JIKA ADA</strong></li>
                
			<li>Nama Penjaga: <?php echo $guardian_name; ?></li>
                
            <li>No. Telefon: <?php echo $guardian_phone; ?></li>
                
            <li>Alamat Rumah: <?php echo $guardian_address; ?></li>
                
            <li>Pekerjaan Penjaga: <?php echo $guardian_occupation; ?></li>
           
			<li>Hubungan: <?php echo $relationship; ?></li>
                
             <!-- ********** -->
            <li><strong>Maklumat Media Sosial</strong></li>
            <li>Facebook: <?php echo $fb_account; ?></li>
            
			<li>Instagram: <?php echo $ig_account; ?></li>
            
			<li>TikTok: <?php echo $tiktok_account; ?></li>
			
			<!-- ********** -->
            <li><strong>Tempat Latihan Industri</strong></li>
            <li>Facebook: <?php echo $intern_place; ?></li>
            
			<br/><br/>
            <button type="submit">Okay</button>
        </ul>
    </form>
</div>

</body>
</html>