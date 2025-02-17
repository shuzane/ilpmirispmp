<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'logout') {
        header("Location: index.php");
		session_destroy();
        exit();
    } 
}
$conn = new mysqli("localhost:3307", "root", "", "ilp");
$email = $_SESSION['users_email'];

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
    <title>Staff Profile - ILP Miri</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<form method="post">
    <div class="login-container">
        <div class="logo">
			<img src="logo.png" alt="Logo" />
        </div>
		
		<div class="student-section">
            <h2>Welcome, <?php echo $first_name . ' ' . $last_name; ?> </h2>
        </div>
		
		<input type="hidden" name="action" value="logout">
		<input type="submit" class="button" value="Logout">
	</div>
</form>
		<div class = "staff-cont">
			<table border="1" class="student-info">
            <tr>
                <td>
						<a href="maklumat_pel.php">
                        <IMG alt="Maklumat Pelajar" src="info.png" width=130 height=90>
						</a>
						<span class="style"><b><center>Maklumat Pelajar</center></b></span>
                    </td>
			</tr>
			<tr>
				<td>
						<a href="pelajar_li.php">
                        <IMG alt="Pelajar Latihan Industri" src="pelajar.png" width=130 height=90>
						</a>
						<span class="style"><b><center>Pelajar Latihan Industri</center></b></span>
                </td>
            </tr>
			<tr>
				<td>
						<a href="update_intern_place.php">
                        <IMG alt="Pelajar Latihan Industri" src="tempat.png" width=130 height=90>
						</a>
						<span class="style"><b><center>Pelajar Latihan Industri</center></b></span>
                </td>
            </tr>
			</table>
        </div>
	
</body>
</html>
