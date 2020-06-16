
<?php

//include('../functions.php')
$db = mysqli_connect("'localhost', 'administrator', 'password', 'multi_login'");
if(isset($_POST["username"], $_POST["email"], $_POST["usertype"], $_POST["password"]))
{
 $username = mysqli_real_escape_string($db, $_POST["username"]);
 $email = mysqli_real_escape_string($db, $_POST["email"]);
 $usertype = mysqli_real_escape_string($db, $_POST["usertype"]);
 $password = md5(mysqli_real_escape_string($db, $_POST["password"]));
 $query = "INSERT INTO users(username, email , usertype , password) VALUES('$username', '$email', '$usertype', '$password')";
 if(mysqli_query($db, $query))
 {
  echo 'Data Inserted';
 }
}
?>