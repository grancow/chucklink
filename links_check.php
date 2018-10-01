<?php 
//ob_start();
include('header.php'); 
function getLink($oldurl) 
{
    //echo "<br>jestem w funkcji getlink i sprawdzam strone " . $oldurl;
	$ch = curl_init();
	//obcinamy nie potrzebne znaki z adresu strony
	$order = array("\r\n", "\n", "\r");
	$url = str_replace($order, $url, $oldurl);
	curl_setopt($ch, CURLOPT_URL,$url);
	//curl_setopt($ch, CURLOPT_PROXY, $proxy);
	//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$soruce = curl_exec($ch);
	//wyświetlamy błąd jeżeli nie pobrało źródła strony
	//if (curl_errno($ch)) { echo '<br>Błąd #' . curl_errno($ch) . ': ' . curl_error($ch);}
	curl_close($ch);

	$regEx = "<a\s[^>]*href=(\"|\'??)([^\"|\' >]*?)\\1[^>]*>(.*)<\/a>";
	if (preg_match_all("/$regEx/siU", $soruce, $foundLinks, PREG_SET_ORDER)) 
	{
        $linki = array();
        $tmp = array();
        $sizeArray = sizeof($foundLinks);
		//echo "<br> Na stronie " .$url. " jest do sprawdzenia " .$sizeArray. " linków<br>";
        for ($i = 0; $i < $sizeArray; $i++) 
		{
            $links[$i][0] = $foundLinks[$i][0];
			$links[$i][1] = $foundLinks[$i][2];
			$links[$i][2] = $foundLinks[$i][3];
        }
        return $links;
    }
	//else {echo "<br> nie wiem czemu ale nie wszedłem do warunku z pregmatch";}
}
function short($c,$d) {
 if(strlen($c) > $i) {        	//sprawdzanie czy tekst jest dłuzszy niz ustaliliśmy
  $ciag = substr($c,0,$d); 		//jesli tak, to obcina ciąg...
  $ciag .="...";                //...dodaje kropki...
  return $ciag;                	//... i zwraca zmodyfikowany ciąg
  }
  else return $c;            	//jesli nie to zwraca wprowadzony ciąg
 }
?>	
		<div class="content">
			<h2>Sprawdzenie linków online</h2>
			<div class="left_box">	
				<p>Dzięki temu narzędziu zaoszczędzisz co najmniej kilka godzin miesięcznie.</p>
				<p>W pole "Poszukiwany adres" wpis adres którego szukasz, a w polu "Lista stron do sprawdzenia" podaj adresy stron na których skrypt ma poszukiwać linków.</p>
				<p>Ważne obowiązuje ograniczenie do 100 linków sprawdzanych za jednym razem. Czyli należy sprawdzić 100 linków i zapisać sobie wynik, a następnie sprawdzić kolejną 100-tkę.</p>
			</div>
				<form method="post">
					Adres linkowanej strony:<br />
					<input type="text" name="site" placeholder="http://www" /><br /><br />
					<p>Lista stron do sprawdzenia:<br />
					<textarea name="linkilista" cols="70" rows="15" style="width:400px !important;"></textarea><br /><br />
					<input type="submit" value="Przeszukaj" />
				</form><br />
<?php

if ((isset($_POST['linkilista']))&&(isset($_POST['site'])))
{
	echo '<div class="tab">';
	echo '<table width="1100" align="center" bordercolor="#d5d5d5" cellpadding="0" cellspacing="0">';
    echo '	<tr>';
	echo '		<td width="80" align="center" bgcolor="e5e5e5">Status</td>';
	echo '		<td width="260" align="center" bgcolor="e5e5e5">Adres linka</td>';
	echo '		<td width="260" align="center" bgcolor="e5e5e5">Linkowana strona</td>';
	echo '		<td width="150" align="center" bgcolor="e5e5e5">Anchor text</td>';
	echo '		<td width="80" align="center" bgcolor="e5e5e5">Follow</td>';
	echo '	</tr>';
	//pobieram stronę do sprawdzania i docinamy site do optymalnej postaci
	$site_org = $_POST['site'];
	if (preg_match('@^(http://www.|https://www.|http://|https://|www.|())(.*)([^/]+)@i', $site_org, $matches))
	{	
	$tmp_site = $matches[3];
	$tmp_site .= $matches[4];}
	else { $tmp_site = $site_org;}
	//echo "dla linka " . $site . " sprawdzam jego istnienie na stronach www";
	//pobieram listę stron do sprawdzenia czy na nich jest link ze zmiennej $site
	$linkilista = $_POST['linkilista'];
	$linkilista= explode("\n",trim($linkilista));
	//sprawdzam ile linków wprowadzono do sprawdzenia
	$ilelinkow = sizeof($linkilista);
	//echo "<br />ilość stron do sprawdzenia wynosi - " . $ilelinkow;
	//echo "<br /> zaś w polu tekstowym wprowadziłeś następujące dane:";
	//echo "<br /> user ID wynosi: " . $_SESSION['user_id'];
	//zakładam pomocnicze tabele do wyników
	$tmp_ok = array();
	$tmp_bug = array();
	$k = 0;
	$b = 0;
	ob_implicit_flush();
	for ($n = 1; $n < $ilelinkow; $n++) 
		{
		//sprawdźmy czy link jest aktywny, czy jest dofollow/nofollow? jaki jest anchor?
		//wywołanie funkcji wyszukującej linki na stronie
		//echo "<br />sprawdzam czy na stronie - " . $linkilista[$n] . " jest link do strony - " . $site;
		//echo "<br /> zmianna n wynosi ". $n;
		$link = $linkilista[$n];
		$links = getLink($link);
		//sprawdzanie czy wśród linków na stronie jest nasz link i pobranie anchora oraz zweryfikowanie czy link jest dofollow/nofollow
		$sizeArray = sizeof($links);
		//echo "<br />pobrałem ze strony " . $linkilista[$n] . " następująco liczbę linków - " . $sizeArray;
		for ($i = 0; $i < $sizeArray; $i++) 
		{
			//echo "<br />sprawdzam teraz link: " . $links[$i][1];
			if ((strpos($links[$i][1], $tmp_site) !== false))
			{
				//echo "<br />hura jest na niej mój link";
				$sprawdzenie = 1;
				break;
			}
			else 
			{
				//echo "<br />beeee nie ma na niej mojego linka";
				$sprawdzenie = 0;
			}
		}
		//muszę teraz dla danej strony wpisać informację do tymczasowej tabeli albo do tmp jeżeli jest na niej linkowana strona
		//albo do tmp_bug jeżeli nie ma na niej linkowanej strony
			if ($sprawdzenie == 1)
			{
				$site = $links[$i][1];
				$data_ok=true;
				$state = "aktywny";
				$sprawdzenie = 0;
				$anchor = $links[$i][2];
				//echo "<br />anchor mojego linka to " . $anchor;
				$search = "nofollow";
				$xfollow = $links[$i][0];
				if(strpos($xfollow,$search) !== false)
				{	
					$follow = "nofollow"; //link jest nofollow
				}
				else
				{
					$follow = "dofollow"; //link jest dofollow
				}
				//echo "<br />link jest typu " . $follow;
				$order   = array("\r\n", "\n", "\r");
				$tmp_ok[$k][0] = str_replace($order, $tmp_ok[$k][0], $linkilista[$n]);
				//$tmp_ok[$k][0] = $linkilista[$n];
				$tmp_ok[$k][1] = str_replace($order, $tmp_ok[$k][1], $site);
				//$tmp_ok[$k][1] = $site_org;
				$tmp_ok[$k][2] = str_replace($order, $tmp_ok[$k][2], $state);
				//$tmp_ok[$k][2] = $state;
				$tmp_ok[$k][3] = str_replace($order, $tmp_ok[$k][3], $anchor);
				//$tmp_ok[$k][3] = $anchor;
				$tmp_ok[$k][4] = $follow;
				$k++;			
				//wpisuje wynik do tabeli na stronie
				echo '<tr><td width="80" align="center"><i class="material-icons" title="'.$state.'" style="color: green;">sentiment_very_satisfied</i></td>';
				echo '<td width="260" align="center"><a target="_blank" href="'.$linkilista[$n].'" title="'.$linkilista[$n].'">'.short($linkilista[$n],30).'</a></td>';
				echo '<td width="260" align="center"><a target="_blank" href="'.$site.'" title="'.$site.'">'.short($site,30).'</a></td>';
				$anchor1 = mb_convert_encoding($anchor, "UTF-8", "iso-8859-2");
				echo '<td width="150" align="center">'.$anchor1.'</td>';
				if ($follow == "dofollow") {echo '<td width="80" align="center"><i class="material-icons" title="'.$follow.'" style="color: green;">thumb_up</i></td></tr>';}
				elseif ($follow == "nofollow") {echo '<td width="80" align="center"><i class="material-icons" title="'.$follow.'" style="color: red;">thumb_down</i></td></tr>';}
				//elseif (($linktype3 == "brak danych")||($linktype3 == NULL)) {echo '<td width="80" align="center"><i class="material-icons" title="'.$linktype3.'" style="color: black;">clear</i></td></tr>';}
			}
			else
			{
				$data_bug=true;
				$state = "nie aktywny";
				$order   = array("\r\n", "\n", "\r");
				$tmp_bug[$b][0] = str_replace($order, $tmp_bug[$b][0], $linkilista[$n]);
				//$tmp_bug[$b][0] = $linkilista[$n];
				$tmp_bug[$b][1] = str_replace($order, $tmp_bug[$b][1], $site_org);
				//$tmp_bug[$b][1] = $site_org;
				$tmp_bug[$b][2] = $state;
				$b++;
				//wpisuje wynik do tabeli na stronie
				echo '<tr><td width="80" align="center"><i class="material-icons" title="'.$state.'" style="color: red;">sentiment_very_dissatisfied</i></td>';
				echo '<td width="260" align="center"><a target="_blank" href="'.$linkilista[$n].'" title="'.$linkilista[$n].'">'.short($linkilista[$n],30).'</a></td>';
				echo '<td width="260" align="center"><a target="_blank" href="'.$site_org.'" title="'.$site_org.'">'.short($site_org,30).'</a></td>';
				echo '<td width="150" align="center"><i class="material-icons" title="Brak danych" style="color: black;">clear</i></td>';
				echo '<td width="80" align="center"><i class="material-icons" title="Brak danych" style="color: black;">clear</i></td></tr>';
			}
			
//		ob_flush();
//		flush();
		}
	
	//-------------------------
	//zapakujmy wiersze z linkami aktywnymi do pliku csv
	if ($data_ok==true)
	{
	$fp = fopen('active_'.$_SESSION['user_id'].'.csv', 'w');
	//wstawiamy wiersz z nazwami kolumn
	$title = "Adres linka;Adres linkowanej strony;Status;Anchor;Follow\n";
	fwrite($fp, $title);
	for ($i = 0; $i < $k; $i++) 
	{
	$wsad = $tmp_ok[$i][0].";".$tmp_ok[$i][1].";".$tmp_ok[$i][2].";".$tmp_ok[$i][3].";".$tmp_ok[$i][4]."\n";		
	fwrite($fp, $wsad);	
	}	
    fclose($fp);
	}
	if ($data_bug==true)
	{
	//zapakujemy wiersze z linkami nie aktywnymi do pliku csv
	$fp = fopen('noactive_'.$_SESSION['user_id'].'.csv', 'w');
	//wstawiamy wiersz z nazwami kolumn
	$title = "Adres linka;Adres linkowanej strony;Status\n";
	fwrite($fp, $title);
	for ($i = 0; $i < $b; $i++) 
	{
	$wsad = $tmp_bug[$i][0].";".$tmp_bug[$i][1].";".$tmp_bug[$i][2]."\n";	
	fwrite($fp, $wsad);	
	}	
    fclose($fp);
	}
}
ob_end_flush();
?>
					</table>
				</div><br /><br />
					<?php 
						if ($data_ok==true){echo "<div class=\"success\">Na " . $k . " stronach znajduje się szukany przez Ciebie link. W załaczeniu szczegóły w pliku <a href=\"active_".$_SESSION['user_id'].".csv\" target=\"blank\">linki aktywne</a></div>";}
						if ($data_bug==true){echo "<div class=\"success\">Na " . $b . " stronach nie ma szukanego przez Ciebie linka. W załaczeniu szczegóły w pliku <a href=\"noactive_".$_SESSION['user_id'].".csv\" target=\"blank\">brak linków</a></div>";}
					?> 
			</div>
			<div style="clear:both;"></div>
		</div>
		<div class="footer">Link Checker &copy; 2018. Wszelkie prawa zastrzeżone GDAQ.PL Multimedia Sławomir Gdak.</div>
	</div>
</div>
	</body>
</html>