<?php
  #prendo le informazioni dell'utente loggato per ristamparle poi
  $res = trovaProfilo($cid, $_SESSION["utente"]);
  if ($res["status"]=="ko")
  {
    echo $res['msg'];
  }
  $dati = $res["contenuto"];
  #for che agisce una sola volta visto che in $dati ci sono le infromazioni si solo un utente, quello loggato
  foreach ($dati as $key => $info){
?>
  <h1>Modifica Profilo</h1>


  <form class="form-group" action="backend/updateU-EXE.php" method="post" id='form-group' onsubmit="return checkModificaUtente();" enctype='multipart/form-data'>
    <!-- la data di nascita non viene visualizzata e non può essere modificata ma viene inclusa comunque in modo che possa essere passata in post
    e possano essere svolto il controllo sugli anni di servizio-->
    <div class="form-group" style="display: none">
      <label>Data di nascita:</label><br>
      <input type="date" name="data" id='txtDatanascita' value=<?php echo $info[10] ?>>
      <p id="errorData" style="color:red"></p>
    </div>
    <div class="form-group">
      <label for="funzione">Funzione:</label><br>
      <select name="funzione" id="tipo" onchange="mostraCampi(); checkDate(); checkAnni()">
        <option selected="selected" value=<?php echo $info[4] ?>><?php echo $info[4] ?></option>
        <option value='Direttore'>Direttore</option>
        <option value='Impiegato'>Impiegato</option>
        ?>
      </select>
    </div>

    <div class="form-group" id="mostra_1">
      <label for="otherField_2">Ruolo:</label><br>
      <select name="ruolo" id="ruolo">
        <option selected="selected" value=<?php echo $info[5] ?>><?php echo $info[5] ?></option>
        <option value='Impiegato Semplice'>Impiegato Semplice</option>
        <option value='Funzionario'>Funzionario</option>
        <option value='Capo Reparto'>Capo Reparto</option>
        ?>
      </select>
    </div>

    <div id="mostra_2">
      <div class="form-group">
        <label for="otherField">Anni di servizio:</label><br>
        <input type="number" name="anniservizio" placeholder="Anni di servizio" id="txtAnniservizio" onchange="checkAnni()" value=<?php echo $info[7];?> min=0>
          <p id="errorAnni" style="color:red"></p>
      </div>

      <div class="form-group">
        <label for="otherField">Data promozione:</label><br>
        <input type="date" name="datapromozione" placeholder="datapromozione" id='txtDatapromozione' onchange='checkDate()' value=<?php echo $info[8] ?>>
        <p id="errorPromo" style="color:red"></p>
      </div>
    </div>

    <div class="form-group">
      <label>E-mail (sarà la tua login di accesso):</label><br>
      <input type="email" name="mail" id="mail" placeholder="E-mail" value=<?php echo $info[0] ?>>
    </div>

    <div class="form-group">
      <label>Password:</label><br>
      <input type="password" name="password" placeholder="Password" id='txtPassword' value='<?php #echo $info[9];?>' onkeyup='checkCaratteri();'><br>
      <input type="checkbox" onclick="mostra()">Mostra Password
      <p id="errorP" style="color:red"></p>
    </div>

    <div class="form-group">
      <label>Dipartimento:</label><br>
      <select name='nome_dipartimento'>
      <option selected="selected" value=<?php echo $info[3] ?>><?php echo $info[3] ?></option>
      <?php $risultato = selezionaColonna($cid, "dipartimento","nome_dipartimento");
        while($row=$risultato->fetch_row())
        {
          $colonna = $row[0];
          if ($colonna==$info[3]){
            continue;
          }
          echo("<option name='$colonna'>$colonna</option>");
        }
      ?>
      </select>
    </div>

    <div class="form-group">
      <label>Foto:</label><br>
      <input type="file" name="foto" id='inputfile'>
    </div>

    <div class="form-group">
      <button type="submit" name="accetta" id='submitbutton' class='btn btn-success'>Accetta Modifiche</button>
    </div>

  </form>

<?php } ?>
<!-- richiamo immediatamente mostra campi in modo che quando viene caricata la pagina che ha direttore come valore di default per l'input
di tipo mi mostri anche gli input di anni di servizio e data di promozione-->
<script>
  window.onload = function(){
    mostraCampi();
  };
</script>
