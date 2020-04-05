<?php
include('functions.php');
//login();




//echo "working?";

//require "conn.php";
//$username=$_POST["username"];
//$password=$_POST["password"];
//echo $username;
//echo $password;
	//$data=$_POST["jString"];
	///echo $data;
		
	uploadJsonString();
		
		
		/* 
	$password = md5($password);

		$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
		$results = mysqli_query($db, $query);

 
		
if (mysqli_num_rows($results) > 0) { // user found
			// check if user is admin or user
			//$logged_ = mysqli_fetch_assoc($results);
			//$msg_receive
		//$name =$row["username"];
		echo "login success !!! Welcome user echo";
		//$msg = "login success!! Welcome user msg";

		/*
		$data= file_get_contents($_post["data"]);
		$array = json_decode($data, true);
		foreach($array as $row)
		{
			$sql = "INSERT INTO machines(serialNumber) VALUES ('".$row["machineSerialStr"]."')";
			
			mysqli_query($db, $sql);
		}
		echo "data inserted";
		


}
else{
	echo "login not success !! echo";
	//$msg = "login not success !! msg";
}  */
	session_destroy();
?>