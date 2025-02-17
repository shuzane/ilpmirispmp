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

// List of Courses
$courses = [
    "Sijil Teknologi Komputer (Rangkaian)",
    "Sijil Teknologi Telekomunikasi",
    "Sijil Teknologi Penyejukbekuan dan Penyamanan Udara",
    "Sijil Teknologi Kimpalan",
    "Sijil Teknologi Pemasangan Paip Minyak dan Gas",
    "Sijil Teknologi Elektrik",
    "Sijil Teknologi Pemasangan Paip Minyak dan Gas (Vista)",
    "Diploma Teknologi Komputer (Rangkaian)",
    "Diploma Teknologi Elektrik",
    "Diploma Teknologi Telekomunikasi",
    "Diploma Teknologi Penyejukbekuan dan Penyamanan Udara"
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    // Sanitize and validate user inputs
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $course = $conn->real_escape_string($_POST['course']);
    $student_matrix = $conn->real_escape_string($_POST['student_matrix']);
    $intern_place = $conn->real_escape_string($_POST['intern_place']);

    // Start transaction
    $conn->begin_transaction();
    try {
        // Insert into student_info table
        $query = $conn->prepare("INSERT INTO student_info (student_id, name, student_matrix, course, intern_place) 
                                 VALUES (?, ?, ?, ?, ?)");
        $query->bind_param("sssss", $student_id, $name, $student_matrix, $course, $intern_place);
        $query->execute();

        $conn->commit();
        echo "Data inserted successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error inserting data: " . $e->getMessage();
    }

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

// Fetch all student details
$query = $conn->prepare("SELECT name, student_matrix, course, intern_place FROM student_info");
$query->execute();
$result = $query->get_result();

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
            
            <li>Tempat Latihan Industri Pelajar: </li>
            <input type="text" name="intern_place" placeholder="Intern Place" required>
            
            <button type="submit">Submit</button>
        </ul>
    </form>
</div>

<div class="biodata">
    <span class="profile">Maklumat Pelajar Latihan Industri</span>
    <table class = "student-info">
        <thead>
            <tr>
                <th>Nama Penuh</th>
                <th>No. Matrix</th>
                <th>Kursus</th>
                <th>Tempat Latihan Industri</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['student_matrix']; ?></td>
                    <td><?php echo $row['course']; ?></td>
                    <td><?php echo $row['intern_place']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>