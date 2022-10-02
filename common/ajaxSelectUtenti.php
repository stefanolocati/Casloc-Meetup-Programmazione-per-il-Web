<?php
#stampa gli utenti che rispettano le cateogrie scelte dall'utente
  require "connessione.php";
  $dipartimento = $_GET['dipartimento'];
  $tipo = $_GET['tipo'];
  $ruolo = $_GET['ruolo'];
  $utente = $_GET['utente']; //utente mi serve in modo che la query possa selezionare tutti gli utenti tranne quello che sta invitando
  $id_riunione = $_GET['id_riunione'];
  $nposti = $_GET['nposti'];
  #conto il numero di persone totali invitate alla riunione, non importa che abbiano accettato o meno
  $sql = "SELECT count(*) as total FROM invitato_a WHERE riunione='$id_riunione'";
  $res = $cid->query($sql);
  $invitati=$res->fetch_row();
  #metto il numero di posti rimasti in $rimasti
  $rimasti = $nposti-$invitati[0];
  #stampa del numero di posti disponibili, ogni volta che un untente viene selezionato o deselezionato per invitarla il valore viene aggiornato
  #indicando quanti posti sono rimasti o se sono finiti o se si sta sforando la capienza
  echo "<input type='number' id='valore' value='$rimasti' hidden>";
  if ($rimasti==0){
    echo "<p id='errorOccupata' style='color:red'>Non ci sono più posti disponibili.</p>";
  }
  else {
    echo "<p id='errorOccupata' style='color:red'>Ci sono $rimasti posti disponibili.</p>";
  }

  $utenti = array();
  $risultato = array("status"=>"ok","msg"=>"", "contenuto"=>"");
  #metto in $sql l'interrogazione per selezionare gli utenti in base alle scelte di dell'invitante
  if ($dipartimento=="" && $tipo=="" && $ruolo==""){
    $sql = "SELECT mail, nome, cognome, ruolo, dipartimento, tipo FROM utente WHERE mail!='$utente'";
  }
  elseif ($dipartimento=="" && $tipo==""){
    $sql = "SELECT mail, nome, cognome, ruolo, dipartimento, tipo FROM utente WHERE mail!='$utente' and ruolo='$ruolo'";
  }
  elseif ($dipartimento=="" && $ruolo=="") {
    $sql = "SELECT mail, nome, cognome, ruolo, dipartimento, tipo FROM utente WHERE mail!='$utente' and tipo='$tipo'";
  }
  elseif ($ruolo=="" && $tipo=="") {
    $sql = "SELECT mail, nome, cognome, ruolo, dipartimento, tipo FROM utente WHERE mail!='$utente' and dipartimento='$dipartimento'";
  }
  elseif ($dipartimento=="") {
    $sql = "SELECT mail, nome, cognome, ruolo, dipartimento, tipo FROM utente WHERE mail!='$utente' and tipo='$tipo' and ruolo='$ruolo'";
  }
  elseif ($tipo=="") {
    $sql = "SELECT mail, nome, cognome, ruolo, dipartimento, tipo FROM utente WHERE mail!='$utente' and dipartimento='$dipartimento' and ruolo='$ruolo'";
  }
  elseif ($ruolo=="") {
    $sql = "SELECT mail, nome, cognome, ruolo, dipartimento, tipo FROM utente WHERE mail!='$utente' and dipartimento='$dipartimento' and tipo='$tipo'";
  }
  else {
    $sql = "SELECT mail, nome, cognome, ruolo, dipartimento, tipo FROM utente WHERE mail!='$utente' and dipartimento='$dipartimento' and tipo='$tipo' and ruolo='$ruolo'";
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
      $utenti[$row[0]]=$row;
  }
  $risultato["contenuto"]=$utenti;
  $utenti = $risultato["contenuto"];
  #gli utenti sono divisi in già invitati, che vengono stampati a fine pagina e che non possono essere "dis-invitati"
  #ed utenti invitabili stampati nella prima metà della pagina e con un checkbox per indicare se si vuole invitarli o meno
  echo "<h2>Gli utenti che puoi invitare</h2>";
  $presente = False;
  #seleziono gli utenti già invitati alla riunione utilizzando l'id
  $sql = "SELECT utente, riunione FROM invitato_a WHERE riunione='$id_riunione'";
  $res = $cid->query($sql);
  echo "<div class=\"table-responsive\">";
  echo "<table class=\"table text-center\">";
  echo "<tr>
  <th>Mail</th>
  <th>Nome</th>
  <th>Cognome</th>
  <th>Tipo</th>
  <th>Ruolo</th>
  <th>Dipartimento</th>
  <th>Invitato</th>
  </tr>";
  #con questo primo for selezione un utente alla volta tra quelli della prima interrogazione
  foreach ($utenti AS $id => $info) {
    #con questo for controllo se l'utente selezionato e all'interno di quelli già invitati nel caso non lo stampo e salto al prossimo utente
    foreach ($res as $key => $value) {
      if ($info[0]==$value['utente']) {
        $presente = True;
        break;
      }
      else {
        $presente = False;
      }
    }
    if ($presente==True) {
      continue;
    }
    echo "<tr>
    <td>$info[0]</td>
    <td>$info[1]</td>
    <td>$info[2]</td>
    <td>$info[5]</td>
    <td>$info[3]</td>
    <td>$info[4]</td>";
    #checkbox per l'invito
    #
    echo "<td><input type='checkbox' name='$info[0]' id='$info[0]' value='$info[0]' onchange='postiOccupati(\"$info[0]\")'>✔</td></tr>";
  }
  #bottono per acccettare la gente selezionata per essere invitata
  echo '</table><button type="submit" name="invita" class="btn btn-success">Manda gli inviti</button></div>';

  #stampa degli utenti già invitati
  echo "<h2>Gli utenti che che hai già invitato</h2>";
  $presente = False;
  $sql = "SELECT utente, riunione FROM invitato_a WHERE riunione='$id_riunione'";
  $res = $cid->query($sql);
  echo "<div class=\"table-responsive\">";
  echo "<table class=\"table text-center\">";
  echo "<tr>
  <th>Mail</th>
  <th>Nome</th>
  <th>Cognome</th>
  <th>Tipo</th>
  <th>Ruolo</th>
  <th>Dipartimento</th>
  </tr>";
  #stampa di ogni riga per ciascun utente
  foreach ($utenti AS $id => $info) {
    #for che per l'utente della riga corrente controlla se è già stato invitato o no
    foreach ($res as $key => $value) {
      if ($info[0]==$value['utente']) {
        $presente = True;
        break;
      }
      else {
        $presente = False;
      }
    }
    if ($presente==False) {
      continue;
    }
    echo "<tr>
    <td>$info[0]</td>
    <td>$info[1]</td>
    <td>$info[2]</td>
    <td>$info[5]</td>
    <td>$info[3]</td>
    <td>$info[4]</td>";
    echo "</tr>";
  }
  echo '</table></div>';
?>
