<?php
include_once('conexiune.php');


function verifica_existenta_companie($id, $nume_companie)
{
// testeaza existenta companie in baza de date
// daca se apeleaza cu $id null inseamna se creaza o companie noua

    $data = array($nume_companie);
    $string = 'SELECT `id_companie` FROM `companii` WHERE `nume_companie` = ?;';
    $query = interogare($string, $data);
    $result = $query->fetch();
    if($result['id_companie'] && $result['id_companie'] != $id) {
        // daca exista un rezultat si acesta este diferit de $id atunci exista
        // daca $id este null (creare) atunci la orice rezultat care nu este null inseamna ca exista
        echo('exista');
        exit();
    };
    return;
}

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
    for ($i = 0; $row = $query->fetch(); $i++) {
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
    if ($count == 1) {
        echo " companie";
    } else {
        echo " companii";
    }
    echo "</td>";
    echo "</tr>";
    echo "<table>";
}

if (isset($_POST["formular_creare"])) {
    //  Formular creeare companie nou
    ?>
    <h2>Creare companie</h2>
    <form action="/" method="post">
        <input id="id_companie" type="hidden" name="id_companie" value=""/>
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
                </td>
            </tr>
            <tr>
                <td>
                    <label for="oras_companie">Oraș</label>
                    <input class="normal lung"
                           id="oras_companie"
                           type="text"
                           name="oras_companie"
                           autocomplete="off"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="tara_companie">Țară</label>
                    <input class="normal lung"
                           id="tara_companie"
                           type="text"
                           name="tara_companie"
                           autocomplete="off"/>
                </td>
            </tr>
            </tbody>
        </table>
<!--        <input type="hidden"-->
<!--               name="rezervat_nume"-->
<!--               value=""/>-->
        <a href="#" id="creaza_companie" class="submit F1"><h3>Salvează<span class="sosa">å</span></h3></a>
        <a href="#" id="renunta" class="buton_renunta"><h3>Renunță<span class="sosa">ã</span></h3></a>
    </form>
    <?php
    exit();
}
if (isset($_POST["formular_editare"])) {
    // editeaza companie din baza de date
    $id = $_POST["id"];
    $string = 'SELECT * FROM `companii` WHERE `id_companie` = ? LIMIT 1;';
    $data = array($id);
    $query = interogare($string, $data);
    $row = $query->fetch(PDO::FETCH_ASSOC);
    if (count($row) == 1) {
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
                </td>
            </tr>
            <tr>
                <td>
                    <label>Oraș</label>
                    <input class="normal lung"
                           id="oras_companie"
                           type="text"
                           name="oras_companie"
                           value="<?php echo $row['oras_companie']; ?>"
                           autocomplete="off"/>
                </td>
            </tr>
            <tr>
                <td>
                    <label>Țară</label>
                    <input class="normal lung"
                           id="tara_companie"
                           type="text"
                           name="tara_companie"
                           value="<?php echo $row['tara_companie']; ?>"
                           autocomplete="off"/>
                </td>
            </tr>
            </tbody>
        </table>
<!--        <input type="hidden" name="rezervat_nume" value="--><?php //echo $row['nume_companie']; ?><!--"/>-->
        <a href="#" id="editeaza_companie" class="submit F1"><h3>Modifică<span class="sosa">å</span></h3></a>
        <a href="#" id="sterge" class="buton_stergere"><h3>Șterge<span class="sosa">ç</span></h3></a>
        <a href="#" id="renunta" class="buton_renunta"><h3>Renunță<span class="sosa">ã</span></h3></a>
    </form>
    <?php
    exit();
}

if (isset($_POST["salveaza"])) {
    $data = $_POST["formdata"];
    if ($_POST["salveaza"]) { // 1-creaza | 0-modifica
        verifica_existenta_companie(null, $data[1]);
        $string = 'INSERT INTO `companii`
                       (`nume_companie`,
                        `adresa_companie`,
                        `oras_companie`,
                        `tara_companie`,
                        `id_companie`)
		           VALUES (?, ?, ?, ?,NULL);';
        array_shift($data);
    } else {
        verifica_existenta_companie($data[0], $data[1]); // data[0] contine id-ul companiei care se modifica
        $string = 'UPDATE `companii`
                   SET  `nume_companie` = ?,
                        `adresa_companie` = ?,
                        `oras_companie` = ?,
                        `tara_companie` = ?
                   WHERE `id_companie` = ?;';
        array_push($data, array_shift($data));
    }
    $query = interogare($string, $data);
    echo('ok');
    exit();
}
if (isset($_POST["sterge"])) {
    // actiune stergere companiein baza de date
    $string = 'DELETE FROM `companii` WHERE `id_companie` = ? LIMIT 1;';
    $data = array($_POST['id']);
    $query = interogare($string, $data);
    echo('ok');
    exit();
}

if (isset($_POST["companie"])) {
    // alegere companie din baza de date in formulare
    $str = "%" . $_POST["companie"] . "%";
    // prima interogare pentru numarare rezultate
    $string = 'SELECT COUNT(*)
					FROM `companii`
					WHERE (`nume_companie` LIKE ? OR `adresa_companie` LIKE ? OR `oras_companie` LIKE ?)
					ORDER BY `nume_companie` ASC
					LIMIT 1;';
    $data = array($str, $str, $str);
    $query = interogare($string, $data);
    //daca nu sunt rezultate se iese cu mesaj
    $count = $query->fetchColumn();
    if (!$count) {
        echo '<p class="noresults">
			        <strong>Nu există în baza de date.</strong>
			        <br/>Crează compania din meniul
			        <span class="sosa_font" style="color: #000">ñ</span><em>Administrare, Companii</em>
			       </p>';
        exit();
    }
    // interogarea adevarata pentru rezultate
    $string = 'SELECT *
					FROM `companii`
					WHERE (`nume_companie` LIKE ? OR `adresa_companie` LIKE ? OR `oras_companie` LIKE ?)
					ORDER BY `nume_companie` ASC
					LIMIT 5;';
    $query = interogare($string, $data);
    echo '<div class="rec" id="source"><p>' . $_POST["companie"] . '</p></div>';
    for ($i = 0; $row = $query->fetch(); $i++) {
        echo '<div class="rec">';
        echo '<p  id="f' . $row['id_companie'] . '" class="bold">' . $row['nume_companie'] . "</p>";
        echo '<p>' . $row['adresa_companie'] . "</p>";
        echo '<p style="color: #3F41D9;">' . $row['oras_companie'] . '</p>';
        echo '</div>';
    }
    exit();
}
if (isset($_POST["camp_str"])) {

    $string = 'SELECT COUNT(*) FROM `companii`
               WHERE (`nume_companie` LIKE ?)
			   ORDER BY `nume_companie` ASC
			   LIMIT 1;';
    $data = array('%' . $_POST["camp_str"] . '%');
    $query = interogare($string, $data);

    //daca nu sunt rezultate se iese cu mesaj
    $count = $query->fetchColumn();

    if (!$count) {
        echo '<p>Nu există în baza de date.</p>';
        exit();
    }

    if ($_POST["camp_str"] == "") {
        afiseaza_numar_total($count);
        exit();
    }

    // interogarea adevarata pentru rezultate (daca nu s-a iesit mai sus)
    $string = 'SELECT * FROM `companii`
               		WHERE (`nume_companie` LIKE ?)
			   		ORDER BY `nume_companie` ASC;';
    $query = interogare($string, $data);
    afiseaza_tabel($query);
    afiseaza_numar_total($count);
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
$query = interogare($string, NULL);
$count = $query->fetchColumn();
if (!$count) {
    echo '<p>Nu există în baza de date.</p>';
    exit();
}
afiseaza_numar_total($count);
?>