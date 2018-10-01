<?php 
ob_start();
include('header.php');
//deklaracja funkcji
function short($c,$d) {
 if(strlen($c) > $i) {        	//sprawdzanie czy tekst jest dłuzszy niz ustaliliśmy
  $ciag = substr($c,0,$d); 		//jesli tak, to obcina ciąg...
  $ciag .="...";                //...dodaje kropki...
  return $ciag;                	//... i zwraca zmodyfikowany ciąg
  }
  else return $c;            	//jesli nie to zwraca wprowadzony ciąg
 }
//pobieranie danych w ramach formularzy
$project_id = $_GET['id'];
$sort =  $_GET['sort'];
$up =  $_GET['up'];
// pobranie danych na temat filtrowania
$f1 = $_GET['f1'];
$f2 = $_GET['f2'];
$f3 = $_GET['f3'];
$f4 = $_GET['f4'];
// pobieranie danych do szukania
$look = $_POST['look'];
//obliczanie danych na potrzeby stronicowania
$cur_page = isset($_GET['page']) ? $_GET['page'] : 1;
//ustalenie liczby wyników wyswetlanych na stronie
if(!empty($_GET['rpp']))
{
	$rpp = $_GET['rpp']; //ResultPerPage
}
elseif(empty($_GET['rpp']))
{ 
	$rpp=20;
} 
//$results_per_page = $SESSION['rpp'];//$_SESSION['ile']; //Liczba wyników na stronę
$skip = (($cur_page - 1) * $rpp); //liczba pomijanych wierszy na potrzeby stronicowania

//pobranie nazwy projektu
require_once 'dbconnect.php';
$polaczenie = mysqli_connect($host, $user, $password);
mysqli_select_db($polaczenie, $database);
$zapytanie = "SELECT project_name FROM project WHERE project_id = '".$project_id."'";	
$rezultat = mysqli_query($polaczenie, $zapytanie);
$wiersz = mysqli_fetch_assoc($rezultat);
$project_name = $wiersz['project_name'];

//funkcje do przeniesienia do pliku function.php

function number_of_links($project_id, $sort, $up, $f1, $f2, $f3, $f4, $rpp) {
	$page_links = '';
	$tmp = array();
	$tmp[0] = 20;
	$tmp[1] = 50;
	$tmp[2] = 100;
	$sizeArray = sizeof($tmp);
	for ($i=0; $i<3; $i++){
	if ($rpp == $tmp[$i]) {$page_links .= '<b><font color="grey">' . $tmp[$i] .'</font></b> ';}
	else {
	$page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?id='.$project_id;
	if ($sort) {$page_links .= '&sort='.$sort.'&up='.$up;}
	if ($f1) {$page_links .= '&f1='.$f1;}
	if ($f2) {$page_links .= '&f2='.$f2;}
	if ($f3) {$page_links .= '&f3='.$f3;}
	if ($f4) {$page_links .= '&f4='.$f4;}
	$page_links .= '&rpp=' . $tmp[$i] .  '">' . $tmp[$i] . '</a>  ';
	}
	}
	return $page_links;
}

function generate_page_links($cur_page, $num_pages, $project_id, $sort, $up, $f1, $f2, $f3, $f4, $rpp) {
	$page_links = '';
	// odnośnik do poprzedniej strony (-1)
	if ($cur_page > 1) {
		$page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?id='.$project_id;
		if ($sort) {$page_links .= '&sort='.$sort.'&up='.$up;}
		if ($f1) {$page_links .= '&f1='.$f1;}
		if ($f2) {$page_links .= '&f2='.$f2;}
		if ($f3) {$page_links .= '&f3='.$f3;}
		if ($f4) {$page_links .= '&f4='.$f4;}
		if ($rpp) {$page_links .= '&rpp='.$rpp;}
		$page_links .= '&page=' . ($cur_page - 1) . '">«</a> ';
     }
	$i = $cur_page - 4;
	$page = $i + 8;
	for ($i; $i <= $page; $i++) {
		if ($i > 0 && $i <= $num_pages) {
			//jeżeli jesteśmy na danej stronie to nie wyświetlamy jej jako link    
			if ($cur_page == $i  && $i != 0) {
				$page_links .= '<b><font color="grey">' . $i . '</font></b> ';
			}
			else {
			//wyświetlamy odnośnik do 1 strony
				if ($i == ($cur_page - 4) && ($cur_page - 5) != 0) { 
					$page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?id='.$project_id;
					if ($sort) {$page_links .= '&sort='.$sort.'&up='.$up;}
					if ($f1) {$page_links .= '&f1='.$f1;}
					if ($f2) {$page_links .= '&f2='.$f2;}
					if ($f3) {$page_links .= '&f3='.$f3;}
					if ($f4) {$page_links .= '&f4='.$f4;}
					if ($rpp) {$page_links .= '&rpp='.$rpp;}
					$page_links .= '&page=1">1</a> ';
				}
				//wyświetlamy "kropki", jako odnośnik do poprzedniego bloku stron
				if ($i == ($cur_page - 4) && (($cur_page - 6)) > 0) { 
					$page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?id='.$project_id; 
					if ($sort) {$page_links .= '&sort='.$sort.'&up='.$up;}
					if ($f1) {$page_links .= '&f1='.$f1;}
					if ($f2) {$page_links .= '&f2='.$f2;}
					if ($f3) {$page_links .= '&f3='.$f3;}
					if ($f4) {$page_links .= '&f4='.$f4;}
					if ($rpp) {$page_links .= '&rpp='.$rpp;}
					$page_links .= '&page=' . ($cur_page - 5) . '">...</a> ';
				} 
				//wyświetlamy liki do bieżących stron
				$page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?id='.$project_id;
				if ($sort) {$page_links .= '&sort='.$sort.'&up='.$up;}
				if ($f1) {$page_links .= '&f1='.$f1;}
				if ($f2) {$page_links .= '&f2='.$f2;}
				if ($f3) {$page_links .= '&f3='.$f3;}
				if ($f4) {$page_links .= '&f4='.$f4;}
				if ($rpp) {$page_links .= '&rpp='.$rpp;}
				$page_links .= '&page=' . $i . '"> ' . $i . '</a> ';
				//wyświetlamy "kropki", jako odnośnik do następnego bloku stron
				if ($i == $page && (($cur_page + 4) - ($num_pages)) < -1) { 
					$page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?id='.$project_id;
					if ($sort) {$page_links .= '&sort='.$sort.'&up='.$up;}
							if ($f1) {$page_links .= '&f1='.$f1;}
		if ($f2) {$page_links .= '&f2='.$f2;}
		if ($f3) {$page_links .= '&f3='.$f3;}
		if ($f4) {$page_links .= '&f4='.$f4;}
		if ($rpp) {$page_links .= '&rpp='.$rpp;}
					$page_links .= '&page=' . ($cur_page + 5) . '">...</a>';
				} 
				//wyświetlamy odnośnik do ostatniej strony
				if ($i == $page && ($cur_page + 4) != $num_pages) { 
					$page_links .= ' <a href="' . $_SERVER['PHP_SELF'] . '?id='.$project_id;
					if ($sort) {$page_links .= '&sort='.$sort.'&up='.$up;}
							if ($f1) {$page_links .= '&f1='.$f1;}
		if ($f2) {$page_links .= '&f2='.$f2;}
		if ($f3) {$page_links .= '&f3='.$f3;}
		if ($f4) {$page_links .= '&f4='.$f4;}
		if ($rpp) {$page_links .= '&rpp='.$rpp;}
					$page_links .= '&page=' . $num_pages . '">' . $num_pages . '</a> ';
				}
			}
		}
	}
	//odnośnik do następnej strony (+1)
	if ($cur_page < $num_pages) {
		$page_links .= '<a href="' . $_SERVER['PHP_SELF'] . '?id='.$project_id;
		if ($sort) {$page_links .= '&sort='.$sort.'&up='.$up;}
		if ($f1) {$page_links .= '&f1='.$f1;}
		if ($f2) {$page_links .= '&f2='.$f2;}
		if ($f3) {$page_links .= '&f3='.$f3;}
		if ($f4) {$page_links .= '&f4='.$f4;}
		if ($rpp) {$page_links .= '&rpp='.$rpp;}
		$page_links .= '&page=' . ($cur_page + 1) . '">»</a>';
	}
	return $page_links;
}

//if ((!$sort) or ($sort !== s1)){echo '<a href="links.php?id='.$project_id.'&sort=s1&up=1">';}
//elseif (($sort == s1) and ($up == 1)){echo '<a href="links.php?id='.$project_id.'&sort=s1&up=0">';}
//elseif (($sort == s1) and ($up == 0)){echo '<a href="links.php?id='.$project_id.'&sort=s1&up=1">';}

//wyświetlanie linków w zależności od uprawnień

if (($_SESSION['priviliges'] == 1)||($_SESSION['priviliges'] == 2)||(!$filtr)) //jeżeli to manager
{
$zapytanie = "SELECT a.id, u.name, u.surname, a.link_site, a.site, a.status, a.anchor, lt2.linktype2, lt1.linktype1, lt3.linktype3, a.link_date FROM link a, users u, linktype1 lt1, linktype2 lt2, linktype3 lt3 WHERE a.project_id = '".$project_id."' AND a.user_id = u.user_id AND a.linktype2_id = lt2.linktype2_id AND a.linktype1_id = lt1.linktype1_id AND a.linktype3_id = lt3.linktype3_id";						
}
elseif (($_SESSION['priviliges'] == 0)||(!$filtr)) //jeżeli to zwykły pracownik
{
$zapytanie = "SELECT a.id, u.name, u.surname, a.link_site, a.site, a.status, a.anchor, lt2.linktype2, lt1.linktype1, lt3.linktype3, a.link_date FROM link a, users u, linktype1 lt1, linktype2 lt2, linktype3 lt3 WHERE a.project_id = '".$project_id."' AND a.user_id = '".$_SESSION['user_id']."' AND a.user_id = u.user_id AND a.linktype2_id = lt2.linktype2_id AND a.linktype1_id = lt1.linktype1_id AND a.linktype3_id = lt3.linktype3_id";	
}
//dodaje warunek do szukania
if ($look) {$zapytanie .= " AND a.link_site LIKE '%" . $look . "%'";}
//dodaje warunki filtrowania
if ($f1) {$zapytanie .= " AND lt1.linktype1_id = '" . $f1 . "'";}
if ($f2) {$zapytanie .= " AND lt2.linktype2_id = '" . $f2 . "'";}
if ($f3) {$zapytanie .= " AND lt3.linktype3_id = '" . $f3 . "'";}
if ($f4) {$zapytanie .= " AND a.status = '" . $f4 . "'";}
//dodaje warunki sortowania
if (($sort == s1) and ($up == 1)) { $zapytanie .= " ORDER BY a.status ASC";}
elseif (($sort == s1) and ($up == 0)) { $zapytanie .= " ORDER BY a.status DESC";}
elseif (($sort == s2) and ($up == 1)) { $zapytanie .= " ORDER BY a.link_site ASC";}
elseif (($sort == s2) and ($up == 0)) { $zapytanie .= " ORDER BY a.link_site DESC";}
elseif (($sort == s3) and ($up == 1)) { $zapytanie .= " ORDER BY a.site ASC";}
elseif (($sort == s3) and ($up == 0)) { $zapytanie .= " ORDER BY a.site DESC";}
elseif (($sort == s4) and ($up == 1)) { $zapytanie .= " ORDER BY a.anchor ASC";}
elseif (($sort == s4) and ($up == 0)) { $zapytanie .= " ORDER BY a.anchor DESC";}
elseif (($sort == s5) and ($up == 1)) { $zapytanie .= " ORDER BY u.surname ASC";}
elseif (($sort == s5) and ($up == 0)) { $zapytanie .= " ORDER BY u.surname DESC";}
elseif (($sort == s6) and ($up == 1)) { $zapytanie .= " ORDER BY lt3.linktype3 ASC";}
elseif (($sort == s6) and ($up == 0)) { $zapytanie .= " ORDER BY lt3.linktype3 DESC";}
elseif (($sort == s7) and ($up == 1)) { $zapytanie .= " ORDER BY lt2.linktype2 ASC";}
elseif (($sort == s7) and ($up == 0)) { $zapytanie .= " ORDER BY lt2.linktype2 DESC";}
elseif (($sort == s8) and ($up == 1)) { $zapytanie .= " ORDER BY lt1.linktype1 ASC";}
elseif (($sort == s8) and ($up == 0)) { $zapytanie .= " ORDER BY lt1.linktype1 DESC";}
elseif (($sort == s9) and ($up == 1)) { $zapytanie .= " ORDER BY a.link_date ASC";}
elseif (($sort == s9) and ($up == 0)) { $zapytanie .= " ORDER BY a.link_date DESC";}
//$zapytanie = "SELECT * FROM link WHERE project_id='".$project_id."'";
//$zapytanietxt = file_get_contents("zapytanie.txt");
//echo "<br>".$zapytanie;
//echo "<br> wartość parametru sort wynosi ". $sort . " a wartość parametru up wynosi " . $up;
$rezultat = mysqli_query($polaczenie, $zapytanie);
$ile = mysqli_num_rows($rezultat); //liczba wierszy zapisana na potrzeby stronicowania

//w przypadku exportu linków trzeba wpisać dane z bazy do pliku csv lub pdf
if (isset($_POST['rap']))
{
	if ($_POST['rap'] == 1)
	{
		$fp = fopen('export_'.$project_id.'.csv', 'w');
		//wstawiamy wiersz z nazwami kolumn
		$title = "Adres linka;Adres linkowanej strony;Anchor;Źródło linka;Typ linka;Dodał;Status\n";
		fwrite($fp, $title);
		for ($i = 1; $i <= $ile; $i++) 
		{
			$row = mysqli_fetch_assoc($rezultat);
			$link_site = $row['link_site'];
			$name = $row['name'];
			$surname = $row['surname'];
			$site = $row['site'];
			$anchor = $row['anchor'];
			$linktype1 = $row['linktype1'];
			$linktype2 = $row['linktype2'];
			$linktype3 = $row['linktype3'];
			$status = $row['status'];
			$wsad = $link_site.";".$site.";".$anchor.";".$linktype1.";".$linktype2.";".$name." ".$surname.";".$status."\n";	
			//echo "<br />wsad - ".$wsad; 
			fwrite($fp, $wsad);	
		}	
		fclose($fp);
	// komunikat na stronę z linkiem do wygenerowanego pliku
		$_SESSION['csv_exp'] = "Twój wygenrowany plik csv jest <a href=\"export_".$project_id.".csv\" target=\"blank\">tutaj</a>";
	}	
}
?>
		
	<div class="content"  id="tab">
		<div class="info">
			<?php 
			if ($_SESSION['link_add']==true)
				{
					echo '<div class="success">Link został dodany</div>'; 
					unset($_SESSION['link_add']);
				}
				if ($_SESSION['link_check']==true)
				{
					echo '<div class="success">Link został sprawdzony</div>'; 
					unset($_SESSION['link_check']);
				}
				if ($_SESSION['link_update']==true)
				{
					echo '<div class="success">Link został zaktualizowany</div>'; 
					unset($_SESSION['link_update']);
				}
				
				if ($_SESSION['link_del']==true)
				{
					echo '<div class="success">Link został usunięty</div>'; 
					unset($_SESSION['link_del']);	
				}
				if ($_SESSION['e_link'])
				{
					echo '<div class="success">'.$_SESSION['e_link'].'</div>'; 
					unset($_SESSION['e_link']);
				}
				if ($_SESSION['csv_exp'])
				{
					echo '<div class="success">'.$_SESSION['csv_exp'].'</div>'; 
					unset($_SESSION['csv_exp']);
				}
			?>
		</div>

<div class="title"><h2>Linki dla projektu: <?php echo $project_name;?> </h2> </div>
<div class="rap">
	<form action="" method="post">
		<select type="int" name="rap">
			<OPTION value="1">Eksportuj do pliku CSV</OPTION>;
			<OPTION value="2">Eksportuj do pliku PDF</OPTION>;
		</select>
		<input type="submit" value="Generuj" />
	</form>
</div>
<div class="look">
	<form action="" method="post">
		<input type="text" name="look" placeholder="http://www" />
		<input type="submit" value="Wyszukaj linki" />
	</form>
</div>
<!--<div class="filter">
Filtrowanie wyników <a style="cursor: pointer; color: red;" onclick="rozwin('IDENTYFIKATOR')">(kliknij)</a>
<div class="h" id="IDENTYFIKATOR">
	<form action="" method="get">
		<input type="hidden" value="<?php echo $project_id;?>" name="id" />
		<p><label>Źródło linka:</label><select type="int" name="f1">
			<OPTION value=""></OPTION>
			<?php require_once 'dbconnect.php';
			$polaczenie1 = new mysqli($host, $user, $password, $database);
			$rezultat1 = $polaczenie1->query("SELECT * FROM linktype1");
			$ile1 = mysqli_num_rows($rezultat1);
			for ($i = 1; $i <= $ile1; $i++) 
			{		
				$row = mysqli_fetch_assoc($rezultat1);
				$linktype1_id = $row['linktype1_id'];
				$linktype1 = $row['linktype1'];
				echo '<OPTION value="' . $linktype1_id .  '">'. $linktype1 . '</OPTION>';
			}	
			$polaczenie1->close(); ?>
			</select><br /></p><br />
		<p><label>Typ linka:</label><select type="int" name="f2">
			<OPTION value=""></OPTION>
			<?php require_once 'dbconnect.php';
			$polaczenie1 = new mysqli($host, $user, $password, $database);
			$rezultat1 = $polaczenie1->query("SELECT * FROM linktype2");
			$ile1 = mysqli_num_rows($rezultat1);
			for ($i = 1; $i <= $ile1; $i++) 
			{		
				$row = mysqli_fetch_assoc($rezultat1);
				$linktype2_id = $row['linktype2_id'];
				$linktype2 = $row['linktype2'];
				echo '<OPTION value="' . $linktype2_id .  '">'. $linktype2 . '</OPTION>';
			}	
			$polaczenie1->close(); ?>
			</select><br /></p><br />
		<p><label>Follow linka:</label><select type="int" name="f3">
			<OPTION value=""></OPTION>
			<?php require_once 'dbconnect.php';
			$polaczenie1 = new mysqli($host, $user, $password, $database);
			$rezultat1 = $polaczenie1->query("SELECT * FROM linktype3");
			$ile1 = mysqli_num_rows($rezultat1);
			for ($i = 1; $i <= $ile1; $i++) 
			{		
				$row = mysqli_fetch_assoc($rezultat1);
				$linktype3_id = $row['linktype3_id'];
				$linktype3 = $row['linktype3'];
				echo '<OPTION value="' . $linktype3_id .  '">'. $linktype3 . '</OPTION>';
			}	
			$polaczenie1->close(); ?>
			</select><br /></p><br />
		<p><label>Status linka:</label><select type="int" name="f4">
			<OPTION value="" <?php if ($f4 == "") { echo 'selected="selected"';} ?>></OPTION>
			<OPTION value="aktywny" <?php if ($f4 == "aktywny") { echo 'selected="selected"';} ?> >Aktywny</OPTION>;
			<OPTION value="nie_aktywny" <?php if ($f4 == "nie aktywny") { echo 'selected="selected"';} ?> >Nie aktywny</OPTION>;
			}	
			$polaczenie1->close(); ?>
			</select><br /></p><br />
	<p><input type="submit" value="Zastosuj" /></p><br />
	</form>
</div>
</div>-->
<br />
<br />
<div class="tab">
<?php
echo '<table width="1100" align="center" bordercolor="#d5d5d5" cellpadding="0" cellspacing="0">';
echo '<tr>';

$num_pages = ceil($ile / $rpp); //określenie liczby stron
$zapytanie .=  " LIMIT $skip, $rpp"; //dopisujemy do wcześniejszego zapytania, klauzule LIMIT

//wykonanie kwerendy
$rezultat = mysqli_query($polaczenie, $zapytanie);

//echo "z bazy danych pobrano " . $ile . " wierszy<br />";
//echo "okresliłem limit ".$num_pages." stron <br />";
//echo "Zapytanie wygląda ".$zapytanie. "<br />";
//



if ($ile>=1) 
{
//echo '<td width="20" align="center" bgcolor="e5e5e5">Lp</td>';
//domyślnie będzie bez sortowania
//dla każdej kolumny po pierwszym kliknięciu będzie sortowanie ASC po danej kolumnie, 
//muszę dodać dwie zmienne $sort (w niej ukryta będzie informacja po której zmiennej sortujemy np s1 to sortujemy po pierwszej zmiennej czyli statusie) 
//i $up (w niej będzie informacja czy sortowanie jest ASC to up = 1 czy DESC to up = 0)
echo '<td width="80" align="center" bgcolor="e5e5e5">';
if ((!$sort) or ($sort !== s1)){echo '<a href="links.php?id='.$project_id.'&sort=s1&up=1">Status</a></td>';}
elseif (($sort == s1) and ($up == 1)){echo '<a href="links.php?id='.$project_id.'&sort=s1&up=0">Status</a><i class="material-icons">keyboard_arrow_up</i></td>';}
elseif (($sort == s1) and ($up == 0)){echo '<a href="links.php?id='.$project_id.'&sort=s1&up=1">Status</a><i class="material-icons">keyboard_arrow_down</i></td>';}
echo '<td width="170" align="center" bgcolor="e5e5e5">';
if ((!$sort) or ($sort !== s9)){echo '<a href="links.php?id='.$project_id.'&sort=s9&up=1">Data pozyskania linka</a></td>';}
elseif (($sort == s9) and ($up == 1)){echo '<a href="links.php?id='.$project_id.'&sort=s9&up=0">Status</a><i class="material-icons">keyboard_arrow_up</i></td>';}
elseif (($sort == s9) and ($up == 0)){echo '<a href="links.php?id='.$project_id.'&sort=s9&up=1">Status</a><i class="material-icons">keyboard_arrow_down</i></td>';}
echo '<td width="260" align="center" bgcolor="e5e5e5">';
if ((!$sort) or ($sort !== s2)){echo '<a href="links.php?id='.$project_id.'&sort=s2&up=1">Adres linka</a></td>';}
elseif (($sort == s2) and ($up == 1)){echo '<a href="links.php?id='.$project_id.'&sort=s2&up=0">Adres linka</a><i class="material-icons">keyboard_arrow_up</i></td>';}
elseif (($sort == s2) and ($up == 0)){echo '<a href="links.php?id='.$project_id.'&sort=s2&up=1">Adres linka</a><i class="material-icons">keyboard_arrow_down</i></td>';}
echo '<td width="260" align="center" bgcolor="e5e5e5">';
if ((!$sort) or ($sort !== s3)){echo '<a href="links.php?id='.$project_id.'&sort=s3&up=1">Linkowana strona</a></td>';}
elseif (($sort == s3) and ($up == 1)){echo '<a href="links.php?id='.$project_id.'&sort=s3&up=0">Linkowana strona</a><i class="material-icons">keyboard_arrow_up</i></td>';}
elseif (($sort == s3) and ($up == 0)){echo '<a href="links.php?id='.$project_id.'&sort=s3&up=1">Linkowana strona</a><i class="material-icons">keyboard_arrow_down</i></td>';}
echo '<td width="150" align="center" bgcolor="e5e5e5">';
if ((!$sort) or ($sort !== s4)){echo '<a href="links.php?id='.$project_id.'&sort=s4&up=1">Anchor text</a></td>';}
elseif (($sort == s4) and ($up == 1)){echo '<a href="links.php?id='.$project_id.'&sort=s4&up=0">Anchor text</a><i class="material-icons">keyboard_arrow_up</i></td>';}
elseif (($sort == s4) and ($up == 0)){echo '<a href="links.php?id='.$project_id.'&sort=s4&up=1">Anchor text</a><i class="material-icons">keyboard_arrow_down</i></td>';}
echo '<td width="80" align="center" bgcolor="e5e5e5">';
if ((!$sort) or ($sort !== s5)){echo '<a href="links.php?id='.$project_id.'&sort=s5&up=1">Dodał</a></td>';}
elseif (($sort == s5) and ($up == 1)){echo '<a href="links.php?id='.$project_id.'&sort=s5&up=0">Dodał</a><i class="material-icons">keyboard_arrow_up</i></td>';}
elseif (($sort == s5) and ($up == 0)){echo '<a href="links.php?id='.$project_id.'&sort=s5&up=1">Dodał</a><i class="material-icons">keyboard_arrow_down</i></td>';}
echo '<td width="80" align="center" bgcolor="e5e5e5">';
if ((!$sort) or ($sort !== s6)){echo '<a href="links.php?id='.$project_id.'&sort=s6&up=1">Follow</a></td>';}
elseif (($sort == s6) and ($up == 1)){echo '<a href="links.php?id='.$project_id.'&sort=s6&up=0">Follow</a><i class="material-icons">keyboard_arrow_up</i></td>';}
elseif (($sort == s6) and ($up == 0)){echo '<a href="links.php?id='.$project_id.'&sort=s6&up=1">Follow</a><i class="material-icons">keyboard_arrow_down</i></td>';}
echo '<td width="80" align="center" bgcolor="e5e5e5">';
if ((!$sort) or ($sort !== s7)){echo '<a href="links.php?id='.$project_id.'&sort=s7&up=1">Typ linka</a></td>';}
elseif (($sort == s7) and ($up == 1)){echo '<a href="links.php?id='.$project_id.'&sort=s7&up=0">Typ linka</a><i class="material-icons">keyboard_arrow_up</i></td>';}
elseif (($sort == s7) and ($up == 0)){echo '<a href="links.php?id='.$project_id.'&sort=s7&up=1">Typ linka</a><i class="material-icons">keyboard_arrow_down</i></td>';}
echo '<td width="150" align="center" bgcolor="e5e5e5">';
if ((!$sort) or ($sort !== s8)){echo '<a href="links.php?id='.$project_id.'&sort=s8&up=1">Źródło linka</a></td>';}
elseif (($sort == s8) and ($up == 1)){echo '<a href="links.php?id='.$project_id.'&sort=s8&up=0">Źródło linka</a><i class="material-icons">keyboard_arrow_up</i></td>';}
elseif (($sort == s8) and ($up == 0)){echo '<a href="links.php?id='.$project_id.'&sort=s8&up=1">Źródło linka</a><i class="material-icons">keyboard_arrow_down</i></td>';}
echo '<td width="170" align="center" bgcolor="e5e5e5">Akcja</td>';
echo '</tr>';
}
else
{
	echo "<div class=\"info\">";
	echo '<div class="success">Nieznaleziono żadnych linków</div>';
	echo "</div>";	
}	
if ($cur_page<$num_pages ){
$wynik = $rpp;
}
elseif ($cur_page==$num_pages)
 {$wynik = $ile - $skip;}

	for ($i = 1; $i <= $wynik; $i++) 
	{
		$row = mysqli_fetch_assoc($rezultat);
		$id = $row['id'];
		$name = $row['name'];
		$surname = $row['surname'];
		$link_site = $row['link_site'];
		$site = $row['site'];
		$anchor = $row['anchor'];
		$linktype1 = $row['linktype1'];
		$linktype2 = $row['linktype2'];
		$linktype3 = $row['linktype3'];
		$status = $row['status'];
		$link_date = $row['link_date'];
		
//echo '<td width="20" align="center">'.$id.'</td>';
if ($status == "aktywny") {echo '<tr><td width="80" align="center"><i class="material-icons" title="'.$status.'" style="color: green;">sentiment_very_satisfied</i></td>';}
elseif ($status == "nie aktywny") {echo '<tr><td width="80" align="center"><i class="material-icons" title="'.$status.'" style="color: red;">sentiment_very_dissatisfied</i></td>';}
elseif ($status == "") {echo '<tr><td width="80" align="center"><i class="material-icons" title="'.$status.'" style="color: yellow;">sentiment_neutral</i></td>';}

echo '<td width="170" align="center">'.$link_date.'</td>';

//echo '<td width="100" align="center">'.$status.'</td>';
echo '<td width="260" align="center"><a target="_blank" href="'.$link_site.'" title="'.$link_site.'">'.short($link_site,30).'</a></td>';
echo '<td width="260" align="center"><a target="_blank" href="'.$site.'" title="'.$site.'">'.short($site,30).'</a></td>';
$anchor1 = mb_convert_encoding($anchor, "UTF-8", "iso-8859-2");
echo '<td width="150" align="center">'.$anchor1.'</td>';
echo '<td width="80" align="center"><i class="material-icons" title="'.$name.' '.$surname.'">person</i></td>';

if ($linktype3 == "dofollow") {echo '<td width="80" align="center"><i class="material-icons" title="'.$linktype3.'" style="color: green;">thumb_up</i></td>';}
elseif ($linktype3 == "nofollow") {echo '<td width="80" align="center"><i class="material-icons" title="'.$linktype3.'" style="color: red;">thumb_down</i></td>';}
elseif (($linktype3 == "brak danych")||($linktype3 == NULL)) {echo '<td width="80" align="center"><i class="material-icons" title="'.$linktype3.'" style="color: black;">clear</i></td>';}

if ($linktype2 == "tekstowy") {echo '<td width="80" align="center"><i class="material-icons" title="'.$linktype2.'">text_fields</i></td>';}
elseif ($linktype2 == "graficzny") {echo '<td width="80" align="center"><i class="material-icons" title="'.$linktype2.'">image</i></td>';}
elseif ($linktype2 == "przekierowanie") {echo '<td width="80" align="center"><i class="material-icons" title="'.$linktype2.'">redo</i></td>';}

echo '<td width="150" align="center">'.$linktype1.'</td>';
echo '<td width="170" align="center"><a href="link_edit.php?id='.$project_id.'&link_id='.$id.'"><i class="material-icons" title="Edytuj link" style="color: green;">edit</i></a><a href="link_check.php?link_id='.$id.'"><i class="material-icons" title="Sprawdź link" style="color: blue;">offline_bolt</i></a><a href="link_del.php?id='.$project_id.'&link_id='.$id.'" onclick="return confirm(\'Czy na pewno usunąć?\');"><i class="material-icons" title="Usuń link" style="color: red;">cancel</i></a></td>';
echo '</tr>';
	}
?>
</table>
</div>
<!--	<div style="clear:both;"></div> -->
	<div class="page">
		<?php
		//wyświetlanie nawigacji przy stronnicowaniu i możliwości wyboru ilości wynikó na stronie
			echo '<div><label>Linków na stronie </label>';
			echo number_of_links($project_id, $sort, $up, $f1, $f2, $f3, $f4, $rpp);
			echo '</div>';
		if ($num_pages > 1)
		{	
			echo '<div>';
			echo generate_page_links($cur_page, $num_pages, $project_id, $sort, $up, $f1, $f2, $f3, $f4, $rpp);
			echo '</div>';
		}?>
			<fieldset class="legend">
				<legend>Legenda</legend>
				<label>Status linka: <i class="material-icons" title="'.$status.'" style="color: green;">sentiment_very_satisfied</i>- aktywny, <i class="material-icons" title="'.$status.'" style="color: yellow;">sentiment_very_satisfied</i>- nie sprawdzowny, <i class="material-icons" title="'.$status.'" style="color: red;">sentiment_very_satisfied</i>- nie aktywny</label>
				<label><i class="material-icons" title="Imię Nazwisko">person</i>- kto wprowadził link</label>
				<label><i class="material-icons" style="color: green;">thumb_up</i>- dofollow, <i class="material-icons" style="color: red;">thumb_down</i>- nofollow, <i class="material-icons" style="color: black;">clear</i>- brak danych</label>
				<label>Typ linka: <i class="material-icons">text_fields</i>- tekstowy, <i class="material-icons">image</i>- graficzny, <i class="material-icons">redo</i>- przekierowanie</label>
				<label><i class="material-icons" title="Edytuj projekt" style="color: green;">edit</i>- edytuj</label>
				<label><i class="material-icons" title="Sprawdź link" style="color: blue;">offline_bolt</i>- szybkie sprawdzenie</label>
				<label><i class="material-icons" title="Usuń projekt" style="color: red;">cancel</i>- usuń</label>
			</fieldset>
	</div>
	</div>
	</div>
	</div>
		<div class="footer">Link Checker &copy; 2018. Wszelkie prawa zastrzeżone GDAQ.PL Multimedia Sławomir Gdak.</div>
	</body>
</html>
<?php ob_end_flush();?>