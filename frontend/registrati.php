<h1>Registrati</h1>
<!--checkRegistrazione controlla che non siano visualizzati degli errori, se questi non sono presenti allora il tasto submit funziona altrimenti
no (senza comunque essere disabilitato)-->
<form class="form-group" action="backend/insertU-EXE.php" method="post" id='form-group' onsubmit="return checkRegistrazione();" enctype='multipart/form-data'>
  <!-- scelta del tipo di utente: direttore o impiegato -->
  <div class="form-group">
    <label for="funzione">Funzione:</label><br>
    <!-- mostra campi permette di mostrare gli input specifici relativi al direttore o all'impiegato
    check date fa un controllo tra data di nascita e data di promozione nel caso si cambi il tipo da impiegato a direttore-->
    <select name="funzione" id="tipo" onchange="mostraCampi(); checkDate()">
      <option value="Direttore">Direttore</option>
      <option value="Impiegato">Impiegato</option>
    </select>
  </div>
  <!--nel caso si scelga impiegato nell'input precedente viene mostrato questo menu a tendina dove poter scegliere il ruolo compiuto come impiegato-->
  <div class="form-group" id="mostra_1">
    <label for="ruolo">Ruolo:</label><br>
    <select name="ruolo" id="ruolo">
      <option value="Impiegato Semplice">Impiegato Semplice</option>
      <option value="Funzionario">Funzionario</option>
      <option value="Capo Reparto">Capo Reparto</option>
    </select>
  </div>
  <!--checkAnni controlla che gli anni di servizio siano coerenti rispetto alla data di nasacita e quella di promozione-->
  <div id="mostra_2">
    <div class="form-group">
      <label for="anniSerivizio">Anni di servizio:</label><br>
      <input type="number" name="anniservizio" placeholder="Anni di servizio" id="txtAnniservizio" onchange='checkAnni()' min=0>
      <p id="errorAnni" style="color:red"></p>
    </div>

    <div class="form-group">
      <label for="otherField">Data promozione:</label><br>
      <input type="date" name="datapromozione" placeholder="datapromozione" id='txtDatapromozione' onchange='checkDate(); checkAnni()'>
      <p id="errorPromo" style="color:red"></p>
    </div>
  </div>

  <div class="form-group">
    <label>Nome:</label><br>
    <input type="text" name="nome" id="nome" placeholder="Nome">
  </div>

  <div class="form-group">
    <label>Cognome:</label><br>
    <input type="text" name="cognome" id="cognome" placeholder="Cognome">
  </div>

  <div class="form-group">
    <label>E-mail (sarà la tua login di accesso):</label><br>
    <input type="email" name="mail" placeholder="E-mail"id="mail">
  </div>
  <!--checkCaratteri controlla che i caratteri della password inserita siano accettabili altrimenti stampa un errore-->
  <div class="form-group">
    <label>Password:</label><br>
    <input type="password" name="password" placeholder="Password" id='txtPassword' onkeyup='checkCaratteri()'><br>
    <input type="checkbox" onclick="mostra()">Mostra Password
    <p id="errorP" style="color:red"></p>
  </div>
  <!-- con seleziona colonna prendo i dipartimenti dal database e li stampo come option della select-->
  <div class="form-group">
    <label>Dipartimento:</label><br>
    <select name='nome_dipartimento'>
    <?php $risultato = selezionaColonna($cid, "dipartimento","nome_dipartimento");
      while($row=$risultato->fetch_row())
      {
        $colonna = $row[0];
        echo("<option name='$colonna'>$colonna</option>");
      }
    ?>
    </select>
  </div>

  <div class="form-group">
    <label>Data di nascita:</label><br>
    <input type="date" name="data" id='txtDatanascita' onchange='checkDate(); checkAnni()'>
    <p id="errorData" style="color:red"></p>
  </div>

  <div class="form-group">
    <label>Foto:</label><br>
    <input type="file" name="foto" id='inputfile'>
  </div>

  <div class="form-group">
    <button type="submit" name="accetta" class='btn btn-success' id='submitbutton'>Registrati</button>
  </div>

</form>

<!-- richiamo immediatamente mostra campi in modo che quando viene caricata la pagina che ha direttore come valore di default per l'input
di tipo mi mostri anche gli input di anni di servizio e data di promozione-->
<script>
  window.onload = function(){
    mostraCampi();
  };
</script>
