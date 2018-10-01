<?php 
	include('header.php'); 
	$id = $_GET['id'];
	ini_set("display_errors", 0);
	require_once 'dbconnect.php';
	$polaczenie = new mysqli($host, $user, $password, $database);
	$rezultat = $polaczenie->query("SELECT * FROM project WHERE project_id='$id'");
	$row = mysqli_fetch_assoc($rezultat);
	$project_id = $row['project_id'];
	$project_name = $row['project_name'];
	$project_site = $row['site'];
	$project_owner = $row['owner'];
	$email = $row['email'];
	$first_name = $row['first_name'];
	$last_name = $row['last_name'];
	$firm = $row['firm'];
	$street = $row['street'];
	$city = $row['city'];
	$nip = $row['nip'];
	$added = $row['added'];
	$polaczenie->close();
?>
		<div class="content">
			<h2>Edycja projektu</h2>
			<form method="post" action="project_update.php?id=<?php echo $project_id; ?>">
			<div class="left_box">	
				<p><label>Nazwa projektu:</label><?php echo $project_name;?></p>
				<p><label>Strona wwww:</label><?php echo $project_site;?></p>
				<p><label>E-mail:</label><?php echo $email;?></p>
				<p><label>Imię:</label><input type="text" name="first_name" value="<?php echo $first_name;?>"  /></p>
				<p><label>Nazwisko:</label><input type="text" name="last_name" value="<?php echo $last_name;?>"  /></p>
				<p><label>Nazwa firmy:</label><input type="text" name="firm" value="<?php echo $firm;?>"  /></p>
				<p><label>Ulica, nr domu:</label><input type="text" name="street" value="<?php echo $street;?>"  /></p>
				<p><label>Kod, miasto:</label><input type="text" name="city" value="<?php echo $city;?>"  /></p>
				<p><label>NIP:</label><input type="text" name="nip" value="<?php echo $nip;?>"  /></p>
				<?php echo '<p><label>Projekt utworzono:</label>' . $added .'</p>'; ?>
				<p><label>Nad projektem pracują:</label><?php require_once 'dbconnect.php';
											$polaczenie = new mysqli($host, $user, $password, $database);
											$selectDB = "SELECT u.name, u.surname FROM users u, user_project up WHERE up.project_id = '".$project_id."' AND up.user_id = u.user_id";
											$rezultat = $polaczenie->query($selectDB);
											$ile = mysqli_num_rows($rezultat);
											for ($i = 1; $i <= $ile; $i++) 
											{		
												$row = mysqli_fetch_assoc($rezultat);
												$name = $row['name'];
												$surname = $row['surname'];
												echo $name .  " ". $surname . " ";
											}	
											$polaczenie->close(); ?></p>
				<?php if (($_SESSION['priviliges'] == 1) || ($_SESSION['priviliges'] == 2))
				{
				echo '<input type="submit" value="Aktualizuj" />';
				}?>
			</form><br />
			</div>
			<div class="right_box">	
				<form method="post" action="charts.php?id=<?php echo $project_id;?>">
					<p><input type="submit" value="Wykresy" name="submit"></p>
				</form>
				<form method="post" action="links.php?id=<?php echo $project_id;?>">
					<p><input type="submit" value="Wyświetl linki" name="submit"></p>
				</form>
				<?php if (($_SESSION['priviliges'] == 1) || ($_SESSION['priviliges'] == 2))
				{
				echo '<form method="post" action="user_to_project.php?id='.$project_id.'">';
				echo '<p><label>Dodaj pracownika do projektu:</label><select type="int" name="user_id">';
					require_once 'dbconnect.php';
					$polaczenie = new mysqli($host, $user, $password, $database);
					//wypisujemy tylko tych ktrzy jeszcze nie są dodani do projektu a są podwładnymi
					$selectDB = "SELECT user_id, name, surname FROM users WHERE owner = '". $project_owner . "'";
					//echo 'zapytanie jest następujące' . $selectDB;
					$rezultat = $polaczenie->query($selectDB);
					$ile = mysqli_num_rows($rezultat);
					for ($i = 1; $i <= $ile; $i++) 
					{		
						$row = mysqli_fetch_assoc($rezultat);
						$user_id = $row['user_id'];
						$name = $row['name'];
						$surname = $row['surname'];
						echo "<OPTION value='" . $user_id .  "'>". $name . " " . $surname . "</OPTION>";
					}	
					$polaczenie->close();
					echo '</select><br /></p>';
					echo '<input type="submit" value="Dodaj pracownika" name="submit">';
					echo '</form><br />';
				echo '<p><a class="button" href="#popup1">Usuń projekt</a></p>';
				echo '<div id="popup1" class="overlay">';
				echo '<div class="popup">';
				echo '<h2>Czy chcesz usunąć projekt?</h2>';
				echo '<a class="close_no" href="project_edit.php?id='.$project_id.'">Nie</a>';
				echo '<a class="close_yes" href="project_del.php?id='.$project_id.'">Tak</a>';
				echo '</div>';
				echo '</div>';
				}?>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div class="footer">Link Checker &copy; 2018. Wszelkie prawa zastrzeżone GDAQ.PL Multimedia Sławomir Gdak.</div>
	</div>
	</div>
	</body>
</html>
<?php ob_end_flush();?>