<?php
session_start();
$conn = new mysqli("localhost:3307", "root", "", "ilp");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_picture'])) {
    $email = $_SESSION['users_email']; // Dapatkan email pengguna yang login

    // Ambil maklumat fail gambar
    $profile_picture = $_FILES['profile_picture']['name'];
    $profile_picture_tmp = $_FILES['profile_picture']['tmp_name'];
    $profile_picture_size = $_FILES['profile_picture']['size'];
    $upload_folder = 'uploads/' . $profile_picture;

    // Semak saiz gambar (maksimum 2MB)
    if ($profile_picture_size > 2000000) {
        echo "<p>The image is too large. Max size: 2MB</p>";
        exit();
    }

    // Pindahkan fail ke folder uploads/
    if (move_uploaded_file($profile_picture_tmp, $upload_folder)) {
        // Kemaskini gambar dalam database
        $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE email = ?");
        $stmt->bind_param("ss", $profile_picture, $email);
        if ($stmt->execute()) {
            echo "<p>Upload successful! <a href='profile.php'>Go to Profile</a></p>";
        } else {
            echo "<p>Error updating database: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Error uploading the file.</p>";
    }
}

$conn->close();
?>
