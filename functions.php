<?php 
session_start();
//define("constring",     "'localhost', 'administrator', 'password', 'multi_login'");

//$constring = "'localhost', 'administrator', 'password', 'multi_login'";
$db = mysqli_connect('localhost', 'administrator', 'password', 'multi_login')or die("Connection Error: " . mysqli_error($db));

if (!$db) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

//echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
//echo "Host information: " . mysqli_get_host_info($db) . PHP_EOL;

$curdir = getcwd();
//echo $curdir;
//The name of the directory that we need to create.
//$directoryName = "/temp";
//$tempdir=$curdir.$directoryName;
//echo $tempdir;
//Check if the directory already exists.
/*
if(!is_dir($directoryName)){
    //Directory does not exist, so lets create it.
    if(mkdir($curdir ."/temp", 0777){
		echo "Directory has been created...";
	}else{
		echo "failed to create directory...";
	}else{
		echo "directory already exists";
	}
}
*/


// variable declaration
//users
$user=0;//key id
$username = "";
$email    = "";
$user_type = "";
$password = "";
$errors   = array(); 

///machines
$machine= 0;//key id
$serialNumber="";

//devices
$device=0;//key id
$macAddress ="";

//counts
$count=0;
$temperature=0;
$battery=0;

$keyarray = array();
$valarray = array();
//$listarray = [[1,2],];
// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
	register();
}
// REGISTER USER
function register(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $username, $email;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$username    =  e($_POST['username']);
	$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);

	
	// form validation: ensure that the form is correctly filled
	if (empty($username)) { 
		array_push($errors, "Username is required"); 
	}
	$sql_u = "SELECT * FROM users WHERE username='$username'";
	$res_u = mysqli_query($db, $sql_u);
	if (mysqli_num_rows($res_u) > 0) {
  	  array_push($errors, "Username is already taken"); 
  	}
	if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}
	if (empty($password_1)) { 
		array_push($errors, "Password is required"); 
	}
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password_1);//encrypt the password before saving in the database
		$status=0;
		$activationcode=md5($email.time());
		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO users (username, email, user_type, password,activationcode,status) 
					  VALUES('$username', '$email', '$user_type', '$password','$activationcode','$status')";
			mysqli_query($db, $query);
			$_SESSION['success']  = "New user successfully created!!";
			header('location: home.php');
		}else{
			$query = "INSERT INTO users (username, email, user_type, password,activationcode,status) 
					  VALUES('$username', '$email', 'user', '$password','$activationcode','$status')";
			mysqli_query($db, $query);

			$to=$email;
			$msg= "Thanks for new Registration.";
			$subject="Email verification (phpgurukul.com)";
			$headers .= "MIME-Version: 1.0"."\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
			$headers .= 'From:tristan | Programing Blog <tristanliivamagi@gmail.com>'."\r\n";
			$ms.="<html></body><div><div>Dear $username,</div></br></br>";
			$ms.="<div style='padding-top:8px;'>Please click The following link For verifying and activation of your account</div>
			<div style='padding-top:10px;'><a href=  'http://24.84.210.161:8080/email_verification.php?code=$activationcode'>Click Here</a></div>
			<div style='padding-top:4px;'>Powered by <a href='phpgurukul.com'>phpgurukul.com</a></div></div>
			</body></html>";
			mail($to,$subject,$ms,$headers);
			echo "<script>alert('Registration successful, please verify in the registered Email-Id');</script>";
			echo "<script>window.location = 'login.php';</script>";;
			
			// get id of the created user
			$logged_in_user_id = mysqli_insert_id($db);

			$_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
			$_SESSION['success']  = "You are now logged in";
			header('location: index.php');				
		}
	}
}
$regnew=array();
function remote_register(){
global $db, $errors;

global $regnew ;
$data = file_get_contents('php://input');
		$array = json_decode($data, true);
		echo $data;
		echo'<br><br>';
		reg_recursive($array);


$username=$regnew["username"];
$email=$regnew["email"];
$password=$regnew["password_1"];
$password=md5($password);
	$sql_u = "SELECT * FROM users WHERE username='$username'";
	$res_u = mysqli_query($db, $sql_u);
	if (mysqli_num_rows($res_u) > 0) {
  	  echo "username already taken"; 
  	}else{
			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', 'user', '$password')";
			mysqli_query($db, $query);
	}

}

				 
				
				


// return user array from their id
function getUserById($id){
	global $db;
	$query = "SELECT * FROM users WHERE id=" . $id;
	$result = mysqli_query($db, $query);

	$user = mysqli_fetch_assoc($result);
	return $user;
}

// escape string
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="error">';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}	

function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}

// log user out if logout button clicked
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: login.php");
}

// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
	login();
}

// LOGIN USER
function login(){
	global $db, $username, $errors;

	// grab form values
	$username = e($_POST['username']);
	$password = e($_POST['password']);

	// make sure form is filled properly
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	// attempt login if no errors on form
	if (count($errors) == 0) {
		$password = md5($password);

		$query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) { // user found
			// check if user is admin or user
			$logged_in_user = mysqli_fetch_assoc($results);
			
			if ($logged_in_user['user_type'] == 'admin') {

				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";
				header('location: admin/home.php');		  
			}else{
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";

				header('location: index.php');
				
			

			}
		}else {
			array_push($errors, "Wrong username/password combination");
		}
	}
}

function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
		return true;
	}else{
		return false;
	}
}

// Valid constant names
//define("FOO",     "something");
//define("FOO2",    "something else");
//define("FOO_BAR", "something more");
// variable declaration


// call the uploadJson() function if register_btn is clicked
if (isset($_POST['upload_json_btn'])) {
	uploadJson();
}

//insert machine stuff from json file
function uploadJson(){
	global $db, $username;
	//global $db,$regnew,$keyarray;
	//$foreignkey = 0;
	//$id = 0;
	
	if(isset($_FILES['jsonFile'])){
		print_r($_FILES);
	}
	$data= file_get_contents($_FILES['jsonFile']['tmp_name']);//'machinesOperationalData.json'
	$array = json_decode($data,true);
	echo'<br><br>';
	echo $data;
	//var_dump($array);
	echo'<br><br>';
	print_r($array);
	echo'<br><br>';
	//echo $array;
	echo'<br><br>';
	//reg_recursive($array);
	//session_destroy();
	display_array_recursive($array);
	
	
	echo'<br><br>';
	
	database_json($username);
	

}


function uploadJsonString(){
	global $db , $username;
	$data = file_get_contents('php://input');
		$array = json_decode($data, true);
		echo $data;
		echo'<br><br>';
		display_array_recursive($array);
		database_json($username);
	
} 

//for getting user info used in remote user creation
function reg_recursive($json_reg){
	global $regnew;
	if($json_reg){
		foreach($json_reg as $key=> $value){
			if(is_array($value)){
				display_array_recursive($value);
			}else{
				echo$key.'--'.$value.'<br>';
				$regnew +=[$key =>$value];

			}
		}		
	}
}
function database_json($username)
{
	global $db;
	$jsonarray=array();
	
	//users.username, machines.serialNumber, devices.macAddress, counts.count 
		
		$query = "SELECT *
		FROM users 
		LEFT JOIN machines  ON users.id = machines.user
		LEFT JOIN  devices ON machines.id = devices.machine
		LEFT JOIN  counts ON devices.id = counts.device
		WHERE users.username = '$username'
		";  

		//$query = "SELECT * FROM users ";
		$result = mysqli_query($db, $query) or die("Error in Selecting " . mysqli_error($db));
		while($row =mysqli_fetch_assoc($result))
		{
			
			$jsonarray[] = $row;
		}
		//echo $jsonarray;
		echo json_encode($jsonarray);
/* 		$query = "SELECT serialNumber,machine FROM machines WHERE user ='$user'";
		$results = mysqli_query($db, $query);
		while($row =mysqli_fetch_assoc($result))
		{
			$jasonarray+=["serialNumber" => $row];
			
		} */

	
}
function display_array_recursive($json_rec){
	
	global $db;
	global $user , $username , $email , $user_type , $password ;
	global $machine , $serialNumber ;
	global $device , $macAddress ;
	global $count , $temperature , $battery ;
	//global $keyarray , $valarray;
	if($json_rec){
		foreach($json_rec as $key=> $value){
			if(is_array($value)){
				display_array_recursive($value);
			}else{
				//echo$key.'--'.$value.'<br>';
		
				switch ($key) {
					case "username":
						$username=$value;
						
						break;
					case "password"://users
						$query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
						$results = mysqli_query($db, $query);
						if (mysqli_num_rows($results) == 1) { // user found
						// check if user is admin or user
						$logged_in_user = mysqli_fetch_assoc($results);
						$user = $logged_in_user['id'] ;
						//echo '<br>';
						}

						break;
					case "serialNumber"://machines
						$query = "SELECT * FROM machines WHERE serialNumber='$value' LIMIT 1";
						$results = mysqli_query($db, $query);
						if (mysqli_num_rows($results) == 1) { // found the machine already in database
						$found_machine = mysqli_fetch_assoc($results);
						$machine = $found_machine['id'];// get the id of the machine
						$query2="UPDATE machines SET user='$user' WHERE id='$machine'";
						mysqli_query($db, $query2);
						}else{//add new row to database
						//echo '<br>';
						//echo $user;
						//echo '<br>';
						$query = "INSERT INTO machines (user, serialNumber) 
								VALUES('$user', '$value' )";
						mysqli_query($db, $query);//inserting the new row
						$machine = mysqli_insert_id($db);// get the id of the machine
						//echo '<br>';
						//echo $machine;
						//echo '<br>';
							
						}
						
						break;
					case "macAddress"://devices
						$query = "SELECT * FROM devices WHERE macAddress='$value' LIMIT 1";
						$results = mysqli_query($db, $query);
						if (mysqli_num_rows($results) == 1) { // found the device already in database
						$found_device = mysqli_fetch_assoc($results);
						$device = $found_device['id'];// get the id of the device
						$query2="UPDATE devices SET machine='$machine' WHERE id='$device'";
						mysqli_query($db, $query2);
						}else{//add new row to database
						//echo '<br>';
						//echo $machine;
						//echo '<br>';
						$query = "INSERT INTO devices (machine, macAddress) 
								VALUES('$machine', '$value' )";
						mysqli_query($db, $query);//inserting the new row
						$device = mysqli_insert_id($db);// get the id of the machine
						//echo '<br>';
						//echo $device;
						//echo '<br>';
							
						}
						break;		
					case "count":
						$count=$value;/////
						
						break;
					case "temperature":
						$temperature=$value;/////
						
						break;
					case "battery":
						$battery = $value;/////
						//echo '<br>';
						//echo $machine;
						//echo '<br>';
						$query = "INSERT INTO counts (device, count, temperature, battery ) 
								VALUES('$device', '$count', '$temperature', '$battery' )";
						mysqli_query($db, $query);//inserting the new row
						echo mysqli_insert_id($db);// get the id of the machine
						//echo '<br>';
						//echo $device;
						//echo '<br>';
						
						break;	
					default:
						//echo "key not recognized.";
						break;						
				}  
	
			}
		}
	}
	
}