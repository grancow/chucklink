<?php include('header.php');
	unset($_SESSION['project_name']);
?>
	<div class="content">
		<div class="info">
			<?php 
				if ($_SESSION['project_add']==true)
				{
					echo "<div class=\"succes\">Projekt ".$_SESSION['project_added']." został utworzony</div>"; 
					echo "<div class=\"succes\">Teraz czas dodać do niego pierwsze linki tutaj >> <a href=\"link_add.php\">Dodaj link</a></div>";
					unset($_SESSION['project_add']);
					unset($_SESSION['project_added']);
				}
				elseif ($_SESSION['project_del']==true)
				{
					echo "<div class=\"succes\">Projekt ".$_SESSION['project_deleted']." został usunięty</div>"; 
					unset($_SESSION['project_del']);
					unset($_SESSION['project_deleted']);
				}
				elseif ($_SESSION['links_imported']==true)
				{
					echo "<div class=\"succes\">Linki zostały zaimportowane w projekcie. W ciągu 24 godzin system zweryfikuje te linki.</div>"; 
					unset($_SESSION['links_imported']);
				}
			?>
		</div>
		<h2>Projekty</h2>
			<?php 
				if (($_SESSION['priviliges'] == 1) || ($_SESSION['priviliges'] == 2))
				{
					echo '<p><form method="post" action="project_add.php">';
					echo '<input type="submit" value="Dodaj projekt" name="submit">';
					echo '</form></p>';
				}
				ini_set("display_errors", 0);
				require_once 'dbconnect.php';
				$polaczenie = new mysqli($host, $user, $password, $database);
				
				if (($_SESSION['priviliges'] == 1) || ($_SESSION['priviliges'] == 0))
					{
						$selectDB = "SELECT a.project_id, a.project_name FROM project a, user_project up WHERE up.user_id='".$_SESSION['user_id']."' AND a.project_id = up.project_id";
					}
					elseif ($_SESSION['priviliges'] == 2)
					{
						$selectDB = "SELECT project_id, project_name FROM project";
					}
				$rezultat = $polaczenie->query($selectDB);
				$ile = $rezultat->num_rows;
				if ($ile >= 1)
				{
					echo '<div class="tab">';
					echo '<table width="1100" align="center" bordercolor="#d5d5d5" cellpadding="0" cellspacing="0">';
					echo '<tr><td width="350" align="center" bgcolor="e5e5e5">Projekt</td>';
					echo '<td width="150" align="center" bgcolor="e5e5e5">Liczba linków w projekcie</td>';
					echo '<td width="150" align="center" bgcolor="e5e5e5">Liczba aktywnych linków w projekcie</td>';
					echo '<td width="150" align="center" bgcolor="e5e5e5">Liczba nie aktywnych linków w projekcie</td>';
					echo '<td width="150" align="center" bgcolor="e5e5e5">Liczba sprawdzonych linków w projekcie</td>';
					echo '<td width="100" align="center" bgcolor="e5e5e5">Akcja</td></tr>';
				}
				for ($i = 1; $i <= $ile; $i++) 
					{
						$row = mysqli_fetch_assoc($rezultat);
						$project_id = $row['project_id'];
						$project_name = $row['project_name'];
						//wypisz projekt
						echo '<tr><td width="350" align="center"><a href="project_edit.php?id='.$project_id.'">'.$project_name.'</a></td>';
						//robimy kolejne zapytanie do BD o aktualne szczegoły danego projektu
						$zapytanie = "SELECT (SELECT COUNT(*) FROM link WHERE project_id=".$project_id.") as linki, (SELECT COUNT(*) FROM link WHERE project_id=".$project_id." AND status=\"aktywny\") as linki_aktywne, (SELECT COUNT(*) FROM link WHERE project_id=".$project_id." AND status=\"nie aktywny\") as linki_nieaktywne, (SELECT COUNT(*) FROM link WHERE project_id=".$project_id." AND status IS NULL) as linki_bezstatusu";
						$rezultat1 = $polaczenie->query($zapytanie);
						$wynik = mysqli_fetch_assoc($rezultat1);
						$linki = $wynik['linki'];
						$linki_aktywne = $wynik['linki_aktywne'];
						$linki_nieaktywne = $wynik['linki_nieaktywne'];
						$linki_bezstatusu = $wynik['linki_bezstatusu'];
						$linki_sprawdzone = round(((($linki_aktywne + $linki_nieaktywne)/$linki)*100), 2);
						echo '<td width="150" align="center">'.$linki.'</td>';
						echo '<td width="150" align="center">'.$linki_aktywne.'</td>';
						echo '<td width="150" align="center">'.$linki_nieaktywne.'</td>';
						//echo '<td width="150" align="center">'.$linki_sprawdzone.'</td>';
						
						echo '<td width="150" align="center"><div style="border:1px solid #ccc!important;"><div style="width:'.$linki_sprawdzone.'%;background-color:grey;color:black;">'.$linki_sprawdzone.'%</div></div></td>';
						echo '<td width="100" align="center"><a href="project_edit.php?id='.$project_id.'"><i class="material-icons" title="Edytuj projekt" style="color: green;">edit</i></a><a href="charts.php?id='.$project_id.'"><i class="material-icons" title="Wyświetl wykresy" style="color: blue;">show_chart</i></a><a href="links.php?id='.$project_id.'"><i class="material-icons"  title="Wyświetl linki" style="color: blue;">format_list_numbered</i></a><a href="project_del.php?id='.$project_id.'" onclick="return confirm(\'Czy na pewno usunąć projekt?\');"><i class="material-icons" title="Usuń projekt" style="color: red;">cancel</i></a></td></tr>';
					}	
				echo '</table>';
				echo '</div>';
				$polaczenie->close();
			?>	
			<fieldset class="legend">
				<legend>Legenda</legend>
				<label><i class="material-icons" title="Edytuj projekt" style="color: green;">edit</i>- edytuj</label>
				<label><i class="material-icons" title="Wyświetl wykresy" style="color: blue;">show_chart</i>- wykresy</label>
				<label><i class="material-icons" title="Wyświetl linki" style="color: blue;">format_list_numbered</i>- lista linków</label>
				<label><i class="material-icons" title="Usuń projekt" style="color: red;">cancel</i>- usuń</label>
			</fieldset>
		<!--<div style="clear:both;"></div>-->
	</div>
		<div class="footer">Link Checker &copy; 2018. Wszelkie prawa zastrzeżone GDAQ.PL Multimedia Sławomir Gdak.</div>
	</div>
	</div>
	</body>
</html>
