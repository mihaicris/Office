<?php
include('conexiune.php');

function filtrare_si_afisare()
{
	if (isset($_POST["optiuni"]["width"])) {
		$width = $_POST["optiuni"]["width"] * 0.99;
		$width = $width > 1000 ? 1000 : $width;
	} else {
		$width = 1000;
	}

	global $last_year;
	if (isset($_POST["optiuni"]["an"])) {
		$year = $_POST["optiuni"]["an"];
	} else {
		$year = $last_year;
	}

	$query = "SELECT * FROM (
    (SELECT @year := ? AS FY) AS Init,
    (SELECT SUM(valoare_oferta) AS TOTAL_FYP FROM oferte WHERE YEAR(data_oferta) = @year-1) AS COL1,
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
);";
	$header = interogare($query, array($year));

	$query = "SELECT FINAL.* FROM (SELECT
        @i := @i + 1 AS Rank, results.*
      FROM
        (SELECT @i := 0) AS foo,
        (SELECT @an := ?) AS an,
        (SELECT @vanzator := vanzatori.id_vanzator AS id_vanzator,
           concat(vanzatori.nume_vanzator, ' ', vanzatori.prenume_vanzator) AS Vanzator,
                (SELECT SUM(oferte.valoare_oferta) AS GRAND FROM oferte
                WHERE YEAR(data_oferta) = @an-1 AND oferte.id_vanzator_oferta = @vanzator) AS FYP,
                (SELECT SUM(oferte.valoare_oferta) AS GRAND FROM oferte
                WHERE YEAR(data_oferta) = @an AND oferte.id_vanzator_oferta = @vanzator) AS FY,
                (SELECT SUM(oferte.valoare_oferta) AS Ian FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 1 AND oferte.id_vanzator_oferta = @vanzator) AS M1,
                (SELECT SUM(oferte.valoare_oferta) AS Feb FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 2 AND oferte.id_vanzator_oferta = @vanzator) AS M2,
                (SELECT SUM(oferte.valoare_oferta) AS Mar FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 3 AND oferte.id_vanzator_oferta = @vanzator) AS M3,
                (SELECT SUM(oferte.valoare_oferta) AS Apr FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 4 AND oferte.id_vanzator_oferta = @vanzator) AS M4,
                (SELECT SUM(oferte.valoare_oferta) AS Mai FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 5 AND oferte.id_vanzator_oferta = @vanzator) AS M5,
                (SELECT SUM(oferte.valoare_oferta) AS Iun FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 6 AND oferte.id_vanzator_oferta = @vanzator) AS M6,
                (SELECT SUM(oferte.valoare_oferta) AS Iul FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 7 AND oferte.id_vanzator_oferta = @vanzator) AS M7,
                (SELECT SUM(oferte.valoare_oferta) AS Aug FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 8 AND oferte.id_vanzator_oferta = @vanzator) AS M8,
                (SELECT SUM(oferte.valoare_oferta) AS Sep FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 9 AND oferte.id_vanzator_oferta = @vanzator) AS M9,
                (SELECT SUM(oferte.valoare_oferta) AS Oct FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 10 AND oferte.id_vanzator_oferta = @vanzator) AS M10,
                (SELECT SUM(oferte.valoare_oferta) AS Nov FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 11 AND oferte.id_vanzator_oferta = @vanzator) AS M11,
                (SELECT SUM(oferte.valoare_oferta) AS Decem FROM oferte
                WHERE YEAR(data_oferta) = @an AND MONTH(data_oferta) = 12 AND oferte.id_vanzator_oferta = @vanzator) AS M12
         FROM oferte
           INNER JOIN vanzatori ON oferte.id_vanzator_oferta = vanzatori.id_vanzator
         WHERE YEAR(data_oferta) = @an OR YEAR(data_oferta) = @an-1
         GROUP BY oferte.id_vanzator_oferta
         ORDER BY FY DESC) AS results
     ) AS FINAL
ORDER BY Rank;";
	$content = interogare($query, array($year));

	$row = $header->fetch();

	echo '<span class="to_remove titluri">Ofertare detaliată pentru vânzători<br><br></span>';
	$h = '<table class="to_remove" id="stat_clienti">';
	$h .= '<tr>';
	$h .= '<td id="gol" colspan="2"></td>';
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
	$h .= '<td class="ac head w2">Vânzător</td>';
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

	$valori_lunare = [
		$row["M1"] ? $row["M1"] : 0,
		$row["M2"] ? $row["M2"] : 0,
		$row["M3"] ? $row["M3"] : 0,
		$row["M4"] ? $row["M4"] : 0,
		$row["M5"] ? $row["M5"] : 0,
		$row["M6"] ? $row["M6"] : 0,
		$row["M7"] ? $row["M7"] : 0,
		$row["M8"] ? $row["M8"] : 0,
		$row["M9"] ? $row["M9"] : 0,
		$row["M10"] ? $row["M10"] : 0,
		$row["M11"] ? $row["M11"] : 0,
		$row["M12"] ? $row["M12"] : 0
	];

	for ($i = 0; $row = $content->fetch(); $i++) {
		$h .= '<tr>';
		$h .= '<td class="ac w1 cH">' . $row["Rank"] . '</td>';
		$h .= '<td class="al w2 cH nume">' . $row["Vanzator"] . '</td>';
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
	}
	$h .= '</table>';
	echo $h;

	?>
	<span class="to_remove titluri"><br>Grafic oferte lunare<br></span>
	<canvas class="to_remove" id="canvas2" height="301" width="<?php echo $width; ?>"></canvas>
	<script class="to_remove">
		var barChartData = {
			labels:   ["Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie", "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"],
			datasets: [
				{
					fillColor:   "rgba(64, 150, 241, 0.3)",
					strokeColor: "rgba(200, 200, 200, 0.6)",
					data:        [<?php                     // numerele trebuie sa fie de forma 1000.50 cand treci la float
                        for ($i = 0; $i < 12; $i++)
                        {
                             echo $valori_lunare[$i];
                             if ($i != 11) {
                                echo ", ";
                             }
                        }
                    ?>]
				}
			]
		};
		var options = {
			//Boolean - If we show the scale above the chart data
			scaleOverlay:        false,

			//Boolean - If we want to override with a hard coded scale
			scaleOverride:       false,

			//** Required if scaleOverride is true **
			//Number - The number of steps in a hard coded scale
			scaleSteps:          null,
			//Number - The value jump in the hard coded scale
			scaleStepWidth:      null,
			//Number - The scale starting value
			scaleStartValue:     0,

			//String - Colour of the scale line
			scaleLineColor:      "rgba(255,255,255,1)",

			//Number - Pixel width of the scale line
			scaleLineWidth:      2,

			//Boolean - Whether to show labels on the scale
			scaleShowLabels:     true,

			//Interpolated JS string - can access value

			//String - Scale label font declaration for the scale label
			scaleFontFamily:     "Roboto Condensed",

			//Number - Scale label font size in pixels
			scaleFontSize:       12,

			//String - Scale label font weight style
			scaleFontStyle:      "normal",

			//String - Scale label font colour
			scaleFontColor:      "#DDD",

			///Boolean - Whether grid lines are shown across the chart
			scaleShowGridLines:  true,

			//String - Colour of the grid lines
			scaleGridLineColor:  "rgba(255,255,255,.04)",

			//Number - Width of the grid lines
			scaleGridLineWidth:  1,

			//Boolean - If there is a stroke on each bar
			barShowStroke:       true,

			//Number - Pixel width of the bar stroke
			barStrokeWidth:      1,

			//Number - Spacing between each of the X value sets
			barValueSpacing:     <?php echo $width/18; ?>,

			//Number - Spacing between data sets within X values
			barDatasetSpacing:   1,

			//Boolean - Whether to animate the chart
			animation:           true,

			//Number - Number of animation steps
			animationSteps:      80,

			//String - Animation easing effect
			animationEasing:     "easeOutQuart",

			//Function - Fires when the animation is complete
			onAnimationComplete: null,

			// Number - Colour of the value label
			barLabelFontColor: "#FF8"
		};
		var myLine = new Chart(document.getElementById("canvas2").getContext("2d")).Bar(barChartData, options);
	</script>
<?php
}

if (isset($_POST["optiuni"]["listare"])) {

	echo('<h2>Statistici ofertare</h2>');
	$flag = 0;
	$string = "SELECT DISTINCT YEAR(data_oferta) AS ani FROM oferte ORDER BY data_oferta DESC";
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
		<fieldset id="filtre_ofertare">
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
	exit();
}
if (isset($_POST["optiuni"]["filtrare"])) {
	filtrare_si_afisare();
	exit();
}
