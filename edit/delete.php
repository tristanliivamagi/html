
<?php

//include('../functions.php')
$db = mysqli_connect("'localhost', 'administrator', 'password', 'multi_login'");
if(isset($_POST["id"]))
{
 $query = "DELETE FROM users WHERE id = '".$_POST["id"]."'";
 if(mysqli_query($db, $query))
 {
  echo 'Data Deleted';
 }
}
?>