<?php 
	include('functions.php');
	if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header">
		<h2>Home Page</h2>
	</div>
	<div class="content">
		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
			<div class="error success" >
				<h3>
					<?php 
						echo $_SESSION['success']; 
						unset($_SESSION['success']);
					?>
				</h3>
			</div>
		<?php endif ?>
		<!-- logged in user information -->
		<div class="profile_info">
			<img src="images/user_profile.png"  >

			<div>
				<?php  if (isset($_SESSION['user'])) : ?>
					<strong><?php echo $_SESSION['user']['username']; ?></strong>

					<small>
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
						<br>
						<a href="index.php?logout='1'" style="color: red;">logout</a>
						<br>
						<?php 	
						$usern=$_SESSION['user']['username'];
					
							echo $usern;
$query = "SELECT  *
				FROM users 
				LEFT JOIN machines  ON users.id = machines.user
				LEFT JOIN  devices ON machines.id = devices.machine
				LEFT JOIN  counts ON devices.id = counts.device
				WHERE username = '$usern'
				";  
 
 
echo '<table border="0" cellspacing="2" cellpadding="2"> 
      <tr> 
		  
		  <td> <font face="Arial">username</font> </td>
          <td> <font face="Arial">email</font> </td> 
          <td> <font face="Arial">serialNumber </font> </td> 
          <td> <font face="Arial">macAddress</font> </td> 
          <td> <font face="Arial">count</font> </td> 
          <td> <font face="Arial">temperature</font> </td> 
          <td> <font face="Arial">battery</font> </td> 
            <td> <font face="Arial">time</font> </td> 
			
      </tr>';
 
if ($result = $db->query($query)) {
    while ($row = $result->fetch_assoc()) {
		$field1name = $row["username"];
		$field2name = $row["email"];
        $field3name = $row["serialNumber"];
        $field4name = $row["macAddress"];
        $field5name = $row["count"];
		$field6name = $row["temperature"];
		$field7name = $row["battery"];
        $field8name = $row["time"];
 
        echo '<tr> 
		
                  <td>'.$field1name.'</td> 
                  <td>'.$field2name.'</td> 
                  <td>'.$field3name.'</td> 
                  <td>'.$field4name.'</td> 
				  <td>'.$field5name.'</td> 
				  <td>'.$field6name.'</td> 
					<td>'.$field7name.'</td> 
				<td>'.$field8name.'</td>    
				
              </tr>';
		
			  
    }
    $result->free();
} 
				?>
				
					</small>

				<?php endif ?>
			</div>
		</div>
	</div>
</body>
</html>