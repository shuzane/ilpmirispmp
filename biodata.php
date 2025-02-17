<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'home') {
        header("Location: student.php");
        exit();
    } elseif ($_POST['action'] == 'logout') {
        session_destroy();
        header("Location: index.php");
        exit();
    }
}


$conn = new mysqli("localhost:3307", "root", "", "ilp");
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
<form method="post" action = "">
	<div class="login-container">
        <div class="logo">
			<img src="logo.png" alt="Logo">
        </div>
		
		<div class="student-section">
            <h2>Welcome, <?php echo $first_name . ' ' . $last_name; ?> </h2>
        </div>
		
		<input type="hidden" name="action" value="home">
		<input type="submit" class="button" value="Home">
		
		<input type="hidden" name="action" value="logout">
		<input type="submit" class="button" value="Logout">
	</div>	
</form>

<div class="resources">
	<span class="highlight">Pendaftaran</span>
		<ul class="menuhep">
			<li><a href="pel_info.php">Maklumat Pelajar</a></li>
			<li><a href="update_pel_info.php">Kemaskini Maklumat Peribadi</a></li>
			<li><a href="update_li_info.php">Kemaskini Latihan Industri</a></li>
		</ul>
		<br/> <br/>
	</div>

	
</body>
</html>
