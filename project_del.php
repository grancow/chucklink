<?php 
	ob_start();
	session_start();
	$id = $_GET['id'];
	ini_set("display_errors", 0);
	require_once 'dbconnect.php';
	$polaczenie = new mysqli($host, $user, $password, $database);
	$rezultat = $polaczenie->query("SELECT * FROM project WHERE project_id='$id'");
	$row = mysqli_fetch_assoc($rezultat);
	$project_id = $row['project_id'];
	$project_name = $row['project_name'];
	
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
			if ($polaczenie->query("DELETE FROM project WHERE project_id='$id'"))
			{
				if ($polaczenie->query("DELETE FROM user_project WHERE project_id='$id'"))
				{
					if ($polaczenie->query("DELETE FROM link WHERE project_id='$id'"))
					{
						$_SESSION['project_del']=true;
						$_SESSION['project_deleted']=$project_name;
						header('Location: projects.php');
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