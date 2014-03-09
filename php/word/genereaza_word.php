<?php

include_once '../external/opentbs/tbs_class.php'; // Load the TinyButStrong template engine
include_once '../external/opentbs/tbs_plugin_opentbs.php'; // Load the OpenTBS plugin
include_once '../conexiune.php';

// fetch informatii despre clientul cu id=?
$string = "SELECT *,
			DATE_FORMAT(O.data_oferta, '%d-%m-%Y') AS dataoferta,
			DATE_FORMAT(O.data_expirare, '%d-%m-%Y') AS dataexpirare
			FROM `oferte` AS O
			LEFT JOIN `companii` AS C ON O.id_companie_oferta = id_companie
			LEFT JOIN `persoane` AS P ON O.id_persoana_oferta = id_persoana
  			LEFT JOIN `vanzatori` AS V ON O.id_vanzator_oferta = id_vanzator
			WHERE `id_oferta` = ?
			LIMIT 1;";
$data = array($_POST['id']);
$query = interogare($string, $data);
$row = $query->fetch();

// date despre persoana
$gen_persoana = $row['gen_persoana'];
$nume_persoana = $row['nume_persoana'];
if ($gen_persoana) {
	$prenume_persoana = 'Dna. ' . $row['prenume_persoana'];
	$adresare = 'Stimată Doamnă ' . $nume_persoana;
} else {
	$prenume_persoana = 'Dl. ' . $row['prenume_persoana'];
	$adresare = 'Stimate Domnule ' . $nume_persoana;
}
$functie_persoana = $row['functie_persoana'];
$departament_persoana = $row['departament_persoana'];
$tel_persoana = $row['tel_persoana'];
$fax_persoana = $row['fax_persoana'];
$mobil_persoana = $row['mobil_persoana'];
$email_persoana = $row['email_persoana'];

// date despre companie
$nume_companie = $row['nume_companie'];
$adresa_companie = $row['adresa_companie'];
$oras_companie = $row['oras_companie'];
$tara_companie = $row['tara_companie'];

// date despre vanzator
$nume_vanzator = $row['nume_vanzator'];
$prenume_vanzator = $row['prenume_vanzator'];
$tel_vanzator = $row['tel_vanzator'];
$fax_vanzator = $row['fax_vanzator'];
$mobil_vanzator = $row['mobil_vanzator'];
$email_vanzator = $row['email_vanzator'];

// date despre oferta
$nume_oferta = $row['nume_oferta'];
$data_oferta = $row['dataoferta'];
$data_expirare = $row['dataexpirare'];
$referinta_client = 'TODO';
$id_oferta = $row['id_oferta'];
$valoare_oferta = $row['valoare_oferta'];


$output_file_name = 'Oferta.docx';
if (is_readable($output_file_name)) {
	unlink(realpath($output_file_name));
}

// construim wordul
$TBS = new clsTinyButStrong; // new instance of TBS
$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN); // load the OpenTBS plugin
$template = 'demo_ms_word.docx';
$TBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
$TBS->Show(OPENTBS_FILE + TBS_EXIT, $output_file_name);
// $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
exit();