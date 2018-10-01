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
		//echo "<br />ile zaczytałem wierszy ". $sizeArray;
        for ($i = 0; $i < $sizeArray; $i++) {
            $links[$i][0] = $foundLinks[$i][0];
			$links[$i][1] = $foundLinks[$i][2];
			$links[$i][2] = $foundLinks[$i][3];
        }
        
        return $links;
    }
}
	if (isset($_POST['project_id']) && ($_POST['project_id']!="") )
	{
		//Udana walidacja -podano id projektu
		$wszystko_OK=true;
		$project_id  = $_POST['project_id'];
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
		//wstawienie daty ważności linka
		$link_expiration = $_POST['link_expiration'];
		if(isset($link_expiration) && $link_expiration!="")
		{
			$wszystko_OK=true;
		}
		else
		{
			$wszystko_OK=true;
			$link_expiration = NULL;			
		}
		//wprowadzenie informacji o przypominaniu o ważności linka
		$link_reminder = $_POST['link_reminder'];
		if(isset($link_reminder))
		{
			$wszystko_OK=true;
		}
		else
		{
			$wszystko_OK=true;
			$link_reminder = NULL;
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
		//docinamy site do optymalnej postaci
		preg_match('@^(http://www.|https://www.|http://|https://|www.|())(.*)([^/]+)@i', $site, $matches);
		$tmp_site = $matches[3];
		$tmp_site .= $matches[4];
		//sprawdźmy czy link jest aktywny, czy jest dofollow/nofollow? jaki jest anchor?
		//wywołanie funkcji wyszukującej linki na stronie
		$links = getLink($link_site);
		//sprawdzanie czy wśród linków na stronie jest nasz link
		$sizeArray = sizeof($links);
		for ($i = 0; $i < $sizeArray; $i++) 
		{
			if ((strpos($links[$i][1], $tmp_site) !== false))
			{
				$sprawdzenie = 1;
				break;
			}
			else 
			{
				$sprawdzenie = 0;
			}
		}
			//sprawdziliśmy ze jest nasz link to pobieramy cał <a href .... </a> i sprawdzamy anchora i nofollow	
			if ($sprawdzenie==1) 
			{
				$site = $links[$i][1];
				$state = "aktywny";
				$anchor = $links[$i][2];
				$search = "nofollow";
				$xfollow = $links[$i][0];
				if(strpos($xfollow,$search) !== false)
				{	
					$linktype3_id = 1; //link jest nofollow
				}
				elseif (strpos($xfollow,$search) == false)
				{
					$linktype3_id = 2; //link jest dofollow
				}
				else
				{
					$linktype3_id = 3; //brak danych
				}	
			}
			elseif ($sprawdzenie == 0)
			{
				//echo "wszedłem do elseifa ze sprawdzeniem = 0";
				$state = "nie aktywny";
				$linktype3_id = 3; //brak danych
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
				//Czy link już istnieje w bazie?
				$check = "SELECT id FROM link WHERE link_site='".$link_site."' AND site='".$site."'";
				$rezultat = $polaczenie->query($check);
				//echo "</ br>sprawdzam czy link jest już w bazie";
				//echo $check;
				if (!$rezultat) throw new Exception($polaczenie->error);
				
				$ile_takich_linkow = $rezultat->num_rows;
				if($ile_takich_linkow>0)
				{
					$wszystko_OK=false;
					$_SESSION['e_link']="Link nie został dodany, gdyż istnieje już w bazie!";
					//echo "</ br>link jest już w bazie";
					//$location = "Location: links.php?id=".$project_id;
					//header($location);
				}	
				
				if ($wszystko_OK==true)
				{
					//Hurra, wszystkie testy zaliczone, dodajemy link do bazy
					$updateDB = "INSERT INTO link VALUES (NULL, '".$project_id."', '".$_SESSION['user_id']."', '".$link_site."', '".$site."', '".$anchor."','".$linktype1_id."', '".$linktype2_id."', '".$linktype3_id."', '".$link_date."', '".$link_expiration."', '".$link_cost."', '".$first_name."', '".$last_name."', '".$email."', '".$phone."', '".$info."', '".$state."', now(), now(),NULL, '".$link_reminder."')";
					if ($polaczenie->query($updateDB))
					{
						$_SESSION['link_add']=true;
						//echo "</ br>jestem w warunku żeby dodać link";
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
			
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
		
	}
	elseif ((isset($_POST['project_id'])) && ($_POST['project_id']==""))
	{
		$wszystko_OK=false;
		$_SESSION['e_project_id']="Nie wybrałeś projektu!";
	}
	
?>	

		<div class="content">
			<div class="info">
			<?php 
				if ($_SESSION['e_project_id'])
				{
					echo '<br><div class="success">'.$_SESSION['e_project_id'].'</div>'; 
					unset($_SESSION['e_project_id']);
				}
				if ($_SESSION['e_link'])
				{
					echo '<div class="success">'.$_SESSION['e_link'].'</div>'; 
					unset($_SESSION['e_link']);
				}
				if ($_SESSION['e_link_site'])
				{
					echo '<div class="success">'.$_SESSION['e_link_site'].'</div>'; 
					unset($_SESSION['e_link_site']);
				}
				if ($_SESSION['e_site'])
				{
					echo '<div class="success">'.$_SESSION['e_site'].'</div>'; 
					unset($_SESSION['e_site']);
				}
			?>
		</div>
			
			
			<h2>Dodaj nowy link</h2>
			<form method="post">
			<div class="left_box">
				<p><label>Wybierz projekt*:</label><select type="int" name="project_id">
										<OPTION value=""> </OPTION>
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
				<p><label>Adres linka*:</label><input type="url" name="link_site" placeholder="http://www" /><br /></p>
				<p><label>Adres linkowanej strony*:</label><input type="url" name="site" placeholder="http://www" /><br /></p>
				<p><label>Źródło linka*:</label><select type="int" name="linktype1_id">
									<?php require_once 'dbconnect.php';
										$polaczenie = new mysqli($host, $user, $password, $database);
										$rezultat = $polaczenie->query("SELECT * FROM linktype1");
										$ile = mysqli_num_rows($rezultat);
										for ($i = 1; $i <= $ile; $i++) 
										{		
											$row = mysqli_fetch_assoc($rezultat);
											$linktype1_id = $row['linktype1_id'];
											$linktype1 = $row['linktype1'];
											echo '<OPTION value="' . $linktype1_id .  '">'. $linktype1 . '</OPTION>';
										}	
										$polaczenie->close(); ?>
								</select><br /></p>
				<p><label>Typ linka*:</label><select type="text" name="linktype2_id">
									<?php require_once 'dbconnect.php';
										$polaczenie = new mysqli($host, $user, $password, $database);
										$rezultat = $polaczenie->query("SELECT * FROM linktype2");
										$ile = mysqli_num_rows($rezultat);
										for ($i = 1; $i <= $ile; $i++) 
										{		
											$row = mysqli_fetch_assoc($rezultat);
											$linktype2_id = $row['linktype2_id'];
											$linktype2 = $row['linktype2'];
											echo '<OPTION value="' . $linktype2_id .  '">'. $linktype2 . '</OPTION>';
										}	
										$polaczenie->close(); ?>
								</select><br /></p>
				<p><label>Data pozyskania linka:</label><input type="text" id="datepicker1" name="link_date"><br /></p>
				<p><label>Koszt pozyskania linka (PLN):</label><input type="float" name="link_cost" /><br /></p>
				<p><label>Data ważności linka:</label><input type="text" id="datepicker2" name="link_expiration"><br /></p>
				<p><label>Przypomnienie o ważności linka:</label><input type="checkbox" name="link_reminder" value="1" /></p>
				<p>Dane oznaczone * są wymagane do dodania linka</p>
				<br />
				<input type="submit" value="Dodaj link" />
			</div>
			<div class="right_box">		
				<p>Dane kontaktowe do osoby, która opublikowała link (dane opcjonalne):<br /></p>
				<p><label>Imię:</label><input type="text" name="first_name" /><br /></p>
				<p><label>Nazwisko:</label><input type="text" name="last_name" /><br /></p>
				<p><label>E-mail:</label><input type="text" name="email" /><br /></p>
				<p><label>Telefon:</label><input type="text" name="phone" /><br /></p>
				<p><label>Dodatkowe informacje:</label><input type="text" name="info" /><br /></p>
				<br />
			</div>
			</form>
			<div style="clear:both;"></div>
	</div>
		
		<div class="footer">Link Checker &copy; 2018. Wszelkie prawa zastrzeżone GDAQ.PL Multimedia Sławomir Gdak.</div>
	</div>
</div>
	<script>
			$( function() {
			$( "#datepicker1" ).datepicker();
			} );
			$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );
	</script>
		<script>
			$( function() {
			$( "#datepicker2" ).datepicker();
			} );
			$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );
	</script>
	</body>
</html>