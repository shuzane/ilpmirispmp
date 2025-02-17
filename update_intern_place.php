<?php
session_start();

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
    $intern_place = $conn->real_escape_string($_POST['intern_place']);

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
        $query = $conn->prepare("INSERT INTO student_info (student_id, name, student_matrix, course, birth_date, phone_number, address, religion, ethnicity, bank_name, bank_number, disability, mailing_address, postcode, district_city, intern_place) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($query === false) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }
        $query->bind_param("ssssssssssssssss", $student_id, $name, $student_matrix, $course, $birth_date, $phone_number, $address, $religion, $ethnicity, $bank_name, $bank_number, $disability, $mailing_address, $postcode, $district_city, $intern_place);
        if ($query->execute() === false) {
            throw new Exception("Error executing statement: " . $query->error);
        }

        $conn->commit();
        echo "Data inserted successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error inserting data: " . $e->getMessage();
    } finally {
        $query->close();
    }

    $conn->close();
}

// Fetch student details for display
$query = $conn->prepare("SELECT first_name, last_name FROM users WHERE email = ?");
if ($query === false) {
    die("Error preparing statement: " . $conn->error);
}
$query->bind_param("s", $users_email);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

// Ensure values exist before echoing
$first_name = isset($row['first_name']) ? $row['first_name'] : "";
$last_name = isset($row['last_name']) ? $row['last_name'] : "";

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set the header row
$sheet->setCellValue('A1', 'No. Kad Pengenalan');
$sheet->setCellValue('B1', 'Nama Penuh');
$sheet->setCellValue('C1', 'No. Matrix');
$sheet->setCellValue('D1', 'Kursus');
$sheet->setCellValue('E1', 'No. Telefon');
$sheet->setCellValue('F1', 'Tarikh Lahir');
$sheet->setCellValue('G1', 'Alamat Rumah');
$sheet->setCellValue('H1', 'Agama');
$sheet->setCellValue('I1', 'Bangsa');
$sheet->setCellValue('J1', 'Nama Bank');
$sheet->setCellValue('K1', 'No. Akaun Bank');
$sheet->setCellValue('L1', 'OKU (Kecacatan)');
$sheet->setCellValue('M1', 'Alamat Surat Menyurat');
$sheet->setCellValue('N1', 'Poskod');
$sheet->setCellValue('O1', 'Bandar/Daerah');
$sheet->setCellValue('P1', 'Nama Penjaga');
$sheet->setCellValue('Q1', 'No. Telefon Penjaga');
$sheet->setCellValue('R1', 'Alamat Rumah Penjaga');
$sheet->setCellValue('S1', 'Pekerjaan Penjaga');
$sheet->setCellValue('T1', 'Hubungan Penjaga');
$sheet->setCellValue('U1', 'Facebook');
$sheet->setCellValue('V1', 'Instagram');
$sheet->setCellValue('W1', 'TikTok');
$sheet->setCellValue('X1', 'Tempat Latihan Industri');

// Fill data in the sheet
$rowNum = 2;
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

if ($query === false) {
    die("Error preparing statement: " . $conn->error);
}
$query->execute();
$result = $query->get_result();
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['student_id']);
    $sheet->setCellValue('B' . $rowNum, $row['name']);
    $sheet->setCellValue('C' . $rowNum, $row['student_matrix']);
    $sheet->setCellValue('D' . $rowNum, $row['course']);
    $sheet->setCellValue('E' . $rowNum, $row['phone_number']);
    $sheet->setCellValue('F' . $rowNum, $row['birth_date']);
    $sheet->setCellValue('G' . $rowNum, $row['address']);
    $sheet->setCellValue('H' . $rowNum, $row['religion']);
    $sheet->setCellValue('I' . $rowNum, $row['ethnicity']);
    $sheet->setCellValue('J' . $rowNum, $row['bank_name']);
    $sheet->setCellValue('K' . $rowNum, $row['bank_number']);
    $sheet->setCellValue('L' . $rowNum, $row['disability']);
    $sheet->setCellValue('M' . $rowNum, $row['mailing_address']);
    $sheet->setCellValue('N' . $rowNum, $row['postcode']);
    $sheet->setCellValue('O' . $rowNum, $row['district_city']);
    $sheet->setCellValue('P' . $rowNum, $row['guardian_name']);
    $sheet->setCellValue('Q' . $rowNum, $row['guardian_phone']);
    $sheet->setCellValue('R' . $rowNum, $row['guardian_address']);
    $sheet->setCellValue('S' . $rowNum, $row['guardian_occupation']);
    $sheet->setCellValue('T' . $rowNum, $row['relationship']);
    $sheet->setCellValue('U' . $rowNum, $row['fb_account']);
    $sheet->setCellValue('V' . $rowNum, $row['ig_account']);
    $sheet->setCellValue('W' . $rowNum, $row['tiktok_account']);
    $sheet->setCellValue('X' . $rowNum, $row['intern_place']);
    $rowNum++;
}

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="DATA_PELAJAR_LI_SESI.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

// Close statement
$query->close();
?>