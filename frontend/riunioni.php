<!--bottoni che permettono di visualizzare le riunioni selezionando un mese una settimana o un giorno-->
<div class="form-group" style="display: inline-block;">
  <label>Mese:</label>
  <input type="date" name="mese" id="mese" value="" onchange="mostraRiunioni('<?php echo $_SESSION['utente'] ?>')">
</div>
<div class="form-group" style="display: inline-block;">
  <label>Settimana:</label>
  <input type="date" name="settimana" id="settimana" value="" onchange="mostraRiunioni('<?php echo $_SESSION['utente'] ?>')">
</div>
<div class="form-group" style="display: inline-block;">
  <label>Giorno:</label>
  <input type="date" name="giorno" id="giorno" value="" onchange="mostraRiunioni('<?php echo $_SESSION['utente'] ?>')">
</div>
<!--html mette a disposizione i tipi di input month e week ma non sono supportati da tutti i browser, per questo utilizzo
solo tipi date dai quali estraggo singolarmente mese settimana e giorno in php-->
<!--con il tasto mostra tutte vengono mostrate di nuovo tutte le riunioni, la funzione js mostraTutteRiunioni azzera i valori degli input
precedenti e poi viene richiamamta la funzione per stampare le riunioni-->
<div class="form-group" style="display: inline-block; float:right;">
  <button id="tutti" type="button" name="tutti" style="display: inline-block;" onclick="mostraTutteRiunioni(); mostraRiunioni('<?php echo $_SESSION['utente'] ?>')">Mostra tutte</button>
</div>


<div id='stampaRiunioni'>
  <!-- qua vengono stampate le riunioni -->
</div>

<!--pulsante che permette di andare alla pagine di visualizzazione delle riunioni trascorse-->
<div style="display:flex;justify-content:center;align-items:center;">
  <button class="btn" type="button" name="passate"><a style="color:#212529" href="index.php?op=riunioniPassate">Riunioni Passate</a></button>
</div>

<!--modal vuota il cui cotenuto viene cambiato a seconda delle interazioni con la pagina, se schiaccio sul pulsante
per visualizzare i partecipanti la modal viene riempita con questi, se schiaccio su cancella riunione, nella modal
viene stampato un messaggio per chiedermi se sono sicuro dell'operazione-->
<div class="modal" id="modal" tabindex="-1" role="dialog"  aria-hidden="true" >
  <div class="modal-admin">
    <div class="modal-content" id="contenuto">

    </div>
  </div>
</div>

<!--viene eseguita la funzione al caricamento della pagine in modo che vengano stampate di default tutte le riunioni che non si sono ancora verificate-->
<script type="text/javascript">
window.onload = function(){
  mostraRiunioni('<?php echo $_SESSION['utente'] ?>');
};
</script>
