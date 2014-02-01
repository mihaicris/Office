<?php
	include_once('conexiune.php');
	function afiseaza_tabel($query)
	{
		echo '<table class="companii rezultate">';
		echo "<tr>";
		echo '<th>ID</th>';
		echo '<th>Companie</th>';
		echo '<th>Adresă</th>';
		echo '<th>Oraș</th>';
		echo '<th>Țară</th>';
		echo "</tr>";
		for($i = 0; $row = $query->fetch(); $i++) {
			echo '<tr class="record">';
			echo '<td id="f' . $row['id_companie'] . '"><span class="id">' . $row['id_companie'] . '</span><span class="sosa actiune">a</span></td>';
			echo '<td class="companie">' . $row['nume_companie'] . '</td>';
			echo '<td class="adresa">' . $row['adresa_companie'] . '</td>';
			echo '<td class="oras">' . $row['oras_companie'] . '</td>';
			echo '<td class="tara">' . $row['tara_companie'] . '</td>';
			echo "</tr>";
		} //end for
		echo "</table>";
	}

	function afiseaza_numar_total($count)
	{
		echo '<table>';
		echo "<tr>";
		echo '<td class="total">' . $count;
		if($count == 1) {
			echo " companie";
		} else {
			echo " companii";
		}
		echo "</td>";
		echo "</tr>";
		echo "<table>";
	}

//  Formular creeare companie nou
	if(isset($_POST["creare"])) {
		?>
		<h2>Creare companie</h2>
		<form action="/" method="post">
			<label for="nume_companie">Nume</label><br>
			<input class="normal lung" id="nume_companie" type="text" name="nume_companie" autocomplete="off"
				   value="<?php echo $_POST["nume"]; ?>"/>
			<img id="c_nume" src="" alt=""><br>
			<label for="adresa_companie">Adresă</label><br>
			<input class="normal lung" id="adresa_companie" type="text" name="adresa_companie" autocomplete="off"
				   value=""
				   autofocus/>
			<img id="c_adresa" src="" alt=""><br>
			<label for="oras_companie">Oraș</label><br>
			<input class="normal scurt" id="oras_companie" type="text" name="oras_companie" autocomplete="off"
				   value=""/>
			<img id="c_oras" src="" alt=""><br>
			<label for="tara_companie">Țară</label><br>
			<input class="normal scurt" id="tara_companie" type="text" name="tara_companie" autocomplete="off"
				   value=""/>
			<img id="c_tara" src="" alt=""><br>
			<a href="#" id="creaza_companie" class="submit"><h3>Salvează<span class="sosa">å</span></h3></a>
			<a href="#" id="renunta" class="buton_renunta"><h3>Renunță</h3></a>
		</form>
		<?php
		exit();
	} //end if

// salvare companie noua (1) sau modificare companie existenta (2) in baza de date
	if(isset($_POST["salveaza"])) {
		$action = $_POST["salveaza"];
		$nume   = $_POST["nume"];
		$adresa = $_POST["adresa"];
		$oras   = $_POST["oras"];
		$tara   = $_POST["tara"];
		if($action == 1) { // se creaza un companie nou daca salveaza=1
			$data   = array($nume, $adresa, $oras, $tara);
			$string = 'INSERT INTO `companii`
                       (`id_companie`,
                        `nume_companie`,
                        `adresa_companie`,
                        `oras_companie`,
                        `tara_companie`)
		           VALUES (NULL, ?, ?, ?, ?);';
		} else { // se modifica companiel existent daca salveaza=2
			$id     = $_POST["id"];
			$data   = array($nume, $adresa, $oras, $tara, $id);
			$string = 'UPDATE `companii`
                   SET  `nume_companie` = ?,
                        `adresa_companie` = ?,
                        `oras_companie` = ?,
                        `tara_companie` = ?
                   WHERE `id_companie` = ?;';
		}
		try {
			$query = $db->prepare($string);
			$query->execute($data);
			echo('ok');
		} catch(Exception $e) {
			echo $e->getMessage();
		}
		exit();
	}

// editeaza companie din baza de date
	if(isset($_POST["editeaza"])) {
		$id     = $_POST["editeaza"];
		$string = 'SELECT *
               FROM companii
               WHERE id_companie = ?
               LIMIT 1;';
		$data   = array($id);
		$query  = $db->prepare($string);
		$query->execute($data);
		$row = $query->fetch(PDO::FETCH_ASSOC);
		if(count($row) == 1) {
			echo("Inexistent");
			exit();
		}
		?>
		<h2>Modificare companie</h2>
		<form action="/" method="post" id="editare_companii">
			<input id="id_companie" type="hidden" name="id_companie" value="<?php echo $row['id_companie']; ?>"/>
			<label>Nume</label><br>
			<input class="normal lung" id="nume_companie" type="text" name="nume_companie" autocomplete="off"
				   value="<?php echo $row['nume_companie']; ?>"/>
			<img id="c_nume" src="../images/yes_small.png" alt=""><br>
			<label>Adresă</label><br>
			<input class="normal lung" id="adresa_companie" type="text" name="adresa_companie" autocomplete="off"
				   value="<?php echo $row['adresa_companie']; ?>"/>
			<img id="c_adresa" src="../images/yes_small.png" alt=""><br>
			<label>Oraș</label><br>
			<input class="normal scurt" id="oras_companie" type="text" name="oras_companie" autocomplete="off"
				   value="<?php echo $row['oras_companie']; ?>"/>
			<img id="c_oras" src="../images/yes_small.png" alt=""><br>
			<label>Țară</label><br>
			<input class="normal scurt" id="tara_companie" type="text" name="tara_companie" autocomplete="off"
				   value="<?php echo $row['tara_companie']; ?>"/>
			<img id="c_tara" src="../images/yes_small.png" alt=""><br>
			<a href="#" id="modifica" class="submit"><h3>Modifică<span class="sosa">å</span></h3></a>
			<a href="#" id="sterge" class="buton_stergere"><h3>Șterge<span class="sosa">ç</span></h3></a>
			<a href="#" id="renunta" class="buton_renunta"><h3>Renunță</h3></a>
		</form>
		<?php
		exit();
	}

// actiune stergere companiein baza de date
	if(isset($_POST["sterge"])) {
		$id     = $_POST["id"];
		$string = 'DELETE FROM `office`.`companii` WHERE `companii`.`id_companie` = ' . $id . ' LIMIT 1;';
		$query  = $db->query($string);
		$query->execute();
		echo('ok');
		exit();
	}

// alegere companie din baza de date in formulare
	if(isset($_POST["companie"])) {

		$str = "%" . $_POST["companie"] . "%";
		// prima interogare pentru numarare rezultate
		$data = array($str, $str, $str);

		$string = 'SELECT COUNT(*) FROM `companii`
			WHERE (`nume_companie` LIKE ? OR `adresa_companie` LIKE ? OR `oras_companie` LIKE ?)
			ORDER BY `nume_companie` ASC LIMIT 1;';
		$query  = $db->prepare($string);
		$query->execute($data);

		//daca nu sunt rezultate se iese cu mesaj
		$count = $query->fetchColumn();
		if(!$count) { //daca nu sunt rezultate se iese cu mesaj
			echo '<p class="noresults">Nu există în baza de date.<br/>Crează compania nouă înaintea persoanei.</p>';
			exit();
		}
		// interogarea adevarata pentru rezultate (daca nu s-a iesit mai sus)
		$string = 'SELECT * FROM `companii`
			WHERE (`nume_companie` LIKE ? OR `adresa_companie` LIKE ? OR `oras_companie` LIKE ?)
			ORDER BY `nume_companie` ASC LIMIT 3;';
		$query  = $db->prepare($string);
		$query->execute($data);

		echo '<div class="rec" id="source">';
		echo '<p>' . $_POST["companie"] . '</p>';
		echo '</div>';
		for($i = 0; $row = $query->fetch(); $i++) {
			echo '<div class="rec">';
			echo '<p  id="f' . $row['id_companie'] . '" class="bold">' . $row['nume_companie'] . "</p>";
			echo '<p>' . $row['adresa_companie'] . "</p>";
			echo '<p style="color: #3F41D9;">' . $row['oras_companie'] . '</p>';
			echo '</div>';
		} //end for
		exit();
	} // end isset

// cautare companie in baza de date
	if(isset($_POST["camp_str"])) {
		$data = array('%' . $_POST["camp_str"] . '%'); //
		// prima interogare pentru numar de rezultate
		$string = 'SELECT COUNT(*) FROM companii
               WHERE (nume_companie LIKE ?)
			   ORDER BY nume_companie ASC
			   LIMIT 1;';
		$query  = $db->prepare($string);
		$query->execute($data);
		//daca nu sunt rezultate se iese cu mesaj
		$count = $query->fetchColumn();
		if(!$count) {
			echo '<p>Nu există în baza de date.</p>';
			exit();
		}
		if($data[0] == '%%') {
			afiseaza_numar_total($count);
			exit();
		}
		// interogarea adevarata pentru rezultate (daca nu s-a iesit mai sus)
		$string = 'SELECT * FROM companii
               WHERE (nume_companie LIKE ?)
			   ORDER BY nume_companie ASC;';
		$query  = $db->prepare($string);
		$query->execute($data);
		afiseaza_tabel($query);
		afiseaza_numar_total($count);
		exit();
	} // end if

// testeaza existenta companie in baza de date
	if(isset($_POST["nume_test"])) {
		$data   = array($_POST["nume_test"]);
		$string = 'SELECT COUNT(*)
               FROM companii
               WHERE nume_companie = ?;';
		$query  = $db->prepare($string);
		$query->execute($data);
		if(!$query->fetchColumn() == 0) {
			echo 'este'; //daca nu sunt rezultate numele nu este in baza de date
		}
		exit();
	} // end if

// afisare default cand se apeleaza companie fara nici un parametru de cautare
?>
	<h2>Listă companii</h2>
	<form action="/" method="post" id="submit">
		<label for="camp">Caută</label>
		<br>
		<input class="normal mediu" id="camp" type="text" name="camp_str" autocomplete="off"/>
		<a href="#" id="companie_noua" class="submit nou">
			<h3>Crează o companie nouă</h3>
		</a>
	</form>
<?php
	$string = 'SELECT COUNT(*)
           FROM companii
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
	$string = 'SELECT *
           FROM companii
           ORDER BY nume_companie ASC;';
	$query  = $db->query($string);
	$query->execute();
//afiseaza_tabel($query);
	afiseaza_numar_total($count);
	exit();
?>