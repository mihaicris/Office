<?php

	include_once('conexiune.php');

	function afiseaza_tabel($query)
	{
		echo '<table class="persoane rezultate">';
		echo '<tr>';
		echo '<th>ID</th>';
		echo '<th>Nume și prenume</th>';
		echo '<th>Companie</th>';
		echo '<th>E-mail</th>';
		echo '<th>Mobil</th>';
		echo "</tr>";
		for($i = 0; $row = $query->fetch(); $i++) {
			echo '<tr class="record">';
			echo '<td id="f' . $row['id_persoana'] . '"><span class="id">' . $row['id_persoana'] . '</span><span class="sosa actiune">a</span></td>';
			echo '<td class="nume">' . $row['nume_persoana'] . ' ' . $row['prenume_persoana'] . '</td>';
			echo '<td class="companie">' . $row['nume_companie'] . '</td>';
			echo '<td class="email"><a class="email" href="mailto:' . $row['email_persoana'] . '">' . $row['email_persoana'] . '</a></td>';
			echo '<td class="mobil">' . $row['mobil_persoana'] . '</td>';
			echo '</tr>';
		} //end for
		echo '</table>';
	}

	function afiseaza_numar_total($count)
	{
		echo '<table>';
		echo '<tr>';
		echo '<td class="total">' . $count;
		if($count == 1) {
			echo " persoană";
		} else {
			echo " persoane";
		}
		echo '</td>';
		echo '</tr>';
		echo '</table>';
	}

	if(isset($_POST["formular-creare"])) {
		//  Formular creeare persoana de contact noua
		?>
		<h2>Creare persoană contact</h2>
		<form action="/" method="post">
			<input id="id_persoana" type="hidden" name="id_persoana" value=""/>
			<table class="persoane">
				<tbody>
				<tr>
					<td>
						<label for="nume_persoana">Nume</label>
						<input class="normal mediu"
							   id="nume_persoana"
							   type="text"
							   name="nume_persoana"
							   autocomplete="off"/>
					</td>
					<td>
						<label for="prenume_persoana">Prenume</label>
						<input class="normal mediu"
							   id="prenume_persoana"
							   type="text"
							   name="prenume_persoana"
							   autocomplete="off"/>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<label for="tel_persoana">Telefon fix</label>
						<input class="normal mediu"
							   id="tel_persoana"
							   type="text"
							   name="tel_persoana"
							   autocomplete="off"/>
					</td>
					<td>
						<label for="fax_persoana">Fax</label>
						<input class="normal mediu"
							   id="fax_persoana"
							   type="text"
							   name="fax_persoana"
							   autocomplete="off"/>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<label for="mobil_persoana">Telefon Mobil</label>
						<input class="normal mediu"
							   id="mobil_persoana"
							   type="text"
							   name="mobil_persoana"
							   autocomplete="off"/>
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>
						<label for="email_persoana">Adresă de email</label>
						<input class="normal mediu"
							   id="email_persoana"
							   type="text"
							   name="email_persoana"
							   autocomplete="off"/>
					</td>
					<td>
						<label for="sex">Sex</label>
						<select id="sex">
							<option id="default" value=""></option>
							<option value="0">Bărbat</option>
							<option value="1">Femeie</option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<label for="camp_cauta_companie">Companie</label>
						<input class="normal mediu"
							   id="camp_cauta_companie"
							   type="text"
							   name="companie_persoana"
							   autocomplete="off"/>
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>
						<label for="dep_persoana">Departament</label>
						<input class="normal mediu"
							   id="dep_persoana"
							   type="text"
							   name="dep_persoana"
							   autocomplete="off"/>
					</td>
					<td>
						<label for="functie_persoana">Funcție</label>
						<input class="normal mediu"
							   id="functie_persoana"
							   type="text"
							   name="functie_persoana"
							   autocomplete="off"/>
					</td>
					<td></td>
				</tr>
				</tbody>
			</table>
			<input id="id_companie_persoana"
				   type="hidden"
				   name="id_companie_persoana"
				   value=""/>
			<a href="#" id="creaza_persoana" class="submit"><h3>Salvează<span class="sosa">å</span></h3></a>
			<a href="#" id="renunta" class="buton_renunta"><h3>Renunță</h3></a>
		</form>
		<div class="tabel"></div>
		<?php
		exit();
	}
	if(isset($_POST["formular-editare"])) {
		// editeaza persoana din baza de date
		$id     = $_POST["formular-editare"];
		$string = 'SELECT P.*, C.nume_companie
				   FROM `persoane` AS P
				   LEFT JOIN companii AS C
				   ON P.id_companie_persoana = C.id_companie
				   WHERE P.id_persoana = ?
				   LIMIT 1;';
		$data   = array($id);
		$query  = interogare($string, $data);
		$row    = $query->fetch(PDO::FETCH_ASSOC);
		if(count($row) == 1) {
			echo("Inexistent");
			exit();
		}
		?>
		<h2>Modificare persoană de contact</h2>
		<form action="/" method="post">
			<input id="id_persoana" type="hidden" name="id_persoana" value="<?php echo $row['id_persoana']; ?>"/>
			<table class="persoane">
				<tbody>
				<tr>
					<td>
						<label for="nume_persoana">Nume</label>
						<input class="normal mediu" id="nume_persoana" type="text" name="nume_persoana"
							   value="<?php echo $row['nume_persoana']; ?>" autocomplete="off"/>
					</td>
					<td>
						<label for="prenume_persoana">Prenume</label>
						<input class="normal mediu" id="prenume_persoana" type="text" name="prenume_persoana"
							   value="<?php echo $row['prenume_persoana']; ?>" autocomplete="off"/>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<label for="tel_persoana">Telefon fix</label>
						<input class="normal mediu" id="tel_persoana" type="text" name="tel_persoana"
							   value="<?php echo $row['tel_persoana']; ?>" autocomplete="off"/>
					</td>
					<td>
						<label for="fax_persoana">Fax</label>
						<input class="normal mediu" id="fax_persoana" type="text" name="fax_persoana"
							   value="<?php echo $row['fax_persoana']; ?>" autocomplete="off"/>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<label for="mobil_persoana">Telefon Mobil</label>
						<input class="normal mediu" id="mobil_persoana" type="text" name="mobil_persoana"
							   value="<?php echo $row['mobil_persoana']; ?>" autocomplete="off"/>
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>
						<label for="email_persoana">Adresă de email</label>
						<input class="normal mediu" id="email_persoana" type="text" name="email_persoana"
							   value="<?php echo $row['email_persoana']; ?>" autocomplete="off"/>
					</td>
					<td>
						<label for="sex">Sex</label>
						<select id="sex">
							<option value="0" <?php if(!$row['gen_persoana']) echo 'selected="selected"' ?>>Bărbat
							</option>
							<option value="1" <?php if($row['gen_persoana']) echo 'selected="selected"' ?>>Femeie
							</option>
						</select>
					</td>
					<td></td>
				</tr>
				<tr>
					<td>
						<label for="camp_cauta_companie">Companie</label>
						<input class="normal mediu" id="camp_cauta_companie" type="text" name="companie_persoana"
							   value="<?php echo $row['nume_companie']; ?>" autocomplete="off"/>
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>
						<label for="dep_persoana">Departament</label>
						<input class="normal mediu" id="dep_persoana" type="text" name="dep_persoana"
							   value="<?php echo $row['departament_persoana']; ?>" autocomplete="off"/>
					</td>
					<td>
						<label for="functie_persoana">Funcție</label>
						<input class="normal mediu" id="functie_persoana" type="text" name="functie_persoana"
							   value="<?php echo $row['functie_persoana']; ?>" autocomplete="off"/>
					</td>
					<td></td>
				</tr>
				</tbody>
			</table>
			<input id="id_companie_persoana"
				   type="hidden"
				   name="id_companie_persoana"
				   value="<?php echo $row['id_companie_persoana']; ?>"/>

			<a href="#" id="editeaza_persoana" class="submit"><h3>Modifică<span class="sosa">å</span></h3></a>
			<a href="#" id="sterge" class="buton_stergere"><h3>Șterge<span class="sosa">ç</span></h3></a>
			<a href="#" id="renunta" class="buton_renunta"><h3>Renunță</h3></a>
		</form>
		<div class="tabel"></div>
		<?php
		exit();
	}

	if(isset($_POST["salveaza"])) {
		$data = $_POST["formdata"];
		fb($data);
		if($_POST["salveaza"]) {
			fb('se salveza nou');
			$string = 'INSERT INTO `persoane`
					(`nume_persoana`,
					`prenume_persoana`,
					`tel_persoana`,
					`fax_persoana`,
					`mobil_persoana`,
					`email_persoana`,
					`gen_persoana`,
					`id_companie_persoana`,
					`departament_persoana`,
					`functie_persoana`,
					`id_persoana`)
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL);';
			array_shift($data); // se ia primul element (id) si se pune la sfarsit
		} else {
			fb('se modifica existent');
			$string = 'UPDATE `persoane`
                   	    SET	`nume_persoana` = ?,
							`prenume_persoana` = ?,
							`tel_persoana` = ?,
							`fax_persoana` = ?,
							`mobil_persoana` = ?,
							`email_persoana` = ?,
							`gen_persoana` = ?,
							`id_companie_persoana` = ?,
							`departament_persoana` = ?,
							`functie_persoana`= ?
                       	WHERE `id_persoana` = ?;';
			array_push($data, array_shift($data));
		}
		fb($data);
		fb($string);

		$query = interogare($string, $data);
		echo('ok');
		exit();
	}
	if(isset($_POST["sterge"])) {
		// actiune stergere companiein baza de date
		$string = 'DELETE FROM `persoane` WHERE `id_persoana` = ? LIMIT 1;';
		$data   = array($_POST['id']);
		$query  = interogare($string, $data);
		echo('ok');
		exit();
	}

	if(isset($_POST["camp_str"])) {
		// cautare persoane in baza de date
		$str = "%" . $_POST["camp_str"] . "%";
		// prima interogare pentru numar de rezultate
		$data   = array($str, $str, $str, $str, $str, $str);
		$string = 'SELECT COUNT(*)
					FROM persoane AS P
					LEFT JOIN companii AS C ON P.id_companie_persoana = C.id_companie
					WHERE (nume_persoana LIKE ?
						OR prenume_persoana LIKE ?
						OR functie_persoana LIKE ?
						OR departament_persoana LIKE ?
						OR email_persoana LIKE ?
						OR nume_companie LIKE ?)
					ORDER BY nume_persoana ASC LIMIT 1;';
		$query  = interogare($string, $data);
		$count  = $query->fetchColumn();
		if(!$count) { //daca nu sunt rezultate se iese cu mesaj
			// echo "<h2>Rezultate căutare</h2>";
			echo '<p>Nu există în baza de date.</p>';
			exit();
		}
		if($str == '%%') {
			afiseaza_numar_total($count);
			exit();
		}
		// interogarea adevarata pentru rezultate (daca nu s-a iesit mai sus)
		$string = 'SELECT P.* , C.nume_companie
				FROM persoane AS P
				LEFT JOIN companii AS C ON P.id_companie_persoana = C.id_companie
				WHERE (nume_persoana LIKE ?
				    OR prenume_persoana LIKE ?
					OR functie_persoana LIKE ?
					OR departament_persoana LIKE ?
					OR email_persoana LIKE ?
					OR nume_companie LIKE ?)
				ORDER BY nume_persoana ASC;';
		$query  = $db->prepare($string);
		$query->execute($data);
		afiseaza_tabel($query);
		afiseaza_numar_total($count);
		exit();
	}

?>
	<h2>Lista persoane de contact</h2>
	<form action="/" method="post" id="submit">
		<label for="camp">Caută</label>
		<input id="camp" class="normal mediu" type="text" name="camp_str" autocomplete="off"/>
		<a href="#" id="produs_nou" class="submit nou">
			<h3>Crează un contact nou</h3>
		</a>
	</form>
<?php
	$string = 'SELECT COUNT(*)
           FROM `persoane`
           LIMIT 1;';
	$query  = $db->query($string);
	$query->execute();
	$count = $query->fetchColumn();
	if(!$count) { //daca nu sunt rezultate se iese cu mesaj
		// echo "<h2>Rezultate căutare</h2>";
		echo '<p>Nu există în baza de date.</p>';
		exit();
	}
	// interogarea adevarata pentru rezultate (daca nu s-a iesit mai sus)
	$string = 'SELECT P.*, C.nume_companie
           FROM persoane AS P
           LEFT JOIN companii AS C
           ON P.id_companie_persoana = C.id_companie
           ORDER BY nume_persoana ASC;';
	$query  = $db->query($string);
	$query->execute();
	afiseaza_numar_total($count);
	exit();
