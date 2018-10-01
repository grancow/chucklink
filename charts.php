<?php 
include('header.php');

	//pobierz id projektu
	$project_id = $_GET['id'];
	//pobranie danych do wykresu: nazwę projektu i dane do projektu
	ini_set("display_errors", 0);
	require_once 'dbconnect.php';
	$polaczenie = mysqli_connect($host, $user, $password);
	mysqli_select_db($polaczenie, $database);
	//pobieram nazwę projektu
	$zapytanie = "SELECT project_name FROM project WHERE project_id = '".$project_id."'";	
	$rezultat = mysqli_query($polaczenie, $zapytanie);
	$wiersz = mysqli_fetch_assoc($rezultat);
	$project_name = $wiersz['project_name'];
	 
	//podanie domyślnych dat dla wykresu
	$to_date = date("Y-m-d");	
	$from_date = date("Y-m-d", strtotime( "$to_date -1 month" ));
	$wykres = array();
	//generowanie wykresu ze zmienionymi danymi
	if (isset($_POST['from_date']))
	{
		// zczytaj dane z formularza
		$to_date = $_POST['to_date'];
		$from_date = $_POST['from_date'];
		$wykres=$_POST['wykres'];
		$zapytanie2 = "SELECT * FROM charts WHERE date >= '".$from_date."' AND date <= '".$to_date."' AND project_id = '".$project_id."' ORDER BY date ASC";
	}
	elseif (!isset($_POST['from_date']))
	{
		$zapytanie2 = "SELECT * FROM charts WHERE date >= '".$from_date."' AND date <= '".$to_date."' AND project_id = '".$project_id."' ORDER BY date ASC";
	}
	$rezultat = mysqli_query($polaczenie, $zapytanie2);
	$ile = mysqli_num_rows($rezultat); 

	//wykonanie kwerendy
	//$rezultat = mysqli_query($polaczenie, $zapytanie2);
	// trzeba wkleić warunek sprawdzający czy ktoś zmienił w firmuarzu, jeżeli nie to domyślne warotść wyświetlania
		for ($i = 1; $i <= $ile; $i++) 
		{
		$row = mysqli_fetch_assoc($rezultat);
		$chart_table[$i][0] = $row['date'];
		$chart_table[$i][1] = $row['Link_Active'];	
		$chart_table[$i][2] = $row['LT1_Active'];
		$chart_table[$i][3] = $row['LT2_Active'];
		$chart_table[$i][4] = $row['LT3_Active'];
		$chart_table[$i][5] = $row['LT4_Active'];
		$chart_table[$i][6] = $row['LT5_Active'];
		$chart_table[$i][7] = $row['LT6_Active'];
		$chart_table[$i][8] = $row['LT7_Active'];
		$chart_table[$i][9] = $row['LT8_Active'];		
		}
?>
<!--	<script src="chart_nav.js"></script> -->
	<script type="text/javascript">
		google.charts.load('current', {packages: ['corechart', 'line']});
		google.charts.setOnLoadCallback(drawChart);
	  
		function drawChart() {
		var data = new google.visualization.DataTable();
		data.addColumn('date', 'Data');
	  <?php	
		// wypisuje tabelę dla wykresu
		//if (in_array("all",$wykres)||(!$wykres)) {echo "	data.addColumn('number', 'wszystkie');\n";}
		if (in_array("lt1",$wykres)||(!$wykres)) {echo "	data.addColumn('number', 'artykuł gościnny');\n";}
		if (in_array("lt2",$wykres)) {echo "	data.addColumn('number', 'serwis ogłoszeniowy');\n";}
		if (in_array("lt3",$wykres)) {echo "	data.addColumn('number', 'marketing szeptany');\n";}
		if (in_array("lt4",$wykres)) {echo "	data.addColumn('number', 'komentarz na blogu');\n";}
		if (in_array("lt5",$wykres)) {echo "	data.addColumn('number', 'komentarz na forum');\n";}
		if (in_array("lt6",$wykres)) {echo "	data.addColumn('number', 'profil');\n";}
		if (in_array("lt7",$wykres)) {echo "	data.addColumn('number', 'sidebar');\n";}
		if (in_array("lt8",$wykres)) {echo "	data.addColumn('number', 'inny');\n";}
		echo "data.addRows([\n";
		for ($i = 1; $i <= $ile; $i++) 
		{ 
			//muszę wyłuskać datę
			$time = $chart_table[$i][0];
			$formatted_date = strtotime( $time );
			$year = date('Y', $formatted_date );
			$month = date('m', $formatted_date );
			$day = date('d', $formatted_date );
			echo "[new Date(".$year.", ".$month.", ".$day.")";
			//teraz wstawiam posczególne dane dla wykresu
			//if (in_array("all",$wykres)||(!$wykres)) {echo ",". $chart_table[$i][1];}
			if (in_array("lt1",$wykres)||(!$wykres)) {echo ",". $chart_table[$i][2];}
			if (in_array("lt2",$wykres)) {echo ",". $chart_table[$i][3];}
			if (in_array("lt3",$wykres)) {echo ",". $chart_table[$i][4];}
			if (in_array("lt4",$wykres)) {echo ",". $chart_table[$i][5];}
			if (in_array("lt5",$wykres)) {echo ",". $chart_table[$i][6];}
			if (in_array("lt6",$wykres)) {echo ",". $chart_table[$i][7];}
			if (in_array("lt7",$wykres)) {echo ",". $chart_table[$i][8];}
			if (in_array("lt8",$wykres)) {echo ",". $chart_table[$i][9];}
			echo "],\n";
		}?>
        ]);
		
		var formatter1 = new google.visualization.DateFormat({pattern: "dd/MM/yyyy"});
		var formatter_short = new google.visualization.DateFormat({formatType: 'short'});
		formatter1.format(data, 0);
		
		var options = {
		hAxis: { 
				format:'dd/MM/yyyy', 
				textStyle: { fontSize: 12}},
		vAxis: { 
				textStyle: { fontSize: 12}},
		legend: { position: 'right'},
		legendTextStyle: { fontSize: 12},
		width: 1170,
		height: 500,
		colors: ['#2308f1', '#097138', '#a52714', '#f6ff09', '#dd0aea', '#06d4f9', '#ffc521', '#9b00ed', '#36b03c' ],
		chartArea: {left:100,top:50,width:'80%',height:'80%'}
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
	</script>
	<div class="content">
		<div class="info">
			<div id="info"></div>
		</div>
		<h2>Wykresy dla projektu: <?php echo $project_name; ?></h2>
	<form method="post">
	<div class="chart_nav_date">
		<label>Od:<input type="data" id="datepicker1" name="from_date" value="<?php echo $from_date; ?>"></label><label>Do:<input type="text" id="datepicker2" name="to_date" value="<?php echo $to_date; ?>"></label>
	<!--	<div class="c10">
			<label>Pozyskane linki<input type="radio" name="rodzaj" value="all" <?php if (in_array("all",$wykres)||(!$wykres)){ echo "checked='checked'";}?>></label>
		</div>
		<div class="c11">
			<label>Status linków<input type="radio" name="rodzaj" value="lt1" ></label>
		</div>	-->		
		
	</div>
	<div class="chart_nav">
<!--		<div class="c1">
			<label>Wyszystkie linki<input type="checkbox" name=wykres[] value="all" <?php if (in_array("all",$wykres)||(!$wykres)){ echo "checked='checked'";}?>></label>
		</div> -->
		<div class="c2">
			<label>Artykuł gościnne<input type="checkbox" name=wykres[] value="lt1" <?php if (in_array("lt1",$wykres)||(!$wykres)){ echo "checked='checked'";}?>></label>
		</div>
		<div class="c3">
			<label>Serwis ogłoszeniowy<input type="checkbox" name=wykres[] value="lt2" <?php if (in_array("lt2",$wykres)){ echo "checked='checked'";}?>></label>
		</div>
		<div class="c4">
			<label>Marketing szeptany<input type="checkbox" name=wykres[] value="lt3" <?php if (in_array("lt3",$wykres)){ echo "checked='checked'";}?>></label>
		</div>
		<div class="c5">
			<label><input type="checkbox" name=wykres[] value="lt4" <?php if (in_array("lt4",$wykres)){ echo "checked='checked'";}?>/>Komentarz na blogu</label>
		</div>
		<div class="c6">
			<label>Komentarz na forum<input type="checkbox" name=wykres[] value="lt5" <?php if (in_array("lt5",$wykres)){ echo "checked='checked'";}?>></label>
		</div>
		<div class="c7">
			<label>Profil<input type="checkbox" name=wykres[] value="lt6" <?php if (in_array("lt6",$wykres)){ echo "checked='checked'";}?>></label>
		</div>
		<div class="c8">
			<label>Sidebar<input type="checkbox" name=wykres[] value="lt7" <?php if (in_array("lt7",$wykres)){ echo "checked='checked'";}?>></label>
		</div>
		<div class="c9">
			<label><input type="checkbox" name=wykres[] value="lt8" <?php if (in_array("lt8",$wykres)){ echo "checked='checked'";}?>/>Inny</label>
		</div>
		<input type="submit" value="Pokaż wykres" style="float: left;" />
	</div>
</form>
	<div id="chart_div" style="width: 1170px; height: 500px; clear: both;"></div>
	
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