<?php
include_once 'conexiune.php';

function formatare($n)
{
	return ($n ? number_format($n, 0, ",", ".") : "-");
}

function afisare_tabel($header, $content)
{
	$row = $header->fetch();
	$h = '<table class="rezultate">';
	$h .= '<tr>';
	$h .= '<th colspan="4"></td>';
	$h .= '<th class="align_center">FY-1 TOTAL</th>';
	$h .= '<th class="align_center">FY TOTAL</th>';
	$h .= '<th class="align_center">Ianuarie</th>';
	$h .= '<th class="align_center">Februarie</th>';
	$h .= '<th class="align_center">Martie</th>';
	$h .= '<th class="align_center">Aprilie</th>';
	$h .= '<th class="align_center">Mai</th>';
	$h .= '<th class="align_center">Iunie</th>';
	$h .= '<th class="align_center">Iulie</th>';
	$h .= '<th class="align_center">August</th>';
	$h .= '<th class="align_center">Septembrie</th>';
	$h .= '<th class="align_center">Octombrie</th>';
	$h .= '<th class="align_center">Noiembrie</th>';
	$h .= '<th class="align_center">Decembrie</th>';
	$h .= '</tr>';
	$h .= '<tr>';
	$h .= '<th>Rank</th>';
	$h .= '<th class="w_nume">Companie</th>';
	$h .= '<th>Oraş</th>';
	$h .= '<th>Ţară</th>';
	$h .= '<td class="align_right">' . formatare($row["TOTAL_FYP"]) . '</td>';
	$h .= '<td class="align_right">' . formatare($row["TOTAL_FY"]) . '</td>';
	$h .= '<td class="align_right">' . formatare($row["M1"]) . '</td>';
	$h .= '<td class="align_right">' . formatare($row["M2"]) . '</td>';
	$h .= '<td class="align_right">' . formatare($row["M3"]) . '</td>';
	$h .= '<td class="align_right">' . formatare($row["M4"]) . '</td>';
	$h .= '<td class="align_right">' . formatare($row["M5"]) . '</td>';
	$h .= '<td class="align_right">' . formatare($row["M6"]) . '</td>';
	$h .= '<td class="align_right">' . formatare($row["M7"]) . '</td>';
	$h .= '<td class="align_right">' . formatare($row["M8"]) . '</td>';
	$h .= '<td class="align_right">' . formatare($row["M9"]) . '</td>';
	$h .= '<td class="align_right">' . formatare($row["M10"]) . '</td>';
	$h .= '<td class="align_right">' . formatare($row["M11"]) . '</td>';
	$h .= '<td class="align_right">' . formatare($row["M12"]) . '</td>';
	$h .= '</tr>';
	$h .= '</tr>';

	for ($i = 0; $row = $content->fetch(); $i++) {
		$h .= '<tr>';
		$h .= '<td class="align_center">' . $row["Rank"] . '</td>';
		$h .= '<td class="companie w_nume">' . $row["nume_companie"] . '</td>';
		$h .= '<td class="align_center">' . $row["oras_companie"] . '</td>';
		$h .= '<td class="align_center">' . $row["tara_companie"] . '</td>';
		$h .= '<td class="align_right">' . formatare($row["FYP"]) . '</td>';
		$h .= '<td class="align_right">' . formatare($row["FY"]) . '</td>';
		$h .= '<td class="align_right">' . formatare($row["M1"]) . '</td>';
		$h .= '<td class="align_right">' . formatare($row["M2"]) . '</td>';
		$h .= '<td class="align_right">' . formatare($row["M3"]) . '</td>';
		$h .= '<td class="align_right">' . formatare($row["M4"]) . '</td>';
		$h .= '<td class="align_right">' . formatare($row["M5"]) . '</td>';
		$h .= '<td class="align_right">' . formatare($row["M6"]) . '</td>';
		$h .= '<td class="align_right">' . formatare($row["M7"]) . '</td>';
		$h .= '<td class="align_right">' . formatare($row["M8"]) . '</td>';
		$h .= '<td class="align_right">' . formatare($row["M9"]) . '</td>';
		$h .= '<td class="align_right">' . formatare($row["M10"]) . '</td>';
		$h .= '<td class="align_right">' . formatare($row["M11"]) . '</td>';
		$h .= '<td class="align_right">' . formatare($row["M12"]) . '</td>';
		$h .= '</tr>';
	}
	$h .= '</table>';
	echo $h;
}

?>
<h2>Statistici clienti</h2>

<?php

$query = 'SELECT * FROM (
    (SELECT @year := ? AS FY) AS Init,
    (SELECT SUM(valoare_oferta) AS TOTAL_FYP FROM oferte WHERE YEAR (data_oferta) = @year-1) AS COL1,
    (SELECT SUM(valoare_oferta) AS TOTAL_FY FROM oferte WHERE YEAR (data_oferta) = @year) AS COL2,
    (SELECT SUM(valoare_oferta) AS M1 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 1) AS COL3,
    (SELECT SUM(valoare_oferta) AS M2 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 2) AS COL4,
    (SELECT SUM(valoare_oferta) AS M3 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 3) AS COL5,
    (SELECT SUM(valoare_oferta) AS M4 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 4) AS COL6,
    (SELECT SUM(valoare_oferta) AS M5 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 5) AS COL7,
    (SELECT SUM(valoare_oferta) AS M6 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 6) AS COL8,
    (SELECT SUM(valoare_oferta) AS M7 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 7) AS COL9,
    (SELECT SUM(valoare_oferta) AS M8 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 8) AS COL10,
    (SELECT SUM(valoare_oferta) AS M9 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 9) AS COL11,
    (SELECT SUM(valoare_oferta) AS M10 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 10) AS COL12,
    (SELECT SUM(valoare_oferta) AS M11 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 11) AS COL13,
    (SELECT SUM(valoare_oferta) AS M12 FROM oferte WHERE YEAR(data_oferta) = @year AND MONTH(data_oferta) = 12) AS COL14
);';
$header = interogare($query, array("2014"));
$query = 'SELECT FINAL.* FROM (SELECT
        @i := @i + 1 AS Rank, results.*
      FROM
        (SELECT @i := 0) AS foo,
        (SELECT @an := ?) AS an,
        (SELECT @comp := companii.id_companie AS id_companie,
           companii.nume_companie, companii.oras_companie, companii.tara_companie,
                (SELECT SUM(oferte.valoare_oferta) AS GRAND FROM oferte
                WHERE YEAR(data_oferta) = @an-1 AND oferte.id_companie_oferta = @comp) AS FYP,
                (SELECT SUM(oferte.valoare_oferta) AS GRAND FROM oferte
                WHERE YEAR(data_oferta) = @an AND oferte.id_companie_oferta = @comp) AS FY,
                (SELECT SUM(oferte.valoare_oferta) AS Ian FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 1 AND oferte.id_companie_oferta = @comp) AS M1,
                (SELECT SUM(oferte.valoare_oferta) AS Feb FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 2 AND oferte.id_companie_oferta = @comp) AS M2,
                (SELECT SUM(oferte.valoare_oferta) AS Mar FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 3 AND oferte.id_companie_oferta = @comp) AS M3,
                (SELECT SUM(oferte.valoare_oferta) AS Apr FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 4 AND oferte.id_companie_oferta = @comp) AS M4,
                (SELECT SUM(oferte.valoare_oferta) AS Mai FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 5 AND oferte.id_companie_oferta = @comp) AS M5,
                (SELECT SUM(oferte.valoare_oferta) AS Iun FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 6 AND oferte.id_companie_oferta = @comp) AS M6,
                (SELECT SUM(oferte.valoare_oferta) AS Iul FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 7 AND oferte.id_companie_oferta = @comp) AS M7,
                (SELECT SUM(oferte.valoare_oferta) AS Aug FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 8 AND oferte.id_companie_oferta = @comp) AS M8,
                (SELECT SUM(oferte.valoare_oferta) AS Sep FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 9 AND oferte.id_companie_oferta = @comp) AS M9,
                (SELECT SUM(oferte.valoare_oferta) AS Oct FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 10 AND oferte.id_companie_oferta = @comp) AS M10,
                (SELECT SUM(oferte.valoare_oferta) AS Nov FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 11 AND oferte.id_companie_oferta = @comp) AS M11,
                (SELECT SUM(oferte.valoare_oferta) AS Decem FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 12 AND oferte.id_companie_oferta = @comp) AS M12
         FROM oferte
           INNER JOIN companii ON oferte.id_companie_oferta = companii.id_companie
         GROUP BY oferte.id_companie_oferta
         ORDER BY FY DESC) AS results
     ) AS FINAL
ORDER BY Rank;';
$content = interogare($query, array("2014"));

afisare_tabel($header, $content);
exit()
?>
