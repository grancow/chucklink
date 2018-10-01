<?php 
session_start();
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
    //echo "<br>jestem w funkcji getlink i sprawdzam strone " . $url;
	
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
		//echo "<br> Na stronie " .$url. " jest do sprawdzenia " .$sizeArray. " linków<br>";
        for ($i = 0; $i < $sizeArray; $i++) {
            $links[$i][0] = $foundLinks[$i][0];
			$links[$i][1] = $foundLinks[$i][2];
			$links[$i][2] = $foundLinks[$i][3];
        }
        
        return $links;
    }
}
		//pobieramy ID linka do sprawdzenia
		$id = $_GET['link_id'];
		//ustawiamy datę sprawdzenia
		$data = date('Y/m/d H:i:s');
		//pobieramy dane linka do sprawdzenia
		ini_set("display_errors", 0);
		require_once 'dbconnect.php';
		$polaczenie = new mysqli($host, $user, $password, $database);
		$rezultat = $polaczenie->query("SELECT * FROM link WHERE id='$id'");
		$row = mysqli_fetch_assoc($rezultat);
		$project_id = $row['project_id'];
		$link_site = $row['link_site'];
		$site = $row['site'];
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
				//echo "<br>hura jest link na tej stronie, sprawdzenie wynosi " . $sprawdzenie;
				break;
			}
			else 
			{
				$sprawdzenie = 0;
			}
		}
			//sprawdziliśmy ze jest nasz link to pobieramy cał <a href .... </a> i sprawdzamy anchora i nofollow	
			if ($sprawdzenie == 1) 
			{
				$site = $links[$i][1];
				$state = "aktywny";
				//echo "<br>sprawdziłem stronę " . $link_site . "i jest na niej moj link :)";
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
				$updateDB = "UPDATE link SET site='" . $site . "', status='" . $state ."', aktualizacja='" . $data . "', anchor='" . $anchor ."', linktype3_id='" . $linktype3_id . "' WHERE id='$id'";
			}
			elseif ($sprawdzenie == 0)
			{
				//echo "wszedłem do elseifa ze sprawdzeniem = 0";
				$state = "nie aktywny";
				$updateDB = "UPDATE link SET status='" . $state ."', aktualizacja='" . $data . "' WHERE id='$id'";
			}
		// aktualizujemy link w bazie danych
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
				if ($polaczenie->query($updateDB))
				{
					$_SESSION['link_check']=true;
					header('Location: links.php?id='.$project_id.'');
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
?>