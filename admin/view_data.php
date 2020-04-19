
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
		<h2>Admin - View User</h2>
	</div>
<?php 
$username = "administrator"; 
$password = "password"; 
$database = "multi_login"; 
$mysqli = new mysqli("localhost", $username, $password, $database); 
$query = "SELECT * FROM users";
 
 
echo '<table border="0" cellspacing="2" cellpadding="2"> 
      <tr> 
		  
		  <td> <font face="Arial">id</font> </td>
          <td> <font face="Arial">User name</font> </td> 
          <td> <font face="Arial">Email</font> </td> 
          <td> <font face="Arial">User Type</font> </td> 
		  <td> <font face="Arial">Password</font> </td> 
  
      </tr>';
 
if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
		$field1name = $row["id"];
        $field2name = $row["username"];
        $field3name = $row["email"];
        $field4name = $row["user_type"];
        $field5name = $row["password"];
 
        echo '<tr> 
                  <td>'.$field1name.'</td> 
                  <td>'.$field2name.'</td> 
                  <td>'.$field3name.'</td> 
                  <td>'.$field4name.'</td> 
				   <td>'.$field5name.'</td> 
              </tr>';
		
			  
    }
    $result->free();
} 
?>
</body>
</html>