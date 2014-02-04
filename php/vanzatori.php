<?php
	include('conexiune.php');

	//  Afisare tabel

	function afiseaza_tabel($query)
	{
		echo '<table class="vanzatori rezultate">';
		echo '<tr>';
		echo '<th>ID</th>';
		echo '<th>Nume și prenume</th>';
		echo '</tr>';
		for($i = 0; $row = $query->fetch(); $i++) {
			echo '<tr class="record">';
			echo '<td id="f' . $row['id_vanzator']
				. '"><span class="id">'
				. $row['id_vanzator']
				. '</span><span class="sosa actiune">a</span></td>';
			echo '<td class="nume">'
				. $row['nume_vanzator'] . ' ' . $row['prenume_vanzator'] . '</td>';
			echo '</tr>';
		} //end for
	}

	function afiseaza_numar_total($count)
	{
		echo '<table>';
		echo "<tr>";
		echo '<td class="total">' . $count;
		if($count == 1) {
			echo " vânzător";
		} else {
			echo " vânzători";
		}
		echo "</td>";
		echo "</tr>";
		echo "<table>";
	}

	if(isset($_POST["formular-creare"])) {
		//  Formular creeare vanzator nou
		?>
		<h2>Creare vânzător</h2>
		<form action="/" method="post" id="creare_vanzator">
			<table class="vanzatori">
				<tbody>
				<tr>
					<td>
						<label for="nume_vanzator">Nume</label>
						<input class="normal mediu"
							   id="nume_vanzator"
							   type="text"
							   name="nume_vanzator"
							   value="<?php echo $_POST["nume"]; ?>"
							   autocomplete="off"/>
						<img id="c_adresa" src="../images/yes_small.png" alt="imagine_lipsa">
					</td>
				</tr>
				<tr>
					<td>
						<label for="prenume_vanzator">Prenume</label>
						<input class="normal mediu"
							   id="prenume_vanzator"
							   type="text"
							   name="prenume_vanzator"
							   autocomplete="off"
							   autofocus/>
						<img id="c_adresa" src="../images/yes_small.png" alt="imagine_lipsa">
					</td>
				</tr>
				</tbody>
			</table>
			<a href="#" id="creaza_vanzator" class="submit"><h3>Salvează<span class="sosa">å</span></h3></a>
			<a href="#" id="renunta" class="buton_renunta"><h3>Renunță</h3></a>
		</form>
		<?php
		exit();
	}
	if(isset($_POST["formular-editare"])) {
		// editeaza vanzator din baza de date
		$id     = $_POST["formular-editare"];
		$string = 'SELECT *
               FROM vanzatori
               WHERE id_vanzator = ?
               LIMIT 1;';
		$data   = array($id);
		$query  = interogare($string, $data);
		$row    = $query->fetch(PDO::FETCH_ASSOC);
		if(count($row) == 1) {
			echo("Inexistent");
			exit();
		}
		?>
		<h2>Modificare vânzător</h2>
		<form action="/" method="post">
			<input id="id_vanzator"
				   type="hidden"
				   name="id_vanzator"
				   value="<?php echo $row['id_vanzator']; ?>"/>
			<table class="vanzatori">
				<tbody>
				<tr>
					<td>
						<label for="nume_vanzator">Nume</label>
						<input class="normal mediu"
							   id="nume_vanzator"
							   type="text"
							   name="nume_vanzator"
							   value="<?php echo $row['nume_vanzator']; ?>"
							   autocomplete="off"/>
						<img id="c_nume" src="../images/yes_small.png" alt="imagine_lipsa">
					</td>
				</tr>
				<tr>
					<td>
						<label for="prenume_vanzator">Prenume</label>
						<input class="normal mediu"
							   id="prenume_vanzator"
							   type="text"
							   name="prenume_vanzator"
							   value="<?php echo $row['prenume_vanzator']; ?>"
							   autocomplete="off"/>
						<img id="c_adresa" src="../images/yes_small.png" alt="imagine_lipsa">
					</td>
				</tr>
				</tbody>
			</table>
			<a href="#" id="modifica_vanzator" class="submit"><h3>Modifică<span class="sosa">å</span></h3></a>
			<a href="#" id="sterge" class="buton_stergere"><h3>Șterge<span class="sosa">ç</span></h3></a>
			<a href="#" id="renunta" class="buton_renunta"><h3>Renunță</h3></a>
		</form>
		<?php
		exit();
	}
	if(isset($_POST["salveaza"])) {
		// salvare vanzator nou (1) sau modificare vanzator existent (2) in baza de date
		$action  = $_POST["salveaza"];
		$nume    = $_POST["nume"];
		$prenume = $_POST["prenume"];

		if($action == 1) { // se creaza un vanzator nou daca salveaza=1
			$data   = array($nume, $prenume);
			$string = 'INSERT INTO vanzatori
                    (id_vanzator, nume_vanzator, prenume_vanzator)
                   VALUES (NULL, ?, ?);';
		} else { // se modifica vanzatorul existent daca salveaza=2
			$id     = $_POST["id"];
			$data   = array($nume, $prenume, $id);
			$string = "UPDATE vanzatori
                   SET nume_vanzator = ?, prenume_vanzator = ?
                   WHERE id_vanzator = ?;";
		}
		$query = interogare($string, $data);
		echo('ok');
		exit();
	}
	if(isset($_POST["sterge"])) {
		// actiune stergere vanzator in baza de date
		$data   = array($_POST["id"]);
		$string = 'DELETE FROM `vanzatori` WHERE `vanzatori`.`id_vanzator` = ? LIMIT 1;';
		$query  = interogare($string, $data);
		echo('ok');
		exit();
	}
	if(isset($_POST["camp_str"])) {
		// cautare vanzator in baza de date
		$str  = "%" . $_POST["camp_str"] . "%";
		$data = array($str, $str);
		// prima interogare pentru numar de rezultate
		$string = 'SELECT COUNT(*)
               FROM vanzatori
			   WHERE (nume_vanzator LIKE ? OR prenume_vanzator LIKE ?);';

		$query = interogare($string, $data);
		//daca nu sunt rezultate se iese cu mesaj
		$count = $query->fetchColumn();
		if(!$count) {
			echo '<p>Nu există în baza de date.</p>';
			exit();
		}
		// interogarea adevarata pentru rezultate (daca nu s-a iesit mai sus)
		$string = 'SELECT *
               FROM vanzatori
			   WHERE (nume_vanzator LIKE ? OR prenume_vanzator LIKE ?)
			   ORDER BY nume_vanzator, prenume_vanzator ASC;';
		$query  = interogare($string, $data);
		afiseaza_tabel($query, $count);
		exit();
	} // end if
	if(isset($_POST["nume_test"])) {
		// testeaza existenta vanzator in baza de date
		$nume    = $_POST["nume"];
		$prenume = $_POST["prenume"];
		$data    = array($nume, $prenume);
		$string  = 'SELECT COUNT(*)
               FROM vanzatori
               WHERE (nume_vanzator = ? AND prenume_vanzator = ?);';
		$query   = interogare($string, $data);
		if($query->fetchColumn()) {
			echo 'este'; //daca nu sunt rezultate numele nu este in baza de date
		}
		exit();
	} // end if
	// afisare default cand se apeleaza vanzator fara nici un parametru de cautare
?>
	<h2>Listă vânzători</h2>
	<form action="/" method="post" id="submit">
		<label for="camp">Caută</label>
		<input class=" normal mediu"
			   id="camp"
			   type="text"
			   name="camp_str"
			   autocomplete="off"/>
		<a href="#" id="vanzator_nou" class="submit nou">
			<h3>Crează un vânzător nou</h3>
		</a>
	</form>
<?php
	$string = 'SELECT COUNT(*) FROM `vanzatori` LIMIT 1;';
	$query  = interogare($string, NULL);
	$count  = $query->fetchColumn();
	if(!$count) { //daca nu sunt rezultate se iese cu mesaj
		// echo "<h2>Rezultate căutare</h2>";
		echo '<p>Nu există în baza de date.</p>';
		exit();
	}
	// interogarea adevarata pentru rezultate (daca nu s-a iesit mai sus)
	$string = 'SELECT *
           FROM `vanzatori`
           ORDER BY nume_vanzator, prenume_vanzator ASC;';
	$query  = interogare($string, NULL);
	afiseaza_tabel($query);
	afiseaza_numar_total($count);
	exit();
?>