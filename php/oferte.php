
<?php
include_once('conexiune.php');

$string = 'SELECT *
FROM persoane
ORDER BY nume_persoana,prenume_persoana ASC;';
$query = $db->query($string);
$query->execute();
?>
<h2>Listă oferte</h2>

<form action="/" method="post" id="selectie_persoana">
    <label>Persoană de contact</label><br>
    <?php
    echo '<select class="mediu">';
    echo '<option id="default" value="">Alegeți...</option>';
    for ($i = 0; $row = $query->fetch(); $i++) {
        echo '<option value=' . $row['id_persoana'] . '>' . $row['nume_persoana'] . ' ' . $row['prenume_persoana'] . '</option>';
    } //end for
    echo '</select>';
    ?>
    <a href="php/word/Oferta.docx" id="word" class="submit">
        <h3>Generează ofertă</h3>
    </a>
</form>
