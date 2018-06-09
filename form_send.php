<?php

require_once "pdo.php";

if(!isset($_POST['submit']))
{
	//This page should not be accessed directly. Need to submit the form.
	echo "error; you need to submit the form!";
}
$fname = $_POST['firstname'];
$lname = $_POST['lastname'];
$visitor_email = $_POST['emailaddress'];
$phone = $_POST['contact_no'];
$gender = $_POST['gender'];
$prof = $_POST['profession'];
$message = $_POST['msg'];

//Validate first
if(empty($fname)||empty($lname)||empty($visitor_email)) 
{
    echo "Name and email are mandatory!";
    exit;
}

if(IsInjected($visitor_email))
{
    echo "Bad email value!";
    exit;
}

$stmt = $pdo->prepare('INSERT INTO reg
			(fname, lname, email, phone, gender, profession, msg) VALUES ( :fn, :ln, :mail, :ph, :gen, :prof, :msg)');
		$stmt->execute(array(
			':fn' => $fname,
			':ln' => $lname,
			':mail' => $visitor_email,
			':ph' => $phone,
			':gen' => $gender,
			':prof' => $prof,
			':msg' => $message));

$email_from = 'sarojnayak@skaipal.in';//<== update the email address
$email_subject = "New Form submission";
$email_body = "You have received a new message regarding course registration from $fname $lname.\n".
	"E-mail id: $visitor_email \n".
	"Contact no.: $phone \n".
	"Gender: $gender \n".
	"Profession: $prof \n".
	"Here is the message:\n $message \n";
    
$to = "dddaisyd06@gmail.com";//<== update the email address
$headers = "From: $email_from \r\n";
$headers .= "Reply-To: $visitor_email \r\n";
//Send the email!
mail($to,$email_subject,$email_body,$headers);
//mail($to,$email_subject,$email_body,"From: no-reply@phphelp.com X-Mailer: My PHP Script");
//mail("dt.kanha@gmail.com",$email_subject,$email_body);
//done. redirect to thank-you page.


$email_from = $to;//<== update the email address
$email_sub = "Received your form!";
$email_body = "We have received your mail regarding ATH courses. We will enroll you in the program via Skaipal soon and you will receive a mail from ATH as soon as you are enrolled.  \n \n
Regards, \n
Skaipal Consulting Private Ltd. \n";

//$visitor_email="dddaisyd06@gmail.com";
$headers = "From: $email_from \r\n" ;
$headers.="Reply-To: $to \r\n" ;
mail($visitor_email,$email_sub,$email_body,$headers);
/*if(mail("dddaisyd06@gmail.com",$email_sub,$email_body))
{
echo("Successful");
}
else
echo("Nt sned");*/


//header('Location: thank-you.html');
header('Location: ath.html');

// Function to validate against any email injection attempts
function IsInjected($str)
{
  $injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
  $inject = join('|', $injections);
  $inject = "/$inject/i";
  if(preg_match($inject,$str))
    {
    return true;
  }
  else
    {
    return false;
  }
}
   
?> 
