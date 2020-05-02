<?php 
include('../functions.php');

if (!isAdmin()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: ../login.php');
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Registration system PHP and MySQL - Create user</title>
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
		<h2>Admin - View Data</h2>
	</div>
	<div class="content">
	 &nbsp; <a href="home.php"> + Go Back to home</a>
	 <small>
<?php 
/* 
$username = "administrator"; 
$password = "password"; 
$database = "multi_login"; 
$mysqli = new mysqli("localhost", $username, $password, $database);  */
//$query = "SELECT * FROM users";users.username, machines.serialNumber, devices.macAddress, counts.count
$query = "SELECT  *
				FROM users 
				LEFT JOIN machines  ON users.id = machines.user
				LEFT JOIN  devices ON machines.id = devices.machine
				LEFT JOIN  counts ON devices.id = counts.device
				
				";  
 
 
echo '<table border="1" cellspacing="2" cellpadding="2"> 
      <tr> 
		  
		  <td> <font face="Arial">username</font> </td>
          <td> <font face="Arial">email</font> </td> 
		  <td> <font face="Arial">user type</font> </td> 
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
		$field22name = $row["user_type"];
        $field3name = $row["serialNumber"];
        $field4name = $row["macAddress"];
        $field5name = $row["count"];
		$field6name = $row["temperature"];
		$field7name = $row["battery"];
        $field8name = $row["time"];
 
        echo '<tr> 
		
                  <td>'.$field1name.'</td> 
                  <td>'.$field2name.'</td> 
				   <td>'.$field22name.'</td> 
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
</div>
</small>
</body>
</html>