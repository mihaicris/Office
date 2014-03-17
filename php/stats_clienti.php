<?php

include_once 'conexiune.php';

function filtrare_si_afisare()
{
	global $last_year;
	if (isset($_POST["optiuni"]["an"])) {
		$year = $_POST["optiuni"]["an"];
	} else {
		$year = $last_year;
	}

	$query = "SELECT * FROM (
    (SELECT @year := ? AS FY) AS Init,
    (SELECT SUM(valoare_oferta) AS TOTAL_FYP FROM oferte WHERE stadiu = 1 AND YEAR(data_oferta) = @year-1) AS COL1,
    (SELECT SUM(valoare_oferta) AS TOTAL_FY FROM oferte WHERE stadiu = 1 AND YEAR (data_oferta) = @year) AS COL2,
    (SELECT SUM(valoare_oferta) AS M1 FROM oferte WHERE stadiu = 1 AND YEAR(data_oferta) = @year AND MONTH(data_oferta) = 1) AS COL3,
    (SELECT SUM(valoare_oferta) AS M2 FROM oferte WHERE stadiu = 1 AND YEAR(data_oferta) = @year AND MONTH(data_oferta) = 2) AS COL4,
    (SELECT SUM(valoare_oferta) AS M3 FROM oferte WHERE stadiu = 1 AND YEAR(data_oferta) = @year AND MONTH(data_oferta) = 3) AS COL5,
    (SELECT SUM(valoare_oferta) AS M4 FROM oferte WHERE stadiu = 1 AND YEAR(data_oferta) = @year AND MONTH(data_oferta) = 4) AS COL6,
    (SELECT SUM(valoare_oferta) AS M5 FROM oferte WHERE stadiu = 1 AND YEAR(data_oferta) = @year AND MONTH(data_oferta) = 5) AS COL7,
    (SELECT SUM(valoare_oferta) AS M6 FROM oferte WHERE stadiu = 1 AND YEAR(data_oferta) = @year AND MONTH(data_oferta) = 6) AS COL8,
    (SELECT SUM(valoare_oferta) AS M7 FROM oferte WHERE stadiu = 1 AND YEAR(data_oferta) = @year AND MONTH(data_oferta) = 7) AS COL9,
    (SELECT SUM(valoare_oferta) AS M8 FROM oferte WHERE stadiu = 1 AND YEAR(data_oferta) = @year AND MONTH(data_oferta) = 8) AS COL10,
    (SELECT SUM(valoare_oferta) AS M9 FROM oferte WHERE stadiu = 1 AND YEAR(data_oferta) = @year AND MONTH(data_oferta) = 9) AS COL11,
    (SELECT SUM(valoare_oferta) AS M10 FROM oferte WHERE stadiu = 1 AND YEAR(data_oferta) = @year AND MONTH(data_oferta) = 10) AS COL12,
    (SELECT SUM(valoare_oferta) AS M11 FROM oferte WHERE stadiu = 1 AND YEAR(data_oferta) = @year AND MONTH(data_oferta) = 11) AS COL13,
    (SELECT SUM(valoare_oferta) AS M12 FROM oferte WHERE stadiu = 1 AND YEAR(data_oferta) = @year AND MONTH(data_oferta) = 12) AS COL14
);";
	$header = interogare($query, array($year));
	$query = "SELECT FINAL.* FROM (SELECT
        @i := @i + 1 AS Rank, results.*
      FROM
        (SELECT @i := 0) AS foo,
        (SELECT @an := ?) AS an,
        (SELECT @comp := companii.id_companie AS id_companie,
           companii.nume_companie, companii.oras_companie, companii.tara_companie,
                (SELECT SUM(oferte.valoare_oferta) AS GRAND FROM oferte
                WHERE stadiu = 1 AND YEAR(data_oferta) = @an-1 AND oferte.id_companie_oferta = @comp) AS FYP,
                (SELECT SUM(oferte.valoare_oferta) AS GRAND FROM oferte
                 WHERE stadiu = 1 AND YEAR (data_oferta) = @an AND oferte.id_companie_oferta = @comp) AS FY,
                (SELECT SUM(oferte.valoare_oferta) AS Ian FROM oferte
                 WHERE stadiu = 1 AND YEAR (data_oferta) = @an AND MONTH(data_oferta) = 1 AND oferte.id_companie_oferta = @comp) AS M1,
                (SELECT SUM(oferte.valoare_oferta) AS Feb FROM oferte
                 WHERE stadiu = 1 AND YEAR (data_oferta) = @an AND MONTH(data_oferta) = 2 AND oferte.id_companie_oferta = @comp) AS M2,
                (SELECT SUM(oferte.valoare_oferta) AS Mar FROM oferte
                 WHERE stadiu = 1 AND YEAR (data_oferta) = @an AND MONTH(data_oferta) = 3 AND oferte.id_companie_oferta = @comp) AS M3,
                (SELECT SUM(oferte.valoare_oferta) AS Apr FROM oferte
                 WHERE stadiu = 1 AND YEAR (data_oferta) = @an AND MONTH(data_oferta) = 4 AND oferte.id_companie_oferta = @comp) AS M4,
                (SELECT SUM(oferte.valoare_oferta) AS Mai FROM oferte
                 WHERE stadiu = 1 AND YEAR (data_oferta) = @an AND MONTH(data_oferta) = 5 AND oferte.id_companie_oferta = @comp) AS M5,
                (SELECT SUM(oferte.valoare_oferta) AS Iun FROM oferte
                 WHERE stadiu = 1 AND YEAR (data_oferta) = @an AND MONTH(data_oferta) = 6 AND oferte.id_companie_oferta = @comp) AS M6,
                (SELECT SUM(oferte.valoare_oferta) AS Iul FROM oferte
                 WHERE stadiu = 1 AND YEAR (data_oferta) = @an AND MONTH(data_oferta) = 7 AND oferte.id_companie_oferta = @comp) AS M7,
                (SELECT SUM(oferte.valoare_oferta) AS Aug FROM oferte
                 WHERE stadiu = 1 AND YEAR (data_oferta) = @an AND MONTH(data_oferta) = 8 AND oferte.id_companie_oferta = @comp) AS M8,
                (SELECT SUM(oferte.valoare_oferta) AS Sep FROM oferte
                 WHERE stadiu = 1 AND YEAR (data_oferta) = @an AND MONTH(data_oferta) = 9 AND oferte.id_companie_oferta = @comp) AS M9,
                (SELECT SUM(oferte.valoare_oferta) AS Oct FROM oferte
                 WHERE stadiu = 1 AND YEAR (data_oferta) = @an AND MONTH(data_oferta) = 10 AND oferte.id_companie_oferta = @comp) AS M10,
                (SELECT SUM(oferte.valoare_oferta) AS Nov FROM oferte
                 WHERE stadiu = 1 AND YEAR (data_oferta) = @an AND MONTH(data_oferta) = 11 AND oferte.id_companie_oferta = @comp) AS M11,
                (SELECT SUM(oferte.valoare_oferta) AS Decem FROM oferte
                 WHERE stadiu = 1 AND YEAR (data_oferta) = @an AND MONTH(data_oferta) = 12 AND oferte.id_companie_oferta = @comp) AS M12
         FROM oferte
           INNER JOIN companii ON oferte.id_companie_oferta = companii.id_companie
         WHERE (stadiu = 1) AND (YEAR(data_oferta) = @an OR YEAR(data_oferta) = @an-1)
         GROUP BY oferte.id_companie_oferta
         ORDER BY FY DESC) AS results
     ) AS FINAL
ORDER BY Rank;";
	$content = interogare($query, array($year));

	$row = $header->fetch();

	echo '<span class="to_remove titluri">Comenzi detaliate pentru clienți<br><br></span>';
	$h = '<table class="to_remove" id="stat_clienti">';
	$h .= '<tr>';
	$h .= '<td id="gol" colspan="4"></td>';
	$h .= '<td class="ac head w5">';
	$h .= $year - 1;
	$h .= '</td>';
	$h .= '<td class="ac head w6">';
	$h .= $year;
	$h .= '</td>';
	$h .= '<td class="ac head wM">Ian.</td>';
	$h .= '<td class="ac head wM">Feb.</td>';
	$h .= '<td class="ac head wM">Mar.</td>';
	$h .= '<td class="ac head wM">Apr.</td>';
	$h .= '<td class="ac head wM">Mai</td>';
	$h .= '<td class="ac head wM">Iun.</td>';
	$h .= '<td class="ac head wM">Iul.</td>';
	$h .= '<td class="ac head wM">Aug.</td>';
	$h .= '<td class="ac head wM">Sep.</td>';
	$h .= '<td class="ac head wM">Oct.</td>';
	$h .= '<td class="ac head wM">Noi.</td>';
	$h .= '<td class="ac head wM">Dec.</td>';
	$h .= '</tr>';
	$h .= '<tr>';
	$h .= '<td class="ac head w1">Rank</td>';
	$h .= '<td class="ac head w2">Companie</td>';
	$h .= '<td class="ac head w3">Oraş</td>';
	$h .= '<td class="ac head w4">Ţară</td>';
	$h .= '<td class="ar totFYP w5">' . formatare($row["TOTAL_FYP"]) . '</td>';
	$h .= '<td class="ar totFY w6">' . formatare($row["TOTAL_FY"]) . '</td>';
	$h .= '<td class="ar Mtot wM">' . formatare($row["M1"]) . '</td>';
	$h .= '<td class="ar Mtot wM">' . formatare($row["M2"]) . '</td>';
	$h .= '<td class="ar Mtot wM">' . formatare($row["M3"]) . '</td>';
	$h .= '<td class="ar Mtot wM">' . formatare($row["M4"]) . '</td>';
	$h .= '<td class="ar Mtot wM">' . formatare($row["M5"]) . '</td>';
	$h .= '<td class="ar Mtot wM">' . formatare($row["M6"]) . '</td>';
	$h .= '<td class="ar Mtot wM">' . formatare($row["M7"]) . '</td>';
	$h .= '<td class="ar Mtot wM">' . formatare($row["M8"]) . '</td>';
	$h .= '<td class="ar Mtot wM">' . formatare($row["M9"]) . '</td>';
	$h .= '<td class="ar Mtot wM">' . formatare($row["M10"]) . '</td>';
	$h .= '<td class="ar Mtot wM">' . formatare($row["M11"]) . '</td>';
	$h .= '<td class="ar Mtot wM">' . formatare($row["M12"]) . '</td>';
	$h .= '</tr>';

	for ($i = 0; $row = $content->fetch(); $i++) {
		$h .= '<tr>';
		$h .= '<td class="ac w1 cH">' . $row["Rank"] . '</td>';
		$h .= '<td class="al w2 cH companie">' . $row["nume_companie"] . '</td>';
		$h .= '<td class="ac w3 cH">' . $row["oras_companie"] . '</td>';
		$h .= '<td class="ac w4 cH">' . $row["tara_companie"] . '</td>';
		$h .= '<td class="ar w5 cFYP">' . formatare($row["FYP"]) . '</td>';
		$h .= '<td class="ar w6 cFY">' . formatare($row["FY"]) . '</td>';
		$h .= '<td class="ar wM cM">' . formatare($row["M1"]) . '</td>';
		$h .= '<td class="ar wM cM">' . formatare($row["M2"]) . '</td>';
		$h .= '<td class="ar wM cM">' . formatare($row["M3"]) . '</td>';
		$h .= '<td class="ar wM cM">' . formatare($row["M4"]) . '</td>';
		$h .= '<td class="ar wM cM">' . formatare($row["M5"]) . '</td>';
		$h .= '<td class="ar wM cM">' . formatare($row["M6"]) . '</td>';
		$h .= '<td class="ar wM cM">' . formatare($row["M7"]) . '</td>';
		$h .= '<td class="ar wM cM">' . formatare($row["M8"]) . '</td>';
		$h .= '<td class="ar wM cM">' . formatare($row["M9"]) . '</td>';
		$h .= '<td class="ar wM cM">' . formatare($row["M10"]) . '</td>';
		$h .= '<td class="ar wM cM">' . formatare($row["M11"]) . '</td>';
		$h .= '<td class="ar wM cM">' . formatare($row["M12"]) . '</td>';
		$h .= '</tr>';
		if ($row["FY"]) {
			$values[$i] = $row["FY"] / 1e6;
		}

	}
	$h .= '</table>';
	echo $h;

	?>



<?php

}

if (isset($_POST["optiuni"]["listare"])) {

	echo('<h2>Statistici clienti</h2>');
	$flag = 0;
	$string = "SELECT DISTINCT YEAR(data_oferta) AS ani FROM oferte WHERE stadiu = 1 ORDER BY data_oferta DESC";
	$query = interogare($string, null);
	$ani = $query->fetchAll();
	if (count($ani)) {
		$html = '<div id="lista_ani" class="ddm">';
		for ($i = 0; $i < count($ani); $i++) {
			$html .= '<div class="rec align_center">';
			$html .= '<p id="f' . $ani[$i]["ani"] . '">' . $ani[$i]["ani"] . '</p>';
			$html .= '</div>';
			if (!$flag) {
				$last_year = $ani[$i]["ani"];
				$flag = 1;
			}
		}
		$html .= '</div>';
		echo $html;
	} else {
		echo('<span class="total to_remove">Nu sunt date pentru raport.</span>');
		exit();
	}
	?>

	<form action="/" method="post" id="formular_filtre">
		<fieldset id="filtre_clienti">
			<table>
				<tbody>
				<tr>
					<td>
						<label for="select_an">An financiar</label>
					</td>
					<td>
						<input class="normal superscurt"
							   id="select_an"
							   type="text"
							   name="select_an"
							   value="<?php echo $last_year ?>"
							   readonly/>
					</td>
				</tr>
				</tbody>
			</table>
			<input id="filtre" type="hidden"/>
		</fieldset>
	</form>
	<?php
	filtrare_si_afisare();
}
if (isset($_POST["optiuni"]["filtrare"])) {

	filtrare_si_afisare();
	exit();
}

exit()
?>
