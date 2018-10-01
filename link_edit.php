<?php 
	include('header.php'); 
	$id = $_GET['link_id'];
	ini_set("display_errors", 0);
	require_once 'dbconnect.php';
	$polaczenie = new mysqli($host, $user, $password, $database);
	$rezultat = $polaczenie->query("SELECT * FROM link WHERE id='$id'");
	$row = mysqli_fetch_assoc($rezultat);
	$project_id = $row['project_id'];
	$link_site = $row['link_site'];
	$site = $row['site'];
	$anchor = $row['anchor'];
	$linktype1_id_val = $row['linktype1_id'];
	$linktype2_id_val = $row['linktype2_id'];
	$linktype3_id_val = $row['linktype3_id'];
	$link_date = $row['link_date'];
	$link_cost = $row['link_cost'];
	$link_expiration = $row['link_expiration'];
	$link_reminder = $row['link_reminder'];
	$first_name = $row['first_name'];
	$last_name = $row['last_name'];
	$email = $row['email'];
	$phone = $row['phone'];
	$info = $row['info'];
	$status = $row['status'];
	$aktualizacja = $row['aktualizacja'];
	$polaczenie1 = new mysqli($host, $user, $password, $database);
	$rezultat1 = $polaczenie1->query("SELECT project_name FROM project WHERE project_id='$project_id'");
	$row1 = mysqli_fetch_assoc($rezultat1);
	$project_name = $row1['project_name'];
	$polaczenie->close();	
?>
	<div class="content">		
			<h2>Edycja linka</h2>
			<form method="post" action="link_update.php?id=<?php echo $id; ?>">
			<div class="left_box">
				<p><label>Nazwa projektu:</label><input type="text" name="project_name" value="<?php echo $project_name;?>" disabled="disabled" /><br /></p>
				<input type="hidden" name="project_id" value="<?php echo $project_id;?>" />
				<p><label>Adres linka:</label><input type="url" name="link_site" value="<?php echo $link_site;?>" disabled="disabled" /><br /></p>
				<p><label>Adres linkowanej strony:</label><input type="url" name="site" value="<?php echo $site;?>" disabled="disabled" /><br /></p>
				<p><label>Źródło linka:</label><select type="int" name="linktype1_id">
									<?php require_once 'dbconnect.php';
										$polaczenie = new mysqli($host, $user, $password, $database);
										$rezultat = $polaczenie->query("SELECT * FROM linktype1");
										$ile = mysqli_num_rows($rezultat);
										for ($i = 1; $i <= $ile; $i++) 
										{		
											$row = mysqli_fetch_assoc($rezultat);
											$linktype1_id = $row['linktype1_id'];
											$linktype1 = $row['linktype1'];
											if ($linktype1_id == $linktype1_id_val) {echo '<OPTION value="' . $linktype1_id .  '" selected="selected">'. $linktype1 . '</OPTION>';}	
											else {echo '<OPTION value="' . $linktype1_id .  '">'. $linktype1 . '</OPTION>';}
										}	
										$polaczenie->close(); ?>
								</select><br /></p>
				<p><label>Typ linka:</label><select type="text" name="linktype2_id">
									<?php require_once 'dbconnect.php';
										$polaczenie = new mysqli($host, $user, $password, $database);
										$rezultat = $polaczenie->query("SELECT * FROM linktype2");
										$ile = mysqli_num_rows($rezultat);
										for ($i = 1; $i <= $ile; $i++) 
										{		
											$row = mysqli_fetch_assoc($rezultat);
											$linktype2_id = $row['linktype2_id'];
											$linktype2 = $row['linktype2'];
											if ($linktype2_id == $linktype2_id_val) {echo '<OPTION value="' . $linktype2_id .  '" selected="selected">'. $linktype2 . '</OPTION>';}
											else {echo '<OPTION value="' . $linktype2_id .  '">'. $linktype2 . '</OPTION>';}
										}	
										$polaczenie->close(); ?>
								</select><br /></p>
				<p><label>Anchor linka:</label> <?php echo $anchor;?></p>
				<p><label>Data pozyskania linka:</label><input type="text" id="datepicker1" name="link_date" value="<?php echo $link_date;?>" /><br /></p>
				<p><label>Koszt pozyskania linka (PLN):</label><input type="float" name="link_cost" value="<?php echo $link_cost;?>" /><br /></p>
				<p><label>Data ważności linka:</label><input type="text" id="datepicker2" name="link_expiration" value="<?php echo $link_expiration;?>"><br /></p>
				<p><label>Przypomnienie o ważności linka:</label><input type="checkbox" name="link_reminder" value="1" <?php if($link_reminder==1) echo "checked";?>/></p>
				<p><label>Status linka:</label><input type="text" name="status" value="<?php echo $status;?>" disabled="disabled" /><br /></p>
				<p><label>Ostatnia aktualizacja statusu:</label><input type="text" name="aktualizacja" value="<?php echo $aktualizacja;?>" disabled="disabled" /><br /></p>
			</div>
			<div class="right_box">		
				<p>Dane kontaktowe do osoby, która opublikowała link (dane opcjonalne):<br /></p>
				<p><label>Imię:</label><input type="text" name="first_name" value="<?php echo $first_name;?>" /></p>
				<p><label>Nazwisko:</label><input type="text" name="last_name" value="<?php echo $last_name;?>" /></p>
				<p><label>E-mail:</label><input type="text" name="email" value="<?php echo $email;?>" /></p>
				<p><label>Telefon:</label><input type="text" name="phone" value="<?php echo $phone;?>" /><br /></p>
				<p><label>Dodatkowe informacje:</label><input type="text" name="info" value="<?php echo $info;?>" /><br /></p>
				<input type="submit" value="Aktualizuj dane" />
				</form>				
				<p><a class="button" href="link_del.php?id=<?php echo $project_id;?>&link_id=<?php echo $id;?>" onclick="return confirm('Czy na pewno usunąć?')">Usuń link</a></p>
			</div>
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
<?php ob_end_flush();?>