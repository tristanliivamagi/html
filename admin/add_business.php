<?php include('../functions.php') ?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration system PHP and MySQL - Add Business</title>
	<link rel="stylesheet" type="text/css" href="../style.css">
	<style>
		.header {
			background: #003366;
		}
		button[name=register_btn] {
			background: #003366;
		}
	</style>
</head>
<body>
	<div class="header">
		<h2>Admin - add business</h2>
	</div>
	
	<form method="post" action="add_business.php">

		<?php echo display_error(); ?>

		<div class="input-group">
			<label>business name</label>
			<input type="password" name="password_1">
		</div>
		<div class="input-group">
			<label>Confirm business name</label>
			<input type="password" name="password_2">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="add_business_btn"> + Add business</button>
		</div>
	</form>
</body>
</html>