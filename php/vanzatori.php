<?php
include('conexiune.php');

function verifica_existenta_vanzator($id, $nume_vanzator, $prenume_vanzator)
{
// testeaza existenta  in baza de date
// daca se apeleaza cu $id null inseamna se testeaza un vanzator nou

	$data = array($nume_vanzator, $prenume_vanzator);
	$string = 'SELECT `id_vanzator`
                FROM `vanzatori`
                WHERE (`nume_vanzator` = ? AND `prenume_vanzator` = ?);';
	$query = interogare($string, $data);
	$result = $query->fetch();
	if ($result['id_vanzator'] && $result['id_vanzator'] != $id) {
		// daca exista un rezultat si acesta este diferit de $id atunci exista
		// daca $id este null (creare) atunci la orice rezultat care nu este null inseamna ca exista
		echo('exista');
		exit();
	};
	return;
}

function afiseaza_rezultate($query)
{
	echo '<table class="rezultate">';
	echo '<tr>';
	echo '<th class="">Acțiuni</th>';
	echo '<th>Nume și prenume</th>';
	echo '<th>Activ</th>';
	echo '</tr>';
	for ($i = 0; $row = $query->fetch(); $i++) {
		echo '<tr>';
		echo '<td class="align_center" id="f' . $row['id_vanzator'] . '"><span class="id"></span><span title="Editează" class="sosa actiune">a</span></td>';
		echo '<td class="nume">'
			. $row['nume_vanzator'] . ' ' . $row['prenume_vanzator'] . '</td>';
		echo $row['activ'] ? '<td class="align_center companie">DA' : '<td class="align_center id">NU';
		echo '</td>';
		echo '</tr>';
	} //end for
	echo "</table>";
}

function afiseaza_numar_total($count)
{
	echo '<span class="total">' . $count;
	if ($count == 1) {
		echo " vânzător";
	} else {
		echo " vânzători";
	}
	echo "</span>";
}

if (isset($_POST["optiuni"]["formular_creare"])) {
	//  Formular creeare vanzator nou
	?>
	<h2>Creare vânzător</h2>
	<form class="formular" action="/" method="post" id="creare_vanzator">
		<input id="id_vanzator" type="hidden" name="id_vanzator" value=""/>
		<table>
			<tbody>
			<tr>
				<td>
					<label for="nume_vanzator">Nume</label>
					<input class="normal mediu"
						   id="nume_vanzator"
						   type="text"
						   name="nume_vanzator"
						   autocomplete="off"/>
				</td>
				<td>
					<label for="prenume_vanzator">Prenume</label>
					<input class="normal mediu"
						   id="prenume_vanzator"
						   type="text"
						   name="prenume_vanzator"
						   autocomplete="off"
						/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="mobil_vanzator">Telefon Mobil</label>
					<input class="normal mediu"
						   id="mobil_vanzator"
						   type="text"
						   name="mobil_vanzator"
						   autocomplete="off"/>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<label for="tel_vanzator">Telefon fix</label>
					<input class="normal mediu"
						   id="tel_vanzator"
						   type="text"
						   name="tel_vanzator"
						   autocomplete="off"/>
				</td>
				<td>
					<label for="fax_vanzator">Fax</label>
					<input class="normal mediu"
						   id="fax_vanzator"
						   type="text"
						   name="fax_vanzator"
						   autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="email_vanzator">Adresă de email</label>
					<input class="normal mediu"
						   id="email_vanzator"
						   type="text"
						   name="email_vanzator"
						   autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="activ">Vânzător activ</label>
					<input id="activ"
						   name="activ"
						   type="checkbox"
						   checked/>
				</td>
			</tr>
			</tbody>
		</table>
	</form>
	<span id="creaza_vanzator" class="submit">Salvează<span class="sosa">å</span></span>
	<span id="renunta" class="buton_renunta">Renunță<span class="sosa">ã</span></span>
	<?php
	exit();
}
if (isset($_POST["optiuni"]["formular_editare"])) {
	// editeaza vanzator din baza de date
	$id = $_POST["id"];
	$string = 'SELECT *
               FROM vanzatori
               WHERE id_vanzator = ?
               LIMIT 1;';
	$data = array($id);
	$query = interogare($string, $data);
	$row = $query->fetch(PDO::FETCH_ASSOC);
	if (count($row) == 1) {
		echo("Inexistent");
		exit();
	}
	?>
	<h2>Modificare vânzător</h2>
	<form class="formular" action="/" method="post">
		<input id="id_vanzator"
			   type="hidden"
			   name="id_vanzator"
			   value="<?php echo $row['id_vanzator']; ?>"/>
		<table>
			<tbody>
			<tr>
				<td>
					<label for="nume_vanzator">Nume</label>
					<input class="normal mediu"
						   id="nume_vanzator"
						   type="text"
						   name="nume_vanzator"
						   value="<?php echo $row["nume_vanzator"]; ?>"
						   autocomplete="off"/>
				</td>
				<td>
					<label for="prenume_vanzator">Prenume</label>
					<input class="normal mediu"
						   id="prenume_vanzator"
						   type="text"
						   name="prenume_vanzator"
						   value="<?php echo $row["prenume_vanzator"]; ?>"
						   autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="mobil_vanzator">Telefon Mobil</label>
					<input class="normal mediu"
						   id="mobil_vanzator"
						   type="text"
						   name="mobil_vanzator"
						   value="<?php echo $row["mobil_vanzator"]; ?>"
						   autocomplete="off"/>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<label for="tel_vanzator">Telefon fix</label>
					<input class="normal mediu"
						   id="tel_vanzator"
						   type="text"
						   name="tel_vanzator"
						   value="<?php echo $row["tel_vanzator"]; ?>"
						   autocomplete="off"/>
				</td>
				<td>
					<label for="fax_vanzator">Fax</label>
					<input class="normal mediu"
						   id="fax_vanzator"
						   type="text"
						   name="fax_vanzator"
						   value="<?php echo $row["fax_vanzator"]; ?>"
						   autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="email_vanzator">Adresă de email</label>
					<input class="normal mediu"
						   id="email_vanzator"
						   type="text"
						   name="email_vanzator"
						   value="<?php echo $row["email_vanzator"]; ?>"
						   autocomplete="off"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="activ">Vânzător activ</label>
					<input id="activ"
						   name="activ"
						   type="checkbox"
						<?php
						if ($row["activ"]) {
							echo "checked";
						}
						?>
						/>
				</td>
			</tr>
			</tbody>
		</table>
	</form>
	<span id="editeaza_vanzator" class="submit">Modifică<span class="sosa">å</span></span>
	<span id="sterge" class="buton_stergere">Șterge<span class="sosa">ç</span></span>
	<span id="renunta" class="buton_renunta">Renunță<span class="sosa">ã</span></span>
	<?php
	exit();
}

if (isset($_POST["salveaza"])) {
	$data = $_POST["formdata"];
	if ($_POST["salveaza"]) { // 1-creaza | 0-modifica
		verifica_existenta_vanzator(null, $data[1], $data[2]);
		$string = 'INSERT INTO `vanzatori`
                       (`nume_vanzator`,
                        `prenume_vanzator`,
                        `tel_vanzator`,
                        `fax_vanzator`,
                        `mobil_vanzator`,
                        `email_vanzator`,
                        `activ`,
                        `id_vanzator`)
		           VALUES (?, ?, ?, ?, ?, ?, ?, NULL);';
		array_shift($data);
	} else {
		verifica_existenta_vanzator($data[0], $data[1], $data[2]); // data[0] contine id-ul vanzatorului care se modifica
		$string = 'UPDATE `vanzatori`
                   SET  `nume_vanzator` = ?,
                        `prenume_vanzator` = ?,
						`tel_vanzator` = ?,
                        `fax_vanzator` = ?,
                        `mobil_vanzator` = ?,
                        `email_vanzator` = ?,
                        `activ` = ?
                   WHERE `id_vanzator` = ?;';
		array_push($data, array_shift($data));
	}
	$query = interogare($string, $data);
	echo('ok');
	exit();
}
if (isset($_POST["sterge"])) {
	// actiune stergere vanzator in baza de date
	$data = array($_POST["id"]);
	$string = 'DELETE FROM `vanzatori` WHERE `vanzatori`.`id_vanzator` = ? LIMIT 1;';
	$query = interogare($string, $data);
	echo('ok');
	exit();
}

if (isset($_POST["select_vanzator"])) {
	// alegere vanzator din baza de date in formulare
	// prima interogare pentru numarare rezultate
	$string = 'SELECT COUNT(*)
					FROM `vanzatori`
					ORDER BY `nume_vanzator` ASC
					LIMIT 1;';
	$query = interogare($string, NULL);
	//daca nu sunt rezultate se iese cu mesaj
	$count = $query->fetchColumn();
	if (!$count) {
		echo '<p class="noresults"><strong>Nu există vânazători activi în baza de date.</strong></p>';
		exit();
	}
	// interogarea adevarata pentru rezultate
	$string = 'SELECT *
					FROM `vanzatori`
					WHERE `activ` = 1
					ORDER BY `nume_vanzator` ASC;';
	$query = interogare($string, NULL);
	for ($i = 0; $row = $query->fetch(); $i++) {
		echo '<div class="rec">';
		echo '<p  id="f' . $row['id_vanzator'] . '">';
		echo $row['nume_vanzator'];
		echo '&nbsp' . $row['prenume_vanzator'] . "</p>";
		echo '</div>';
	}
	exit();
}
if (isset($_POST["camp_str"])) {
	$string = 'SELECT COUNT(*)
               FROM vanzatori
			   WHERE (nume_vanzator LIKE ? OR prenume_vanzator LIKE ?);';
	$str = "%" . $_POST["camp_str"] . "%";
	$data = array($str, $str);
	$query = interogare($string, $data);
	//daca nu sunt rezultate se iese cu mesaj
	$count = $query->fetchColumn();
	if (!$count) {
		echo '<p>Nu există în baza de date.</p>';
		exit();
	}
//	if ($_POST["camp_str"] == "") {
//		afiseaza_numar_total($count);
//		exit();
//	}
	// interogarea adevarata pentru rezultate (daca nu s-a iesit mai sus)
	$string = 'SELECT *
               FROM `vanzatori`
			   WHERE (`nume_vanzator` LIKE ? OR `prenume_vanzator` LIKE ?)
			   ORDER BY `nume_vanzator`, `prenume_vanzator` ASC;';
	$query = interogare($string, $data);
	afiseaza_rezultate($query);
	afiseaza_numar_total($count);
	exit();
}
?>
<h2>Listă vânzători</h2>
<form action="/" method="post" id="submit">
	<table>
		<tbody>
		<tr>
			<td class="align_bottom">
				<label for="camp">Caută</label>
				<input class=" normal mediu"
					   id="camp"
					   type="text"
					   name="camp_str"
					   autocomplete="off"/>
			</td>
			<td class="align_bottom">
				<span class="submit nou" id="vanzator_nou">Crează un vânzător nou</span>
			</td>
		</tr>
		</tbody>
	</table>
</form>
<?php
$string = 'SELECT COUNT(*) FROM `vanzatori` LIMIT 1;';
$query = interogare($string, NULL);
$count = $query->fetchColumn();
if (!$count) { //daca nu sunt rezultate se iese cu mesaj
	// echo "<h2>Rezultate căutare</h2>";
	echo '<p>Nu există în baza de date.</p>';
	exit();
}
$string = 'SELECT * FROM `vanzatori` ORDER BY `nume_vanzator`, `prenume_vanzator` ASC;';
$query = interogare($string, NULL);
afiseaza_rezultate($query);
afiseaza_numar_total($count);
?>
