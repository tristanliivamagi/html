<?php include('../functions.php') 
/* 
if (!isAdmin()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: ../login.php');
}
 */
?>

<!DOCTYPE html>
<html>

<head>
	<title>datbase insert json data</title>
	<link rel="stylesheet" type="text/css" href="../style.css">
	<style>
		.header {
			background: #003366;
		}
		button[name=upload_json_btn] {
			background: #003366;
		}
	</style>
</head>
<body>
	<div class="header">
		<h2>Admin - Upload a JSON file</h2>
	</div>
	
	<form method="post" enctype="multipart/form-data"action="Upload_Json.php">
		
		<div class="input-group">
			<label>Select a json file to upload</label>
			<input type="file" name="jsonFile">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="upload_json_btn"> + upload a file</button>
				 &nbsp; <a href="home.php"> + Go Back to home</a>
		</div>
	</form>
</body>
</html>