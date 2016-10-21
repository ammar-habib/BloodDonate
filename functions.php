<?php
	$MSG = array(
		"SIGNUP_SUCCESS"=>"Account Created Successfully ",
		"SIGNUP_ERROR"=>"Account not Created ",

		"ACCOUNT_DELETE_SUCCESS"=>"Account Delete Successfully ",
		"ACCOUNT_DELETE_ERROR"=>"Account can't Delete ",

		"ACCOUNT_UPDATE_SUCCESS"=>"Account Update Successfully ",
		"ACCOUNT_UPDATE_ERROR"=>"Account can't Update ",
	    
	    "ACCOUNT_VIEW_SUCCESS"=>"See your profile",
		"ACCOUNT_VIEW_NOTFOUND"=>"User Not Found",
		"ACCOUNT_VIEW_ERROR"=>"Account view Failed",

	    "LOGIN_SUCCESS"=>"Login Successfull",
		"LOGIN_NOTFOUND"=>"User not Found Email or password are Incorrent",
		"LOGIN_ERROR"=>"Login Failed", 

	    "GET_ALL_BLOOD_REQUESTS_SUCCESS"=>"See All Blood Requests",
		"GET_ALL_BLOOD_REQUESTS_NOTFOUND"=>"Data not Found",
		"GET_ALL_BLOOD_REQUESTS_ERROR"=>"Get Blood Requests Failed",

        "BLOOD_REQUEST_ACCEPT_SUCCESS"=>"Your Blood Request Accepted",
		"BLOOD_REQUEST_ACCEPT_NOTFOUND"=>"Data not Found",
		"BLOOD_REQUEST_ACCEPT_ERROR"=>"Your Blood Request Not Accepted",

		"GET_My_BLOOD_REQUESTS_SUCCESS"=>"See Your All Blood Requests",
		"GET_My_BLOOD_REQUESTS_NOTFOUND"=>"Data not Found",
		"GET_My_BLOOD_REQUESTS_ERROR"=>"you cann't own Blood Requests",

		"BLOOD_REQUEST_SUCCESS"=>"Blood Request Send Successfully",
		"BLOOD_REQUEST_ERROR"=>"Blood Request not Send",

		"BLOOD_REQUEST_UPDATE_SUCCESS"=>"Blood Request Update Successfully",
		"BLOOD_REQUEST_UPDATE_ERROR"=>" Blood Request Not Update Delete",

		"BLOOD_REQUEST_DELETE_SUCCESS"=>"Blood Request Delete Successfully",
		"BLOOD_REQUEST_DELETE_ERROR"=>"Blood Request Not Delete ",

		"CHANGE_PASSWORD_SUCCESS"=>"password Change Successfully",
		"CHANGE_PASSWORD_NOTFOUND"=>"Email not Found",
		"CHANGE_PASSWORD_ERROR"=>"You can't Change password"
	);

function SIGNUP($conn, $MSG)
{

    $sql = $conn->prepare("INSERT INTO user VALUES ('', ?, ?, SHA1(?), ?, ?, ?, ?, ?, ?, '', now(), ?, 0)");
	$sql->bind_param("ssssssssss", $name, $email, $password, $contact, $gender, $blood_group, $latitude, $longitude, $city,$verify_code);
	
	$name = $_REQUEST["NAME"];
	$email = $_REQUEST["EMAIL"];
	$password = $_REQUEST["PASSWORD"];
	$contact = $_REQUEST["CONTACT"];
	$gender = $_REQUEST["GENDER"];
	$blood_group = $_REQUEST["BLOOD_GROUP"];
	$latitude = $_REQUEST["LATITUDE"];
	$longitude = $_REQUEST["LONGITUDE"];
	$city = $_REQUEST["CITY"];
	$verify_code = SHA1(rand(0,1000));
    if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = $MSG["SIGNUP_SUCCESS"];
		$to      = $email; // Send email to our user


		$headers = "From: noreply@bugdevstudios.com" . "\r\n";
		$headers .= "Reply-To: noreply@bugdevstudios.com" . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


		$subject = 'Account Verification | Donate Blood, Save Life'; // Give the email a subject 
		$message = '<html><body style="width:600px;">';
		$message .= '<h1 style="color:#f40;">Email Verification</h1><br>';
		$message .= '<p style="color:#080;font-size:18px;">To verify your email please click the following button</p><br>';
		$message .= '<p style="border: medium outset #ff0000;background-color:#ff0000;text-decoration:none;padding:2px;"><a href="http://bugdevstudios.com/donate/server.php?REQUEST=VERIFICATION&CODE='.$verify_code.'">Click Me</a></p><br>';
		$message .= '<p style="color:#080;font-size:18px;">Please click this link to Verify your Email:
		http://bugdevstudios.com/donate/server.php?REQUEST=VERIFICATION&CODE='.$verify_code.'</p><br>';
        $message .= '</body></html>';

		if(mail($to, $subject, $message, $headers)) // Send our email
		{
			$json["MESSAGE"] .= ' Your mail has been sent successfully.';
		} else{
		    $json["MESSAGE"] .= ' Unable to send email. Please try again.';
		}
    }
	else
	{
		$json["STATUS"] = "ERROR";
		$json["MESSAGE"] = $MSG["SIGNUP_ERROR"].$sql->error;
	}

	$sql->close();
    return json_encode($json);
    #function ends
}

function VERIFICATION($conn, $MSG)
{	
    
	$sql1 = $conn->prepare("UPDATE user SET status = 1 WHERE verify_code = ? ");
	$sql1->bind_param("s", $verify_code);
	$verify_code = $_REQUEST['CODE'];
    if ($sql1->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = "Your account has been activated, you can now login";
    }
	else
	{
		$json["STATUS"] = "ERROR";
		$json["MESSAGE"] =  "The url is either invalid or you already have activated your account".$sql1->error;
	}
	
	$sql->close();
    return json_encode($json);
    #function ends
	
}

function ACCOUNT_DELETE($conn, $MSG)
{		
	$sql = $conn->prepare("UPDATE user SET status = 2 WHERE user_id = ?");
	$sql->bind_param("i", $user_id);
	$user_id = $_REQUEST["USER_ID"];
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] =  $MSG["ACCOUNT_DELETE_SUCCESS"];
    }
	else
	{
		$json["STATUS"] = "ERROR";
		$json["MESSAGE"] =  $MSG["ACCOUNT_DELETE_ERROR"].$sql->error;
	}

	$sql->close();
	return json_encode($json);
    #function ends
}

function ACCOUNT_UPDATE($conn, $MSG)
{
	$sql = $conn->prepare("UPDATE user SET name = ?, email = ?, password = SHA1(?), contact = ?, gender = ?,  blood_group = ?, latitude = ?, longitude = ?, city = ?, last_donate = ? WHERE user_id = ?");
	$sql->bind_param("ssssssssssi", $name, $email, $password, $contact, $gender, $blood_group, $latitude, $longitude, $city, $last_donate, $user_id);
	
	$user_id = $_REQUEST["USER_ID"];
	$name = $_REQUEST["NAME"];
	$email = $_REQUEST["EMAIL"];
	$password = $_REQUEST["PASSWORD"];
	$contact = $_REQUEST["CONTACT"];
	$gender = $_REQUEST["GENDER"];
	$blood_group = $_REQUEST["BLOOD_GROUP"];
	$latitude = $_REQUEST["LATITUDE"];
	$longitude = $_REQUEST["LONGITUDE"];
	$city = $_REQUEST["CITY"];
	$last_donate = $_REQUEST["LAST_DONATE"];
	
	
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] =  $MSG["ACCOUNT_UPDATE_SUCCESS"];

	}
	else
	{
		$json["STATUS"] = "ERROR";
		$json["MESSAGE"] =  $MSG["ACCOUNT_UPDATE_ERROR"].$sql->error;
	}
    $sql->close();
    return json_encode($json);
    #function ends
}
 
 function ACCOUNT_VIEW($conn, $MSG)
{  
    $sql = $conn->prepare("SELECT * FROM user WHERE user_id = ?");
	$sql->bind_param("i", $user_id);
	$user_id = $_REQUEST["USER_ID"];

	if($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] =  $MSG["ACCOUNT_VIEW_SUCCESS"];
		$sql->bind_result($user_id, $name, $email, $password, $contact, $gender, $blood_group, $latitude, $longitude, $city, $last_donate, $signup_date, $status);
	   
	    $count =0;
		while ($sql->fetch())
		{
			$_data["name"] = $name;
			$_data["email"] = $email;
			$_data["password"] = $password;
			$_data["contact"] = $contact;
			$_data["gender"] = $gender;
			$_data["blood_group"] = $blood_group;
			$_data["latitude"] = $latitude;
            $_data["longitude"] = $longitude;
            $_data["city"] = $city;
            $_data["last_donate"] = $last_donate;
			$_data["signup_date"] = $signup_date;
			$_data["status"] = $status;
			$json["DATA"][] = $_data;
			unset($_data);
			$count++;
		}
		if ($count == 0)
		{
			$json["STATUS"] = "NOTFOUND";
			$json["MESSAGE"] =  $MSG["ACCOUNT_VIEW_NOTFOUND"];

		}
	}
	else
	{	
		$json["STATUS"] = "ERROR";
		$json["MESSAGE"] =  $MSG["ACCOUNT_VIEW_ERROR"];
		return json_encode($json);
	}
	$sql->close();
	return json_encode($json);
    #function ends
}

function LOGIN($conn, $MSG)
{
	$sql = $conn->prepare("SELECT * FROM user WHERE  email = ? AND password = SHA1(?) AND status != 2");
	$sql->bind_param("ss", $email, $password);
    $email = $_REQUEST["EMAIL"];
	$password =$_REQUEST["PASSWORD"];

	if($sql->execute())
	{
	    $json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = $MSG["LOGIN_SUCCESS"];;
		$sql->bind_result($user_id, $name, $email, $password, $contact, $gender, $blood_group, $latitude, $longitude, $city, $last_donate, $signup_date, $verify_code, $status);
	   
	    $count =0;
		while ($sql->fetch())
		{
			$_data["name"] = $name;
			$_data["email"] = $email;
			$_data["contact"] = $contact;
			$_data["gender"] = $gender;
			$_data["blood-group"] = $blood_group;
			$_data["latitude"] = $latitude;
            $_data["longitude"] = $longitude;
            $_data["city"] = $city;
            $_data["last-donate"] = $last_donate;
			$_data["signup_date"] = $signup_date;
			$json["DATA"][] = $_data;
			unset($_data);
			$count++;
		}
		if ($count == 0)
		{
			$json["STATUS"] = "NOTFOUND";
			$json["MESSAGE"] =  $MSG["LOGIN_NOTFOUND"];
		}
	    
    }
    else
    {  
        $json["STATUS"] = "ERROR";
		$json["MESSAGE"] =  $MSG["LOGIN_ERROR"];
		return json_encode($json);
	}
	
	$sql->close();
	return json_encode($json);
	#function ends
} 
 
 function FORGET_PASSWORD($conn, $MSG)
{
	$sql = $conn->prepare("SELECT * FROM user WHERE  email = ? ");
	$sql->bind_param("s",$email);
    $email = $_REQUEST["EMAIL"];
	if($sql->execute())
	{
	    $json["STATUS"] = "SUCCESS";	
	}
	else
	{
		$json["STATUS"] = "FAIL";
	}
	$sql->bind_result($i, $n, $e, $p, $c, $b, $g, $d, $sd, $cc, $s);
	$count =0;
	while($sql->fetch())
	{
		$_data["email"] = $e;
		$_data["password"] = $p;
		$json["DATA"][] = $_data;
		$from = "Coding Cyber";
		$url = "http://www.codingcyber.com/";
		$body  =  "Coding Cyber password recovery Script
		-----------------------------------------------
		Url : $url;
		email Details is : $e;
		Here is your password  : $p;
		Sincerely,
		Coding Cyber";
		$from = "ammarhabib@yahoo.com";
		$subject = "CodingCyber Password recovered";
		$headers1 = "From: $from\n";
		$headers1 .= "Content-type: text/html;charset=iso-8859-1\r\n";
		$headers1 .= "X-Priority: 1\r\n";
		$headers1 .= "X-MSMail-Priority: High\r\n";
		$headers1 .= "X-Mailer: Just My Server\r\n";
		$sentmail = mail( $e, $subject, $body, $headers1 );
		$count++;
	}
	if ($_REQUEST["EMAIL"]!=="")
	{
		$json["STATUS"] = "FAIL";
		$json["MESSAGE"] = "Not found your email in our database";
    }
	if($sentmail==1)
	{
		$json["MESSAGE"] = "Your Password Has Been Sent To Your Email Address.";
	}
		else
		{
		if($_REQUEST["EMAIL"]!=="")
		$json["MESSAGE"] = "Cannot send password to your e-mail address.Problem with sending mail...";
	   }
	   $sql->close();
	   return json_encode($json);
	   #function ends
 } 
 
function GET_ALL_BLOOD_REQUESTS($conn, $MSG)
{
    $sql = $conn->prepare("select u.user_id, u.name, b.request_id, b.blood_group, b.request_date, b.latitude, b.longitude, b.status from user u left join blood_request b ON (u.user_id = b.user_id) where b.status != '2'");
	if($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] =  $MSG["GET_ALL_BLOOD_REQUESTS_SUCCESS"];
		$sql->bind_result($user_id, $name, $request_id, $blood_group, $request_date, $latitude, $longitude, $status);
	   
	    $count =0;
		while ($sql->fetch())
		{
			$_data["USER_ID"] = $user_id;
			$_data["NAME"] = $name;
			$_data["REQUEST_ID"] = $request_id;
		    $_data["BLOOD_GROUP"] = $blood_group;
			$_data["REQUEST_DATE"] = $request_date;
			$_data["LATITUDE"] = $latitude;
			$_data["LONGITUDE"] = $longitude;
	        $_data["STATUS"] = $status;
			$json["DATA"][] = $_data;
			unset($_data);
			$count++;
		}
		if ($count == 0)
		{
			$json["STATUS"] = "NORFOUND";
			$json["MESSAGE"] = $MSG["GET_ALL_BLOOD_REQUESTS_NOTFOUND"];

		}
	}
	else
	{	
		$json["STATUS"] = "ERROR";
		$json["MESSAGE"] = $MSG["GET_ALL_BLOOD_REQUESTS_ERROR"]. $sql->error;
        return json_encode($json);
	}
	$sql->close();
	return json_encode($json);
    #function ends
}
 
function BLOOD_REQUEST_ACCEPT($conn, $MSG)
{
	$sql = $conn->prepare("INSERT INTO accept_request VALUES ('', ?, ?, now(), 1)");
	$sql->bind_param("ii", $user_id, $request_id);
	$user_id = $_REQUEST["USER_ID"];
	$request_id = $_REQUEST["REQUEST_ID"];
	if ($sql->execute())
	{
        
		$sql = $conn->prepare("UPDATE blood_request SET status = 1 WHERE request_id = ?");
        $sql->bind_param("i", $request_id);
		$request_id = $_REQUEST["REQUEST_ID"];
		if ($sql->execute()){
			$json["STATUS"] = "SUCCESS";
			$json["MESSAGE"] = $MSG["BLOOD_REQUEST_ACCEPT_SUCCESS"];
		}
		else
		{
			$json["STATUS"] = "FAIL";
			$json["MESSAGE"] = $MSG["BLOOD_REQUEST_ACCEPT_FAIL"]. $sql->error;
		}
	}
	else
	{
		$json["STATUS"] = "ERROR";
		$json["MESSAGE"] = $MSG["BLOOD_REQUEST_ACCEPT_ERROR"]. $sql->error;
	}
    $sql->close();
    return json_encode($json);
    #function ends
}

function GET_MY_BLOOD_REQUESTS($conn, $MSG)
{
    $sql = $conn->prepare("SELECT * FROM blood_request WHERE user_id = ? AND (status = 0 OR status = 1)");
	$sql->bind_param("i", $user_id);
	$user_id = $_REQUEST["USER_ID"];
	if($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = $MSG["GET_My_BLOOD_REQUESTS_SUCCESS"];
		$sql->bind_result($request_id, $user_id, $longitude, $latitude, $blood_group, $request_date, $status);
	   
	    $count =0;
		while ($sql->fetch())
		{
			$_data["REQUEST_ID"] = $request_id;
			$_data["LONGITUDE"] = $longitude;
			$_data["LATITUDE"] = $latitude;
			$_data["BLOOD_GROUP"] = $blood_group;
			$_data["REQUEST_DATE"] = $request_date;
			$_data["STATUS"] = $status;
			$json["DATA"][] = $_data;
			unset($_data);
			$count++;
		}
		if ($count == 0)
		{
			$json["STATUS"] = "NOTFOUND";
			$json["MESSAGE"] = $MSG["GET_My_BLOOD_REQUESTS_NOTFOUND"]. $sql->error;

		}
	}
	else
	{	
		$json["STATUS"] = "ERROR";
		$json["MESSAGE"] = $MSG["GET_MY_BLOOD_REQUESTS_ERROR"]. $sql->error;
        return json_encode($json);
	}
	$sql->close();
	return json_encode($json);
    #function ends
}

function BLOOD_REQUEST($conn, $MSG)
{
    $sql = $conn->prepare("INSERT INTO blood_request VALUES ('', ?, ?, ?, ?,now(), 0)");
	$sql->bind_param("isss", $user_id, $latitude, $longitude, $blood_group);
	$user_id = $_REQUEST["USER_ID"];
	$latitude = $_REQUEST["LATITUDE"];
    $longitude = $_REQUEST["LONGITUDE"];
	$blood_group = $_REQUEST["BLOOD_GROUP"];
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = $MSG["BLOOD_REQUEST_SUCCESS"];
    }
	else
	{
		$json["STATUS"] = "ERROR";
		$json["MESSAGE"] = $MSG["BLOOD_REQUEST_ERROR"].$sql->error;
	}
    $sql->close();
    return json_encode($json);
    #function ends
}
 
function BLOOD_REQUEST_UPDATE($conn, $MSG)
{
    $sql = $conn->prepare("UPDATE blood_request SET longitude = ?, latitude = ?, blood_group = ?, request_date = now() WHERE request_id = ? AND user_id = ? AND status = 0");
	$sql->bind_param("sssii", $longitude, $latitude, $blood_group, $request_id, $user_id);
    $longitude = $_REQUEST["LONGITUDE"];
	$latitude = $_REQUEST["LATITUDE"];
	$blood_group = $_REQUEST["BLOOD_GROUP"];
	$request_id = $_REQUEST["REQUEST_ID"];
	$user_id =  $_REQUEST["USER_ID"];
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] = $MSG["BLOOD_REQUEST_UPDATE_SUCCESS"];

	}
	else
	{
		$json["STATUS"] = "ERROR";
		$json["MESSAGE"] = $MSG["BLOOD_REQUEST_UPDATE_ERROR"]. $sql->error;
	}
    $sql->close();
    return json_encode($json);
    #function ends
}

function BLOOD_REQUEST_DELETE($conn, $MSG)
{
    
	$sql = $conn->prepare("UPDATE blood_request SET status = 2 WHERE request_id = ?");
	$sql->bind_param("i", $request_id);
	$request_id = $_REQUEST["REQUEST_ID"];
	
	if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$json["MESSAGE"] =  $MSG["BLOOD_REQUEST_DELETE_SUCCESS"];

	}
	else
	{
		$json["STATUS"] = "ERROR";
		$json["MESSAGE"] =  $MSG["BLOOD_REQUEST_DELETE_ERROR"]. $sql->error;;
	}
    $sql->close();
    return json_encode($json);
    #function ends
}

function CHANGE_PASSWORD($conn, $MSG)
{	
    $sql = $conn->prepare("SELECT email FROM user WHERE email = ?");
    $sql->bind_param("s", $email);
    $email = $_REQUEST["EMAIL"];
    if ($sql->execute())
	{
		$json["STATUS"] = "SUCCESS";
		$count =0;
		while ($sql->fetch())
		{
			$count++;
			$_data["EMAIL"] = $email;
			$json["DATA"][] = $_data;
			unset($_data);
        }
        if ($count == 0)
		{
			$json["STATUS"] = "NOTFOUND";
			$json["MESSAGE"] = $MSG["CHANGE_PASSWORD_NOTFOUND"];

		}
		else
		{
			$sql1 = $conn->prepare("UPDATE user SET password = SHA1(?) WHERE email = ?");
			$sql1->bind_param("ss", $password, $email);
			$password = $_REQUEST["PASSWORD"];
            if ($sql1->execute())
			{
				$json["STATUS"] = "SUCCESS";
				$json["MESSAGE"] =  $MSG["CHANGE_PASSWORD_SUCCESS"];
		    }
			else
			{
				$json["STATUS"] = "ERROR";
				$json["MESSAGE"] =  $MSG["CHANGE_PASSWORD_ERROR"].$sql1->error;
			}
		}
    }
	else
	{
		$json["STATUS"] = "ERROR";
		$json["MESSAGE"] =  "$MSGCHANGE_PASSWORD_ERROR".$sql->error;
	}
	$sql->close();
    return json_encode($json);
    #function ends
	
}


 
?>