<?php 
	ob_start();
	include('header.php'); 
	unset($_SESSION['blad']);
	//czy podano nazwę projektu i stronę www?
	$project_id  = $_POST['project_id'];
//	echo 'Zmienna sesyjna dotycząca błędu wygląda tak:' . $_SESSION['blad'];
//	$plik=$_FILES['file']['name'];
//	echo $plik;
//	if (pathinfo($plik, PATHINFO_EXTENSION)!= 'csv')$_SESSION['blad'] = true;
	if ($_POST['check'] == true)
	{
	if ($_FILES[csv][size] > 0) 
	{
		//czy są jakieś wiersze bez wymaganych danych, zakładamy że nie ma błędów
		$data_bug=false;
		//echo 'Wszedłem do walidacji formularza <br />';	

		//		echo 'Odczytałem z formularza id projektu '.$project_id. '<br />';
		//czy złapałem id użytkownika dokonującego importu?
		$user_id = $_SESSION['user_id'];
		//		echo 'Użytkownik, który dokonuje importu plików ma id '.$user_id. '<br />';
		//get the csv file
		$file = $_FILES[csv][tmp_name];
		$handle = fopen($file,"r");
		
		$tmp = array();
		$tmp_bug = array();
		$info_1 = 0;
		$info_2 = 0;
		$info_3 = 0;
		$info_4 = 0;		
		$k = 0;
		$b = 0;
		//loop through the csv file and insert into database
		while ($data = fgetcsv($handle,1000,';','"'))
		{
/*			echo 	"1 kolumna " . addslashes($data[0]).
					", 2 kolumna " . addslashes($data[1]).
					", 3 kolumna " . addslashes($data[2]).
					", 4 kolumna " . addslashes($data[3]).
					", 5 kolumna " . addslashes($data[4]).
					", 6 kolumna " . addslashes($data[5]).
					", 7 kolumna " . addslashes($data[6]).
					", 8 kolumna " . addslashes($data[7]).
					", 9 kolumna " . addslashes($data[8]).
					", 10 kolumna " . addslashes($data[9]).
					", 11 kolumna " . addslashes($data[10]) . "<br />";*/
			//weryfikuje czy nie brakuje którejś z wymaganych zmiennych 
			if (($data[0] == "")||($data[1] == "")||($data[2] == "")||($data[3] == ""))
			{
				
				$data_bug = true;
				//pakuje dany wiersz do tymczasowej tabeli
				$tmp_bug[$b][0]= $data[0];
				$tmp_bug[$b][1]= $data[1];
				$tmp_bug[$b][2]= $data[2];
				$tmp_bug[$b][3]= $data[3];
				$tmp_bug[$b][4]= $data[4];
				$tmp_bug[$b][5]= $data[5];
				$tmp_bug[$b][6]= $data[6];
				$tmp_bug[$b][7]= $data[7];
				$tmp_bug[$b][8]= $data[8];
				$tmp_bug[$b][9]= $data[9];
				$tmp_bug[$b][10]= $data[10];
				//przerywam pętle dla tej iteracji
				$b++;
				continue;
			}
			//sprawdzenie czy nie ma linku już w bazie
			//połączenie z baza danych w celu sprawdzenia istnienia linków
			require_once "dbconnect.php";
			$polaczenie = new mysqli($host, $user, $password, $database);
			$spr1 = "SELECT id FROM link WHERE link_site='".$data[0]."' AND site='".$data[1]."'";
			$rezultat = $polaczenie->query($spr1);
			$ile_takich_linkow = $rezultat->num_rows;
			if($ile_takich_linkow>0)
			{
				$data_bug = true;
				//pakuje dany wiersz do tymczasowej tabeli
				$tmp_bug[$b][0]= $data[0];
				$tmp_bug[$b][1]= $data[1];
				$tmp_bug[$b][2]= $data[2];
				$tmp_bug[$b][3]= $data[3];
				$tmp_bug[$b][4]= $data[4];
				$tmp_bug[$b][5]= $data[5];
				$tmp_bug[$b][6]= $data[6];
				$tmp_bug[$b][7]= $data[7];
				$tmp_bug[$b][8]= $data[8];
				$tmp_bug[$b][9]= $data[9];
				$tmp_bug[$b][10]= $data[10];
				//przerywam pętle dla tej iteracji
				$b++;
				continue;
			}
				//zamknięcie połączenia z bazą danych
	$polaczenie->close();
			//ładuje dane o projekcie i użytkowniku dla danego linku		
			$tmp[$k][0] = $project_id;
			$tmp[$k][1] = $user_id;
			//ładuje dane o link_site
			if ($data[0] != "")
			{
				$tmp[$k][2] = $data[0];
			}
			else
			{
				$info_1++;
				$data_bug = true;
				continue;
			}
			//ładuje dane o site
			if ($data[1] != "")
			{
				$tmp[$k][3] = $data[1];
			}
			else
			{
				$info_2++; 
			}			
			//ładuje dane o typie linka 1 np komentarz
			if($data[2] == "artykuł gościnny") { $tmp[$k][4] = 1;}
			elseif ($data[2] == "serwis ogłoszeniowy") { $tmp[$k][4] = 2;}		
			elseif ($data[2] == "marketing szeptany") { $tmp[$k][4] = 3;}
			elseif ($data[2] == "komentarz na blogu") { $tmp[$k][4] = 4;}
			elseif ($data[2] == "komentarz na forum") { $tmp[$k][4] = 5;}
			elseif ($data[2] == "profil") { $tmp[$k][4] = 6;}
			elseif ($data[2] == "sidebar") { $tmp[$k][4] = 7;}
			elseif ($data[2] == "inny") { $tmp[$k][4] = 8;}
			elseif ($data[2] == "zaplecze seo") { $tmp[$k][4] = 9;}
			elseif ($data[2] == "artykuł ekspercki") { $tmp[$k][4] = 10;}
			elseif ($data[2] == "katalog stron") { $tmp[$k][4] = 11;}
			elseif ($data[2] == "")
			{
				$tmp[$k][4] = NULL; 
				$info_3++;
			}
			//ładuje dane o typie linka 2 np tekstowy
			if($data[3] == "tekstowy") { $tmp[$k][5] = 1;}
			elseif ($data[3] == "graficzny") { $tmp[$k][5] = 2;}		
			elseif ($data[3] == "przekierowanie") { $tmp[$k][5] = 3;}
			elseif ($data[3] == "")
			{
				$tmp[$k][5] = NULL; 
				$info_4++;
			}			
			//ładuje dane o dacie dodania linka			
			if($data[4] != "")
			{
				$tmp[$k][6] = $data[4];
			}
			else
			{
				$tmp[$k][6] = date("Y-m-d");			
			}			
			//ładuje dane o koszcie linka
			if($data[5] != "")
			{
				$tmp[$k][7] = $data[5];
			}
			else
			{
				$tmp[$k][7] = NULL;			
			}			
			//ładuje dane dodatkowe - imię
			if($data[6] != "")
			{
				$tmp[$k][8] = $data[6];
			}
			else
			{
				$tmp[$k][8] = NULL;
			}
			//ładuje dane dodatkowe - nazwisko
			if($data[7] != "")
			{
				$tmp[$k][9] = $data[7];
			}
			else
			{
				$tmp[$k][9] = NULL;
			}
			//ładuje dane dodatkowe - email
			if($data[8] != "")
			{
				$tmp[$k][10] = $data[8];
			}
			else
			{
				$tmp[$k][10] = NULL;
			}
			//ładuje dane dodatkowe - telefon
			if($data[9] != "")
			{
				$tmp[$k][11] = $data[9];
			}
			else
			{
				$tmp[$k][11] = NULL;
			}			
			//ładuje dane dodatkowe - info
			if($data[10] != "")
			{
				$tmp[$k][12] = $data[10];
			}
			else
			{
				$tmp[$k][12] = NULL;
			}
			$k++;
		}
	}
	else
	{
		$_SESSION['blad'] = true;
	}
	/*	wypiszmy co mamy w tabeli po wczytaniu danych z pliku csv
	for ($i = 1; $i < $k; $i++) {
           echo "<br />Dane z lini nr " . $i  . ":<br />" .
				$tmp[$i][0] . ", " . $tmp[$i][1] . ", " . $tmp[$i][2] . ", " . $tmp[$i][3] . ", " . $tmp[$i][4] . ", " . $tmp[$i][5] . ", " . $tmp[$i][6] . ", " . $tmp[$i][7] . ", " . $tmp[$i][8] . ", " . $tmp[$i][9] . ", " . $tmp[$i][10] . ", " . $tmp[$i][11] . ", " . $tmp[$i][12];
        } */
	//zapakujmy dane do importu do tymczasowego pliku csv rozdzielanego średnikami
	$fp = fopen('tmp_'.$project_id.'.csv', 'w');
	for ($i = 1; $i < $k; $i++) 
	{
	$wsad = $tmp[$i][0].";".$tmp[$i][1].";".$tmp[$i][2].";".$tmp[$i][3].";".$tmp[$i][4].";".$tmp[$i][5].";".$tmp[$i][6].";".$tmp[$i][7].";".$tmp[$i][8].";".$tmp[$i][9].";".$tmp[$i][10].";".$tmp[$i][11].";".$tmp[$i][12]."\n";		
	fwrite($fp, $wsad);	
	}	
    fclose($fp);
	
	//zapakujemy wiersze z błędnymi danymi do pliku csv
	if ($data_bug == true)
	{	
	$fp = fopen('tmp_bug_'.$project_id.'.csv', 'w');
	//wstawiamy wiersz z nazwami kolumn
	$title = "Adres linka;Adres linkowanej strony;Źródło linka;Typ linka;Data pozyskania linka;Koszt pozyskania linka;imię;nazwisko;email;telefon;Dodatkowe informacje\n";
	fwrite($fp, $title);
	for ($i = 0; $i < $b; $i++) 
	{
	$wsad = $tmp_bug[$i][0].";".$tmp_bug[$i][1].";".$tmp_bug[$i][2].";".$tmp_bug[$i][3].";".$tmp_bug[$i][4].";".$tmp_bug[$i][5].";".$tmp_bug[$i][6].";".$tmp_bug[$i][7].";".$tmp_bug[$i][8].";".$tmp_bug[$i][9].";".$tmp_bug[$i][10]."\n";	
	fwrite($fp, $wsad);	
	}	
    fclose($fp);
	}
	}
	//po potwierdzeniu uploadu konieczne są dwa kroki

	// 2. sprawdzenie czy link jest aktywny
	
	
	
	//upload danych do tabeli link po potwierdzeniu importu
	if (isset($_POST['imp']))
	{
		//echo "zmienna imp wynosi - " . $_POST['imp'] . "/n";
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
				$file = 'tmp_'.$project_id.'.csv';		
				//echo "wszedłem do pętli z wgraniem danych, nazwa pliku tymczasowego to - " . $file;				
				$handle = fopen($file,"r");
				//loop through the csv file and insert into database
				while ($data = fgetcsv($handle,1000,';','"'))
				{
				$update = "INSERT INTO link VALUES
					(
                    NULL,
					'".addslashes($data[0])."',
                    '".addslashes($data[1])."',
                    '".addslashes($data[2])."',
                    '".addslashes($data[3])."',
					NULL,
                    '".addslashes($data[4])."',
                    '".addslashes($data[5])."',
					'3',
                    '".addslashes($data[6])."',
					NULL,
                    '".addslashes($data[7])."',
                    '".addslashes($data[8])."',
                    '".addslashes($data[9])."',
                    '".addslashes($data[10])."',
                    '".addslashes($data[11])."',
                    '".addslashes($data[12])."',
					NULL,
					NULL,
					now(),
					NULL,
					NULL
					)
					";
				//echo $update . "<br />";	
				$polaczenie->query($update);
				} 
				unlink($file);
				$_SESSION['links_imported']=true;
				$location = "Location: projects.php";
				header($location);

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
			<div class="info"></div>
			<h2>Import linków</h2>
			<div class="center_box">
			<?php
			 // odejmuje od ilości wierszy w pliku, wiersz z nagłówkami
			 $k = $k - 1;
			 echo "<p>We wprowadzonym pliku do importu, jest " . $k . " pozycji do zaimportowania</p>";
			 if ($data_bug == true)
			 {
				echo "<p>Wśród linków do importu są takie, które nie mają wymaganych informacji,</p>";
				echo "<p>lub te dane są błędne, lub taki link już istnieje w bazie.</p>";
				echo "Obok jest plik w którym są pozycje do poprawy. <a href=\"tmp_bug_".$project_id.".csv\" target=\"blank\">plik do poprawy</a>";
			 }
			?> 
			<p>Czy potwierdzasz wykonanie importu?</p>
			<div class="right_box">	
				<form method="post" action="" style="text-align: right; float: left; margin: 10px;">
					<input type="hidden" name="project_id" value="<?php echo $project_id; ?>"/>
					<input type="submit" name="imp" value="Tak"/>
				</form> 
				
				<form method="post" action="links_add.php" style="text-align: left; float: left; margin: 10px;">
					<input type="submit" value="Nie" name="submit">
				</form>
			</div>
	<!--		<div class="left_box">
				<form method="post" action="links_add.php" style="text-align: left;">
					<input type="submit" value="Nie" name="submit">
				</form>
			</div> -->
			</div>
			<div style="clear:both;"></div>
	</div>
		
		<div class="footer">Link Checker &copy; 2018. Wszelkie prawa zastrzeżone GDAQ.PL Multimedia Sławomir Gdak.</div>
	</div>
</div>
	</body>
</html>
<?php ob_end_flush();?>