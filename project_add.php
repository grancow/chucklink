<?php 
	ob_start();
	include('header.php'); 
	if (isset($_POST['email']))
	{
		//Udana walidacja? Załóżmy, że tak!
		$wszystko_OK=true;
		//echo 'Wszedłem do walidacji formularza';	
		//czy podano nazwę projektu i stronę www?
		$project_name = $_POST['project_name'];
		//echo 'Odczytałem z formularza nazwę projektu '.$project_name;
		if(isset($project_name) && $project_name!="")
		{
			$wszystko_OK=true;
			//echo 'jest nazwa projektu';
		}
		else
		{
			$wszystko_OK=false;
			$_SESSION['e_project_name']="Podaj nazwę projektu";
		}
		$project_site = $_POST['project_site'];
		if(isset($project_site) && $project_site!="")
		{
			$wszystko_OK=true;
		}
		else
		{
			$wszystko_OK=false;
			$_SESSION['e_project_name']="Podaj nazwę projektu";
		}
		// Sprawdź poprawność adresu email
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$wszystko_OK=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		}
		//zaczytaj z formularza dane opcjonalne
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$firm = $_POST['firm'];
		$street = $_POST['street'];
		$city = $_POST['city'];
		$nip = $_POST['nip'];
		
		//zaczytaj dane z sesji na temat wprowadzającego
		$project_owner = $_SESSION['user_id'];
		$user_id = $_SESSION['user_id'];
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
				if ($wszystko_OK==true)
				{
					//Hurra, wszystkie testy zaliczone, dodajemy projekt do bazy
					//echo 'można rejestrować<br />';
					if ($polaczenie->query("INSERT INTO project VALUES (NULL, '$project_name', '$project_owner', '$project_site', '$email', '$first_name', '$last_name', '$firm', '$street', '$city', '$nip', now())"))
					{
						// tutaj trzeba dodać powiązania pracownika z projektem, odczytaj project_id dodanego projektu
						$email = $_SESSION['email'];
						$rezultat = $polaczenie->query("SELECT project_id FROM project WHERE project_name = '$project_name' AND owner = '$project_owner'");
						$wiersz = mysqli_fetch_array($rezultat);
						$project_id = $wiersz['project_id'];
						if ($polaczenie->query("INSERT INTO user_project VALUES (NULL, '$user_id', '$project_id')"))
						{
							$_SESSION['project_add']=true;
							$_SESSION['project_added']=$project_name;
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
				
				$polaczenie->close();
			}
			
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
		
	}
	
	
?>	
		<div class="content">
			<h2>Utwórz nowy projekt.</h2>
			<form method="post">
			<div class="left_box">
				<p><label>Nazwa projektu*:</label><input type="text" name="project_name" /><br /></p>
				<p><label>Strona wwww*:</label><input type="text" name="project_site" /><br /></p>
				<p><label>E-mail*:</label><input type="text" name="email" value="<?php echo $_SESSION['email']; ?>"/><br /></p>
				<p><label>Imię:</label><input type="text" name="first_name" /><br /></p>
				<p><label>Nazwisko:</label><input type="text" name="last_name" /><br /></p>
				<p><label>Nazwa firmy:</label><input type="text" name="firm" /><br /></p>
				<p><label>Ulica, nr domu:</label><input type="text" name="street" /><br /></p>
				<p><label>Kod, miasto:</label><input type="text" name="city" /><br /></p>
				<p><label>NIP:</label><input type="text" name="nip" /><br /></p>
				<p><label>Dane oznaczone * są wymagane</label><br /></p>
				<input type="submit" value="Dodaj projekt" />
			</div>
			</form>
			<div class="right_box">
			</div>
		</div>
		<div class="footer">Seosoft - Linkchecker &copy; 2018 </div>
		</div>
	</body>
</html>
<?php ob_end_flush();?>
