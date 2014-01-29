<?php

include_once('../external/opentbs/tbs_class.php'); // Load the TinyButStrong template engine
include_once('../external/opentbs/tbs_plugin_opentbs.php'); // Load the OpenTBS plugin
include_once('../conexiune.php');

// fetch informatii despre clientul cu id=?
$string = 'SELECT * FROM `office`.`persoane` WHERE `id_persoana` = ? LIMIT 1;';
$query = $db->prepare($string);
$data = array($_POST['id_persoana']);
$query->execute($data);
$row = $query->fetch();
	$gen_persoana = $row['gen_persoana'];
	if ($gen_persoana) {
		$prenume_persoana = 'Dna. '.$row['prenume_persoana'];
	} else {
		$prenume_persoana = 'Dl. '.$row['prenume_persoana'];
	}
	$nume_persoana = $row['nume_persoana'];
	$functie_persoana = $row['functie_persoana'];
	$departament_persoana = $row['departament_persoana'];
	$id_companie_persoana = $row['id_companie_persoana'];
	$tel_persoana = $row['tel_persoana'];
	$fax_persoana = $row['fax_persoana'];
	$mobil_persoana = $row['mobil_persoana'];
	$email_persoana = $row['email_persoana'];

// fetch informatii despre compania asociata contactului cu id=?
$string = 'SELECT * FROM `office`.`companii` WHERE `id_companie` = ? LIMIT 1;';
$query = $db->prepare($string);
$data = array($id_companie_persoana);
$query->execute($data);
$row = $query->fetch();
	$nume_companie = $row['nume_companie'];
	$adresa_companie = $row['adresa_companie'];
	$oras_companie = $row['oras_companie'];
	$tara_companie = $row['tara_companie'];

$nume_vanzator = 'Costinel Medinceanu';
$tel_vanzator = '+40 (21) 6296-480';
$fax_vanzator = '+40 (21) 6296-606';
$mobil_vanzator = '+40 (730) 592-835';
$email_vanzator = 'costinel.medinceanu@siemens.com';
$referinta_client = 'E-mail / 12 iunie.2013';
$referinta = '76CMMC-130613MS';
$data = '9 ianuarie 2014';

// construim wordul
$TBS = new clsTinyButStrong; // new instance of TBS
$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN); // load the OpenTBS plugin
$template = 'demo_ms_word.docx';
$TBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
$output_file_name='Oferta.docx';
$TBS->Show(OPENTBS_FILE+TBS_EXIT, $output_file_name);
// $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.