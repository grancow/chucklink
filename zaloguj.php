<?php

	session_start();
	
	if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
	{
		header('Location: index.php');
		exit();
	}

	require_once "dbconnect.php";

	$polaczenie = @new mysqli($host, $user, $password, $database);
	
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		
//		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
	
		if($rezultat = @$polaczenie->query(
		sprintf("SELECT * FROM users WHERE user='%s'",
		mysqli_real_escape_string($polaczenie,$login))))
		{
			$ilu_userow = $rezultat->num_rows;
			if($ilu_userow>0)
			{
				$wiersz = $rezultat->fetch_assoc();
			if (password_verify($haslo, $wiersz['pass']) && ($wiersz['state']=="aktywny"))
				{
				$_SESSION['zalogowany'] = true;
				$_SESSION['user_id'] = $wiersz['user_id'];
				$_SESSION['user'] = $wiersz['email'];
				$_SESSION['email'] = $wiersz['email'];
				$_SESSION['priviliges'] = $wiersz['privileges'];
				
				unset($_SESSION['blad']);
				$rezultat->free_result();
				header('Location: projects.php');
	
				} else 
				{
					$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
					header('Location: index.php');
				}
			} else 
			{
				$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
				header('Location: index.php');
			}
		}
		$polaczenie->close();
	}
	
?>