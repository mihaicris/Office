<?php
include_once 'conexiune.php';

function afiseaza_rezultate($query)
{
    function str_replace_assoc($subject)
    {
        $replace = array(
            '-1-' => '-Ian-',
            '-2-' => '-Feb-',
            '-3-' => '-Mar-',
            '-4-' => '-Apr-',
            '-5-' => '-Mai-',
            '-6-' => '-Iun-',
            '-7-' => '-Iul-',
            '-8-' => '-Aug-',
            '-9-' => '-Sep-',
            '-10-' => '-Oct-',
            '-11-' => '-Noi-',
            '-12-' => '-Dec-',
        );
        return str_replace(array_keys($replace), array_values($replace), $subject);
    }

    $stadiu = ["Deshisă", "Câştigată", "Pierdută"];
    echo '<table class="rezultate">';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Nume proiect</th>';
    echo '<th>Data</th>';
    echo '<th>Companie</th>';
    echo '<th>Vânzător</th>';
    echo '<th>Valoare</th>';
    echo '<th>Stadiu</th>';
    echo "</tr>";
    for ($i = 0; $row = $query->fetch(); $i++) {
        echo '<tr>';
        echo '<td id="f' . $row['id_oferta'] . '"><span class="id">' . $row['id_oferta'] . '</span><span class="sosa actiune">a</span></td>';
        echo '<td title="' . $row['descriere_oferta'] . '">' . $row['nume_oferta'] . '</td>';
        echo '<td>' . str_replace_assoc($row['data_oferta']) . '</td>';
        echo '<td>' . $row['nume_companie'] . '</td>';
        echo '<td>' . $row['prenume_vanzator'] . ' ' . $row['nume_vanzator'] . '</td>';
        echo '<td>' . $row['valoare_oferta'] . '</td>';
        echo '<td>' . $stadiu[$row['stadiu']] . '</td>';
        echo '</tr>';
    } //end for
    echo '</table>';
}

?>
<h2>Listă oferte</h2>
<?php
$string = 'SELECT COUNT(*) FROM `oferte`;';
$query = interogare($string, NULL);
$count = $query->fetchColumn();
if (!$count) {
    echo '<p>Nu există oferte în baza de date.</p>';
    exit();
}
$string = 'SELECT O.id_oferta,
                  O.nume_oferta,
                  O.descriere_oferta,
                  DATE_FORMAT(O.data_oferta, "%e-%c-%Y") AS data_oferta,
                  O.id_companie_oferta,
                  O.id_vanzator_oferta,
                  O.valoare_oferta,
                  O.stadiu,
                  C.nume_companie,
                  V.nume_vanzator, V.prenume_vanzator
           FROM oferte AS O
           LEFT JOIN companii AS C ON O.id_companie_oferta = C.id_companie
           LEFT JOIN vanzatori AS V ON O.id_vanzator_oferta = V.id_vanzator
           ORDER BY `data_oferta` DESC
           LIMIT 10;';
$query = interogare($string, NULL);
afiseaza_rezultate($query);
?>
