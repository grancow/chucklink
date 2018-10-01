<?php
	session_start();
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
		//zczytaj dane z formularza
		$user_id = $_GET['id'];
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$role = $_POST['role'];
		$email = $_POST['email'];
		$state = $_POST['state'];
		$project_id = $_POST['project_id'];
		
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
				$updateDB = "UPDATE users SET name='" . $name ."', surname='" . $surname . "', role='" . $role . "', state='" . $state . "' WHERE email='".$email."'";
				if ($polaczenie->query($updateDB))
				{
						if ($polaczenie->query("INSERT INTO user_project VALUES (NULL, '$user_id', '$project_id')"))
						{
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
