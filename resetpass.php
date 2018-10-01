<?php 
ob_start();
session_start();
include_once 'function.php';
if (isset($_POST["wyslane"])) { // jeżeli formularz został wysłany, to wykonuje się poniższy skrypt
     
      // filtrowanie treści wprowadzonych przez użytkownika
     //$email = htmlspecialchars(stripslashes(strip_tags(trim($_POST["email"]))), ENT_QUOTES);
     //korzystamy z naszej klasy   
     $get=new funkcje();   
     // system sprawdza czy prawidło zostały wprowadzone dane

		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
                $blad=1;
                echo '<p class="blad"> Proszę wprowadzić poprawnie adres email.</p>';
		} else {
        	//sprawdzanie czy istnieje w bazie podany email
            	$sql1="SELECT user FROM users WHERE email='$email'";
            	$result1=$get->get_single_shot($sql1);
               
                if (isset($result1['user'])) {
                    $blad=0; //jeli istnieje login blad=0
                   
                }else {
                	//jesli nie istnieje konto blad=1
                	echo '<p class="blad">Konto o podanym adresie e-mail nie istnieje!</p>';
                	$blad=1; 
                }
            }
	// jesli email istnieje i nie ma żadnego błedu, wysylany zostaje email z powiadomieniem o zmianie hasla
            if ($blad == 0) {
                $sql2="select user, user_id from users where email='$email'";
				$result=$get->get_single_shot($sql2);
                //genaruje activation_key
				$code = uniqid();
                if ($result) {
                	// zapisywanie w zmiennej $list zawartosci tresci email
                    $list = "Witaj! <br>
					Ktoś poprosił o wygenerowanie nowego hasła dla konta: ".$result['user']." <br>
 
					Jeśli jest to błąd, po prostu zignoruj tego e-maila, a nic się nie stanie. <br><br>
 
					Aby ustawić nowe hasło, kliknij <a href='http://www.chuck-link.pl/veryfication.php?resetpaswd=yes&amp;user_id=".$result['user_id']."&amp;code=".$code."'  target='_blank'>tutaj</a>";
                   $headers="From: <admin@chuck-link.pl>".PHP_EOL;
                   $headers.= 'MIME-Version: 1.0' .PHP_EOL;
                   $headers.="Content-type: text/html; charset=utf-8".PHP_EOL;
                   $headers.="X-Mailer: PHP/". phpversion();
                   
                   if(mail($email, "Ustawianie nowego hasła", $list,$headers))
				   {
						$sql3="update users set activation_key='$code' where email='$email'";
						$result=$get->get_single_shot($sql3);
                   		$_SESSION['pass_res']=true;
						$_SESSION['pass_reset']="Odnośnik potwierdzający został wysłany e-mailem";
						header('Location: index.php');
						$ok=1;
					}
                   else 
				   {
                   	echo"Błąd!! - skontaktuj się z administratorami serwisu.";
                   }}}}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Seosoft - Linkchecker</title>
	<link rel="stylesheet" href="style_l.css" type="text/css" />
</head>

<body>
	<div id="container">
		<p>Proszę wprowadzić swój adres email.</p>
		<form action="resetpass.php" method="post">
			<input type="hidden" name="wyslane" value="TRUE" />
			<input type="text" name="email" placeholder="email" onfocus="this.placeholder=''" onblur="this.placeholder='email'" >
			<input type="submit" value="Przypomnij hasło">
		</form>
	</div>
<?php
	if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
?>
</body>
</html>
<?php ob_end_flush();?>