<?php
session_start();
if (!isset($_SESSION['users_email'])) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost:3307", "root", "", "ilp");
$email = $_SESSION['users_email'];

// Jika user tekan upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    
    // Semak jika ada ralat
    if ($file['error'] === 0) {
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $allowed_types = ['jpg', 'jpeg', 'png'];

        // Semak jenis fail & saiz (Maksimum 2MB)
        if (in_array(strtolower($file_ext), $allowed_types) && $file['size'] <= 2000000) {
            // Pastikan folder 'uploads/' wujud
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            // Gunakan nama fail unik untuk elak pertindihan
            $file_name = uniqid("profile_", true) . "." . $file_ext;
            $file_path = 'uploads/' . $file_name;

            // Simpan fail ke server
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                // Kemas kini database dengan nama fail baru
                $query = $conn->prepare("UPDATE users SET profile_picture = ? WHERE email = ?");
                $query->bind_param("ss", $file_name, $email);

                if ($query->execute()) {
                    echo "<script>alert('Profile picture updated successfully!'); window.location.href='profile.php';</script>";
                } else {
                    echo "<p>Error updating profile picture in database.</p>";
                }
            } else {
                echo "<p>Error moving file to server directory.</p>";
            }
        } else {
            echo "<p>Invalid file type or size exceeded (Max: 2MB, JPEG/PNG only).</p>";
        }
    } else {
        echo "<p>Error uploading file. Please try again.</p>";
    }
}

$conn->close();
?>
