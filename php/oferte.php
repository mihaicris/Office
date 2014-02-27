<?php
include_once 'conexiune.php';

$stadiu = ["Deschisă", "Câştigată", "Pierdută"];

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

function afiseaza_rezultate($query)
{
    global $stadiu;
    echo '<table class="rezultate">';
    echo '<tr>';
    echo '<th>Referință</th>';
    echo '<th>Nume proiect</th>';
    echo '<th>Data</th>';
    echo '<th>Companie</th>';
    echo '<th>Vânzător</th>';
    echo '<th>Valoare</th>';
    echo '<th>Relevant</th>';
    echo '<th>Stadiu</th>';
    echo "</tr>";
    for ($i = 0; $row = $query->fetch(); $i++) {
        echo '<tr>';
        echo '<td id="f' . $row['id_oferta'] . '"><span class="id">' . $row['id_oferta'] . '</span><span class="sosa actiune">a</span></td>';
        echo '<td title="' . $row['descriere_oferta'] . '">' . $row['nume_oferta'] . '</td>';
        echo '<td>' . str_replace_assoc($row['data_oferta']) . '</td>';
        echo '<td class="companie">' . $row['nume_companie'] . '</td>';
        echo '<td class="nume">' . $row['nume_vanzator'] . ' ' . $row['prenume_vanzator'] . '</td>';
        echo '<td>' . $row['valoare_oferta'] . '</td>';
        echo '<td class="align_center">';
        if ($row["relevant"]) {
            echo 'Da';
        } else {
            echo 'Nu';
        }
        echo '</td>';
        echo '<td class="stadiu_' . $row['stadiu'] . '">' . $stadiu[$row['stadiu']] . '</td>';
        echo '</tr>';
    } //end for
    echo '</table>';
}

if (isset($_POST["optiuni"]["listare"])) {
    echo "<h2>Listă oferte</h2>";
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
                  O.relevant,
                  C.nume_companie,
                  V.nume_vanzator, V.prenume_vanzator
           FROM oferte AS O
           LEFT JOIN companii AS C ON O.id_companie_oferta = C.id_companie
           LEFT JOIN vanzatori AS V ON O.id_vanzator_oferta = V.id_vanzator
           ORDER BY `data_oferta` DESC
           LIMIT 10;';
    $query = interogare($string, NULL);
    afiseaza_rezultate($query);
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
                           class="datepicker normal extrascurt"
                           data-data="<?php echo $today_MSQL; ?>"
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
                           placeholder="Selectează..."
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
                           placeholder="Selectează..."
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
                           placeholder="Tastează pentru a căuta..."/>
                </td>
                <td>
                    <label for="select_persoana">Persoană de contact</label>
                    <input id="select_persoana"
                           name="select_persoana"
                           type="text"
                           class="normal scurt"
                           value=""
                           data-id=""
                           placeholder="Selectează..."
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
                  DATE_FORMAT(O.data_oferta, "%e-%c-%Y") AS data_oferta,
                  O.data_oferta AS data_oferta_MSQL,
                  O.descriere_oferta,
                  O.id_companie_oferta,
                  O.id_persoana_oferta,
                  O.id_vanzator_oferta,
                  DATE_FORMAT(O.data_expirare, "%e-%c-%Y") AS data_expirare,
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
           LEFT JOIN companii AS C ON O.id_companie_oferta = C.id_companie
           LEFT JOIN persoane AS P ON O.id_persoana_oferta = P.id_persoana
           LEFT JOIN vanzatori AS V ON O.id_vanzator_oferta = V.id_vanzator
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
                           class="datepicker normal extrascurt"
                           data-data="<?php echo $row["data_oferta_MSQL"]; ?>"
                           value="<?php echo(str_replace_assoc($row['data_oferta'])); ?>"
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
                           placeholder="Selectează..."
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
                           value="<?php echo(str_replace_assoc($row['data_expirare'])); ?>"
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
                           placeholder="Selectează..."
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
                           placeholder="Tastează pentru a căuta..."/>
                </td>
                <td>
                    <label for="select_persoana">Persoană de contact</label>
                    <input id="select_persoana"
                           name="select_persoana"
                           type="text"
                           class="normal scurt"
                           value="<?php echo $row["prenume_persoana"] . ' ' . $row["nume_persoana"]; ?>"
                           data-id="<?php echo $row["id_persoana"]; ?>"
                           placeholder="Selectează..."
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
        <!--    <span id="printeaza_oferta" class="buton_printeaza">Printează<span class="sosa">8</span></span>-->
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
}
if (isset($_POST["salveaza"])) {
    $data = $_POST["formdata"];
    if ($_POST["salveaza"]) { // 1-creaza | 0-modifica
//        verifica_existenta_persoana(null, $data[1], $data[2], $data[8]);
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
//        verifica_existenta_persoana($data[0], $data[1], $data[2], $data[8]); // data[0] contine id-ul persoanei care se modifica
//        $string = 'UPDATE `persoane`
//                   	    SET	`nume_persoana` = ?,
//							`prenume_persoana` = ?,
//							`tel_persoana` = ?,
//							`fax_persoana` = ?,
//							`mobil_persoana` = ?,
//							`email_persoana` = ?,
//							`gen_persoana` = ?,
//							`id_companie_persoana` = ?,
//							`departament_persoana` = ?,
//							`functie_persoana`= ?
//                       	WHERE `id_persoana` = ?;';
//        array_push($data, array_shift($data));
    }

    fb($string);

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
