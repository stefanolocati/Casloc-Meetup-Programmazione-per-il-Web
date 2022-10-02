<?php
  echo "<h1>Nuovi Inviti</h1>";
  $date = new DateTime();
  $data = $date->format('Y-m-d g:i:s a');
  #data corrente nel formata uguale a quello ridata dal database
  $res = leggiInviti($cid, $_SESSION["utente"]);
  #in dati vengono inserite le informazioni relative a tutte le riunioni alle quali siamo stati invitati
  $dati = $res["contenuto"];
  echo '<form class="form-group" action="backend/insertRESP-EXE.php" method="post" id="form-Responso" >';
  echo "<div class=\"table-responsive\">";
  echo "<table class=\"table text-center\">";
  echo "<tr>
    <th>Creatore</th>
    <th>Località</th>
    <th>Data e Ora</th>
    <th>Tema</th>
    <th>Durata</th>
    <th>Motivo</th>
    <th>Responso</th>
    </tr>";
  foreach ($dati AS $key => $info) {
    #faccio un controllo sulla data delle riunioni, se queste devono avvenire (quindi data odierna minore di data riunione) e non ho acnora
    #dato un responso, le stampo,con un set di radio button non checkato in modo che l'utente possa scegliere se accettare o meno l'invito
    if ($info[7]=="" && $data<$info[4]){
      $citta_via=logisticaSala($cid, $info[3]); //trova città, via e il numero di posti della sala dove si svolge la riunione
      echo "<tr>
      <td>$info[0] ($info[1]) <p id='$info[1]' style='color:red'></p></td>
      <td>Dipartimento: $info[2]<br>Sala: $info[3]<br>Città: $citta_via[0]<br>Via: $citta_via[1]</td>
      <td>$info[4]</td>
      <td>$info[5]</td>
      <td>$info[6]</td>
      <td><input type='text' name='motivo$info[1]' id='motivo$info[1]' value='$info[8]' onkeyup='checkMotivo(\"$info[1]\")'></td>
      <td><input type='radio' name='$info[1]' value='YES' id='YES$info[1]' onchange='giaOccupato(\"$info[4]\",\"$_SESSION[utente]\",\"$info[1]\",\"$info[6]\")'>✔
      <input type='radio' name='$info[1]' value='NO' id='NO$info[1]' onchange='rifiuto(\"$info[1]\")'>❌
      </td>
      </tr>";
    }
    else {continue;}
  }
  echo "</table>";
  echo "</div>";

  echo "<h1>Inviti che hai già gestito</h1>";
  echo "<div class=\"table-responsive\">";
  echo "<table class=\"table text-center\">";
  echo "<tr>
    <th>Creatore</th>
    <th>Località</th>
    <th>Data e Ora</th>
    <th>Tema</th>
    <th>Durata</th>
    <th>Motivo</th>
    <th>Responso</th>
    </tr>";

  foreach ($dati AS $key => $info) {
    #faccio un controllo sulla data delle riunioni, se queste devono avvenire (quindi data odierna minore di data riunione) ma ho già dato un
    #responso, le stampo con un set di radio button in cui l'opzione corretta, di rifiuto o accettazione, è checkata
    if ($info[7]!="" && $data<$info[4]){
      $citta_via=logisticaSala($cid, $info[3]);
      echo "<tr>
      <td>$info[0] ($info[1]) <p id='$info[1]' style='color:red'></p></td>
      <td>Dipartimento: $info[2]<br>Sala: $info[3]<br>Città: $citta_via[0]<br>Via: $citta_via[1]</td>
      <td>$info[4]</td>
      <td>$info[5]</td>
      <td>$info[6]</td>
      <td><input type='text' name='motivo$info[1]' id='motivo$info[1]' value='$info[8]' onkeyup='checkMotivo(\"$info[1]\")'></td>";
      if ($info[7] == "Accetto"){
        echo"<td><input type='radio' name='$info[1]' value='YES' id='YES$info[1]' onchange='giaOccupato(\"$info[4]\",\"$_SESSION[utente]\",\"$info[1]\",\"$info[6]\")' checked>✔
        <input type='radio' name='$info[1]' value='NO' id='NO$info[1]' onchange='rifiuto(\"$info[1]\")'>❌
        </td>";
      }
      elseif ($info[7] == "Declino"){
        echo"<td><input type='radio' name='$info[1]' value='YES' id='YES$info[1]' onchange='giaOccupato(\"$info[4]\",\"$_SESSION[utente]\",\"$info[1]\",\"$info[6]\")'>✔
        <input type='radio' name='$info[1]' value='NO' id='NO$info[1]' onchange='rifiuto(\"$info[1]\")' checked>❌
        </td>";
      }
      echo"</tr>";

    }
    else {continue;}
  }
  echo "</table>";
  echo "</div>";
  echo '<div class="form-group">
      <button type="submit" name="btncaricainviti" class="btn btn-success" id="accetta">Accetta i cambiamenti</button>
    </div>';
  echo "</form>";

  echo "<h1>Inviti di riunioni passate</h1>";
  echo "<div class=\"table-responsive\">";
  echo "<table class=\"table text-center\">";
  echo "<tr>
    <th>Creatore</th>
    <th>Località</th>
    <th>Data e Ora</th>
    <th>Tema</th>
    <th>Durata</th>
    <th>Motivo</th>
    <th>Responso</th>
    </tr>";
  foreach ($dati AS $key => $info) {
    #vengono stampati solo gli inviti di riunioni già trascorse senza checkbox ma con un campo che dice se l'invito venne declinato o meno
    if ($data>=$info[4]){
      $citta_via=logisticaSala($cid, $info[3]);
      echo "<tr>
      <td>$info[0] ($info[1])</td>
      <td>Dipartimento: $info[2]<br>Sala: $info[3]<br>Città: $citta_via[0]<br>Via: $citta_via[1]</td>
      <td>$info[4]</td>
      <td>$info[5]</td>
      <td>$info[6]</td>
      <td><input type='text' name='motivo$info[1]' value='$info[8]' readonly></td>
      <td>$info[7]</td>";
      echo"</tr>";
    }
    else {continue;}
  }
  echo "</table>";
  echo "</div>";
?>
