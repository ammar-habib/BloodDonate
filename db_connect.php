<?php


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blood-donate";


/* LIVE 
$servername = "localhost";
$username = "safespac_donate";
$password = "P@kistan1";
$dbname = "safespac_donate";*/



$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error)
{
#    die("Connection failed: " . $conn->connect_error);
	
	$json['STATUS'] = 'ERROR';
	$json['MESSEGE'] = $conn->connect_error;
	
	echo json_encode($json);
	$conn->close();
	exit;	

}

?>