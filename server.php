<?php
header('Access-Control-Allow-Origin: *');
include ('db_connect.php');
include ('functions.php');

$req = $_REQUEST['REQUEST'];

switch($req)
{
case 'SIGNUP':
echo SIGNUP($conn, $MSG);
break;

case 'LOGIN':
echo LOGIN($conn, $MSG);
break;

case 'FORGET_PASSWORD':
echo FORGET_PASSWORD($conn, $MSG);
break;

case 'ACCOUNT_DELETE':
echo ACCOUNT_DELETE($conn, $MSG);
break;

case 'ACCOUNT_UPDATE':
echo ACCOUNT_UPDATE($conn, $MSG);
break;

case 'ACCOUNT_VIEW':
echo ACCOUNT_VIEW($conn, $MSG);
break;
 
case 'BLOOD_REQUEST':
echo BLOOD_REQUEST($conn, $MSG);
break;

case 'BLOOD_REQUEST_UPDATE':
echo BLOOD_REQUEST_UPDATE($conn, $MSG);
break;

case 'GET_ALL_BLOOD_REQUESTS':
echo GET_ALL_BLOOD_REQUESTS($conn, $MSG);
break;

case 'BLOOD_REQUEST_ACCEPT':
echo BLOOD_REQUEST_ACCEPT($conn, $MSG);
break;

case 'GET_MY_BLOOD_REQUESTS':
echo GET_MY_BLOOD_REQUESTS($conn, $MSG);
break;

case 'BLOOD_REQUEST_DELETE':
echo BLOOD_REQUEST_DELETE($conn, $MSG);
break;

case 'CHANGE_PASSWORD':
echo CHANGE_PASSWORD($conn, $MSG);
break;

case 'VERIFICATION':
echo VERIFICATION($conn, $MSG);
break;


 
}

include ('db_close.php');

?>