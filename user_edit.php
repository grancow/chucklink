<?php 
	include('header.php');
	$id = $_GET['id'];
	ini_set("display_errors", 0);
	require_once 'dbconnect.php';
	$polaczenie = new mysqli($host, $user, $password, $database);
	$rezultat = $polaczenie->query("SELECT * FROM users WHERE user_id='$id'");
	$row = mysqli_fetch_assoc($rezultat);
	$id = $row['user_id'];
	$name = $row['name'];
	$surname = $row['surname'];
	$email = $row['email'];
	$role = $row['role'];
	$state = $row['state'];
	$added = $row['added'];
	$polaczenie->close();
?>

		<div class="content">
			<h2>Edytuj użytkownika</h2>
			<form method="post" action="<?php echo 'user_update.php?id='.$id; ?>">
			<div class="left_box">
				<p><label>Imię:</label><input type="text" value="<?php echo $name; ?>" name="name" /></p>
				<p><label>Nazwisko:</label><input type="text" value="<?php echo $surname; ?>" name="surname" /></p>
				<p><label>E-mail):</label><input type="text" value="<?php echo $email; ?>" name="email"/></p>
				<p><label>Rola:</label><select type="text" name="role">
					<?php if($role == "pracownik") 
							{
							echo "<option value='pracownik'>pracownik</option><br />";
							echo "<option value='manager'>manager</option><br />";
							} 
							else 
							{
							echo "<option value='manager'>manager</option><br />";
							echo "<option value='pracownik'>pracownik</option><br />";
							}	
					?>
				</select></p>
				<p><label>Status:</label><select type="text" name="state">
					<?php if($state == "aktywny") 
							{
							echo "<option value='aktywny'>aktywny</option><br />";
							echo "<option value='zawieszony'>zawieszony</option><br />";
							} 
							else 
							{
							echo "<option value='zawieszony'>zawieszony</option><br />";
							echo "<option value='aktywny'>aktywny</option><br />";
							}	
					?>
				</select></p>
				<?php if (($_SESSION['priviliges'] == 1) || ($_SESSION['priviliges'] == 2))
				{
				echo "<input type='submit' value='Aktualizuj dane'/>";
				}?>
			</div>
			<div class="right_box">
				<p>Bierze udział w: <?php require_once 'dbconnect.php';
							$polaczenie = new mysqli($host, $user, $password, $database);
							$selectDB = "SELECT u.project_name FROM project u, user_project up WHERE up.project_id = u.project_id AND up.user_id = '".$id."'";

							$rezultat = $polaczenie->query($selectDB);
							$ile = mysqli_num_rows($rezultat);
							for ($i = 1; $i <= $ile; $i++) 
								{	
									$row = mysqli_fetch_assoc($rezultat);
									$project_name = $row['project_name'];
									echo $project_name . " ";
								}	
							$polaczenie->close(); ?></p>
				<?php if (($_SESSION['priviliges'] == 1) || ($_SESSION['priviliges'] == 2))
				{
				echo "<p><label>Dodaj pracownika do projektu:</label><select type='int' name='project_id'>";
										echo "<option value=''></option>";
											require_once 'dbconnect.php';
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
												echo "<OPTION value='" . $project_id . "'>". $project_name . "</OPTION>";
											}	
											$polaczenie->close();
				echo "</select></p>";
				echo '<p><a class="button" href="#popup1">Usuń użytkownika</a></p>';
				echo '<div id="popup1" class="overlay">';
				echo '<div class="popup">';
				echo '<h2>Czy usunąć użytkownika?</h2>';
				echo "<a class='close_no' href='user_edit.php?id=".$id."'>Nie</a>";
				echo "<a class='close_yes' href='user_del.php?id=".$id."'>Tak</a>";
				echo '</div>';
				echo '</div>';
				}?>
		</div>
			<div style="clear:both;"></div>
	</form>
	</div>
		
		<div class="footer">Link Checker &copy; 2018. Wszelkie prawa zastrzeżone GDAQ.PL Multimedia Sławomir Gdak.</div>
	</div>
</div>
	</body>
</html>