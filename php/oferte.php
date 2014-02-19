<?php

include_once 'conexiune.php';

?>
<h2>Listă oferte</h2>

<form action="/" method="post" id="selectie_persoana">
    <label for="select_companie">Companie</label>
    <input id="select_companie"
           name="select_companie"
           type="text"
           class="lung normal"
           value=""
           data-id=""
           placeholder="Tastează pentru a căuta..."/>
    <br/><br/>
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
    <br/><br/>
    <a href="/php/word/Oferta.docx" id="word" class="submit">
        <h3>Generează ofertă</h3>
    </a>
</form>
<div id="lista_persoane" class="ddm"></div>
<div id="lista_companii" class="ddm"></div>
