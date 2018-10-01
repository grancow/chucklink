<?php 
	ob_start();
	include('header.php'); 
	if (isset($_POST['email']))
	{
		//Udana walidacja? Załóżmy, że tak!
		$wszystko_OK=true;
	
		//zczytaj dane z formularza
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$role = $_POST['role'];
		$project_id = $_POST['project_id'];
		if ($role=='pracownik')
		{
			$priviliges = 0;
		}
		elseif ($role=='manager')
		{
			$priviliges = 1;
		}
		// Sprawdź poprawność adresu email
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$wszystko_OK=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		}
		
		//Sprawdź poprawność hasła
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
		
		if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków!";
		}
		
		if ($haslo1!=$haslo2)
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Podane hasła nie są identyczne!";
		}	

		$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
		
		//Zapamiętaj wprowadzone dane
		$_SESSION['fr_name'] = $name;
		$_SESSION['fr_surname'] = $surname;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_haslo1'] = $haslo1;
		$_SESSION['fr_haslo2'] = $haslo2;
		$_SESSION['fr_role'] = $role;
		
		//Kto zakłada konto
		$owner_id = $_SESSION['user_id'];
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
				//Czy email już istnieje?
				$rezultat = $polaczenie->query("SELECT user_id FROM users WHERE email='$email'");
				
				if (!$rezultat) throw new Exception($polaczenie->error);
				
				$ile_takich_maili = $rezultat->num_rows;
				if($ile_takich_maili>0)
				{
					$wszystko_OK=false;
					$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail!";
				}		

				if ($wszystko_OK==true)
				{
					//Hurra, wszystkie testy zaliczone, dodajemy gracza do bazy
					//echo 'można rejestrować<br />';
					if ($polaczenie->query("INSERT INTO users VALUES (NULL, '$email', '$email', '$name', '$surname', '$haslo_hash', '$role', 'aktywny', now(), '$owner_id', '0', $priviliges)"))
					{
						// tutaj trzeba dodać dodanie powiązania pracownika z projektem, odczytaj user_id dodanego użytkownika
						$rezultat = $polaczenie->query("SELECT user_id FROM users WHERE email='$email'");
						$wiersz = mysqli_fetch_array($rezultat);
						$user_id = $wiersz['user_id'];
						//echo 'ID nowego uzytkownika to '.$user_id.' a projekt do którego został przypisany to '.$project_id;
						//exit();
						if ($polaczenie->query("INSERT INTO user_project VALUES (NULL, '$user_id', '$project_id')"))
						{
						$_SESSION['user_add']=true;
						$_SESSION['user_added']=$name." ".$surname;
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
			
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
		
	}
	
	
?>

		<div class="content">
			<h2>Utwórz nowe konto użytkownika</h2>
			<form method="post">
			<div class="left_box">
			<p><label>Imię:</label><input type="text" value="<?php
			if (isset($_SESSION['fr_name']))
			{
				echo $_SESSION['fr_name'];
				unset($_SESSION['fr_name']);
			}
		?>" name="name" /><br />
		
		<?php
			if (isset($_SESSION['e_name']))
			{
				echo '<div class="error">'.$_SESSION['e_name'].'</div>';
				unset($_SESSION['e_name']);
			}
		?></p>

		   <p><label>Nazwisko:</label><input type="text" value="<?php
			if (isset($_SESSION['fr_surname']))
			{
				echo $_SESSION['fr_surname'];
				unset($_SESSION['fr_surname']);
			}
		?>" name="surname" /><br />
		
		<?php
			if (isset($_SESSION['e_surname']))
			{
				echo '<div class="error">'.$_SESSION['e_surname'].'</div>';
				unset($_SESSION['e_surname']);
			}
		?></p>

		<p><label>Login (E-mail):</label><input type="text" value="<?php
			if (isset($_SESSION['fr_email']))
			{
				echo $_SESSION['fr_email'];
				unset($_SESSION['fr_email']);
			}
		?>" name="email" /><br />
		
		<?php
			if (isset($_SESSION['e_email']))
			{
				echo '<div class="error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);
			}
		?></p>
		
		<p><label>Hasło:</label><input type="password"  value="<?php
			if (isset($_SESSION['fr_haslo1']))
			{
				echo $_SESSION['fr_haslo1'];
				unset($_SESSION['fr_haslo1']);
			}
		?>" name="haslo1" /><br />
		
		<?php
			if (isset($_SESSION['e_haslo']))
			{
				echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
				unset($_SESSION['e_haslo']);
			}
		?></p>
		
		<p><label>Powtórz hasło:</label><input type="password" value="<?php
			if (isset($_SESSION['fr_haslo2']))
			{
				echo $_SESSION['fr_haslo2'];
				unset($_SESSION['fr_haslo2']);
			}
		?>" name="haslo2" /><br /></p>
		<input type="submit" value="Dodaj użytkownika" />
			
			</div>
			<div class="right_box">
		
		<?php if ($_SESSION['priviliges'] == 2)
				{
				echo '<p><label>Rola:</label><select type="text" name="role">';
				echo '<option value="pracownik">pracownik</option>';
				echo '<option value="manager">manager</option>';
				echo '</select>';
				}
				elseif ($_SESSION['priviliges'] == 1)
				{
					$role='pracownik';
				}
				?><br /></p>
		<p><label>Dodaj pracownika do projektu:</label><select type="int" name="project_id">
										<OPTION value=''></OPTION>
										<?php require_once 'dbconnect.php';
											$polaczenie = new mysqli($host, $user, $password, $database);
											$selectDB = "SELECT a.project_id, a.project_name FROM project a, user_project up WHERE up.user_id='".$_SESSION['user_id']."' AND a.project_id = up.project_id";
											//echo 'zapytanie jest następujące' . $selectDB;
											$rezultat = $polaczenie->query($selectDB);
											$ile = mysqli_num_rows($rezultat);
											for ($i = 1; $i <= $ile; $i++) 
											{		
												$row = mysqli_fetch_assoc($rezultat);
												$project_id = $row['project_id'];
												$project_name = $row['project_name'];
												echo "<OPTION value='" . $project_id .  "'>". $project_name . "</OPTION>";
											}	
											$polaczenie->close(); ?>
									</select><br /></p>
				<br /><br />
			</div>
			<div style="clear:both;"></div>
	</form>
	</div>
		
		<div class="footer">Seosoft - Linkchecker &copy; 2018 </div>
	</div>
</div>
	</body>
</html>