<?php
  #if che controllo se l'utente loggato è direttore o meno
  #la pagina pianifica di norma non viene nemmeno visualizzata nel menu a tendina della navbar ai non direttori ma questo controllo impedisce ai normali
  #utenti che eventualmente digitano il codice per eseguire questa operazione nella barra di navigazione di svolgere l'operazione
  #di autorizzazione
  if($_SESSION["tipo"]!='Direttore'){
    #se l'utente non è direttore, essendo l'operazione di autorizzazione riservata a questi, viene stampato un messaggio di errore
    echo "<div class=\"alert alert-danger\"><strong>Errore: non sei abilitato ad autorizzare utenti</strong></div>";
  }
  else {
    #leggiUtenti rida la lista di diverse informazioni (mail, dipartimento, ruolo ecc.) di tutti gli utenti eccetto quello loggato
    $res = leggiUtenti($cid, $_SESSION["utente"]);
    $utenti = $res["contenuto"];
    if ($res["status"]=="ko")
    {
      echo $res['msg'];
    }

    echo '<h2>Autorizza degli utenti a creare e gestire riunioni</h2>
    <form class="form-group" action="backend/insertA-EXE.php" method="post" id="form-group">';
    $presente = False;
    #prendo l''identificativo (la mail) degli utenti già autorizzati
    $sql = "SELECT impiegato FROM autorizza";
    $res = $cid->query($sql);
    echo "<div class=\"table-responsive\">";
    echo "<table class=\"table text-center\">";
    echo "<tr>
    <th>Mail</th>
    <th>Nome</th>
    <th>Cognome</th>
    <th>Ruolo</th>
    <th>Dipartimento</th>
    <th>Autorizzazione</th>
    </tr>";
    #stampa delle informazioni di ogni utente nella tabella
    foreach ($utenti AS $id => $info) {
      #se l'utente è un direttore non ha bisogno di essere autorizzato quindi non viene nemmeno stampato
      if ($info[5]=='Direttore'){continue;};
      echo "<tr>
      <td>$info[0]</td>
      <td>$info[1]</td>
      <td>$info[2]</td>
      <td>$info[3]</td>
      <td>$info[4]</td>";
      foreach ($res as $key => $value) {
        #controllo se l'utente che sto stampando è già autorizzato e memorizzo questa informazione nella variabile buleana "presente"
        if ($info[0]==$value['impiegato']) {
          $presente = True;
          break;
        }
        else {
          $presente = False;
        }
      }
      #se l'utente è già autorizzato allora stampo un set di radio button con l'opzione positiva già checkata in modo che il direttore
      #che sta gestendo le autorizzazioni sappia a prima vista quali utenti possono creare riunioni o meno
      #se l'utente non è autorizzato viene stampato un set di radio button con l'opzione negativa checkata
      if ($presente) {
        #viene utilizzato questo meccanismo di nome e valore con parole aggiunte alle mail degli utenti (contenute in $info[0]) perchè
        #avedo usato le mail, che contengono .com .it eccetera, quando queste vengono passate in post php cambia il . in _ quindi
        #nella funzione di inserimento nel file funzioni.php si lavora sulla stringa del nome e del valore delle variabili passsate in post
        #per effettuare gli inserimenti o cancellazioni
        echo "<td><input type='radio' name='presente$info[0]' value='$info[0].YES' checked>✔
        <td><input type='radio' name='presente$info[0]' value='$info[0].NO'>❌
        </td>";
      }
      else {
        echo "<td><input type='radio' name='assente$info[0]' value='$info[0].YES'>✔
        <td><input type='radio' name='assente$info[0]' value='$info[0].NO' checked>❌
        </td>";
      }
      echo "</tr>";
  }

  echo '</table></div><button type="submit" name="autorizza" class="btn btn-success">Accetta i cambiamenti</button></form>';
}
?>
