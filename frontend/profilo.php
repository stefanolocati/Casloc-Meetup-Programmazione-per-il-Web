<?php
#restituisce le info riguardanti il profilo dell'utente loggato
  $res = trovaProfilo($cid, $_SESSION["utente"]);
  if ($res["status"]=="ko")
  {
    echo $res['msg'];
  }

  echo "<h1>Profilo</h1>";
  $dati = $res["contenuto"];

  #le informazioni vengonon stampate ordinatamente in una tabella in stile "nome campo - valore" invece di progettare un layout
  echo "<div class=\"table-responsive\">";
  echo "<table class=\"table text-center\">";
  foreach ($dati as $key => $info) {
    echo '<tr><th>Foto</th><td> <img id="fotoprofilo" src="upload/'.$info[6].'"></img></td></tr>';
    echo "<tr><th>Mail</th><td>$info[0]</td></tr>";
    echo "<tr><th>Nome</th><td>$info[1]</td></tr>";
    echo "<tr><th>Cognome</th><td>$info[2]</td></tr>";
    echo "<tr><th>Dipartimento</th><td>$info[3]</td></tr>";
    echo "<tr><th>Tipo</th><td>$info[4]</td></tr>";
    if ($info[4]=='Impiegato') {
      #if che nel caso si tratti di un utente impiegato ne stampi il ruolo (funzionario, eccetera)
      echo "<tr><th>Ruolo</th><td>$info[5]</td></tr>";
    }
    else {
      #se si stratta di utente direttore vengono stampati altre informazioni opzionali
      echo "<tr><th>Anni di servizio</th><td>$info[7]</td></tr>";
      echo "<tr><th>Data di promozione</th><td>$info[8]</td></tr>";
    }
  }
  echo "</table>";
  echo "</div>";
?>
