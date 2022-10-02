<?php
  #RIUNIONI A CUI HANNO PARTECIPATO UN NUMERO DI IMPIEGATI CHE È SUPERIORE AL NUMERO DI IMPIEGATI CHE AFFERISCONO AL DIPARTIMENTO
  #DELL'IMPIEGATO O DIRIGENTE CHE HA ORGANIZZATO LA RIUNIONE
  $riunioni = null;

  $sql = "SELECT B.creatore, B.id_riunione, B.nome_dipartimento, B.nome_sala, B.data_e_ora, B.tema, B.durata
  FROM invitato_a AS A JOIN riunione AS B ON (A.riunione = B.id_riunione)
  WHERE A.responso = 'Accetto'
  GROUP BY A.riunione
  HAVING COUNT(*) > (SELECT COUNT(*)
                    FROM utente
                    WHERE dipartimento = B.nome_dipartimento)";

  $res = $cid->query($sql);
  if (mysqli_num_rows($res)!=0){
    echo "<h5>Riunioni a cui hanno partecipato un numero di impiegati
    che è superiore al numero di impiegati che afferiscono al dipartimento
    dell'impiegato o dirigente che ha organizzato la riunione.</h5>";
    while($row=$res->fetch_row())
  	{
  			$riunioni[$row[1]]=$row; #uso row[1] perchè si riferisce all'identificativo della riunione, mentre nel select il primo campo ridato è quello del creatore della riunione
  	}
    echo "<div class=\"table-responsive\">";
    echo "<table class=\"table text-center\">";
    echo "<tr>
      <th>Creatore</th>
      <th>ID Riunione</th>
      <th>Località</th>
      <th>Data e Ora</th>
      <th>Tema</th>
      <th>Durata</th>
      </tr>";
    foreach ($riunioni AS $id => $info) {
      $citta_via=logisticaSala($cid, $info[3]); //trova città, via e il numero di posti della sala dove si svolge la riunione
      echo "<tr>
        <td>$info[0]</td>
        <td>$info[1]</td>
        <td>Dipartimento: $info[2]<br>Sala: $info[3]<br>Città: $citta_via[0]<br>Via: $citta_via[1]</td>
        <td>$info[4]</td>
        <td>$info[5]</td>
        <td>$info[6]</td>";
    }
    echo "</table>
    </div>";
  }

  #stampo il numero di riunioni create da direttori
  $riunioni = null;
  $sql = "SELECT COUNT(*) FROM riunione JOIN utente ON (creatore = mail) WHERE tipo = 'Direttore'";
  $res = $cid->query($sql);
  if (mysqli_num_rows($res)!=0){
    echo "<h2>Numero di riunioni create da direttori: " . ($res->fetch_row())[0] . "</h2>";
  }

  #stampo il numero di riunioni create da impiegati
  $riunioni = null;
  $sql = "SELECT COUNT(*) FROM riunione JOIN utente ON (creatore = mail) WHERE tipo != 'Direttore'";
  $res = $cid->query($sql);
  if (mysqli_num_rows($res)!=0){
    echo "<h2>Numero di riunioni create da impiegati: " . ($res->fetch_row())[0] . "</h2>";
  }
?>
