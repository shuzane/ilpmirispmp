<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Institut Latihan Perindustrian (ILP) Miri</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-bg">
    <form method="POST">
		<div class="login-container">
			<!-- Logo Section -->
			<div class="logo">
				<img src="logo.png" alt="Logo" />
			</div>
	
		<!-- Form Section -->
			<div class="form-section">
				<h2>Register</h2>
				<label for="first_name">First Name:</label>
				<input type="text" id="first_name" name="first_name" required><br>

				<label for="last_name">Last Name:</label>
				<input type="text" id="last_name" name="last_name" required><br>

				<label for="email">Email:</label>
				<input type="email" id="email" name="email" required><br>

				<label for="password">Password:</label>
				<input type="password" id="password" name="password" required><br>

				<label for="con_password">Confirm Password:</label>
				<input type="password" id="con_password" name="con_password" required><br>

				<button type="submit">Register</button>
			</div>
		</div>
	</form>
</div>

		<div class="copyright">
    © Copyright : Industri Latihan Perindustrian Miri
</div>

<?php
// Database connection
$host = "localhost:3307"; // Adjust as needed
$dbname = "ilp";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture data
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['con_password'];

    // Check if passwords match
    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Store user in database
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fname, $lname, $email, $hashed_password);
        if ($stmt->execute()) {
            // Redirect to login page
            header("Location: index.php");
            exit(); // Ensure no further code runs
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Passwords do not match. Please try again.</p>";
    }
}

$conn->close();
?>


</body>
</html>
