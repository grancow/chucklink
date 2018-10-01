
<?php
if (isset($_POST['info']))
{
	$pole = $_POST['info'];
	$pole = explode("\n",trim($pole));
	echo "<br />Wprowadziłeś do sprawdzenia następujacy link: " . $_POST['site'];
	$sizeArray = sizeof($pole);
	echo "<br />ilość lini w polu textarea wynosi - " . $sizeArray;
	echo "<br /> zaś w polu tekstowym wprowadziłeś następujące dane:";
	for ($n = 0; $n < $sizeArray; $n++) 
		{echo "<br />".$pole[$n];}
	echo "<br /> to bybyło na tyle";
	}
?>
<form method="post">
<p><label>Adres linkowanej strony*:</label><input type="url" name="site" placeholder="http://www" /><br /></p>
<p><label>Dodatkowe informacje:</label><textarea name="info" cols="70" rows="15"> </textarea><br /></p>

<input type="submit" value="Dodaj link" />
</form>
