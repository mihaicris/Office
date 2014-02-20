<?php
include('conexiune.php');

function afiseaza_rezultate($query, $count)
{
	echo '<table class="rezultate">';
	echo "<tr>";
	echo '<th>ID</th>';
	echo '<th>Cod produs</th>';
	echo '<th>Titlu</th>';
	echo '<th class="pret">Preț</th>';
	echo "</tr>";
	for ($i = 0; $row = $query->fetch(); $i++) {
		echo '<tr>';
		echo '<td id="f' . $row['id_produs'] . '"class="id actiune">' . $row['id_produs'] . '</td>';
		echo '<td class="cod">' . $row['cod_produs'] . '</td>';
		echo '<td class="titlu">' . $row['titlu_produs'] . '</td>';
		echo '<td class="pret">' . $row['pret_produs'] . '</td>';
		echo "</tr>";
	} //end for
	echo "</table>";
	echo '<table>';
	echo "<tr>";
	echo '<td class="total">' . $count;
	if ($count == 1) {
		echo " produs";
	} else {
		echo " produse";
	}
	echo "</td>";
	echo "</tr>";
	echo "<table>";
}

//=======================================================================================================================
// cautare produse in baza de date
if (isset($_POST["camp_str"])) {

	echo '<p>de facut</p>';

	exit();
} // end if
?>
	<h2>Listă produse</h2>
	<form action="/" method="post" id="submit">
		<label for="camp">Caută</label><br>
		<input class="scurt" id="camp" type="text" name="camp_str" autocomplete="off"/>
		<span id="produs_nou" class="submit nou">
			Crează un produs nou
		</span>
	</form>
<?php
$string = 'SELECT COUNT(*)
           FROM produse
           LIMIT 1;';
$query = $db->query($string);
$query->execute();
$count = $query->fetchColumn();
if (!$count) { //daca nu sunt rezultate se iese cu mesaj
	// echo "<h2>Rezultate căutare</h2>";
	echo '<p>Nu există în baza de date.</p>';
	exit();
}
// interogarea adevarata pentru rezultate (daca nu s-a iesit mai sus)
$string = 'SELECT *
           FROM produse
           ORDER BY cod_produs ASC;';
$query = $db->query($string);
$query->execute();
afiseaza_rezultate($query, $count);
exit();
?>