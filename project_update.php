<?php
	session_start();
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
		// pobierz id projektu
		$project_id = $_GET['id'];
		//zczytaj dane z formularza
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$firm = $_POST['firm'];
		$street = $_POST['street'];
		$city = $_POST['city'];
		$nip = $_POST['nip'];
		
		require_once "dbconnect.php";
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
				//Hurra, wszystkie testy zaliczone, dodajemy gracza do bazy
				//echo 'można rejestrować<br />';
				$updateDB = "UPDATE project SET first_name='" . $first_name ."', last_name='" . $last_name . "', firm='" . $firm . "', street='" . $street . "', city='" . $city . "', nip='" . $nip . "' WHERE project_id='".$project_id."'";
				echo $updateDB.'<br />';
				if ($polaczenie->query($updateDB))
				{
					header('Location: projects.php');
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
		
ob_end_flush();?>