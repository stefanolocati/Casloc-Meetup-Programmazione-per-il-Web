<?php
#restituisci chi partecipa e chi non partecipa ad una riunione

  #uso $c come fosse un count per verificare se la query mi da risultati o meno, in questa maniera posso stampare un messaggio diverso
  #del tipo "partecipa della gente: lista della gente che partecipa" oppure "non partecipa nessuno"
  require "connessione.php";
  $id_riunione = $_GET['id_riunione'];
  $c= 0;
  #controllo se ci sono dei partecipanti
  $sql = "SELECT utente FROM invitato_a WHERE riunione='$id_riunione' AND responso='Accetto'";
  $res = $cid->query($sql);
  if (mysqli_num_rows($res)==0){
    $c=$c+1;
  }
  else{
    #se ci sono dei partecipanti li stampo
    echo '<h3>Partecipano:</h3>';
    echo "<div  style='text-align:center'>";
    echo '<a>';
    while($row=$res->fetch_row())
    {
      $colonna=$row[0];
      echo "$colonna<br>";
    }
    echo '</a>';
    echo '</div>';
  }
  $sql = "SELECT utente, motivo FROM invitato_a WHERE riunione='$id_riunione' AND responso='Declino'";
  $res = $cid->query($sql);
  if (mysqli_num_rows($res)==0){
    if ($c==1){
      #se c è uno significa che è stato aggiornato precedentemente e che la prima interrogazione non aveva ridaoto alcuna tupla quindi
      #nessuno è stato ancora invitato, allora lo stampo
      echo "<h3>Non ci sono ancora responsi</h3>";
    }
  }
  else{
    #se il risultato della query non è nullo significa che della gente non partecipa, stampo chi non partecipa assieme al motivo tra parentesi
    echo '<h3>Non partecipano:</h3>';
    echo "<div  style='text-align:center'>";
    echo '<a>';
    while($row=$res->fetch_row())
    {
      $colonna=$row;
      echo "$colonna[0] (motivo: $colonna[1])<br>";
    }
    echo '</a>';
    echo '</div>';
  }
?>
