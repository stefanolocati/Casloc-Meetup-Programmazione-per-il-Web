<?php
#stampa nel menu a tendina di una sala le sale appartenenti al dipartimento scelto
  require "connessione.php";
  #in q viene messo il nome del dipartimento che viene passato dalla funzione che chiede la chiamata ajax, a sua volta passato dal
  #campo in input compilato dall'utente
  $q = $_GET['q'];
  $sql = "SELECT nome_sala FROM sala_riunioni WHERE nome_dipartimento='$q';";
  $res = $cid->query($sql);
  while($row=$res->fetch_row())
  {
    $colonna=$row[0];
    echo("<option value='$colonna' id='$colonna'>$colonna</option>");
  }


?>
