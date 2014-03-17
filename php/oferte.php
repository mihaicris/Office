<?php
include_once 'conexiune.php';

$stadiu = ["Deschisă", "Câştigată", "Pierdută"];

function verifica_existenta_oferta($id, $nume_oferta, $data_oferta, $id_companie)
{
// testeaza existenta  in baza de date
// daca se apeleaza cu $id null inseamna se testeaza o oferta

	$data = array($nume_oferta, $data_oferta, $id_companie);
	$string = 'SELECT `id_oferta` FROM `oferte` WHERE (`nume_oferta` = ? AND `data_oferta` = ? AND `id_companie_oferta` = ?);';
	$query = interogare($string, $data);
	$result = $query->fetch();
	if ($result['id_oferta'] && $result['id_oferta'] != $id) {
		// daca exista un rezultat si acesta este diferit de $id atunci exista
		// daca $id este null (creare) atunci la orice rezultat care nu este null inseamna ca exista
		echo('exista');
		exit();
	};
	return;
}

function afiseaza_rezultate($query, $filtru)
{
	global $stadiu;
	$flag = 0;
	$count = 0;
	$cur_date = date("Y-m-d");
	$h = '<table class="rezultate to_remove">';
	$h .= '<tr>';
	$h .= '<th class="w_ref">Referință</th>';
	$h .= '<th class="w_data">Data</th>';
	$h .= '<th class="w_nume">Nume ofertă</th>';
	$h .= '<th class="w_comp">Companie</th>';
	$h .= '<th class="w_vanz">Vânzător</th>';
	$h .= '<th class="w_val">Valoare €</th>';
	$h .= '<th class="w_rel">Relevant</th>';
	$h .= '<th class="w_stad">Stadiu</th>';
	$h .= '<th class="w_valab">Valabilitate</th>';
	$h .= "</tr>";
	for ($i = 0; $row = $query->fetch(); $i++) {
		$flag = 1;
		$count++;
		$h .= '<tr>';
		$h .= '<td class="w_ref align_center id" id="f' . $row['id_oferta'] . '">
		' . $row['id_oferta'] . '
			<a href="php/word/Oferta.docx" title="Printează" class="sosa print">p</a>
			<span title="Editează" class="sosa actiune">a</span>
			</td>';
		$h .= '<td class="w_data align_center">' . str_replace_assoc($row['dataoferta'], TRUE) . '</td>';
		$h .= '<td class="w_nume" title="' . $row['descriere_oferta'] . '">' . $row['nume_oferta'] . '</td>';
		$h .= '<td class="w_comp companie">' . $row['nume_companie'] . '</td>';
		$h .= '<td class="w_vanz nume">' . $row['nume_vanzator'] . ' ' . $row['prenume_vanzator'] . '</td>';
		$h .= '<td class="w_val align_right">' . number_format($row['valoare_oferta'], 0, ',', '.') . '</td>';
		$h .= $row["relevant"] ? '<td class="w_rel align_center companie">DA</td>' : '<td class="w_rel align_center id">NU</td>';
		$h .= '<td class="w_stad align_center"><span class="stadiu_' . $row['stadiu'] . '">' . $stadiu[$row['stadiu']] . '</span></td>';
		if (!$row['stadiu']) {
			$h .= $row["data_expirare"] < $cur_date
				? '<td class="w_valab align_center "><span class="expirata">Expirată</span></td>'
				: '<td class="w_valab align_center companie">Activă</td>';
		} else {
			$h .= "<td></td>";
		}
		$h .= '</tr>';
	}
	$h .= '</table>';
	if ($flag) {
		echo $h;
	}
	echo '<span class="total to_remove">' . $count;
	if ($count == 1) {
		echo " ofertă";
		echo $filtru ? " găsită" : " recentă";
	} else {
		echo " oferte";
		echo $filtru ? " găsite" : " recente";

	}
	echo "</span>";
}

function filtrare_si_afisare()
{
	$data = array();
	$string = "SELECT O . id_oferta,
                  O . nume_oferta,
                  O . descriere_oferta,
                  DATE_FORMAT(O . data_oferta, '%e-%m-%Y') AS dataoferta,
                  O . id_companie_oferta,
                  O . data_expirare,
                  O . id_vanzator_oferta,
                  O . valoare_oferta,
                  O . stadiu,
                  O . relevant,
                  C . nume_companie,
                  V . nume_vanzator,
                  V . prenume_vanzator
           FROM oferte AS O
           INNER JOIN companii AS C ON O . id_companie_oferta = C . id_companie
           INNER JOIN vanzatori AS V ON O . id_vanzator_oferta = V . id_vanzator";
	$flag = 0;
	if (isset($_POST["optiuni"]["companie"])) {
		$string .= "\r\nWHERE O . id_companie_oferta = ?";
		array_push($data, $_POST["optiuni"]["companie"]);
		$flag = 1;
	}
	if (isset($_POST["optiuni"]["vanzator"])) {
		if ($flag) {
			$string .= "\r\nAND O . id_vanzator_oferta = ?";
		} else {
			$string .= "\r\nWHERE O . id_vanzator_oferta = ?";
		}
		array_push($data, $_POST["optiuni"]["vanzator"]);
		$flag = 1;
	}
	if (isset($_POST["optiuni"]["stadiu"])) {
		if ($flag) {
			$string .= "\r\nAND O . stadiu = ?";
		} else {
			$string .= "\r\nWHERE O . stadiu = ?";
		}
		array_push($data, $_POST["optiuni"]["stadiu"]);
		$flag = 1;
	}
	if (isset($_POST["optiuni"]["valabilitate"])) {
		if ($flag) {
			$string .= $_POST["optiuni"]["valabilitate"]
				? "\r\nAND O . data_expirare < curdate()"
				: "\r\nAND O . data_expirare >= curdate()";
		} else {
			$string .= $_POST["optiuni"]["valabilitate"]
				? "\r\nWHERE O . data_expirare < curdate()"
				: "\r\nWHERE O . data_expirare >= curdate()";
		}
		$flag = 1;
	};
	if (isset($_POST["optiuni"]["an"])) {
		if ($flag) {
			$string .= "\r\nAND YEAR(data_oferta) = ?";
		} else {
			$string .= "\r\nWHERE YEAR(data_oferta) = ?";
		}
		array_push($data, $_POST["optiuni"]["an"]);
		$flag = 1;
	}
	if (isset($_POST["optiuni"]["luna"])) {
		if ($flag) {
			$string .= "\r\nAND MONTH(data_oferta) = ?";
		} else {
			$string .= "\r\nWHERE MONTH(data_oferta) = ?";
		}
		array_push($data, $_POST["optiuni"]["luna"]);
		$flag = 1;
	}
	if (isset($_POST["optiuni"]["nume"])) {
		if ($flag) {
			$string .= "\r\nAND nume_oferta LIKE ?";
		} else {
			$string .= "\r\nWHERE nume_oferta LIKE ?";
		}
		array_push($data, "%" . $_POST["optiuni"]["nume"] . "%");
		$flag = 1;
	}
	if (isset($_POST["optiuni"]["id_oferta"])) {
		if ($flag) {
			$string .= "\r\nAND id_oferta = ?";
		} else {
			$string .= "\r\nWHERE id_oferta = ?";
		}
		array_push($data, $_POST["optiuni"]["id_oferta"]);
		$flag = 1;
	}

	$string .= "\r\nORDER BY `data_oferta` DESC";
	if (empty($data)) {
		$string .= "\r\nLIMIT 10;";
		$filtru = false;
	} else {
		$string .= "\r\n;";
		$filtru = true;
	}
	fb($string);
	$query = interogare($string, $data);
	afiseaza_rezultate($query, $filtru);
}

if (isset($_POST["optiuni"]["listare"])) {
	?>
	<div id="lista_vanzatori" class="ddm"></div>
	<div id="lista_companii" class="ddm"></div>
	<div id="lista_stadii" class="ddm">
		<div class="rec">
			<p id="f00">Deschise</p>
		</div>
		<div class="rec">
			<p id="f01">Câștigate</p>
		</div>
		<div class="rec">
			<p id="f02">Pierdute</p>
		</div>
	</div>
	<?php
	$string = "SELECT DISTINCT YEAR(data_oferta) AS ani FROM oferte ORDER BY data_oferta";
	$query = interogare($string, null);
	$ani = $query->fetchAll();
	if (count($ani)) {
		$html = '<div id="lista_ani" class="ddm">';
		for ($i = 0; $i < count($ani); $i++) {
			$html .= '<div class="rec">';
			$html .= '<p id="f' . $ani[$i]["ani"] . '">' . $ani[$i]["ani"] . '</p>';
			$html .= '</div>';
		}
		$html .= '</div>';
		echo $html;
	}
	?>
	<div id="lista_valabilitate" class="ddm">
		<div class="rec">
			<p id="f00">Active</p>
		</div>
		<div class="rec">
			<p id="f01">Expirate</p>
		</div>
	</div>
	<div id="lista_luni" class="ddm">
		<div class="rec">
			<p id="f1">Ianuarie</p>
		</div>
		<div class="rec">
			<p id="f2">Februarie</p>
		</div>
		<div class="rec">
			<p id="f3">Martie</p>
		</div>
		<div class="rec">
			<p id="f4">Aprilie</p>
		</div>
		<div class="rec">
			<p id="f5">Mai</p>
		</div>
		<div class="rec">
			<p id="f6">Iunie</p>
		</div>
		<div class="rec">
			<p id="f7">Iulie</p>
		</div>
		<div class="rec">
			<p id="f8">August</p>
		</div>
		<div class="rec">
			<p id="f9">Septembrie</p>
		</div>
		<div class="rec">
			<p id="f10">Octombrie</p>
		</div>
		<div class="rec">
			<p id="f11">Noiembrie</p>
		</div>
		<div class="rec">
			<p id="f12">Decembrie</p>
		</div>
	</div>

	<h2>Listă oferte</h2>
	<form action="/" method="post" id="formular_filtre">
		<fieldset id="filtre_oferte">
			<table>
				<tbody>
				<tr>
					<td>
						<label for="select_companie">Companie</label>
					</td>
					<td>
						<input class="normal standard"
							   id="select_companie"
							   type="text"
							   name="select_companie"
							   placeholder="Tastează pentru a căuta ..."
							/>
					</td>
					<td class="spatiu_stanga">
						<label for="select_an">An financiar</label>
					</td>
					<td>
						<input class="normal extrascurt"
							   id="select_an"
							   type="text"
							   name="select_an"
							   placeholder="Selectează ..."
							   readonly/>
					</td>
					<td class="spatiu_stanga">
						<label for="select_an">Luna</label>
					</td>
					<td>
						<input class="normal extrascurt"
							   id="select_luna"
							   type="text"
							   name="select_luna"
							   placeholder="Selectează ..."
							   readonly/>
					</td>
					<td class="spatiu_stanga align_right" colspan="2">
						<span id="reset" class="submit">Reset</span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="select_vanzator">Vânzător</label>
					</td>
					<td>
						<input id="select_vanzator"
							   class="scurt normal"
							   name="select_vanzator"
							   type="text"
							   placeholder="Selectează ..."
							   readonly/>
					</td>
					<td class="spatiu_stanga">
						<label for="select_stadiu">Oferte</label>
					</td>
					<td>
						<input id="select_stadiu"
							   name="select_stadiu"
							   type="text"
							   class="normal extrascurt"
							   placeholder="Selectează ..."
							   readonly/>
					</td>
					<td class="spatiu_stanga">
						<label for="select_valabilitate">Filtrare</label>
					</td>
					<td>
						<input class="normal extrascurt"
							   id="select_valabilitate"
							   type="text"
							   name="select_valabilitate"
							   placeholder="Selectează ..."
							   readonly/>
					</td>
				</tr>
				<tr>
					<td>
						<label for="select_nume_oferta">Nume proiect</label>
					</td>
					<td>
						<input class="normal scurt"
							   id="select_nume_oferta"
							   type="text"
							   name="select_nume_oferta"
							   placeholder="Tastează pentru a căuta ..."
							/>
					</td>
					<td class="spatiu_stanga">
						<label for="select_id_oferta">Referință</label>
					</td>
					<td>
						<input class="normal extrascurt"
							   id="select_id_oferta"
							   type="text"
							   name="select_id_oferta"
							   placeholder="Caută ..."
							/>
					</td>
					<td></td>
					<td></td>
				</tr>
				</tbody>
			</table>
			<input id="filtre" type="hidden"/>
		</fieldset>
	</form>
<?php
//	$string = "SELECT COUNT(*) FROM oferte";
//	$query = interogare($string, null);
//	$count_all = $query->fetchColumn();
	filtrare_si_afisare();
	exit();
}
if (isset($_POST["optiuni"]["filtrare"])) {
	filtrare_si_afisare();
	exit();
}
if (isset($_POST["optiuni"]["formular_creare"])) {

	date_default_timezone_set('Europe/Bucharest');
	$months = ['Ian', 'Feb', 'Mar', 'Apr', 'Mai', 'Iun', 'Iul', 'Aug', 'Sep', 'Oct', 'Noi', 'Dec='];
	$ziua = getdate()['mday'];
	$luna = getdate()['mon'];
	$an = getdate()['year'];
	$timestamp = getdate()['0'];
	$today = $ziua . '-' . $months[$luna - 1] . '-' . $an;
	$today_MSQL = date('Y-m-d');
	$date1 = mktime(0, 0, 0, $luna, $ziua + 30, $an);
	$ziua1 = getdate($date1)['mday'];
	$luna1 = getdate($date1)['mon'];
	$an1 = getdate($date1)['year'];
	$viitor = $ziua1 . '-' . $months[$luna1 - 1] . '-' . $an1;
	$viitor_MSQL = date('Y-m-d', $date1);
	?>
	<h2>Creare ofertă nouă</h2>
	<form class="formular" action="/" method="post" id="formular_oferta_noua">
		<input id="id_oferta"
			   type="hidden"
			   name="id_oferta"
			   value=""/>
		<table>
			<tbody>
			<tr>
				<td>
					<label for="nume_oferta">Nume proiect</label>
					<input id="nume_oferta"
						   name="nume_oferta"
						   type="text"
						   class="normal lung"
						   value=""/>
				</td>
				<td>
					<label for="data_oferta">Data ofertă / Valabilitate</label>
					<input id="data_oferta"
						   name="data_oferta"
						   type="text"
						   class="datepicker normal data_scurt"
						   data-data=" <?php echo $today_MSQL; ?>"
						   value="<?php echo $today; ?>"/>
					<input id="valabilitate"
						   name="valabilitate"
						   class="normal megascurt"
						   type="text"
						   value="30"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="select_vanzator">Vânzător</label>
					<input id="select_vanzator"
						   class="scurt normal"
						   name="select_vanzator"
						   type="text"
						   placeholder="Selectează ..."
						   data-id=""
						   readonly
						/>
				</td>
				<td>
					<label for="data_expirare">Data expirare ofertă</label>
					<input id="data_expirare"
						   name="data_expirare"
						   type="text"
						   class="data_expirare extrascurt"
						   value="<?php echo $viitor; ?>"
						   data-data="<?php echo $viitor_MSQL; ?>"
						   disabled="disabled"/>
				</td>
			</tr>
			<tr>
				<td rowspan="2">
					<label for="descriere_oferta">Descriere</label>
					<textarea id="descriere_oferta"
							  maxlength="400"
							  spellcheck="false"></textarea>
				</td>
				<td>
					<label for="select_stadiu">Stadiu ofertă</label>
					<input id="select_stadiu"
						   name="select_stadiu"
						   type="text"
						   class="normal extrascurt"
						   value="Deschisă"
						   data-id="0"
						   placeholder="Selectează ..."
						   readonly
						/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="relevant">Inclus în volum ofertare</label>
					<input id="relevant"
						   name="relevant"
						   type="checkbox"
						   checked/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="select_companie">Companie</label>
					<input id="select_companie"
						   name="select_companie"
						   type="text"
						   class="lung normal"
						   value=""
						   data-id=""
						   placeholder="Tastează pentru a căuta ..."/>
				</td>
				<td>
					<label for="select_persoana">Persoană de contact</label>
					<input id="select_persoana"
						   name="select_persoana"
						   type="text"
						   class="normal scurt"
						   value=""
						   data-id=""
						   placeholder="Selectează ..."
						   readonly
						/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="valoare_oferta">Valoare ofertă</label>
					<input id="valoare_oferta"
						   name="valoare_oferta"
						   type="text"
						   placeholder="EUR"
						   class="scurt valoare_oferta"
						   value=""/>
				</td>
				<td></td>
			</tr>
			</tbody>
		</table>
		<!--    <span id="printeaza_oferta" class="buton_printeaza">Printează<span class="sosa">8</span></span>-->
	</form>
	<span id="creaza_oferta" class="submit">Salvează<span class="sosa">å</span></span>
	<span id="renunta" class="buton_renunta">Renunță<span class="sosa">ã</span></span>
	<div id="lista_companii" class="ddm"></div>
	<div id="lista_vanzatori" class="ddm"></div>
	<div id="lista_persoane" class="ddm"></div>
	<div id="lista_stadii" class="ddm">
		<div class="rec">
			<p id="f00">Deschisă</p>
		</div>
		<div class="rec">
			<p id="f01">Câștigată</p>
		</div>
		<div class="rec">
			<p id="f02">Pierdută</p>
		</div>
	</div>
	<?php
	exit();
}
if (isset($_POST["optiuni"]["formular_editare"])) {
// editeaza oferta din baza de date
	$id = $_POST["id"];
	$string = 'SELECT O.id_oferta,
                  O.nume_oferta,
                  DATE_FORMAT(O.data_oferta, "%e-%m-%Y") AS dataoferta,
                  O.data_oferta AS data_oferta_MSQL,
                  O.descriere_oferta,
                  O.id_companie_oferta,
                  O.id_persoana_oferta,
                  O.id_vanzator_oferta,
                  DATE_FORMAT(O.data_expirare, "%e-%m-%Y") AS dataexpirare,
                  O.data_expirare AS data_expirare_MSQL,
                  O.valabilitate,
                  O.valoare_oferta,
                  O.relevant,
                  O.stadiu,
                  C.id_companie,
                  C.nume_companie,
                  V.*,
                  P.id_persoana,
                  P.nume_persoana,
                  P.prenume_persoana
           FROM oferte AS O
           INNER JOIN companii AS C ON O.id_companie_oferta = C.id_companie
           INNER JOIN persoane AS P ON O.id_persoana_oferta = P.id_persoana
           INNER JOIN vanzatori AS V ON O.id_vanzator_oferta = V.id_vanzator
           WHERE O.id_oferta = ?
           LIMIT 1;';
	$data = array($id);
	$query = interogare($string, $data);
	$row = $query->fetch(PDO::FETCH_ASSOC);
	if (count($row) == 1) {
		echo("Inexistent");
		exit();
	}
	?>
	<h2>Modificare ofertă</h2>
	<form class="formular" action="/" method="post" id="formular_oferta_noua">
		<input id="id_oferta"
			   type="hidden"
			   name="id_oferta"
			   value="<?php echo $row['id_oferta']; ?>"/>
		<table>
			<tbody>
			<tr>
				<td>
					<label for="nume_oferta">Nume proiect</label>
					<input id="nume_oferta"
						   name="nume_oferta"
						   type="text"
						   class="normal lung"
						   value="<?php echo $row['nume_oferta']; ?>"/>
				</td>
				<td>
					<label for="data_oferta">Data ofertă / Valabilitate</label>
					<input id="data_oferta"
						   name="data_oferta"
						   type="text"
						   class="datepicker normal data_scurt"
						   data-data="<?php echo $row["data_oferta_MSQL"]; ?>"
						   value="<?php echo(str_replace_assoc($row['dataoferta'], TRUE)); ?>"
						/>
					<input id="valabilitate"
						   name="valabilitate"
						   class="normal megascurt"
						   type="text"
						   value="<?php echo $row['valabilitate']; ?>"/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="select_vanzator">Vânzător</label>
					<input id="select_vanzator"
						   class="scurt normal"
						   name="select_vanzator"
						   type="text"
						   placeholder="Selectează ..."
						   value="<?php echo($row['nume_vanzator'] . ' ' . $row['prenume_vanzator']); ?>"
						   data-id="<?php echo($row['id_vanzator']); ?>"
						   readonly
						/>
				</td>
				<td>
					<label for="data_expirare">Data expirare ofertă</label>
					<input id="data_expirare"
						   name="data_expirare"
						   type="text"
						   class="data_expirare extrascurt"
						   data-data="<?php echo $row["data_expirare_MSQL"]; ?>"
						   value="<?php echo(str_replace_assoc($row['dataexpirare'], TRUE)); ?>"
						   disabled="disabled"/>
				</td>
			</tr>
			<tr>
				<td rowspan="2">
					<label for="descriere_oferta">Descriere</label>
					<textarea id="descriere_oferta"
							  maxlength="400"
							  spellcheck="false"><?php echo($row['descriere_oferta']); ?></textarea>
				</td>
				<td>
					<label for="select_stadiu">Stadiu ofertă</label>
					<input id="select_stadiu"
						   name="select_stadiu"
						   type="text"
						   class="normal extrascurt"
						   value="<?php echo($stadiu[$row['stadiu']]); ?>"
						   data-id="<?php echo($row['stadiu']); ?>"
						   placeholder="Selectează ..."
						   readonly
						/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="relevant">Inclus în volum ofertare</label>
					<input id="relevant"
						   name="relevant"
						   type="checkbox"
						<?php
						if ($row["relevant"]) {
							echo "checked";
						}
						?>
						/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="select_companie">Companie</label>
					<input id="select_companie"
						   name="select_companie"
						   type="text"
						   class="lung normal"
						   value="<?php echo $row["nume_companie"]; ?>"
						   data-id="<?php echo $row["id_companie"]; ?>"
						   placeholder="Tastează pentru a căuta ..."/>
				</td>
				<td>
					<label for="select_persoana">Persoană de contact</label>
					<input id="select_persoana"
						   name="select_persoana"
						   type="text"
						   class="normal scurt"
						   value="<?php echo $row["prenume_persoana"] . ' ' . $row["nume_persoana"]; ?>"
						   data-id="<?php echo $row["id_persoana"]; ?>"
						   placeholder="Selectează ..."
						   readonly
						/>
				</td>
			</tr>
			<tr>
				<td>
					<label for="valoare_oferta">Valoare ofertă</label>
					<input id="valoare_oferta"
						   name="valoare_oferta"
						   type="text"
						   placeholder="EUR"
						   class="scurt valoare_oferta"
						   value="<?php echo $row["valoare_oferta"]; ?>"/>
				</td>
				<td></td>
			</tr>
			</tbody>
		</table>
	</form>
	<span id="editeaza_oferta" class="submit">Modifică<span class="sosa">å</span></span>
	<span id="sterge" class="buton_stergere">Șterge<span class="sosa">ç</span></span>
	<span id="renunta" class="buton_renunta">Renunță<span class="sosa">ã</span></span>
	<div id="lista_companii" class="ddm"></div>
	<div id="lista_vanzatori" class="ddm"></div>
	<div id="lista_persoane" class="ddm"></div>
	<div id="lista_stadii" class="ddm">
		<div class="rec">
			<p id="f00">Deschisă</p>
		</div>
		<div class="rec">
			<p id="f01">Câștigată</p>
		</div>
		<div class="rec">
			<p id="f02">Pierdută</p>
		</div>
	</div>
	<?php
	exit();
}
if (isset($_POST["salveaza"])) {
	$data = $_POST["formdata"];
	if ($_POST["salveaza"]) { // 1-creaza | 0-modifica
		verifica_existenta_oferta(null, $data[1], $data[2], $data[9]);
		$string = 'INSERT INTO `oferte`
					(`nume_oferta`,
					`data_oferta`,
					`valabilitate`,
					`id_vanzator_oferta`,
					`data_expirare`,
					`descriere_oferta`,
					`stadiu`,
					`relevant`,
					`id_companie_oferta`,
					`id_persoana_oferta`,
					`valoare_oferta`,
					`id_oferta`)
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL);';
		array_shift($data);
	} else {
		verifica_existenta_oferta($data[0], $data[1], $data[2], $data[9]);
		$string = 'UPDATE `oferte`
                   	    SET	`nume_oferta` = ? ,
                            `data_oferta` = ?,
					        `valabilitate` = ?,
					        `id_vanzator_oferta` = ?,
					        `data_expirare` = ?,
					        `descriere_oferta` = ?,
					        `stadiu` = ?,
					        `relevant` = ?,
					        `id_companie_oferta` = ?,
					        `id_persoana_oferta` = ?,
					        `valoare_oferta` = ?
                      	WHERE `id_oferta` = ?;';
		array_push($data, array_shift($data));
	}
	$query = interogare($string, $data);
	echo('ok');
	exit();
}
if (isset($_POST["sterge"])) {
	// actiune stergere oferta din baza de date
	$string = 'DELETE FROM `oferte` WHERE `id_oferta` = ? LIMIT 1;';
	$data = array($_POST['id']);
	$query = interogare($string, $data);
	echo('ok');
	exit();
}
?>
