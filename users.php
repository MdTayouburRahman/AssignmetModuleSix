<!DOCTYPE html>
<html>
<head>
	<title>Users List</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<h1>Users List</h1>
		<table class="table">
			<thead>
				<tr>
					<th scope="col">Name</th>
					<th scope="col">Email</th>
					<th scope="col">Profile Picture</th>
					<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				// Define the directory where the profile pictures are stored
				$uploads_dir = 'uploads/';

				// Read the data from the CSV file
				$users = array();
				if (($handle = fopen($uploads_dir.'users.csv', 'r')) !== FALSE) {
				    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
				        $users[] = $data;
				    }
				    fclose($handle);
				}

				// Handle deleting a user
				if (isset($_POST['delete_user'])) {
				    // Get the index of the user to delete
				    $index = $_POST['delete_user'];

				    // Remove the user from the array
				    $deleted_user = $users[$index];
				    unset($users[$index]);

				    // Delete the user's profile picture from the server
				    $filename = $deleted_user[2];
				    $filepath = $uploads_dir . $filename;
				    if (file_exists($filepath)) {
				        unlink($filepath);
				    }

				    // Rewrite the CSV file with the updated user list
				    $fp = fopen($uploads_dir.'users.csv', 'w');
				    foreach ($users as $user) {
				        fputcsv($fp, $user);
				    }
				    fclose($fp);

				    // Redirect back to the users list
				    header('Location: users.php');
				    exit();
				}

				// Display the user data in the table
				foreach ($users as $index => $user) {
					echo '<tr>';
					echo '<td>' . $user[0] . '</td>';
					echo '<td>' . $user[1] . '</td>';
					echo '<td><img src="' . $uploads_dir . $user[2] . '" width="100"></td>';
					echo '<td>';
					echo '<form method="POST">';
					echo '<input type="hidden" name="delete_user" value="' . $index . '">';
					echo '<button type="submit" class="btn btn-danger">Delete</button>';
					echo '</form>';
					echo '</td>';
					echo '</tr>';
				}
				?>
			</tbody>
		</table>
		<p><a href="index.php">Go back to home</a></p>
	</div>

	<!-- Bootstrap JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
