<?php
#stampa le riunioni che avvengono nel giorno, settimana o mese selezionato dall'utente
  require "connessione.php";
  include "funzioni.php";
  $mese = $_GET['mese']; //prendo il mese selezionato dall'utente
  $settimana = $_GET['settimana']; //prendo la settimana selezionata dall'utente
  $giorno = $_GET['giorno']; //prendo il giorno selezionato dall'utente
  $creatore = $_GET['creatore']; //creatore è in realtà l'utente loggato
  $riunioni = array();
  $risultato = array("status"=>"ok","msg"=>"", "contenuto"=>"");
  //prende le informazioni di mese settimana e giorno che sono stringhe e le converto in dati temporali
  //se la stringa è vuota la lascio tale senza convertirla, questo perchè con la conversione verrebbe messa di default a valore 01
  //e falserebbe il risultato delle interrogazioni al database
  if ($mese!=""){
    $mese = date("m",strtotime($mese));
  }
  if ($settimana!=""){
    $settimana= date("W",strtotime($settimana));
  }
  if ($giorno!=""){
    $giorno = date("d",strtotime($giorno));
  }
  #in base al valore di mese, settimana o giorno e alle loro combinazioni date dall'utente, ammesso che siano accettabili, viene preparata
  #nella variabili $sql l'interrogazione corrispondente
  if ($mese=="" && $settimana=="" && $giorno==""){
    $sql = "SELECT DISTINCT creatore, id_riunione, nome_dipartimento, nome_sala, data_e_ora, tema, durata
    FROM riunione LEFT OUTER JOIN invitato_a ON (id_riunione=riunione)
    WHERE (riunione.creatore = '$creatore' or (utente='$creatore' and responso='Accetto'))
    ORDER BY data_e_ora";
  }
  elseif ($mese=="" && $settimana==""){
    $sql = "SELECT DISTINCT creatore, id_riunione, nome_dipartimento, nome_sala, data_e_ora, tema, durata
    FROM riunione LEFT OUTER JOIN invitato_a ON (id_riunione=riunione)
    WHERE (riunione.creatore = '$creatore' or (utente='$creatore' and responso='Accetto')) AND DAY(data_e_ora)='$giorno'
    ORDER BY data_e_ora";
  }
  elseif ($mese=="" && $giorno=="") {
    $sql = "SELECT DISTINCT creatore, id_riunione, nome_dipartimento, nome_sala, data_e_ora, tema, durata
    FROM riunione LEFT OUTER JOIN invitato_a ON (id_riunione=riunione)
    WHERE (riunione.creatore = '$creatore' or (utente='$creatore' and responso='Accetto')) AND WEEK(data_e_ora)='$settimana'
    ORDER BY data_e_ora";
  }
  elseif ($settimana=="" && $giorno=="") {
    $sql = "SELECT DISTINCT creatore, id_riunione, nome_dipartimento, nome_sala, data_e_ora, tema, durata
    FROM riunione LEFT OUTER JOIN invitato_a ON (id_riunione=riunione)
    WHERE (riunione.creatore = '$creatore' or (utente='$creatore' and responso='Accetto')) AND MONTH(data_e_ora)='$mese'
    ORDER BY data_e_ora";
  }
  elseif ($mese=="") {
    $sql = "SELECT DISTINCT creatore, id_riunione, nome_dipartimento, nome_sala, data_e_ora, tema, durata
    FROM riunione LEFT OUTER JOIN invitato_a ON (id_riunione=riunione)
    WHERE (riunione.creatore = '$creatore' or (utente='$creatore' and responso='Accetto')) AND WEEK(data_e_ora)='$settimana' AND DAY(data_e_ora)='$giorno'
    ORDER BY data_e_ora";
  }
  elseif ($settimana=="") {
    $sql = "SELECT DISTINCT creatore, id_riunione, nome_dipartimento, nome_sala, data_e_ora, tema, durata
    FROM riunione LEFT OUTER JOIN invitato_a ON (id_riunione=riunione)
    WHERE (riunione.creatore = '$creatore' or (utente='$creatore' and responso='Accetto')) AND MONTH(data_e_ora)='$mese' AND DAY(data_e_ora)='$giorno'
    ORDER BY data_e_ora";
  }
  elseif ($giorno=="") {
    $sql = "SELECT DISTINCT creatore, id_riunione, nome_dipartimento, nome_sala, data_e_ora, tema, durata
    FROM riunione LEFT OUTER JOIN invitato_a ON (id_riunione=riunione)
    WHERE (riunione.creatore = '$creatore' or (utente='$creatore' and responso='Accetto')) AND WEEK(data_e_ora)='$settimana' AND MONTH(data_e_ora)='$mese'
    ORDER BY data_e_ora";
  }
  else {
    $sql = "SELECT DISTINCT creatore, id_riunione, nome_dipartimento, nome_sala, data_e_ora, tema, durata
    FROM riunione LEFT OUTER JOIN invitato_a ON (id_riunione=riunione)
    WHERE (riunione.creatore = '$creatore' or (utente='$creatore' and responso='Accetto')) AND WEEK(data_e_ora)='$settimana' AND DAY(data_e_ora)='$giorno' AND MONTH(data_e_ora)='$mese'
    ORDER BY data_e_ora";
  }
  $res = $cid->query($sql);
  if ($res==null)
  {
    $risultato["status"]="ko";
    $risultato["msg"]="Errore nella esecuzione della interrogazione";
    return $risultato;
  }
  while($row=$res->fetch_row())
  {
      $riunioni[$row[1]]=$row; #uso row[1] perchè si riferisce all'identificativo della riunione
  }
  $risultato["contenuto"]=$riunioni;
  $riunioni = $risultato["contenuto"];
  #in $date rigistro il current timestamp in modo da poterlo confrontare con la data di ogni riunione e in base a quello
  #stampare le riunioni non ancora avvenute o quelle passate
  $date = date('Y-m-d H:i:s');
  #creo la tabella e riempio le table head
  echo "<h1>Riunioni</h1>";
  echo "<div class=\"table-responsive\">";
  echo "<table class=\"table text-center\">";
  echo "<tr>
    <th>Creatore</th>
    <th>ID Riunione</th>
    <th>Località</th>
    <th>Data e Ora</th>
    <th>Tema</th>
    <th>Durata</th>

    <th>Modifica</th>
    <th>Invita</th>
    <th>Elimina</th>
    <th>Invitati</th>
    </tr>";
  foreach ($riunioni AS $id => $info) {
    #controllo per stampare solo le riunioni che devono ancora avvenire
    if ($info[4]<$date) {
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
      #se la riunione stampata non è stata creata dall'utente loggato allora non può modificarla, cancellarla, invitare gente o visualizzare ipartecipanti
      #può solo visualizzare i partecipanti, quindi viene visualizzate una x invece che le img cliccabili che portano alle pagine con quelle funzioni
      if ($info[0]!=$creatore) {
        echo "<td>❌</td>
          <td>❌</td>
          <td>❌</td>
          <td>❌</td>";
      }
      else {
        #con show modal viene visualizzata una modal vuota, con cancellaRiunione viene stampata nella modal la richiesta se l'utente è certo di
        #voler cancellare la riunione, con visualizzaUtenti viene stampato nella modal l'elenco degli utenti partecipanti e di quelli non partecipanti
        #con relativi motivi (gli utenti che non hanno dato responso sono esclusi)
        echo "<td><a href='index.php?op=modificaRiunione&valore=$info[1]'><img class='smallicons' src='images/update.png'></a></td>
          <td><a href='index.php?op=invitaUtenti&valore=$info[1]'><img class='smallicons' src='images/plus.png'></a></td>
          <td><a ><img class='smallicons' src='images/trash.png' onclick='showModal(); cancellaRiunione(\"$info[1]\")' style='cursor:pointer;'></a></td>
          <td><a ><img class='smallicons' src='images/lente.png' onclick='showModal(); visualizzaUtenti(\"$info[1]\")' style='cursor:pointer;'></a></td>";
      }
  }
  echo "</table>";
  echo "</div>";
?>
