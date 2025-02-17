<?php
session_start();

// Database credentials
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

$users_email = $_SESSION['users_email'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    // Sanitize and validate user inputs
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $course = $conn->real_escape_string($_POST['course']);
    $student_matrix = $conn->real_escape_string($_POST['student_matrix']);
    $birth_date = $conn->real_escape_string($_POST['birth_date']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $address = $conn->real_escape_string($_POST['address']);
    $religion = $conn->real_escape_string($_POST['religion']);
    $ethnicity = $conn->real_escape_string($_POST['ethnicity']);
    $bank_name = $conn->real_escape_string($_POST['bank_name']);
    $bank_number = $conn->real_escape_string($_POST['bank_number']);
    $disability = $conn->real_escape_string($_POST['disability']);
    $mailing_address = $conn->real_escape_string($_POST['mailing_address']);
    $postcode = $conn->real_escape_string($_POST['postcode']);
    $district_city = $conn->real_escape_string($_POST['district_city']);

    $father_name = $conn->real_escape_string($_POST['father_name']);
    $father_phone = $conn->real_escape_string($_POST['father_phone']);
    $father_occupation = $conn->real_escape_string($_POST['father_occupation']);
    $father_address = $conn->real_escape_string($_POST['father_address']);
    $family_income = $conn->real_escape_string($_POST['family_income']);

    $mother_name = $conn->real_escape_string($_POST['mother_name']);
    $mother_phone = $conn->real_escape_string($_POST['mother_phone']);
    $mother_occupation = $conn->real_escape_string($_POST['mother_occupation']);
    $mother_address = $conn->real_escape_string($_POST['mother_address']);

    $guardian_name = $conn->real_escape_string($_POST['guardian_name']);
    $guardian_phone = $conn->real_escape_string($_POST['guardian_phone']);
    $guardian_occupation = $conn->real_escape_string($_POST['guardian_occupation']);
    $guardian_address = $conn->real_escape_string($_POST['guardian_address']);
    $relationship = $conn->real_escape_string($_POST['relationship']);

    $fb_account = $conn->real_escape_string($_POST['fb_account']);
    $ig_account = $conn->real_escape_string($_POST['ig_account']);
    $tiktok_account = $conn->real_escape_string($_POST['tiktok_account']);

    // Start transaction
    $conn->begin_transaction();
    try {
        // Insert into student_info table
        $query = $conn->prepare("INSERT INTO student_info (student_id, name, student_matrix, course, birth_date, phone_number, address, religion, ethnicity, bank_name, bank_number, disability, mailing_address, postcode, district_city) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssssssssssssss", $student_id, $name, $student_matrix, $course, $birth_date, $phone_number, $address, $religion, $ethnicity, $bank_name, $bank_number, $disability, $mailing_address, $postcode, $district_city);
        $query->execute();

        // Insert into father_info table
        $query_father = $conn->prepare("INSERT INTO father_info 
                                        (father_id, father_name, father_phone, father_occupation, father_address, family_income) 
                                        VALUES (?, ?, ?, ?, ?, ?)");
        $query_father->bind_param("issssd", $student_id, $father_name, $father_phone, $father_occupation, $father_address, $family_income);
        $query_father->execute();

        // Insert into mother_info table
        $query_mother = $conn->prepare("INSERT INTO mother_info 
                                        (mother_id, mother_name, mother_phone, mother_occupation, mother_address) 
                                        VALUES (?, ?, ?, ?, ?)");
        $query_mother->bind_param("issss", $student_id, $mother_name, $mother_phone, $mother_occupation, $mother_address);
        $query_mother->execute();

        // Insert into guardian_info table
        $query_guardian = $conn->prepare("INSERT INTO guardian_info 
                                          (guardian_id, guardian_name, guardian_phone, guardian_occupation, guardian_address, relationship) 
                                          VALUES (?, ?, ?, ?, ?, ?)");
        $query_guardian->bind_param("isssss", $student_id, $guardian_name, $guardian_phone, $guardian_occupation, $guardian_address, $relationship);
        $query_guardian->execute();

        // Insert into social_media table
        $query_social = $conn->prepare("INSERT INTO social_media 
                                        (student_id, fb_account, ig_account, tiktok_account) 
                                        VALUES (?, ?, ?, ?)");
        $query_social->bind_param("isss", $student_id, $fb_account, $ig_account, $tiktok_account);
        $query_social->execute();

        $conn->commit();
        echo "Data inserted successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error inserting data: " . $e->getMessage();
    }

    // Close statements
    $query->close();
    $query_father->close();
    $query_mother->close();
    $query_guardian->close();
    $query_social->close();
    $conn->close();
}

// Fetch student details for display
$query = $conn->prepare("SELECT first_name, last_name FROM users WHERE email = ?");
$query->bind_param("s", $users_email);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

// Ensure values exist before echoing
$first_name = isset($row['first_name']) ? $row['first_name'] : "";
$last_name = isset($row['last_name']) ? $row['last_name'] : "";

// Close statement
$query->close();
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
            <li>No. Kad Pengenalan: </li>
            <input type="text" name="student_id" placeholder="Student ID" required>
            
            <li>Nama Penuh: </li>
            <input type="text" name="name" placeholder="Name" required>
            
            <li>No. Matrix: </li>
            <input type="text" name="student_matrix" placeholder="Student Matrix" required>
			
			<li>Kursus: </li>
            <input type="text" name="course" placeholder="Course" required>
            
            <li>No. Telefon: </li>
            <input type="text" name="phone_number" placeholder="Phone Number" required>
    
            <li>Tarikh Lahir: </li>
            <input type="date" name="birth_date" placeholder="Birth Date" required>
            
            <li>Alamat Rumah: </li>
            <input type="text" name="address" placeholder="Address" required>
    
            <li>Agama: </li>
            <input type="text" name="religion" placeholder="Religion">
    
            <li>Bangsa: </li>
            <input type="text" name="ethnicity" placeholder="Ethnicity">
    
            <li>Nama Bank: </li>
            <input type="text" name="bank_name" placeholder="Bank Name">
    
            <li>No. Akaun Bank: </li>
            <input type="text" name="bank_number" placeholder="Bank Number">
    
            <li>OKU (Kecacatan): </li>
            <input type="text" name="disability" placeholder="Disability">
    
            <li>Alamat Surat Menyurat: </li>
            <input type="text" name="mailing_address" placeholder="Mailing Address">
    
            <li>Poskod: </li>
            <input type="text" name="postcode" placeholder="Postcode">
    
            <li>Bandar/Daerah: </li>
            <input type="text" name="district_city" placeholder="District/City">
    
            <!-- ********** -->
            <li><strong>Maklumat Bapa</strong></li>
            <li>Nama Bapa: </li>
            <input type="text" name="father_name" placeholder="Father Name">
    
            <li>No. Telefon: </li>
            <input type="text" name="father_phone" placeholder="Phone Number" required>
    
            <li>Alamat Rumah: </li>
            <input type="text" name="father_address" placeholder="Address" required>
    
            <li>Pekerjaan Bapa: </li>
            <input type="text" name="father_occupation" placeholder="Occupation" required>
    
            <li>Pendapat Keluarga: RM </li>
            <input type="number" name="family_income" placeholder="Family Income" step="0.01">

            <!-- ********** -->
            <li><strong>Maklumat Ibu</strong></li>
            <li>Nama Ibu: </li>
            <input type="text" name="mother_name" placeholder="Mother Name">
    
            <li>No. Telefon: </li>
            <input type="text" name="mother_phone" placeholder="Phone Number" required>
    
            <li>Alamat Rumah: </li>
            <input type="text" name="mother_address" placeholder="Address" required>
    
            <li>Pekerjaan Ibu: </li>
            <input type="text" name="mother_occupation" placeholder="Occupation" required>
            
            <!-- ********** -->
            <li><strong>Maklumat Penjaga *JIKA ADA</strong></li>
            <li>Nama Penjaga: </li>
            <input type="text" name="guardian_name" placeholder="Guardian Name">
    
            <li>No. Telefon: </li>
            <input type="text" name="guardian_phone" placeholder="Phone Number" required>
    
            <li>Alamat Rumah: </li>
            <input type="text" name="guardian_address" placeholder="Address" required>
    
            <li>Pekerjaan Penjaga: </li>
            <input type="text" name="guardian_occupation" placeholder="Occupation" required>

            <li>Hubungan Penjaga: </li>
            <input type="text" name="relationship" placeholder="Relationship">

            <!-- ********** -->
            <li><strong>Maklumat Media Sosial</strong></li>
            <li>Facebook: </li>
            <input type="text" name="fb_account" placeholder="Facebook Account">
    
            <li>Instagram: </li>
            <input type="text" name="ig_account" placeholder="Instagram Account">
    
            <li>TikTok: </li>
            <input type="text" name="tiktok_account" placeholder="TikTok Account">

            <br/><br/>
            <button type="submit">Submit</button>
        </ul>
    </form>
</div>

</body>
</html>