<?php 
	ob_start();
	session_start();
	$id = $_GET['id'];
	ini_set("display_errors", 0);
	require_once 'dbconnect.php';
	$polaczenie = new mysqli($host, $user, $password, $database);
	$rezultat = $polaczenie->query("SELECT name, surname FROM users WHERE user_id='$id'");
	$row = mysqli_fetch_assoc($rezultat);
	$name = $row['name'];
	$surname = $row['surname'];
	
	mysqli_report(MYSQLI_REPORT_STRICT);
	
	try 
	{
		$polaczenie = new mysqli($host, $user, $password, $database);
		if ($polaczenie->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			if ($polaczenie->query("DELETE FROM users WHERE user_id='$id'"))
			{
				if ($polaczenie->query("DELETE FROM user_project WHERE user_id='$id'"))
				{
					$_SESSION['user_del']=true;
					$_SESSION['user_deleted']=$name.' '.$surname;
					header('Location: users.php');
				}
				else
				{
					throw new Exception($polaczenie->error);
				}
			}
			else
			{
				throw new Exception($polaczenie->error);
			}
		}
		$polaczenie->close();			
	}
	catch(Exception $e)
	{
		echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
		echo '<br />Informacja developerska: '.$e;
	}
?>