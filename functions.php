<?php 
session_start();

// connect to database  dbLocation   user           password    dbName
$db = mysqli_connect('localhost', 'administrator', 'password', 'multi_login')or die("Connection Error: " . mysqli_error($db));

if (!$db) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($db) . PHP_EOL;

$curdir = getcwd();
//echo $curdir;
//The name of the directory that we need to create.
$directoryName = "/temp";
$tempdir=$curdir.$directoryName;
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

	// grap form values
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
define("FOO",     "something");
define("FOO2",    "something else");
define("FOO_BAR", "something more");
// variable declaration
//$filename="";


// call the login() function if register_btn is clicked
if (isset($_POST['upload_json_btn'])) {
	uploadJson();
}

//insert machine stuff from json file
function uploadJson(){
		
	if(isset($_FILES['jsonFile'])){
		print_r($_FILES);
	}


	$data= file_get_contents($_FILES['jsonFile']['tmp_name']);//'machinesOperationalData.json'
		$array = json_decode($data, true);
		echo $data;
		echo'<br><br>';
		display_array_recursive($array);
		//foreach($array as $row)
		//{
		//	$sql = "INSERT INTO machines(serialNumber) VALUES ('".$row["machineSerialStr"]."')";
			
		//	mysqli_query($db, $sql);
		//}
		//echo "data inserted";
}

function uploadJsonString(){
	
	$data = file_get_contents('php://input');
		$array = json_decode($data, true);
		echo $data;
		echo'<br><br>';
		display_array_recursive($array);
		//foreach($array as $row)
		//{
		//	$sql = "INSERT INTO machines(serialNumber) VALUES ('".$row["machineSerialStr"]."')";
			
		//	mysqli_query($db, $sql);
		//}
		//echo "data inserted";
}

function display_array_recursive($json_rec){

	global $db;
	global $user , $username , $email , $user_type , $password ;
	global $machine , $serialNumber ;
	global $device , $macAddress ;
	global $count , $temperature , $battery ;

	$query= "" ;

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
						$password=$value;  
						echo $username;
						echo $password;
						$password = md5($password);
						
						$query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
						
						echo $query;
						//$results = mysqli_query($db, $query)or die(mysqli_error($db));
						//echo $results ;
						if (mysqli_num_rows($results) >0) { // user found
							$row = mysql_fetch_row($results);
							$user = $row[0];
							echo $user;
							echo "got here";
						}			 
						break;
					case "serialNumber"://machines
					
					
						$serialNumber=$value;		
						$query = "INSERT INTO machines (user, serialNumber) 
					  VALUES('$user', '$SerialNumber')";
					    $results = mysqli_query($db, $query);
						$query = "SELECT * FROM machines WHERE serialNumber='$serialNumber'";
						//$results = mysqli_query($db, $query)or die(mysqli_error($db));
						if (mysqli_num_rows($results) >0) { // machine found
							$row = mysql_fetch_row($results);
							$machine = $row[0];
							echo $machine;
						}	 
						break;
					case "macAddress"://devices
						$macAddress=$value;
						$query = "INSERT INTO devices (machine, macAddress) 
					   VALUES('$machine', '$macAddress')";
					    $results = mysqli_query($db, $query);
						$query = "SELECT * FROM machines WHERE macAddress='$macAddress'";
						//$results = mysqli_query($db, $query)or die(mysqli_error($db));
						if (mysqli_num_rows($results) >0) { // machine found
							$row = mysql_fetch_row($results);
							$device = $row[0];
							echo $device;
						} 
						break;		
					case "count":
						$count=$value;
						break;
					case "temperature":
						$temperature=$value;
						break;
					case "battery":
						$battery=$value;
						$query = "INSERT INTO devices (device, count, temperature, battery) 
					   VALUES('$device', '$count' , '$temperature' , '$battery' )"; 
					   // $results = mysqli_query($db, $query)or die(mysqli_error($db));
						break;	
					default:
						echo "key not recognized.";
						break;						
				}  
	
			}
		}
	}
	
}