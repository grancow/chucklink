<?php

	session_start();
	
	if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: seolink.php');
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
	
	<div id="container">
		<form action="lost_password.php" method="post">
			<input type="text" name="email" placeholder="email" onfocus="this.placeholder=''" onblur="this.placeholder='email'" >
			<input type="submit" value="Przypomnij hasło">
		</form>
	</div>
<?php
	if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
?>

</body>
</html>