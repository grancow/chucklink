<?php
	session_start();
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Seosoft - Linkchecker</title>
	<meta name="description" content="Serwis o" />
	<meta name="keywords" content="" />
	<link href="style.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<!--<link rel="stylesheet" href="/resources/demos/style.css">-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script src="http://www.zyciebezglutenu.pl/linkcheck/js/jquery-ui.js"></script>
	<script language="javascript" type="text/javascript">
		function rozwin(co){
			with(document.getElementById(co)){className=className=='h'?'v':'h';}
							}
	</script>
	<script>
			$( function() {
			$( "#datepicker" ).datepicker();
			} );
			$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );
	</script>
	<script src='http://cdn.intum.com/10072/widget.js' type='text/javascript'></script>
</head>
<body>
<div class="conteiner">	
	<div class="wrapper">
		<div class="nav">
			<ol>
				<li><a href="projects.php"><i class="material-icons">view_list</i>Projekty</a></li>
				<?php if (($_SESSION['priviliges'] == 1) || ($_SESSION['priviliges'] == 2))
				{
				echo '<li><a href="users.php"><i class="material-icons">supervisor_account</i>Użytkownicy</a></li>';
				}?>	
				<li><a href="link_add.php"><i class="material-icons">add_box</i>Dodaj link</a></li>
				<li><a href="links_add.php"><i class="material-icons">queue</i>Import linków</a></li>
				<li><a href="links_check.php"><i class="material-icons">offline_bolt</i>Sprawdź linki</a></li>
				<li><a href="user_edit.php?id=<?php echo $_SESSION['user_id']; ?>"><i class="material-icons">person</i>Moje konto</a></li>
				<li><a href="logout.php"><i class="material-icons">input</i>Wyloguj</a></li>
			</ol>
		</div>