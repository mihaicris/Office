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

	if(isset($_POST["formular-creare"])) {
		//  Formular creeare companie nou
		?>
		<h2>Creare companie</h2>
		<form action="/" method="post">
			<table class="companii">
				<tbody>
				<tr>
					<td>
						<label for="nume_companie">Nume</label>
						<input class="normal lung"
							   id="nume_companie"
							   type="text"
							   name="nume_companie"
							   value="<?php echo $_POST["nume"]; ?>"
							   autocomplete="off"/>
						<img id="c_nume" src="../images/yes_small.png" alt="imagine_lipsa">
					</td>
				</tr>
				<tr>
					<td>
						<label for="adresa_companie">Adresă</label>
						<input class="normal lung"
							   id="adresa_companie"
							   type="text"
							   name="adresa_companie"
							   autocomplete="off"/>
						<img id="c_adresa" src="../images/yes_small.png" alt="imagine_lipsa">
					</td>
				</tr>
				<tr>
					<td>
						<label for="oras_companie">Oraș</label>
						<input class="normal scurt"
							   id="oras_companie"
							   type="text"
							   name="oras_companie"
							   autocomplete="off"/>
						<img id="c_oras" src="../images/yes_small.png" alt="imagine_lipsa">
					</td>
				</tr>
				<tr>
					<td>
						<label for="tara_companie">Țară</label>
						<input class="normal scurt"
							   id="tara_companie"
							   type="text"
							   name="tara_companie"
							   autocomplete="off"/>
						<img id="c_tara" src="../images/yes_small.png" alt="imagine_lipsa">
					</td>
				</tr>
				</tbody>
			</table>
			<a href="#" id="creaza_companie" class="submit"><h3>Salvează<span class="sosa">å</span></h3></a>
			<a href="#" id="renunta" class="buton_renunta"><h3>Renunță</h3></a>
		</form>
		<?php
		exit();
	}
	if(isset($_POST["formular-editare"])) {
		// editeaza companie din baza de date
		$id     = $_POST["formular-editare"];
		$string = 'SELECT * FROM `companii` WHERE `id_companie` = ? LIMIT 1;';
		$data   = array($id);
		$query  = interogare($string, $data);
		$row    = $query->fetch(PDO::FETCH_ASSOC);
		if(count($row) == 1) {
			echo("Inexistent");
			exit();
		}
		?>
		<h2>Modificare companie</h2>
		<form action="/" method="post" id="editare_companii">
			<input id="id_companie" type="hidden" name="id_companie" value="<?php echo $row['id_companie']; ?>"/>
			<table class="companii">
				<tbody>
				<tr>
					<td>
						<label>Nume</label>
						<input class="normal lung"
							   id="nume_companie"
							   type="text"
							   name="nume_companie"
							   value="<?php echo $row['nume_companie']; ?>"
							   autocomplete="off"/>
						<img id="c_nume" src="../images/yes_small.png" alt="imagine_lipsa">
					</td>
				</tr>
				<tr>
					<td>
						<label>Adresă</label>
						<input class="normal lung"
							   id="adresa_companie"
							   type="text"
							   name="adresa_companie"
							   value="<?php echo $row['adresa_companie']; ?>"
							   autocomplete="off"/>
						<img id="c_adresa" src="../images/yes_small.png" alt="imagine_lipsa">
					</td>
				</tr>
				<tr>
					<td>
						<label>Oraș</label>
						<input class="normal scurt"
							   id="oras_companie"
							   type="text"
							   name="oras_companie"
							   value="<?php echo $row['oras_companie']; ?>"
							   autocomplete="off"/>
						<img id="c_oras" src="../images/yes_small.png" alt="imagine_lipsa">
					</td>
				</tr>
				<tr>
					<td>
						<label>Țară</label>
						<input class="normal scurt"
							   id="tara_companie"
							   type="text"
							   name="tara_companie"
							   value="<?php echo $row['tara_companie']; ?>"
							   autocomplete="off"/>
						<img id="c_tara" src="../images/yes_small.png" alt="imagine_lipsa">
					</td>
				</tr>
				</tbody>
			</table>
			<a href="#" id="modifica_companie" class="submit"><h3>Modifică<span class="sosa">å</span></h3></a>
			<a href="#" id="sterge" class="buton_stergere"><h3>Șterge<span class="sosa">ç</span></h3></a>
			<a href="#" id="renunta" class="buton_renunta"><h3>Renunță</h3></a>
		</form>
		<?php
		exit();
	}

	if(isset($_POST["salveaza"])) {
		// salvare companie noua (1) sau modificare companie existenta (2) in baza de date
		$nume   = $_POST["nume"];
		$adresa = $_POST["adresa"];
		$oras   = $_POST["oras"];
		$tara   = $_POST["tara"];
		if($_POST["salveaza"] == 1) { // se creaza un companie nou daca salveaza=1
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
		$query = interogare($string, $data);
		echo('ok');
		exit();
	}
	if(isset($_POST["sterge"])) {
		// actiune stergere companiein baza de date
		$string = 'DELETE FROM `office`.`companii` WHERE `companii`.`id_companie` = ? LIMIT 1;';
		$data   = array($_POST['id']);
		$query  = interogare($string, $data);
		echo('ok');
		exit();
	}

	if(isset($_POST["companie"])) {
		// alegere companie din baza de date in formulare
		$str = "%" . $_POST["companie"] . "%";
		// prima interogare pentru numarare rezultate
		$string = 'SELECT COUNT(*)
					FROM `companii`
					WHERE (`nume_companie` LIKE ? OR `adresa_companie` LIKE ? OR `oras_companie` LIKE ?)
					ORDER BY `nume_companie` ASC
					LIMIT 1;';
		$data   = array($str, $str, $str);
		$query  = interogare($string, $data);
		//daca nu sunt rezultate se iese cu mesaj
		$count = $query->fetchColumn();
		if(!$count) {
			echo '<p class="noresults"><strong>Nu există în baza de date.</strong><br/>Trebuie creată în avans.</p>';
			exit();
		}
		// interogarea adevarata pentru rezultate
		$string = 'SELECT *
					FROM `companii`
					WHERE (`nume_companie` LIKE ? OR `adresa_companie` LIKE ? OR `oras_companie` LIKE ?)
					ORDER BY `nume_companie` ASC
					LIMIT 5;';
		$query  = interogare($string, $data);
		echo '<div class="rec" id="source"><p>' . $_POST["companie"] . '</p></div>';
		for($i = 0; $row = $query->fetch(); $i++) {
			echo '<div class="rec">';
			echo '<p  id="f' . $row['id_companie'] . '" class="bold">' . $row['nume_companie'] . "</p>";
			echo '<p>' . $row['adresa_companie'] . "</p>";
			echo '<p style="color: #3F41D9;">' . $row['oras_companie'] . '</p>';
			echo '</div>';
		}
		exit();
	}
	if(isset($_POST["camp_str"])) {
		// cautare companie in baza de date
		// prima interogare pentru numar de rezultate
		$string = 'SELECT COUNT(*) FROM companii
               WHERE (nume_companie LIKE ?)
			   ORDER BY nume_companie ASC
			   LIMIT 1;';
		$data   = array('%' . $_POST["camp_str"] . '%');
		$query  = interogare($string, $data);
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
		$string = 'SELECT * FROM `companii`
               		WHERE (`nume_companie` LIKE ?)
			   		ORDER BY `nume_companie` ASC;';
		$query  = interogare($string, $data);
		afiseaza_tabel($query);
		afiseaza_numar_total($count);
		exit();
	}
	if(isset($_POST["nume_test"])) {
		// testeaza existenta companie in baza de date
		$data   = array($_POST["nume_test"]);
		$string = 'SELECT COUNT(*)
					FROM `companii`
					WHERE `nume_companie` = ?;';
		$query  = interogare($string, $data);
		if(!$query->fetchColumn() == 0) {
			echo 'este';
		}
		exit();
	}
?>
	<h2>Listă companii</h2>
	<form action="/" method="post" id="submit">
		<label for="camp">Caută</label>
		<input class="normal mediu" id="camp" type="text" name="camp_str" autocomplete="off"/>
		<a href="#" id="companie_noua" class="submit nou">
			<h3>Crează o companie nouă</h3>
		</a>
	</form>
<?php
	// afisare default cand se apeleaza companie fara nici un parametru de cautare
	$string = 'SELECT COUNT(*) FROM `companii` LIMIT 1;';
	$query  = interogare($string, NULL);
	$count  = $query->fetchColumn();
	if(!$count) {
		echo '<p>Nu există în baza de date.</p>';
		exit();
	}
	afiseaza_numar_total($count);
?>