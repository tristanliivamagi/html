<?php 
  
// The location of the PDF file 
// on the server 
$filename = "TermsOfService.pdf"; 
  
// Header content type 
header("Content-type: application/pdf"); 
  
header("Content-Length: " . filesize($filename)); 
  
// Send the file to the browser. 
readfile($filename); 
	// &nbsp; <a href="register.php"> + Go Back</a>
?>  