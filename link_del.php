<?php 
	ob_start();
	session_start();
	$id = $_GET['link_id'];
	$project_id = $_GET['id'];
	ini_set("display_errors", 0);
	require_once 'dbconnect.php';
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
			if ($polaczenie->query("DELETE FROM link WHERE id='$id'"))
			{
				$_SESSION['link_del']=true;
				header('Location: links.php?id='.$project_id.'');
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