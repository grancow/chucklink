<?php 
//tworzymy klasę
 
class funkcje{
 
// połączenie z bazą danych
function connect_bd(){
 $result= new mysqli('localhost', 'srv21221_lcuser', 'PHNFIlokJP', 'srv21221_linkcheck');
 if (mysqli_connect_errno() === 0){
	$result -> query("SET NAMES 'utf8'");
   if (!$result) return false;
	else { 	return $result;	}
	}
  }
 
//funkcja zapisująca pojedynczy wynik z bazy do tablicy
public function get_single_shot($quest){
		$connect=$this->connect_bd();
		$result=$connect->query($quest);
		if (!$result){echo "blad w get single shot <br> w zapytaniu: ".$quest."<br>"; return false;}
		if ($result->num_rows>0)
		{
			$result_array=@$result->fetch_assoc();
			return $result_array;
		}
		else {
		return 0;
		}
	}
//zamyka klase
}
?>