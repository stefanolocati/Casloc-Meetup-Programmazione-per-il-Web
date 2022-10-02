<h1 id='titolo'>Pianifica</h1>
<!--controllo per impedire di far visualizzare le funzionalità di questa pagina ad utenti non autorizzati o che non siano direttori-->
<?php if($_SESSION["tipo"]!='Direttore' and $_SESSION["autorizzazione"]!=$_SESSION["utente"]){
  echo "<div class=\"alert alert-danger\"><strong>Errore: non sei abilitato a pianificare riunioni</strong></div>";
}
else { ?>
<!--check pianifica: se non sono visualizzati errori allora il tasto submit funziona altrimenti no,
è bloccato anche nel caso in cui dei campi siano lascaiti vuoti-->
<form class="form-group" action="backend/insertR-EXE.php" method="post" id='formPianifica' onsubmit="return checkPianifica();">

  <div class="form-group">
    <label>ID Riunione:</label><br>
    <!--checkRiunione controlla che l'id non abbia carattere indesiderati-->
    <input type="text" name="identificativo" placeholder="ID Riunione" id="identificativo" onkeyup="checkRiunione()">
    <p id="errorID" style="color:red"></p>
  </div>
  <!--con mostraSale, passando come parametro il dipartimento selezionato, nell'input successivo vengono date come option del select le sale del dipartimento selezionato-->
  <!--con la funzione giaPianificata viene visualizzato un messaggio di errore nel caso la riunione che si sta pianificando si sovrapponga per tempo e luogo ad una già pianificata-->
  <!--con mostraInfoSala stampo informazioni quali città e via del dipartimento e strumenti messi a disposizione per la sala selezionata-->
  <div class="form-group">
    <label>Dipartimento:</label><br>
    <select name='nome_dipartimento' id="dipartimento" onchange="mostraSale(this.value); giaPianificata()" onclick="mostraSale(this.value)" onmouseover="mostraInfoSala(this.value); giaPianificata()">
    <!--con seleziona colonna stampo come option di questa select l'elenco dei dipartimenti dell'azienda-->
    <?php $risultato = selezionaColonna($cid, "dipartimento","nome_dipartimento");
      while($row=$risultato->fetch_row())
      {
        $colonna = $row[0];
        echo("<option value='$colonna' id='$colonna' >$colonna</option>");
      }
    ?>
    </select>
  </div>

  <div class="form-group" >
    <label>Sala:</label><br>
    <select name='nome_sala' id='sala' onchange="giaPianificata(); mostraInfoSala(this.value)" onmouseover="mostraInfoSala(this.value)">
    </select>
  </div>
  <p id="infoSala"></p>
  <!--con checkDataRiunione controllo che la data della riunione che sto pianificando non sia già passata-->
  <div class="form-group">
    <label>Data Riunione:</label><br>
    <input type="datetime-local" name="data" id="data" onchange="checkDataRiunione(); giaPianificata()" onclick="checkDataRiunione(); giaPianificata()" onmouseover="giaPianificata()">
    <p id="errorData" style="color:red"></p>
    <p id="errorGiaPianificata" style="color:red"></p>
  </div>

  <div class="form-group">
    <label>Tema:</label><br>
    <input type="text" name="tema" placeholder="Tema Riunione" id="tema">
  </div>

  <div class="form-group">
    <label>Durata:</label><br>
    <input type="time" name="durata" id="durata" onchange="giaPianificata()">
  </div>

  <div class="form-group">
    <button type="submit" name="accetta" class='btn btn-success' id='submitbutton' onmouseover="giaPianificata()">Crea riunione</button>
  </div>

</form>

<?php } #fine del controllo per i non autorizzati?>

<script type="text/javascript">
  window.onload = function() {
    mostraSale(document.getElementById('dipartimento').value);
  };
  window.onload = function() {
    mostraInfoSala(document.getElementById('sala').value);
  };

</script>
