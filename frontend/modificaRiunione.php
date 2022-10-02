<?php
  #passo ad una query in una funzione l'id della riunione che si vuole modificare e la funzione mi restituisce i dati
  #di quella riunione già presenti nel database in modo che possano essere stampati nei campi input senza dover essere reinseriti
  #dall'utente nel caso questo voglia lascirli cos' per modificarne solo certi altri
  $res = trovaRiunione($cid, $_GET["valore"]);
  if ($res["status"]=="ko")
  {
    echo $res['msg'];
  }
  $dati = $res["contenuto"];
  #$_GET["valore"] contiene l'id della riunione che si vuole modificare e viene passato dalla pagina precedente (riunioni) e
  #messo in una variaible dedicata
  $id_riunione = $_GET["valore"];
  echo "<h1 id='titolo'>Modifica Riunione</h1>";
  #il for cicla solo una volta in quanto contiene i dati solo della specifica riunione che si vuole modificare
  foreach ($dati as $key => $info){
    #l'if effettua un controllo per stabile se l'utente loggato che vuole accedere a questa funzione è abilitato o meno
    #può modificare la riunione solo l'utente che l'ha creata, nel caso questo sia ancora autorizzato
    #altrimenti viene stampato un messaggio di errore
    if($_SESSION["tipo"]!='Direttore' and $_SESSION["autorizzazione"]!=$_SESSION["utente"] or $_SESSION["utente"]!=$info[5]){
      echo "<div class=\"alert alert-danger\"><strong>Errore: non sei abilitato a modificare la riunione</strong></div>";
    }
    else {
?>
  <!--nella pagina di backend dell'action passo come parametro l'id della riunione in modo che possa essere usata nella query per trovare
  la riunione le cui informazioni sono da modificare-->
  <form class="form-group" action="backend/updateR-EXE.php?identificativo=<?php echo $id_riunione ?>" method="post" id='formPianifica' onsubmit="return checkModifcaRiunione();">

    <input type="text" name="identificativo" id="identificativo" value='<?php echo $id_riunione ?>' style="display: none">
    <!--con mostraSale, passando come parametro il dipartimento selezionato, nell'input successivo vengono date come option del select le sale del dipartimento selezionato-->
    <!--con la funzione giaPianificata viene visualizzato un messaggio di errore nel caso la riunione che si sta pianificando si sovrapponga per tempo e luogo ad una già pianificata-->
    <!--con mostraInfoSala stampo informazioni quali città e via del dipartimento e strumenti messi a disposizione per la sala selezionata-->
    <div class="form-group">
      <label>Dipartimento:</label><br>
      <select name='nome_dipartimento' id="dipartimento" onchange="mostraSale(this.value); giaPianificata()" onclick="mostraSale(this.value)" onmouseover="mostraInfoSala(this.value); giaPianificata()">
        <option selected="selected"><?php echo $info[1] ?></option>
        <!--con seleziona colonna prendo i dati dal database e li stampo, in questo caso l'eleenco dei dipartimenti
        come dipartimento settato di default c'è però quello già selezionato dall'utente quando ha creato la riunione-->
      <?php $risultato = selezionaColonna($cid, "dipartimento","nome_dipartimento");
        while($row=$risultato->fetch_row())
        {
          $colonna = $row[0];
          if ($colonna==$info[1]){
            continue;
          }
          echo("<option name='$colonna'>$colonna</option>");
        }
      ?>
      </select>
    </div>

    <div class="form-group" >
      <label>Sala:</label><br>
      <select name='nome_sala' id='sala' onchange="giaPianificata(); mostraInfoSala(this.value)" onmouseover="mostraInfoSala(this.value); giaPianificata()">
        <option selected="selected"><?php echo $info[0] ?></option>
      </select>
    </div>
    <p id="infoSala"></p>

    <div class="form-group">
      <label>Data Riunione:</label><br>
      <!--la conversione fatta nel tag php è necessario in quanto il risultato di date + orario che viene dal database non è di per sè
      compatibile col tipo datetime local di html-->
      <input type="datetime-local" name="data" value=<?php echo date('Y-m-d\TH:i:s', strtotime($info[3]));?>  id="data"
      onchange="checkDataRiunione(); giaPianificata()" onclick="checkDataRiunione(); giaPianificata()" onmouseover="giaPianificata()">
      <p id="errorData" style="color:red"></p>
      <p id="errorGiaPianificata" style="color:red"></p>
    </div>

    <div class="form-group">
      <label>Tema:</label><br>
      <input type="text" name="tema" value=<?php echo $info[2];?> placeholder="Tema Riunione" id="tema">
    </div>

    <div class="form-group">
      <label>Durata:</label><br>
      <input type="time" name="durata" value=<?php echo $info[4];?> id="durata" onchange="giaPianificata()">
    </div>

    <div class="form-group">
      <button type="submit" name="accetta" class='btn btn-success' id='submitbutton' onmouseover="giaPianificata()">Accetta le modifiche</button>
    </div>

  </form>

<?php } } #fine del controllo per i non autorizzati, viene chiuso l'else e il for?>
