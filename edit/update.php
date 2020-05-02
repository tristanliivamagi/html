
<?php

//include('../functions.php')
$db = mysqli_connect("'localhost', 'administrator', 'password', 'multi_login'");
if(isset($_POST["id"]))
{
 $value = mysqli_real_escape_string($db, $_POST["value"]);
 $query = "UPDATE users SET ".$_POST["column_name"]."='".$value."' WHERE id = '".$_POST["id"]."'";
 if(mysqli_query($db, $query))
 {
  echo 'Data Updated';
 }
}
?>
