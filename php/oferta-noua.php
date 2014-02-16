<?php
include_once 'conexiune.php';

date_default_timezone_set('Europe/Bucharest');
$months = ['Ian', 'Feb', 'Mar', 'Apr', 'Mai', 'Iun', 'Iul', 'Aug', 'Sep', 'Oct', 'Noi', 'Dec='];
$ziua = getdate()['mday'];
$luna = getdate()['mon'];
$an = getdate()['year'];
$timestamp = getdate()['0'];
$today = $ziua . '-' . $months[$luna - 1] . '-' . $an;
$date1 = mktime(0, 0, 0, $luna, $ziua + 30, $an);
$ziua1 = getdate($date1)['mday'];
$luna1 = getdate($date1)['mon'];
$an1 = getdate($date1)['year'];
$viitor = $ziua1 . '-' . $months[$luna1 - 1] . '-' . $an1;
?>
<h2>Creare ofertă nouă</h2>
<form action="/" method="post" id="formular_oferta_noua">
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
                       class="normal scurt"
                       value=""
                       data-id=""
                       placeholder="Selectează..."
                       readonly
                    />
            </td>
        <tr>
            <td>
                <label for="relevant">Inclus în volum ofertare</label>
                <input id="relevant"
                       name="relevant"
                       type="checkbox"
                       checked="checked"/>
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
    <a href="#" id="creaza_oferta" class="submit F1"><h3>Salvează<span class="sosa">å</span></h3></a>
    <a href="#" id="renunta" class="buton_renunta"><h3>Resetează<span class="sosa">ã</span></h3></a>
    <!--    <a href="#" id="printeaza_oferta" class="buton_printeaza"><h3>Printează<span class="sosa">8</span></h3></a>-->
</form>
<div id="lista_companii" class="ddm"></div>
<div id="lista_vanzatori" class="ddm"></div>
<div id="lista_persoane" class="ddm"></div>
<div id="lista_stadii" class="ddm">
    <div class="rec">
        <p id="f00">Ofertă deschisă</p>
    </div>
    <div class="rec">
        <p id="f01">Ofertă câștigată</p>
    </div>
    <div class="rec">
        <p id="f02">Ofertă pierdută</p>
    </div>
</div>
