<?php 
	ob_start();
	include('header.php'); 
function getLink($url) {
	
	/*$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, "http://localhost");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$soruce = curl_exec($ch);
    curl_close($ch);*/

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	//curl_setopt($ch, CURLOPT_PROXY, $proxy);
	//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$soruce = curl_exec($ch);
	curl_close($ch);
	
	
	$regEx = "<a\s[^>]*href=(\"|\'??)([^\"|\' >]*?)\\1[^>]*>(.*)<\/a>";
   if (preg_match_all("/$regEx/siU", $soruce, $foundLinks, PREG_SET_ORDER)) {
        $linki = array();
        $tmp = array();
        $sizeArray = sizeof($foundLinks);
		echo "<br />ile zaczytałem wierszy ". $sizeArray;
        for ($i = 0; $i < $sizeArray; $i++) {
            $links[$i][0] = $foundLinks[$i][0];
			$links[$i][1] = $foundLinks[$i][2];
			$links[$i][2] = $foundLinks[$i][3];
        }
        
        return $links;
    }
}
	
	if (isset($_POST['project_id']))
	{
	unset($_SESSION['blad']);
//	echo 'Zmienna sesyjna dotycząca błędu wygląda tak:' . $_SESSION['blad'];
//	$plik=$_FILES['file']['name'];
//	echo $plik;
//	if (pathinfo($plik, PATHINFO_EXTENSION)!= 'csv')$_SESSION['blad'] = true;

	if ($_FILES[csv][size] > 0) 
	{
		//Udana walidacja? Załóżmy, że tak!
		$wszystko_OK=true;
		echo 'Wszedłem do walidacji formularza <br />';	
		//czy podano nazwę projektu i stronę www?
		$project_id  = $_POST['project_id'];
				echo 'Odczytałem z formularza id projektu '.$project_id. '<br />';
		//czy złapałem id użytkownika dokonującego importu?
		$user_id = $_SESSION['user_id'];
				echo 'Użytkownik, który dokonuje importu plików ma id '.$user_id. '<br />';
		//get the csv file
		$file = $_FILES[csv][tmp_name];
		$handle = fopen($file,"r");
		
		$tmp = array();
		$info_1 = 0;
		$info_2 = 0;
		$info_3 = 0;
		$info_4 = 0;		
		$k = 0;
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
			//ładuje dane o projekcie i użytkowniku dla danego linku		
			$tmp[$k][0] = $project_id;
			$tmp[$k][1] = $user_id;
			//ładuje dane o link_site
			if (addslashes($data[0]) != "")
			{
				$tmp[$k][2] = addslashes($data[0]);
			}
			else
			{
				$info_1++; 
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
	//wypiszmy co mamy w tabeli po wczytaniu danych z pliku csv
	for ($i = 0; $i < $k; $i++) {
           echo "<br />Dane z lini nr " . $i  . ":<br />" .
				$tmp[$i][0] . ", " . $tmp[$i][1] . ", " . $tmp[$i][2] . ", " . $tmp[$i][3] . ", " . $tmp[$i][4] . ", " . $tmp[$i][5] . ", " . $tmp[$i][6] . ", " . $tmp[$i][7] . ", " . $tmp[$i][8] . ", " . $tmp[$i][9] . ", " . $tmp[$i][10] . ", " . $tmp[$i][11] . ", " . $tmp[$i][12];
        }
	}
		
	if (isset($_POST['project_id_numer']))
	{
		//Udana walidacja? Załóżmy, że tak!
		$wszystko_OK=true;
		//echo 'Wszedłem do walidacji formularza <br />';	
		//czy podano nazwę projektu i stronę www?
		$project_id  = $_POST['project_id'];
		//		echo 'Odczytałem z formularza nazwę projektu '.$project_id. '<br />';
		if(isset($project_id) && $project_id!="")
		{
			$wszystko_OK=true;
			//echo 'jest nazwa projektu';
		}
		else
		{
			$wszystko_OK=false;
			$_SESSION['e_project_name']="Podaj nazwę projektu";
		}
		$link_site = $_POST['link_site'];
//		echo 'Odczytałem z formularza adres linka '.$link_site. '<br />';
		if(isset($link_site) && $link_site!="")
		{
			$wszystko_OK=true;
		}
		else
		{
			$wszystko_OK=false;
			$_SESSION['e_link_site']="Podaj adres linka";
		}
		$site = $_POST['site'];
//		echo 'Odczytałem z formularza adres linkowanej strony '.$site. '<br />';
		if(isset($site) && $site!="")
		{
			$wszystko_OK=true;
		}
		else
		{
			$wszystko_OK=false;
			$_SESSION['e_site']="Podaj adres linkowanej strony";
		}
		/* przycinamy adres linkwanej strony 
		$regEx = "<a\s[^>]*href=(\"|\'??)([^\"|\' >]*?)\\1[^>]*>(.*)<\/a>";
		preg_match("/$regEx/siU",$site, $matches);
		$site = $matches[2];*/
		
		$linktype1_id = $_POST['linktype1_id'];
//		echo 'Odczytałem z formularza typ linka 1: '.$linktype1_id. '<br />';
		$linktype2_id = $_POST['linktype2_id'];
//		echo 'Odczytałem z formularza typ linka 2: '.$linktype2_id. '<br />';
		$link_date = $_POST['link_date'];
//		echo 'Odczytałem z formularza datę wstawienia linka: '.$link_date. '<br />';		
//		echo 'Odczytałem z formularza nazwę projektu '.$project_id. '<br />';
		if(isset($link_date) && $link_date!="")
		{
			$wszystko_OK=true;
		}
		else
		{
			$wszystko_OK=true;
			$link_date = date("Y-m-d");			
		}
		$link_cost = $_POST['link_cost'];
//		echo 'Odczytałem z formularza koszt wstawienia linka: '.$link_cost. '<br />';
		if(isset($link_cost))
		{
			$wszystko_OK=true;
		}
		else
		{
			$wszystko_OK=true;
			$link_cost = NULL;
		}
		
		// Sprawdź poprawność adresu email
		$email = $_POST['email'];
		if(isset($email))
		{
		$wszystko_OK=true;
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
			if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
			{
			$wszystko_OK=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
			}
		}
		else
		{
			$wszystko_OK=true;
			$email = NULL;
		}
		//zaczytaj z formularza dane opcjonalne
		$first_name = $_POST['first_name'];
		if(isset($first_name))
		{
			$wszystko_OK=true;
		}
		else
		{
			$wszystko_OK=true;
			$first_name = NULL;
		}
		$last_name = $_POST['last_name'];
		if(isset($last_name))
		{
			$wszystko_OK=true;
		}
		else
		{
			$wszystko_OK=true;
			$last_name = NULL;
		}
		$phone = $_POST['phone'];
		if(isset($phone))
		{
			$wszystko_OK=true;
		}
		else
		{
			$wszystko_OK=true;
			$phone = NULL;
		}
		$info = $_POST['info'];
		if(isset($info))
		{
			$wszystko_OK=true;
		}
		else
		{
			$wszystko_OK=true;
			$info = NULL;
		}
		//sprawdźmy czy link jest aktywny, czy jest dofollow/nofollow? jaki jest anchor?
		//wywołanie funkcji wyszukującej linki na stronie
		$links = getLink($link_site);
		//sprawdzanie czy wśród linków na stronie jest nasz link i pobranie anchora oraz zweryfikowanie czy link jest dofollow/nofollow
		$sizeArray = sizeof($links);
		for ($i = 0; $i < $sizeArray; $i++) 
		{
			if ((strpos($links[$i][1], $site) !== false))
			{
				$sprawdzenie = 1;
			}
			else 
			{
				$sprawdzenie = 0;
			}
			//sprawdziliśmy ze jest nasz link to pobieramy cał <a href .... </a> i sprawdzamy anchora i nofollow	
			if ($sprawdzenie==1) 
			{
				$state = "aktywny";
				$sprawdzenie = 0;
				$anchor = $links[$i][2];
				$search = "nofollow";
				$xfollow = $links[$i][0];
				if(strpos($xfollow,$search) !== false)
				{	
					$linktype3_id = 1; //link jest nofollow
				}
				else
				{
					$linktype3_id = 2; //link jest dofollow
				}
			}
		}
		// dodajemy link do bazy danych
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
					//Hurra, wszystkie testy zaliczone, dodajemy link do bazy
					$updateDB = "INSERT INTO link VALUES (NULL, '".$project_id."', '".$_SESSION['user_id']."', '".$link_site."', '".$site."', '".$anchor."','".$linktype1_id."', '".$linktype2_id."', '".$linktype3_id."', '".$link_date."', '".$link_cost."', '".$first_name."', '".$last_name."', '".$email."', '".$phone."', '".$info."', '".$state."', NULL)";
					if ($polaczenie->query($updateDB))
					{
						$_SESSION['link_add']=true;
						$location = "Location: links.php?id=".$project_id;
						//echo $location;
						//exit();
						header($location);
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
			<div class="info">
	<!--		<?php 
				if ($_SESSION['blad']==true)
				{
					echo '<div class="success">Nie dodałeś żadnego pliku csv lub Twój plik jest pusty</div>'; 
					unset($_SESSION['blad']);
				}
			?> -->
			</div>
			<h2>Import linków</h2>
			<div class="left_box">	
			<form action="links_added.php" method="post" enctype="multipart/form-data">
					<p><label>Wybierz projekt:</label><select type="int" name="project_id">
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
				<p><label>Wybierz plik csv do importu:</label><input name="csv" type="file" id="csv" /><br /></p>
				<input type="hidden" value="true" name="check" />
				<input type="submit" value="Importuj" />
			</form>
			</div>
			<div class="right_box">
Wytyczne do pliku CSV <a style="cursor: pointer; color: red;" onclick="rozwin('IDENTYFIKATOR')">(kliknij)</a>
<div class="h" id="IDENTYFIKATOR">
<ol style="list-style-type: decimal">
	<li>Ściągnij przykładowy plik z danymi do importu <a href="chuck-link_import_file_example.csv" target="blank">TUTAJ</a></li>
	<li>Kolumnywystępujące w pliku csv: adres linka, adres linkowanej strony, źródło linka, typ linka, data pozyskania linka, koszt pozyskania linka oraz dane kontaktowe osoby, która opublikowała linka: imię, nazwisko, email, telefon, dodatkowe informacje</li>
	<li>Polami obowiązkowymi, żeby link został dodany są: adres linka, adres linkowanej strony, źródło linka, typ linka</li>
	<li>Wymagania odnośnie uzupełniania poszczególnych pól:</li>
		<ol style="list-style-type: lower-alpha">
			<li>Możliwe wartości dla pola źródło linka (artykuł gościnny, serwis ogłoszeniowy, marketing szeptany, komentarz na blogu, komentarz na forum, profil, sidebar, inny, zaplecze seo, artykuł ekspercki, katalog stron)</li>
			<li>Możliwe wartości pola typ linka (tekstowy, graficzny, przekierowanie)</li>
			<li>W pole data pozyskania linka możesz wpisać datę w formacie 2017-02-24. Jeżeli nie uzupełnisz tego pola to system automatycznie wstawi dzisiejszą datę</li>
			<li>W polu koszt linka wpisujesz liczbę całkowitą lub ułamek dziesiętny np. 1.5. Pamiętaj separatorem części dziesiętnych jest znak kropki</li>
		</ol>	
	<li>Nie usuwaj wiersza z nazwami poszczególnych kolumn</li>
	<li>Pamiętaj, żeby ustawić kodowanie pliku na UTF-8 (bez BOOM)</li>
	<li>Zapisz plik z rozszerzeniem CSV</li>
	<li>Pamiętaj, żeby separatorem pomiędzy poszczególnymi komórkami był znak średnika (;)</li>
	<li>Po wykonanej migracji w ciagu 24 godzin system zweryfikuje:</li>
		<ol style="list-style-type: lower-alpha">
			<li>status linka (aktywny/nieaktywny)</li>
			<li>pobierze anchor tekst</li>
			<li>zweryfikuje czy link jest dofollow/nofollow</li>
		</ol>
</ol>
</div>
			</div>
			<div style="clear:both;"></div>
	</div>
		
		<div class="footer">Link Checker &copy; 2018. Wszelkie prawa zastrzeżone GDAQ.PL Multimedia Sławomir Gdak.</div>
	</div>
</div>
	</body>
</html>