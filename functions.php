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

		if (isset($_POST['user_type'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', '$user_type', '$password')";
			mysqli_query($db, $query);
			$_SESSION['success']  = "New user successfully created!!";
			header('location: home.php');
		}else{
			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', 'user', '$password')";
			mysqli_query($db, $query);

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

			$query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', 'user', '$password')";
			mysqli_query($db, $query);


}

				 
				
				
// call the addBusiness() function if register_btn is clicked
if (isset($_POST['add_business_btn'])) {
	addBusiness();
}
function addBusiness(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $businessName;
	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	$businessName    =  e($_POST['businessName']);
// form validation: ensure that the form is correctly filled
	if (empty($businessName)) { 
		array_push($errors, "businessName is required"); 
	}
	// register user if there are no errors in the form
	if (count($errors) == 0) {
		
		
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
function Display_Data()
{
	global $db, $username, $errors;
	
	
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
	global $db;
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
	//print_r($regnew);
	/* foreach ($keyarray as list($table,$key,$val1,$val2, $val3)){

		echo "here!";
		switch ($table){
			case"users":
			$query = "SELECT id FROM users WHERE username='$val1' LIMIT 1";
			$results = mysqli_query($db, $query);
			if (mysqli_num_rows($results) == 1) { // user found
			
			]
			break;
			case"machines":
			
			break;
			case"devices":
			
			break;
			case"counts":
			
			break;
			default:
				echo "key not recognized.";
			break;			
			
			
		} */
		
		/*
		//check if it exists already
		$results = mysqli_query($db, $Qa);
	
		//if it douse then get the id foreignkey and move on
		if (mysqli_num_rows($results) == 1) { // user found
		
		$foreignkey = mysqli_fetch_assoc($results);
		}else{
			

		}				//else create the thing inserting the foreign key get the id key of it and move on
		
	} */

}

//INSERT INTO `machines` (`id`, `user`, `serialNumber`, `dateCreated`) VALUES ('2', '1', 'fgdhg', '2020-04-07')



function uploadJsonString(){
	global $db;
	$data = file_get_contents('php://input');
		$array = json_decode($data, true);
		echo $data;
		echo'<br><br>';
		display_array_recursive($array);
	
} 


function reg_recursive($json_reg){
	global $regnew;
	if($json_rec){
		foreach($json_rec as $key=> $value){
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
	
	$query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
	$results = mysqli_query($db, $query);
	if (mysqli_num_rows($results) == 1) { // user found
	// check if user is admin or user
	$logged_in_user = mysqli_fetch_assoc($results);
	$user = $logged_in_user['id'] ;
	}
	
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
				echo$key.'--'.$value.'<br>';
		
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
						echo '<br>';
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
						echo '<br>';
						echo $user;
						echo '<br>';
						$query = "INSERT INTO machines (user, serialNumber) 
								VALUES('$user', '$value' )";
						mysqli_query($db, $query);//inserting the new row
						$machine = mysqli_insert_id($db);// get the id of the machine
						echo '<br>';
						echo $machine;
						echo '<br>';
							
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
						echo '<br>';
						echo $machine;
						echo '<br>';
						$query = "INSERT INTO devices (machine, macAddress) 
								VALUES('$machine', '$value' )";
						mysqli_query($db, $query);//inserting the new row
						$device = mysqli_insert_id($db);// get the id of the machine
						echo '<br>';
						echo $device;
						echo '<br>';
							
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
						echo '<br>';
						echo $machine;
						echo '<br>';
						$query = "INSERT INTO counts (device, count, temperature, battery ) 
								VALUES('$device', '$count', '$temperature', '$battery' )";
						mysqli_query($db, $query);//inserting the new row
						echo mysqli_insert_id($db);// get the id of the machine
						echo '<br>';
						echo $device;
						echo '<br>';
						
						break;	
					default:
						echo "key not recognized.";
						break;						
				}  
	
			}
		}
	}
	
}