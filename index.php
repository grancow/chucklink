<?php
	session_start();
	if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: projects.php');
		exit();
	}
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
	<div class="info">
	<?php 
		if ($_SESSION['pass_ch']==true)
		{
			echo $_SESSION['pass_changed']; 
			unset($_SESSION['pass_ch']);
			unset($_SESSION['pass_changed']);
		}
		elseif ($_SESSION['pass_nch']==true)
		{
			echo $_SESSION['pass_nchanged']; 
			unset($_SESSION['pass_nch']);
			unset($_SESSION['pass_nchanged']);
		}
		elseif ($_SESSION['pass_res']==true)
		{
			echo $_SESSION['pass_reset']; 
			unset($_SESSION['pass_res']);
			unset($_SESSION['pass_reset']);
		}
	?>
	</div>
	<div id="container">
		<form action="zaloguj.php" method="post">
			<input type="text" name="login" placeholder="email" onfocus="this.placeholder=''" onblur="this.placeholder='email'" >
			<input type="password" name="haslo" placeholder="hasło" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'" >			
			<input type="submit" value="Zaloguj się">
		</form>
		<p><a href="resetpass.php">Nie pamiętasz hasła</a></p>
	</div>
<?php
	if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
?>
</body>
</html>