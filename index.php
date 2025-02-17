<?php
// Start the session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - Institut Latihan Perindustrian (ILP) Miri</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-bg">
	<form method="POST" action="">
		<div class="login-container">
			<!-- Logo Section -->
			<div class="logo">
				<img src="logo.png" alt="Logo" />
			</div>
		
		<!-- Form Section -->
			<div class="form-section">
				<h2>Log In</h2>
            
				<label for="email">Email:</label>
				<input type="email" id="email" name="email" required><br>

				<label for="password">Password:</label>
				<input type="password" id="password" name="password" required><br>

				<input type="submit" name="login" class="button" value="Login">
            
				<p>Don't have an account? <a href="reg.php">Register here</a>.</p>
				<p>Forgot password? <a href="forgot.php">Forgot Password</a>.</p>
			</div>
		</div>
	</form>
</div>

<div class="copyright">
    © Copyright : Industri Latihan Perindustrian Miri
</div>

<?php
// Database connection
$host = "localhost:3307";  // Adjust if needed
$dbname = "ilp";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute a query to retrieve the stored hashed password
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($stored_password);
        
        if ($stmt->fetch() && password_verify($password, $stored_password)) {
            // Store user session if login is successful
            $_SESSION['users_email'] = $email;  // Store email in session

            // Redirect based on the email domain
            if (strpos($email, '@jtm.gov.my') !== false) {
                header("Location: staff.php");
                exit();
            } else {
                header("Location: student.php");
                exit();
            }
        } else {
            echo "<script>alert('Invalid email or password. Please try again.');</script>";
        }
        
        $stmt->close();
    } else {
        echo "<script>alert('Database query error. Please try again later.');</script>";
    }
}

$conn->close();
?>

</body>
</html>
