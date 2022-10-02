<?php
  #con leggiRiunioni inserisco in $res le tuple che mi danno le informazioni sulle riunioni create,
  #o a cui partecipa, l'utente al quale corrisponde la sessione
  $res = leggiRiunioni($cid, $_SESSION["utente"]);
  if ($res["status"]=="ko")
  {
    echo $res['msg'];
  }
  #in $date rigistro il current timestamp in modo da poterlo confrontare con la data della riunione e in base a quello stampare
  #le riunioni che devono ancora avvenire o quelle passate
  $date = date('Y-m-d H:i:s');
  $riunioni = $res["contenuto"];
  #creo la tabella e riempio le table head
  echo "<h1>Riunioni Passate</h1>";
  echo "<div class=\"table-responsive\">";
  echo "<table class=\"table text-center\">";
  echo "<tr>
    <th>Creatore</th>
    <th>ID Riunione</th>
    <th>Dipartimento</th>
    <th>Località</th>
    <th>Tema</th>
    <th>Durata</th>

    <th>Elimina</th>
    <th>Invitati</th>
    </tr>";
  foreach ($riunioni AS $id => $info) {
    #controllo per stampare solo le riunioni già avvenute
    if ($info[4]>$date) {
      continue;
    }
    $citta_via=logisticaSala($cid, $info[3]); #prendo le informazioni relative alla città e via di dove si svolge la riunione
    echo "<tr>
      <td>$info[0]</td>
      <td>$info[1]</td>
      <td>Dipartimento: $info[2]<br>Sala: $info[3]<br>Città: $citta_via[0]<br>Via: $citta_via[1]</td>
      <td>$info[4]</td>
      <td>$info[5]</td>
      <td>$info[6]</td>";
      #se la riunione stampata non è stata creata dall'utente loggato allora non può essere cancellata e non può visualizzare chi via ha partecipato
      if ($info[0]!=$_SESSION["utente"]) {
        echo "<td>❌</td>
        <td>❌</td>";
      }
      else {
        echo "<td><a ><img class='smallicons' src='images/trash.png' onclick='showModal(); cancellaRiunione(\"$info[1]\")' style='cursor:pointer;'></a></td>
        <td><a ><img class='smallicons' src='images/lente.png' onclick='showModal(); visualizzaUtenti(\"$info[1]\")' style='cursor:pointer;'></a></td>";
      }
  }
  echo "</table>";
  echo "</div>";
?>

<!--pulsante che permette di visualizzare le riunioni-->
<div style="display:flex;justify-content:center;align-items:center;">
  <button class="btn" type="button" name="passate"><a style="color:#212529" href="index.php?op=riunioni">Riunioni</a></button>
</div>

<!--modal vuota il cui cotenuto viene cambiato a seconda delle interazioni con la pagina, se schiaccio sul pulsante
per visualizzare i partecipanti la modal viene riempita con questi, se schiaccio su cancella riunione, nella modal
viene stampato un messaggio per chiedermi se sono sicuro dell'operazione-->
<div class="modal" id="modal" tabindex="-1" role="dialog"  aria-hidden="true" >
  <div class="modal-dialog">
    <div class="modal-content" id="contenuto">

    </div>
  </div>
</div>
