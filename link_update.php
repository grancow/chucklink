<?php
	session_start();
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
		// pobierz id linku do modyfikacji
		$id = $_GET['id'];
		//zczytaj dane z formularza
		$project_id = $_POST['project_id'];
		$link_site = $_POST['link_site'];
		//echo 'pobrałem strone na któej jest link - ' . $link_site .'<br />';
		$site = $_POST['site'];
		$anchor = $_POST['anchor'];
		$linktype1_id = $_POST['linktype1_id'];
		$linktype2_id = $_POST['linktype2_id'];
		$linktype3_id = $_POST['linktype3_id'];
		$link_date = $_POST['link_date'];
		$link_cost = $_POST['link_cost'];
		$link_expiration = $_POST['link_expiration'];
		$link_reminder = $_POST['link_reminder'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$info = $_POST['info'];
		$status = $_POST['status'];
		$aktualizacja = $_POST['aktualizacja'];
		
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
				$updateDB = "UPDATE link SET linktype1_id='" . $linktype1_id ."', linktype2_id='" . $linktype2_id ."', link_date='" . $link_date ."', link_cost='" . $link_cost ."', first_name='" . $first_name ."', last_name='" . $last_name . "', email='" . $email . "', phone='" . $phone . "', info='" . $info . "', link_expiration='".$link_expiration."', link_reminder='".$link_reminder."' WHERE id='".$id."'";
				//echo $updateDB.'<br />';
				$_SESSION['link_update']=true;
				if ($polaczenie->query($updateDB))
				{
						$location = "Location: links.php?id=".$project_id;
						header($location);
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