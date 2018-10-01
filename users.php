<?php include('header.php');?>
		
		<div class="content">
			<div class="info">
				<?php 
					if ($_SESSION['user_add']==true)
					{
						echo "<div class=\"succes\">Konto dla użytkownika ".$_SESSION['user_added']." zostało założone</div>"; 
						unset($_SESSION['user_add']);
						unset($_SESSION['user_added']);
					}elseif ($_SESSION['user_del']==true)
					{
						echo "<div class=\"succes\">Konto użytkownika ".$_SESSION['user_deleted']." zostało usunięte</div>";
						unset($_SESSION['user_del']);
						unset($_SESSION['user_deleted']);
					}
				?>
				</div>
			<p><form method="post" action="user_add.php">
				<input type="submit" value="Dodaj użytkownika" name="submit">
			</form><br /><br /></p>
			<div class="tab">
			<table width="1100" align="center" bordercolor="#d5d5d5" cellpadding="0" cellspacing="0">     
				<tr>
				<?php 
					ini_set("display_errors", 0);
					require_once 'dbconnect.php';
					$polaczenie = new mysqli($host, $user, $password, $database);
					if ($_SESSION['priviliges'] == 1)
					{
						$selectDB = "SELECT * FROM users WHERE owner='".$_SESSION['user_id']."'";
					}
					elseif ($_SESSION['priviliges'] == 2)
					{
						$selectDB = "SELECT * FROM users";
					}
					$rezultat = $polaczenie->query($selectDB);
					$ile = $rezultat->num_rows;
					if ($ile>=1) 
					{
echo<<<END
<td width="150" align="left" bgcolor="e5e5e5">Imię</td>
<td width="150" align="left" bgcolor="e5e5e5">Nazwisko</td>
<td width="300" align="left" bgcolor="e5e5e5">E-mail</td>
<td width="100" align="center" bgcolor="e5e5e5">Rola</td>
<td width="100" align="center" bgcolor="e5e5e5">Status</td>
<td width="150" align="center" bgcolor="e5e5e5">Data utworzenia</td>
<td width="250" align="center" bgcolor="e5e5e5">Edycja</td>
</tr><tr>
END;
					}
					
					for ($i = 1; $i <= $ile; $i++) 
					{
						$row = mysqli_fetch_assoc($rezultat);
						$id = $row['user_id'];
						$name = $row['name'];
						$surname = $row['surname'];
						$email = $row['email'];
						$role = $row['role'];
						$status = $row['state'];
						$added = $row['added'];
echo '<td width="150" align="left">'.$name.'</td>';
echo '<td width="150" align="left">'.$surname.'</td>';
echo '<td width="300" align="left">'.$email.'</td>';
echo '<td width="100" align="center">'.$role.'</td>';
//echo '<td width="100" align="center">'.$state.'</td>';
if ($status == "aktywny") {echo '<td width="100" align="center"><i class="material-icons" title="'.$status.'" style="color: green;">sentiment_very_satisfied</i></td>';}
elseif ($status == "zawieszony") {echo '<td width="100" align="center"><i class="material-icons" title="'.$status.'" style="color: red;">sentiment_very_dissatisfied</i></td>';}

echo '<td width="150" align="center">'.$added.'</td>';
echo '<td width="250" align="center"><a class="edit" href="user_edit.php?id='.$id.'"><i class="material-icons">mode_edit</i>Edytuj</a></td>';
echo '</tr>';
					}
				?>
</table>
</div>
		</div>
		
		<div class="footer">Seosoft - Linkchecker &copy; 2018 </div>
	</div>
</div>
	</body>
</html>

