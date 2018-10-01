<?php
function getLink($url) 
{
	//$proxy = '196.196.90.77:80';
	//$proxyauth = 'topseo2:topseo2';
	
    echo "<br>jestem w funkcji getlink i sprawdzam strone " . $url;
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
	if (preg_match_all("/$regEx/siU", $soruce, $foundLinks, PREG_SET_ORDER)) 
	{
        $linki = array();
        $tmp = array();
        $sizeArray = sizeof($foundLinks);
		echo "<br> Na stronie " .$url. " jest do sprawdzenia " .$sizeArray. " linków<br>";
        for ($i = 0; $i < $sizeArray; $i++) 
		{
            $links[$i][0] = $foundLinks[$i][0];
			$links[$i][1] = $foundLinks[$i][2];
			$links[$i][2] = $foundLinks[$i][3];
        }
        return $links;
    }
}
$project_id = 14;
//zmienna pomocnicza do wykresów - na aktualny dzień
$ch_now = array();
ini_set("display_errors", 0);
require_once 'dbconnect.php';
$polaczenie = mysqli_connect($host, $user, $password);
mysqli_query($polaczenie, "SET CHARSET utf8");
mysqli_query($polaczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
mysqli_select_db($polaczenie, $database);
$zapytanie = "SELECT id, link_site, site, linktype1_id FROM link WHERE project_id = '".$project_id."'";
$rezultat = mysqli_query($polaczenie, $zapytanie);
$ile = mysqli_num_rows($rezultat);
echo "z bazy danych pobrano " . $ile . " stron do sprawdzenia w ramach tego projektu";
if ($ile>=1) 
{
//	$k = 0;
	$data = date('Y/m/d H:i:s');
	$ch_now[0] = $data;
	for ($i = 1; $i <= $ile; $i++) 
	{
		$row = mysqli_fetch_assoc($rezultat);
		$id = $row['id'];
		$site = $row['site'];
		$link_site = $row['link_site'];
		$linktype1_id = $row['linktype1_id']; 
		//docinamy site do optymalnej postaci
		preg_match('@^(http://www.|https://www.|http://|https://|www.|())(.*)([^/]+)@i', $site, $matches);
		$tmp_site = $matches[3];
		$tmp_site .= $matches[4];
		//sprawdźmy czy link jest aktywny, czy jest dofollow/nofollow? jaki jest anchor?
		//wywołanie funkcji wyszukującej linki na stronie
		$links = getLink($link_site);
		//sprawdzanie czy wśród linków na stronie jest nasz link i pobranie anchora oraz zweryfikowanie czy link jest dofollow/nofollow
		$sizeArray = sizeof($links);
		$sprawdzenie = 0;
		for ($n = 0; $n < $sizeArray; $n++) 
		{
			echo "<br>jestem w pętli sprawdzającej teraz link " .$links[$n][1]. ", sprawdzenie wynosi " .$sprawdzenie ." a zmianna n wynosi " . $n;
			// sprawdzenie czy na przeszukiwanej stronie wśród istniejących na niej linków jest link do naszej strony
			if ((strpos($links[$n][1], $tmp_site) !== false))
			{
				$sprawdzenie = 1;
				echo "<br>hura jest link na tej stronie, sprawdzenie wynosi " . $sprawdzenie;
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
				$site = $links[$n][1];
				$state = "aktywny";
		//		$data = date('Y/m/d H:i:s');
				echo "<br>sprawdziłem stronę " . $link_site . "i jest na niej moj link :)";
				$anchor = $links[$n][2];
				$search = "nofollow";
				$xfollow = $links[$n][0];
				if(strpos($xfollow,$search) !== false)
				{	
					$linktype3_id = 1; //link jest nofollow
				}
				else
				{
					$linktype3_id = 2; //link jest dofollow
				}
				$updateDB = "UPDATE link SET site='" . $site . "', status='" . $state ."', aktualizacja='" . $data . "', anchor='" . $anchor ."', linktype3_id='" . $linktype3_id . "' WHERE id='$id'";
				//w tym miejscu powinien zostać dodany link aktywny, o typie x do daty dzisiejszej dla wykresów
				if ($linktype1_id == 1) {$ch_now[1]++;}
				elseif ($linktype1_id == 2) {$ch_now[3]++;}
				elseif ($linktype1_id == 3) {$ch_now[5]++;}
				elseif ($linktype1_id == 4) {$ch_now[7]++;}
				elseif ($linktype1_id == 5) {$ch_now[9]++;}
				elseif ($linktype1_id == 6) {$ch_now[11]++;}
				elseif ($linktype1_id == 7) {$ch_now[13]++;}
				elseif ($linktype1_id == 8) {$ch_now[15]++;}
			}
			elseif ($sprawdzenie == 0)
			{
				echo "wszedłem do elseifa ze sprawdzeniem = 0";
				$state = "nie aktywny";
		//		$data = date('Y/m/d H:i:s');
				$updateDB = "UPDATE link SET status='" . $state ."', aktualizacja='" . $data . "' WHERE id='$id'";
				//w tym miejscu powinien zostać dodany link nie aktywny, o typie x do daty dzisiejszej dla wykresów
		//		$ch_now[$k][0] = $data;
				if ($linktype1_id == 1) {$ch_now[2]++;}
				elseif ($linktype1_id == 2) {$ch_now[4]++;}
				elseif ($linktype1_id == 3) {$ch_now[6]++;}
				elseif ($linktype1_id == 4) {$ch_now[8]++;}
				elseif ($linktype1_id == 5) {$ch_now[10]++;}
				elseif ($linktype1_id == 6) {$ch_now[12]++;}
				elseif ($linktype1_id == 7) {$ch_now[14]++;}
				elseif ($linktype1_id == 8) {$ch_now[16]++;}
			}
	//		$k++;
			//czy linia poniżej jest potrzeba? przecież mam połączenie z BD otwarte
			$polaczenie = new mysqli($host, $user, $password, $database);
			if ($polaczenie->query($updateDB))
				{
					$sprawdzenie = 0;
					//$polaczenie->close();
				}
	}
	//tutaj należy zrobić INSERT do tabeli CHART z aktualnymi ilościami linków per typ z podziałem na aktywne/nie aktywne
	$ch_now[17] = $ch_now[1] + $ch_now[3] + $ch_now[5] + $ch_now[7] + $ch_now[9] + $ch_now[11] + $ch_now[13] + $ch_now[15];
	$ch_now[18] = $ch_now[2] + $ch_now[4] + $ch_now[6] + $ch_now[8] + $ch_now[10] + $ch_now[12] + $ch_now[14] + $ch_now[16];

	echo "<br>robie insert, zmienna i wynosi ".$i." data wynosi ".$ch_now[0]." ilość całkowita linków aktywnych to ". $ch_now[17] .", a linków utraconych to " . $ch_now[18];
	$polaczenie->query("INSERT INTO charts_all VALUES (NULL, '".$project_id."', '".$ch_now[0]."', '".$ch_now[1]."','".$ch_now[2]."','".$ch_now[3]."','".$ch_now[4]."','".$ch_now[5]."','".$ch_now[6]."','".$ch_now[7]."','".$ch_now[8]."','".$ch_now[9]."','".$ch_now[10]."','".$ch_now[11]."','".$ch_now[12]."','".$ch_now[13]."','".$ch_now[14]."','".$ch_now[15]."','".$ch_now[16]."','".$ch_now[17]."','".$ch_now[18]."')");
}
/*tutaj wprowadzimy komendy do weryfikacji danych na potrzebę wykresów

	1. Pobieram z BD informacje o linkach z funkcją ASC
	SELECT * FROM `link` WHERE `project_id`=14 ORDER BY link_date ASC
	Pobrać można tylko niezbędne dane:  data linka, linktype1, status
	2. Tworzymy pomocniczą tablicę dwuwymiarową  
	3. Weryfikujemy każdy pobrany wiersz z BD
		a. Sprawdzamy czy data jest taka sama jak poprzednio 
	4. Przy update będzie sprawdzana data czy istnieje w BD jeżeli tak to robimy update jeżeli nie to zakładamy nowy wiersz */
//czy następne 4 linijki sa potrzebne przecież już to na początku pliku zdefiniowwaliśmy????????
$polaczenie = mysqli_connect($host, $user, $password);
mysqli_query($polaczenie, "SET CHARSET utf8");
mysqli_query($polaczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
mysqli_select_db($polaczenie, $database);
$zapytanie = "SELECT link_date, linktype1_id, status FROM link WHERE project_id='".$project_id."' ORDER BY link_date ASC";
$rezultat = mysqli_query($polaczenie, $zapytanie);
$ile = mysqli_num_rows($rezultat);
//tworzę tymczasową tablicę
$chart = array();
if ($ile>=1) 
{
	echo "<br>teraz sprawdzam dane do wykresu";
	$k = 0; //definiuje zmieną pomocniczą, jej wartość określa ilość dat w projekcie
	for ($i = 1; $i <= $ile; $i++) 
	{
		$row = mysqli_fetch_assoc($rezultat);
		$link_date = $row['link_date'];
		$linktype1_id = $row['linktype1_id'];
		$status = $row['status'];
		// a co z datami pomiędzy linkami występującymi w tablicy link ??????
		
		
		//sprawdzenie czy kolejny link nie jest z tej samej daty
		if ($link_date != $chart[$k][0]) {$k++;}
		$chart[$k][0] = $link_date;
		if ($linktype1_id == 1)
		{
			if ($status == 'aktywny') {$chart[$k][1]++;}
			elseif ($status == 'nie aktywny') {$chart[$k][2]++;}
		}
		elseif ($linktype1_id == 2)
		{
			if ($status == 'aktywny') {$chart[$k][3]++;}
			elseif ($status == 'nie aktywny') {$chart[$k][4]++;}
		}		
		elseif ($linktype1_id == 3)
		{
			if ($status == 'aktywny') {$chart[$k][5]++;}
			elseif ($status == 'nie aktywny') {$chart[$k][6]++;}
		}
		elseif ($linktype1_id == 4)
		{
			if ($status == 'aktywny') {$chart[$k][7]++;}
			elseif ($status == 'nie aktywny') {$chart[$k][8]++;}
		}
		elseif ($linktype1_id == 5)
		{
			if ($status == 'aktywny') {$chart[$k][9]++;}
			elseif ($status == 'nie aktywny') {$chart[$k][10]++;}
		}
		elseif ($linktype1_id == 6)
		{
			if ($status == 'aktywny') {$chart[$k][11]++;}
			elseif ($status == 'nie aktywny') {$chart[$k][12]++;}
		}
		elseif ($linktype1_id == 7)
		{
			if ($status == 'aktywny') {$chart[$k][13]++;}
			elseif ($status == 'nie aktywny') {$chart[$k][14]++;}
		}
		elseif ($linktype1_id == 8)
		{
			if ($status == 'aktywny') {$chart[$k][15]++;}
			elseif ($status == 'nie aktywny') {$chart[$k][16]++;}
		}
		$chart[$k][17] = $chart[$k][1] + $chart[$k][3] + $chart[$k][5] + $chart[$k][7] + $chart[$k][9] + $chart[$k][11] + $chart[$k][13] + $chart[$k][15];
		$chart[$k][18] = $chart[$k][2] + $chart[$k][4] + $chart[$k][6] + $chart[$k][8] + $chart[$k][10] + $chart[$k][12] + $chart[$k][14] + $chart[$k][16];
	}
	echo "<br>zmienna k wynois ".$k;
}
	//$polaczenie = new mysqli($host, $user, $password, $database);
	// ściagam daty jakie są dla projektu w tabeli charts
	$rezultat = $polaczenie->query("SELECT date FROM charts WHERE project_id='".$project_id."'");
	//$wiersz = mysqli_fetch_array($rezultat);
	$ile = mysqli_num_rows($rezultat);
	$dates = array();
	for ($i = 1; $i <= $ile; $i++) 
	{
		$wiersz = mysqli_fetch_assoc($rezultat);
		$dates[$i] = $wiersz['date'];
	}
	
	for ($i = 1; $i <= $k; $i++)
	{
		echo "<br>Zaczynam dodawać lub updatować tabelę chart";
		echo "<br>Zas zmienna wiersz wynosi ". $dates[$i];
		//trzeba sprawdzić czy danej daty dla projektu już nie ma w tabeli, 
	if (in_array($chart[$i][0],$dates))
		{
		echo "<br>robie update, zmienna i wynosi ".$i." data wynosi ".$chart[$i][0]." ilość całkowita linków aktywnych to ". $chart[$i][17] .", a linków utraconych to " . $chart[$i][18];
		//jeżeli jest to robimy update danych (UPDATE)
		$polaczenie->query("UPDATE charts SET LT1_Active='" . $chart[$i][1] ."', LT1_Lost='" . $chart[$i][2] . "', LT2_Active='" . $chart[$i][3] ."', LT2_Lost='" . $chart[$i][4] . "', LT3_Active='" . $chart[$i][5] ."', LT3_Lost='" . $chart[$i][6] . "', LT4_Active='" . $chart[$i][7] ."', LT4_Lost='" . $chart[$i][8] . "', LT5_Active='" . $chart[$i][9] ."', LT5_Lost='" . $chart[$i][10] . "', LT6_Active='" . $chart[$i][11] ."', LT6_Lost='" . $chart[$i][12] . "', LT7_Active='" . $chart[$i][13] ."', LT7_Lost='" . $chart[$i][14] . "', LT8_Active='" . $chart[$i][15] ."', LT8_Lost='" . $chart[$i][16] . "', Link_Active='" . $chart[$i][17] ."', Link_Lost='" . $chart[$i][18] . "' WHERE date='".$chart[$i][0]."' AND project_id='".$project_id."'");
		}
		else
		{
		echo "<br>robie insert, zmienna i wynosi ".$i." data wynosi ".$chart[$i][0]." ilość całkowita linków aktywnych to ". $chart[$i][17] .", a linków utraconych to " . $chart[$i][18];
		//jeżeli nie ma to zakładamy nowy wiersz (INSERT)
		$polaczenie->query("INSERT INTO charts VALUES (NULL, '".$project_id."', '".$chart[$i][0]."', '".$chart[$i][1]."','".$chart[$i][2]."','".$chart[$i][3]."','".$chart[$i][4]."','".$chart[$i][5]."','".$chart[$i][6]."','".$chart[$i][7]."','".$chart[$i][8]."','".$chart[$i][9]."','".$chart[$i][10]."','".$chart[$i][11]."','".$chart[$i][12]."','".$chart[$i][13]."','".$chart[$i][14]."','".$chart[$i][15]."','".$chart[$i][16]."','".$chart[$i][17]."','".$chart[$i][18]."')");
		}
	}
$polaczenie->close();
?>		