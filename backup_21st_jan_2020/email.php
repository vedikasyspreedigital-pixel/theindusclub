<?php 

$req1=file_get_contents('php://input');
 $req=json_decode($req1);

//if(isset($_POST['submit'])){
$to = 'contact@theindusclub.com';

//$to = 'ajay.tribhuwan@practicenext.com';

$from =  $req->email; // Sender's Email address
$first_name = $req->name;
$mail1 = $req->email;
$phone = $req->company;
$mess = $req->mobile;
$city = $req->city;
$age = $req->age;
$degination = $req->designation;
$subject = "THE INDUS CLUB | Website query";

$social = $req->social;

$message = " Name - ".$first_name."\n Company Name - ".$phone."\n Email Id- ".$mail1."\n Mobile - ".$mess."\n City - ".$city."\n age - ".$age."\n Designation- ".$degination."\n social - ".$social;

$headers = "From:" . $from. "\r\n"; 
//$headers .= 'Cc: ' . "\r\n";
$headers .= 'Bcc: siddhanth.nair@triature.co' . "\r\n";

$mail=  mail($to, $subject, $message, $headers);
if ($mail) { 
 echo "success";  
}
else { 
 echo "error";
}
//}
?>