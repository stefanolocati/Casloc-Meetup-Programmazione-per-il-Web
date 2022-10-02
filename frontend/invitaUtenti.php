<?php
  #con trovaRiunione vengono prese le informazioni relative ad un riunione di cui si passa l'id
  $res = trovaRiunione($cid, $_GET["valore"]);
  if ($res["status"]=="ko"){echo $res['msg'];}
  $dati = $res["contenuto"];
  #$_GET["valore"] contiene l'id della riunione per la quale si vuole invitare della gente
  #messo in una variaible dedicata
  $id_riunione = $_GET["valore"];
  $valore = null;
  #res[2] contine il numero massimo di persone che possono stare nella sala dove si svolge la riunione
  #il for cicla una sola volta ed estra le informazioni della riunione alla quale si volgiono initare utenti
  foreach ($dati as $chiave => $valore){break;}
  $res= logisticaSala($cid, $valore[0]);
  #se non si è l'utente che ha creato questa riunione viene stampato un messaggio di errore e non si visualizza alcuna funzionalità
  if($_SESSION["utente"]!=$valore[5]){
    echo "<div class=\"alert alert-danger\"><strong>Errore: non sei abilitato a invitare la riunione</strong></div>";
  }
  else {
?>
<!--div nel quale sono stampati i bottini per selezionare i tipi di utenti che si vogliono invitare, direttori, impiegati
a sua volta, impiegati semplici, funzionari o capi reparto oppure mostrare di nuovo tutti gli utenti-->
<div class="objectcontainer">
  <!--in un input nascosto inserisco il numero di posti massimi contenibili nella sala dove si svolge la riunione-->
  <input type="text" id='posti_sala' value="<?php echo $res[2]; ?>" hidden>
  <!-- il bottone tutti permette di rivisualizzare tutti gli utenti invitabili e quelli già invitati-->
  <!-- sistemare il bottone in modo che se si schiaccia tutti vengono resettati i campi-->
  <button id="tutti" type="button" name="tutti" style="display: inline-block;" onclick="mostraTutti(); mostraUtenti('<?php echo $_SESSION['utente'] ?>','<?php echo $id_riunione ?>')">Mostra tutti</button>
  <!--permettere di selezionare un dipartimento e quindi vedere tutti gli utenti invitabili e già invitati per quella riunione-->
  <div class="form-group" style="display: inline-block;" >
    <label>Dipartimento:</label>
    <select name='nome_dipartimento' id="dipartimento" onchange="mostraUtenti('<?php echo $_SESSION['utente'] ?>','<?php echo $id_riunione ?>')">
      <option value=""></option>
    <?php $risultato = selezionaColonna($cid, "dipartimento","nome_dipartimento");
      while($row=$risultato->fetch_row())
      {
        $colonna = $row[0];
        echo("<option value='$colonna' id='$colonna'>$colonna</option>");
      }
    ?>
    </select>
  </div>

  <!--permettere di selezionare direttori o utenti e stampare quindi quelli invitabili e già invitati-->
  <div class="form-group" style="display: inline-block;">
    <label for="funzione">Funzione:</label>
    <select name="funzione" id="tipo" onchange="mostraUtenti('<?php echo $_SESSION['utente'] ?>','<?php echo $id_riunione ?>'); mostraCampi()">
      <option value=""></option>
      <option value="Direttore">Direttore</option>
      <option value="Impiegato">Impiegato</option>
    </select>
  </div>

  <!--permettere di selezionare il ruolo degli impiegati e visualizzare in base a questo di stampare quelli invitabili e già invitati-->
  <div class="form-group" id="mostra_1" style="display: inline-block; display: none;" onchange="mostraUtenti('<?php echo $_SESSION['utente'] ?>','<?php echo $id_riunione ?>')">
    <label for="ruolo">Ruolo:</label>
    <select name="ruolo" id="ruolo">
      <option value=""></option>
      <option value="Impiegato Semplice">Impiegato Semplice</option>
      <option value="Funzionario">Funzionario</option>
      <option value="Capo Reparto">Capo Reparto</option>
    </select>
  </div>
</div>

<!--alla pagina di backend richiamata nell'action passo l'id della riunione in modo da poter eseguire correttamente le query-->
<form class="form-group" action="backend/insertINV-EXE.php?id_riunione=<?php echo $id_riunione; ?>" method="post" id="form-group" onsubmit="return checkInviti();">

  <div id="formInvito">
    <!-- div nel quale stampare la tabella per l'invito degli utenti-->
  </div>

</form>

  <script>
    window.onload = function(){
      mostraCampi();
    };
    //fa si che di default quando viene caricata la pagina vengano stampati tutti gli utenti senza distinzione per categoria
    window.onload = function(){
      mostraUtenti('<?php echo $_SESSION['utente'] ?>','<?php echo $id_riunione ?>');
    };
  </script>

<?php } ?>
