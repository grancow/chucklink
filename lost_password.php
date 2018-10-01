<?php
require "dbconnect.php";

$key =  $_GET['key'];
$email = mysqli_real_escape_string(htmlspecialchars($_POST['email']));
$checkemail = mysqli_query('SELECT * FROM users WHERE email="'.$email.'"');
$checkkey = 'SELECT * FROM users WHERE activation_key="'.$key.'"';
$rekord = mysqli_fetch_assoc(mysql_query($checkkey));
$ck = mysqli_fetch_row(mysql_query($checkkey));
$useremail = $rekord['email'];
$checkemail = mysqli_fetch_row($checkemail);
$domena = $_SERVER['HTTP_HOST'];
$skrypt= $_SERVER['SCRIPT_NAME'];
$parametry = $_SERVER['QUERY_STRING'];
$address =  $domena . $skrypt. '?' . $parametry;
$headers = 'From: NADAWCA' . "\r\n" .
    'Reply-To: NADAWCA' . "\r\n" .i;
    //'X-Mailer: PHP/' . phpversion();
    
if(isset($key) and $key == 0 || isset($key) and !$ck[1]) {
echo '
Incorrect or used activation key.
';
exit;
}
if(isset($key) and is_numeric($key)) {
$new = rand(1000000000,5000000000);
$newhash = sha1($new);
$query = "UPDATE users SET password='".$newhash."' WHERE activation_key='".$key."';";
mysqli_query ($query);
$to      = ''.$useremail.'';
$subject = 'New password';
$message = 'Welcome , your current password: '.$new.'';
mail($to, $subject, $message, $headers);
$finish = "UPDATE users SET activation_key='0' WHERE email='".$email."';";
mysqli_query($finish);
echo 'New password has been sent to specified email.';
exit;
}
if(empty($email)) {
echo '<form method="post" action="#">
<table>
<tr><td>Your Email:</td><td><input type="text" name="email"/></td></tr>
</table>
<input type="submit" value="Send password"/>';
exit;
}
if(isset($email) and !filter_var("$email", FILTER_VALIDATE_EMAIL)) {
echo 'This email address is inncorect.';
exit;
}
if(isset($email) and !$checkemail[1]) {
echo '
The email does not exist in database..';
exit;
}
if(isset($email) and $checkemail[1]) {
$new = rand(1000000000,5000000000);
$query = "UPDATE user SET activation_key='".$new."' WHERE email='".$email."';";
mysqli_query ($query);
$to      = ''.$email.'';
$subject = 'New password';
$message = 'Hi , if you want to change the password on:  http://'.$address.'key='.$new.'';
mail($to, $subject, $message, $headers);
echo 'Information about the new password has been sent to your e-mail.';
}
?>
