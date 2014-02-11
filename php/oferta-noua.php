<?php
include_once 'conexiune.php';

date_default_timezone_set('Europe/Bucharest');
$months = ['Ian' , 'Feb' ,'Mar', 'Apr' , 'Mai' , 'Iun' , 'Iul' , 'Aug', 'Sep', 'Oct', 'Noi', 'Dec='];
$ziua = getdate()['mday'];
$luna = getdate()['mon'];
$an = getdate()['year'];
$timestamp = getdate()['0'];

$today = $ziua.'-'.$months[$luna-1].'-'.$an;


$date1 = mktime(0,0,0,$luna +1, $ziua, $an);
$ziua1= getdate($date1)['mday'];
$luna1=getdate($date1)['mon'];
$an1 =getdate($date1)['year'];

$viitor = $ziua1.'-'.$months[$luna1-1].'-'.$an1;



?>

<h2>Creare ofertă nouă</h2>
<form action="/" method="post" id="formular_oferta_noua">
    <input id="id_oferta" type="hidden" name="id_oferta" value=""/>
    <table class="oferta_noua">
        <tbody>
        <tr>
            <td>
                <label for="nume_oferta">Nume proiect</label>
                <input class="normal lung"
                       id="nume_oferta"
                       type="text"
                       name="nume_oferta"/>
            </td>
            <td>
                <label for="data_oferta">Data ofertă / Valabilitate</label>
                <input id="data_oferta" class="datepicker normal extrascurt" type="text" value="<?php echo $today; ?>"/>
                <input id="valabilitate" class="normal megascurt" type="text" value="30"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="vanzator_oferta">Vânzător</label>
                <input class="normal scurt"
                       id="vanzator_oferta"
                       type="text"
                       name="vanzator_oferta"/>
            </td>
            <td>
                <label for="data_expirare">Data expirare ofertă</label>
                <input id="data_expirare"
                       class="data_expirare extrascurt"
                       type="text"
                       value="<?php echo $viitor; ?>"
                       disabled="disabled"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="descriere_oferta">Descriere</label>
                <textarea id="descriere_oferta" maxlength="400"></textarea>
            </td>
            <td>

                <label for="referinta">Referință</label>
                <input class="normal scurt"
                       id="referinta"
                       name="referinta"
                       type="text"/><br/><br/>
                <label for="relevant">Inclus în volum ofertare</label>
                <input id="relevant" type="checkbox" checked="checked"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="camp_cauta_companie">Companie</label>
                <input class="normal lung"
                       id="camp_cauta_companie"
                       type="text"/>
            </td>
            <td>
                <label for="camp_cauta_persoana">Persoană de contact</label>
                <input class="normal scurt"
                       id="camp_cauta_persoana"
                       type="text"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="valoare_oferta">Valoare ofertă</label>
                <input class="scurt valoare_oferta"
                       id="valoare_oferta"
                       type="text"/>
            </td>
            <td>
                <label for="stadiu_oferta">Stadiu</label>
                <input class="normal scurt"
                       id="stadiu_oferta"
                       type="text"
                       name="stadiu_oferta"/>
            </td>
        </tr>
        </tbody>
    </table>
    <input id="id_companie"
           type="hidden"
           name="id_companie"
           value=""/>
    <input id="id_persoana"
           type="hidden"
           name="id_persoana"
           value=""/>
    <a href="#" id="creaza_oferta" class="submit F1"><h3>Salvează<span class="sosa">å</span></h3></a>
    <a href="#" id="renunta" class="buton_renunta"><h3>Resetează<span class="sosa">ã</span></h3></a>
    <!--    <a href="#" id="printeaza_oferta" class="buton_printeaza"><h3>Printează<span class="sosa">8</span></h3></a>-->
</form>
<div class="tabel"></div>