<html>
<body>
<?php 
$username = "administrator"; 
$password = "password"; 
$database = "multi_login"; 
$mysqli = new mysqli("localhost", $username, $password, $database); 
$query = "SELECT * FROM users";
 
 
echo '<table border="0" cellspacing="2" cellpadding="2"> 
      <tr> 
          <td> <font face="Arial">Value1</font> </td> 
          <td> <font face="Arial">Value2</font> </td> 
          <td> <font face="Arial">Value3</font> </td> 
		  <td> <font face="Arial">Value4</font> </td> 
  
      </tr>';
 
if ($result = $mysqli->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $field1name = $row["username"];
        $field2name = $row["email"];
        $field3name = $row["user_type"];
        $field4name = $row["password"];
 
        echo '<tr> 
                  <td>'.$field1name.'</td> 
                  <td>'.$field2name.'</td> 
                  <td>'.$field3name.'</td> 
                  <td>'.$fieldname.'</td> 
              </tr>';
    }
    $result->free();
} 
?>
</body>
</html>