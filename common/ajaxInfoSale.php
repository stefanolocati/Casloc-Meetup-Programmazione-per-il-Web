<?php
#prende le informazioni realtive ad una sala quando quseta viene cambiata dal menu a tendina nella pagina di pianificazione delle riunioni
  require "connessione.php";
  #in q viene messo il nome della sala che viene passato dalla funzione che chiede la chiamata ajax, a sua volta passato dal
  #campo in input compilato dall'utente
  $q = $_GET['q'];
  $sql = "SELECT citta, via, numero_posti from dipartimento join sala_riunioni on (dipartimento.nome_dipartimento=sala_riunioni.nome_dipartimento)
  where nome_sala='$q';";
  $res = $cid->query($sql);
  if (mysqli_num_rows($res)!=0){
    while($row=$res->fetch_row())
    {
      $colonna=$row;
      echo "Città: $colonna[0], Via: $colonna[1], Posti in sala: $colonna[2]<br>";
    }
  }
  $sql = "SELECT tipo, quantità FROM strumento WHERE nome_sala='$q';";
  $res = $cid->query($sql);
  if (mysqli_num_rows($res)!=0){
    while($row=$res->fetch_row())
    {
      $colonna=$row;
      echo "Strumento: $colonna[0], Quantità: $colonna[1]<br>";
    }
  }



?>
