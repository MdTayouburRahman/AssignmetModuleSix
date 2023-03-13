<?php
// Start the session
session_start();

// Define a function to validate the email address
function validate_email($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Define a function to generate a unique filename for the profile picture
function generate_filename($filename) {
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	$filename = pathinfo($filename, PATHINFO_FILENAME);
	return $filename . '_' . time() . '.' . $ext;
}

// Define the directory where the profile pictures will be stored
$uploads_dir = 'uploads/';
// Create the "uploads" directory if it doesn't exist
if (!file_exists($uploads_dir)) {
	mkdir($uploads_dir);
}
// Create the "users.csv" file if it doesn't exist
if (!file_exists($uploads_dir . 'users.csv')) {
	$fp = fopen($uploads_dir . 'users.csv', 'w');
	fclose($fp);
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Validate the form inputs
	if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_FILES['profile-pic']) && validate_email($_POST['email'])) {
		// Save the profile picture to the server
		$filename = generate_filename($_FILES['profile-pic']['name']);
		move_uploaded_file($_FILES['profile-pic']['tmp_name'], $uploads_dir . $filename);

		// Save the user's information to the CSV file
		$user_data = array($_POST['name'], $_POST['email'], $filename);
		$fp = fopen($uploads_dir .'users.csv', 'a');
		fputcsv($fp, $user_data);
		fclose($fp);

		// Set the session and cookie variables
		$_SESSION['name'] = $_POST['name'];
		setcookie('name', $_POST['name'], time()+3600);

		// Redirect to the success page
		header('Location: users.php');
		exit();
	} else {
		// Display an error message
		echo 'Please fill out all fields and enter a valid email address.';
	}
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Example Form</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<!-- Custom styles -->
	<style>
		.form-container {
			margin-top: 50px;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6 form-container">
				<form action="#" method="post" enctype="multipart/form-data">
					<div class="mb-3">
						<label for="name" class="form-label">Name:</label>
						<input type="text" class="form-control" id="name" name="name">
					</div>

					<div class="mb-3">
						<label for="email" class="form-label">Email:</label>
						<input type="email" class="form-control" id="email" name="email">
					</div>

					<div class="mb-3">
						<label for="password" class="form-label">Password:</label>
						<input type="password" class="form-control" id="password" name="password">
					</div>

					<div class="mb-3">
						<label for="profile-pic" class="form-label">Profile Picture:</label>
						<input type="file" class="form-control" id="profile-pic" name="profile-pic">
					</div>

					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
  
	<!-- Bootstrap JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
