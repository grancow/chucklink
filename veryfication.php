<?
  ob_start();
  session_start();
  include_once 'function.php'; //dolaczanie pliku z klasa funkcje
  $user_id = $_GET['user_id'];
  $code = $_REQUEST['code'];
  $get=new funkcje();
  $connect=$get->connect_bd();
  //sprawdzamy czy zgadza sie activation_key do zmiany hasła
	$quest="select user_id from users where user_id='$user_id' and activation_key='$code'";
    $result1=$get->get_single_shot($quest);
    if (isset($result1['user_id']))
	{
		$blad=0; 
    }
	else
	{
		$blad=1;
		$_SESSION['pass_nch']=true;
		$_SESSION['pass_nchanged']="Wygląda że ktoś już skorzystał z tego linka";
		header('Location: index.php');
    } 
	if ($blad==0)
	{
   	   if (isset($_POST["code"])) 
	   { // jeżeli formularz został wysłany, to wykonuje się poniższy skrypt
       		//$login = htmlspecialchars(stripslashes(strip_tags(trim($_REQUEST["user"]))), ENT_QUOTES);
       		//$code = htmlspecialchars(stripslashes(strip_tags(trim($_POST["code"]))), ENT_QUOTES);
            // filtrowanie treści wprowadzonych przez użytkownika
            $haslo = $_POST["password"];
            $haslo2 = $_POST["password2"];
            // system sprawdza czy prawidło zostały wprowadzone dane
			$blad=0;
            if (strlen($haslo) < 6 or strlen($haslo) > 30 ) {
                $blad=1;
				$_SESSION['pass_def']=true;
				$_SESSION['pass_defect']="Proszę poprawnie wpisać hasło (od 6 znaków do 30 znaków)";
            }
            if ($haslo !== $haslo2) {
                $blad=1;
        		$_SESSION['pass_def']=true;
				$_SESSION['pass_defect']="Podane hasła nie są ze sobą zgodne";
            }  
            // jeżeli nie ma żadnego błedu, użytkownik zostaje zarejestronwany i wysłany do niego e-mail z linkiem aktywacyjnym
            if ($blad == 0) {
                $haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);  // zaszyfrowanie hasla
				
				$quest="update users set pass='$haslo_hash', activation_key='0'  where user_id='$user_id'";
     			$result=$connect->query($quest);
				//usuwamy kod aktywacyjny
				$quest2="update users set activation_key='0' where user_id='$user_id'";
				$result2=$get->get_single_shot($quest2);
				
                if ($result)
				{
					$_SESSION['pass_ch']=true;
					$_SESSION['pass_changed']="Hasło zostało zmienione";
					header('Location: index.php');
					$ok=1;
                }
                else "wystąpił nieznany błąd - zmiany hasła";
            }
            
        }?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Seosoft - Linkchecker</title>
	<link rel="stylesheet" href="style_l.css" type="text/css" />
</head>
<body>
	<div class="info">
	<?php 
		if ($_SESSION['pass_def']==true)
		{
			echo $_SESSION['pass_defect']; 
			unset($_SESSION['pass_def']);
			unset($_SESSION['pass_cdefect']);
		}
	?>
	</div>
	<div id="container">
		<p>Ustaw nowe hasło</p>
		<form method="post">
			<input type="hidden" name="code" value="<?php echo $_REQUEST['code'];?>" />
			<input type="password" name="password" placeholder="wpisz hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'" >
			<input type="password" name="password2" placeholder="powtórz hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'" >
			<input type="submit" value="Zapisz nowe hasło">
		</form>
	</div>
</body>
</html>	
<?php   }
	else
	{
		$_SESSION['pass_nch']=true;
		$_SESSION['pass_nchanged']="Wygląda że ktoś już skorzystał z tego linka";
		header('Location: index.php');
	}
   ob_end_flush();
   ?>