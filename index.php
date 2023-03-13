<?php
session_start();

// Function to validate email address
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to generate unique filename
function generateFilename($filename) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $filename = uniqid() . "_" . date("Y-m-d_H-i-s") . "." . $ext;
    return $filename;
}

// Check if the form is submitted
if(isset($_POST["submit"])) {
	$name = $_POST["name"];
	$email = $_POST["email"];
	$password = $_POST["password"];

	// Validate form inputs
	if(empty($name) || empty($email) || empty($password) || !validateEmail($email)) {
		echo "Please fill out all fields with valid inputs.";
		exit();
	}

	// Save profile picture to the server
	$filename = $_FILES["profile_pic"]["name"];
	$tmpname = $_FILES["profile_pic"]["tmp_name"];
	$upload_dir = "uploads/";
	$new_filename = generateFilename($filename);
	move_uploaded_file($tmpname, $upload_dir.$new_filename);

	// Save user data to CSV file
	$user_data = array($name, $email, $new_filename);
	$file = fopen("users.csv","a");
	fputcsv($file, $user_data);
	fclose($file);

	// Set cookie with user's name
	setcookie("name", $name, time() + (86400 * 30), "/");

	// Redirect to success page
	header("Location: success.php");
	exit();
}
?>