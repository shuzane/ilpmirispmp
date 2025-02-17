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
$intern_place = $student['intern_place'] ?? "";

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
    <title>All Students' Information - ILP Miri</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
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
    <span class="profile">Peribadi Pelajar</span>
    <div class="resources4">
        <ul id="countrytabs" class="shadetabs">
            <li><a href="#" class="tablinks" onclick="openTab(event, 'MaklumatPelajar')">Maklumat Pelajar</a></li>
            <li><a href="#" class="tablinks" onclick="openTab(event, 'MaklumatBapa')">Maklumat Bapa Pelajar</a></li>
            <li><a href="#" class="tablinks" onclick="openTab(event, 'MaklumatIbu')">Maklumat Ibu Pelajar</a></li>
            <li><a href="#" class="tablinks" onclick="openTab(event, 'MaklumatPenjaga')">Maklumat Penjaga Pelajar</a></li>
            <li><a href="#" class="tablinks" onclick="openTab(event, 'SosialMedia')">Sosial Media</a></li>
        </ul>
    </div>

    <div id="MaklumatPelajar" class="tabcontent">
        <table class = "student-info">
            <thead>
                <tr>
                    <th>No. Kad Pengenalan</th>
                    <th>Nama Penuh</th>
                    <th>No. Matrix</th>
                    <th>Kursus</th>
                    <th>No. Telefon</th>
                    <th>Tarikh Lahir</th>
                    <th>Alamat Rumah</th>
                    <th>Agama</th>
                    <th>Bangsa</th>
                    <th>Nama Bank</th>
                    <th>No. Akaun Bank</th>
                    <th>OKU (Kecacatan)</th>
                    <th>Alamat Surat Menyurat</th>
                    <th>Poskod</th>
                    <th>Bandar/Daerah</th>
                    <th>Tempat Latihan Industri</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['student_id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['course']; ?></td>
                        <td><?php echo $row['student_matrix']; ?></td>
                        <td><?php echo $row['phone_number']; ?></td>
                        <td><?php echo $row['birth_date']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['religion']; ?></td>
                        <td><?php echo $row['ethnicity']; ?></td>
                        <td><?php echo $row['bank_name']; ?></td>
                        <td><?php echo $row['bank_number']; ?></td>
                        <td><?php echo $row['disability']; ?></td>
                        <td><?php echo $row['mailing_address']; ?></td>
                        <td><?php echo $row['postcode']; ?></td>
                        <td><?php echo $row['district_city']; ?></td>
                        <td><?php echo $row['intern_place']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div id="MaklumatBapa" class="tabcontent">
        <table class = "student-info">
            <thead>
                <tr>
                    <th>Nama Bapa</th>
                    <th>No. Telefon Bapa</th>
                    <th>Alamat Rumah Bapa</th>
                    <th>Pekerjaan Bapa</th>
                    <th>Pendapat Keluarga</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['father_name']; ?></td>
                        <td><?php echo $row['father_phone']; ?></td>
                        <td><?php echo $row['father_address']; ?></td>
                        <td><?php echo $row['father_occupation']; ?></td>
                        <td><?php echo $row['family_income']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div id="MaklumatIbu" class="tabcontent">
        <table class = "student-info">
            <thead>
                <tr>
                    <th>Nama Ibu</th>
                    <th>No. Telefon Ibu</th>
                    <th>Alamat Rumah Ibu</th>
                    <th>Pekerjaan Ibu</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['mother_name']; ?></td>
                        <td><?php echo $row['mother_phone']; ?></td>
                        <td><?php echo $row['mother_address']; ?></td>
                        <td><?php echo $row['mother_occupation']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div id="MaklumatPenjaga" class="tabcontent">
        <table class = "student-info">
            <thead>
                <tr>
                    <th>Nama Penjaga</th>
                    <th>No. Telefon Penjaga</th>
                    <th>Alamat Rumah Penjaga</th>
                    <th>Pekerjaan Penjaga</th>
                    <th>Hubungan Penjaga</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['guardian_name']; ?></td>
                        <td><?php echo $row['guardian_phone']; ?></td>
                        <td><?php echo $row['guardian_address']; ?></td>
                        <td><?php echo $row['guardian_occupation']; ?></td>
                        <td><?php echo $row['relationship']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div id="SosialMedia" class="tabcontent">
        <table class = "student-info">
            <thead>
                <tr>
                    <th>Facebook</th>
                    <th>Instagram</th>
                    <th>TikTok</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['fb_account']; ?></td>
                        <td><?php echo $row['ig_account']; ?></td>
                        <td><?php echo $row['tiktok_account']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Open the first tab by default
    document.getElementsByClassName('tablinks')[0].click();
</script>

</body>
</html>