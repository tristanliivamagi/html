<?php
//include('functions.php') 
//login();


require "conn.php";
$username=$_POST["username"];
$password=$_POST["password"];
echo $username;
echo $password;

	$password = md5($password);

		$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
		$results = mysqli_query($db, $query);


		
if (mysqli_num_rows($results) > 0) { // user found
			// check if user is admin or user
			//$logged_ = mysqli_fetch_assoc($results);
			//$msg_receive
		//$name =$row["username"];
		echo "login success !!! Welcome user echo";
		$msg = "login success!! Welcome user msg";
}
else{
	echo "login not success !! echo";
	$msg = "login not success !! msg";
}
?>