<?php
  #che controlla se l'utente che si sta loggando è già essitente permettendo quindi poi di crearne la relativa sessione
  function isUser($cid,$username,$pwd)
  {
    $risultato= array("msg"=>"","status"=>"ok");
    $sql = "SELECT * FROM utente where mail = '$username' and password = '$pwd'";
    $res = $cid->query($sql);
    if ($res==null)
    {
        $msg = "Login o password non sono stati inseriti correttamente<br/>" . $res->error;
        $risultato["status"]="ko";
        $risultato["msg"]=$msg;
    }elseif($res->num_rows==0 || $res->num_rows>1)
    {
        $msg = "Login o password sbagliate";
        $risultato["status"]="ko";
        $risultato["msg"]=$msg;
    }elseif($res->num_rows==1)
    {
      $msg = "Login effettuato con successo";
      $risultato["status"]="ok";
      $risultato["msg"]=$msg;
    }
      return $risultato;
  }

  #funzione per riconoscere il tipo di utente (direttore o impiegato) che serve per la sessione
  function trovaTipo($cid,$mail)
  {
    $sql = "SELECT tipo FROM utente where mail='$mail'";
    $res = $cid->query($sql);
    while($row=$res->fetch_row())
  	{
  			return $utente[$row[0]]=$row;
        break;
  	}
  }

  #funzione per riconoscere se l'utente è autorizzato o no
  function trovaAut($cid,$mail)
  {
    $sql = "SELECT impiegato FROM autorizza where impiegato='$mail'";
    $res = $cid->query($sql);
    while($row=$res->fetch_row())
  	{
  			return $utente[$row[0]]=$row;
        break;
  	}
  }

  #vengono dati in input il nome di una tabella del database ed il nome di una sua colonna, vengono restituiti gli elementi di quella colonna
  function selezionaColonna($cid, $tabella, $colonna)
  {
    $sql = "SELECT DISTINCT $colonna FROM $tabella";
    $res = $cid->query($sql);
    if (!$res) {
        echo "errore";
    }
    return $res;
  }

  #resituisce le tuple delle riunioni a cui l'utente dato in input partecipa (o che ha creato)
  #viene usata nella pagina di visualizzazione delle riunioni passate
  function leggiRiunioni($cid, $id_utente)
  {
  	$riunioni = array();
  	$risultato = array("status"=>"ok","msg"=>"", "contenuto"=>"");

  	if ($cid->connect_errno) {
  		$risultato["status"]="ko";
  		$risultato["msg"]="Errore nella connessione al db " . $cid->connect_error;
  		return $risultato;
  	}
  	$sql = "SELECT DISTINCT creatore, id_riunione, nome_dipartimento, nome_sala, data_e_ora, tema, durata
    FROM riunione LEFT OUTER JOIN invitato_a ON (id_riunione=riunione)
    WHERE riunione.creatore = '$id_utente' or (utente='$id_utente' and responso='Accetto') ORDER BY data_e_ora";
  	$res=$cid->query($sql);
  	if ($res==null)
  	{
  		$risultato["status"]="ko";
  		$risultato["msg"]="Errore nella esecuzione della interrogazione " . $cid->error;
  		return $risultato;
  	}
  	while($row=$res->fetch_row())
  	{
  			$riunioni[$row[1]]=$row; #uso row[1] perchè si riferisce all'identificativo della riunione
  	}
  	$risultato["contenuto"]=$riunioni;
  	return $risultato;
  }

  #prende i campi dal db relativi alla riunione di cui si è dato l'id, viene usato nella pagina di modifica delle riunioni
  function trovaRiunione($cid, $id_riunione)
  {
      $info = array();
    	$risultato = array("status"=>"ok","msg"=>"", "contenuto"=>"");
    	if ($cid->connect_errno) {
    		$risultato["status"]="ko";
    		$risultato["msg"]="Errore nella connessione al db " . $cid->connect_error;
    		return $risultato;
    	}
      $sql = "SELECT  nome_sala, nome_dipartimento, tema, data_e_ora, durata, creatore, id_riunione FROM riunione WHERE id_riunione='$id_riunione';";
      $res = $cid->query($sql);
      if ($res==null)
    	{
    		$risultato["status"]="ko";
    		$risultato["msg"]="Errore nella esecuzione della interrogazione " . $cid->error;
    		return $risultato;
    	}
    	while($row=$res->fetch_row())
    	{
    			$info[$row[0]]=$row;
    	}
    	$risultato["contenuto"]=$info;
    	return $risultato;
  }

  #inserimento di un utente nel database quando si registra
  function scriviUtente($cid, $tipo, $ruolo, $anniservizio, $datapromozione, $nome, $cognome, $mail, $password, $nome_dipartimento, $foto, $datanascita)
  {
    $risultato = array("status"=>"ok","msg"=>"", "contenuto"=>"");
  	$msg="";
  	$errore=false;
    if ($cid->connect_errno) {
  		$risultato["status"]="ko";
  		$risultato["msg"]="errore nella connessione al db " . $cid->connect_error;
  		return $risultato;
  	}
    #controllo per vedere se tutte le informazioni sono state inserite
    if (empty($nome) || empty($cognome) || empty($mail) || empty($password) || empty($datanascita) ||
    ($tipo=='Direttore' && (empty($anniservizio) && $anniservizio!=0 || empty($datapromozione)) ) )
  	{
  		$errore = true;
  		$msg = "Non hai inserito tutte le informazioni<br/>";
  	}
    #controllo che gli anni di servizio non siano superiori all'età
    if ($tipo=='Direttore') {
      $date1 = date("Y-m-d h:i:sa");
      $date1 = new DateTime($date1);
      $date2 = new DateTime($datanascita);
      $interval = $date1->diff($date2);
      if ($anniservizio > $interval->y){
        $errore = true;
        $msg .= "I tuoi anni di servizio non sono coerenti con la tua età<br/>";
      }
    }
    #controllo per verificare che la password abbia solo caratteri accettabili
    $numeri = ['0','1','2','3','4','5','6','7','8','9'];
    $speciali = ['!','?','£','$','%','&','(',')','[',']','{','}'];
    $non_permessi = [' ','_','.',',',';',':','-'];
    if (!preg_match('/[' . preg_quote(implode(',', $numeri)) . ']+/',$password)){
      $errore = true;
      $msg .= "La password non ha numeri<br/>";
    }
    if (!preg_match('/[' . preg_quote(implode(',', $speciali)) . ']+/',$password)){
      $errore = true;
      $msg .= "La password non ha caratteri speciali<br/>";
    }
    if (preg_match('/[' . preg_quote(implode(',', $non_permessi)) . ']+/',$password)){
      $errore = true;
      $msg .= "La password ha caratteri non permessi (spazi, punti, virgole, ecc)<br/>";
    }
    #controllare per i direttori (che sono gli unici col campo data promozione) abbiamo la data di nascita minore di quella di promozione
    if ($tipo=='Direttore' && $datapromozione < $datanascita)
  	{
  		$errore = true;
  		$msg .= "La data di nascita deve essere minore della data di promozione<br/>";
  	}
    #i direttori non hanno campo ruolo quindi viene messo null di default
    if ($tipo=='Direttore') {
      $ruolo = NULL;
    }
    #gli impiegati non hanno i campi anniservizio e datapromozione quindi vengono messi di default a null
    if ($tipo=='Impiegato') {
      $anniservizio = NULL;
      $datapromozione = NULL;
    }
    if (!$errore)
  	{
      #cripto la password
      $password = md5($password);
      if ($tipo=='Direttore') {
        #se sto modificando l'utente in un direttore allora  prima faccio diventare il direttore di quel dipartimento un impiegato
        $sql = "UPDATE utente SET tipo='Impiegato', ruolo='Impiegato Semplice', data_promozione=NULL, anni_servizio=NULL WHERE tipo = 'Direttore' AND dipartimento = '$nome_dipartimento'";
        $res = $cid->query($sql);
      }
      #se non ci sono errori allora eseguo l'inserimento nel database
  	  $sql = "INSERT INTO utente(password, nome, cognome, ruolo, tipo, data_nascita, data_promozione, foto, anni_servizio, dipartimento, mail)
  	          VALUES ('$password', '$nome', '$cognome', '$ruolo', '$tipo', '$datanascita', '$datapromozione', '$foto', '$anniservizio', '$nome_dipartimento', '$mail')";
  	  $res = $cid->query($sql);
      if ($tipo=='Direttore') {
        #se sto inserendo un utente direttore allora aggiorno il direttore nella tabella dipartimento
        $sql = "UPDATE dipartimento SET direttore='$mail' WHERE nome_dipartimento = '$nome_dipartimento'";
        $res = $cid->query($sql);
      }
  	  if ($res==null)
  		{
  			$risultato["msg"]= "Problema nella memorizzazione del risultato sul database<br/>";
  			$risultato["status"]="ko";
  		}
  	  else
  	  {
  	   $risultato["msg"] = "L'operazione di inserimento si &egrave; conclusa con successo";
  	   $risultato["status"]="ok";
  	  }
  	}
  	else
  	{
  		$risultato["msg"] = "Non sono stati compilati correttamente dei campi del form.<br/>"	. $msg;
  	  $risultato["status"]="ko";
  	}
  	return $risultato;
  }

  #inserimento di una nuova riunione nel database
  function scriviRiunione($cid, $id, $nome_sala, $nome_dipartimento, $tema, $data_e_ora, $durata, $creatore){
    $risultato = array("status"=>"ok","msg"=>"", "contenuto"=>"");
  	$msg="";
  	$errore=false;
    if ($cid->connect_errno) {
  		$risultato["status"]="ko";
  		$risultato["msg"]="errore nella connessione al db " . $cid->connect_error;
  		return $risultato;
  	}
    #controllo se le informazioni sono inserite
    if (empty($tema) || empty($data_e_ora) || empty($durata) || empty($id))
  	{
  		$errore = true;
  		$msg = "Non hai inserito tutte le informazioni<br/>";
  	}
    $tema = $cid -> real_escape_string($tema);
    #controllo se la data della riunione è già passata o meno
    if ($data_e_ora < date("Y-m-d h:i:sa")){
      $errore = true;
      $msg .= "La data della riunione non può essere già passata<br/>";
    }
    #controllo se l'id della riunione ha solo caratteri permessi
    $non_permessi = ["!", "?", "$","%","£","&", "(", ")","=","{","}","[","]",".","_","\"","\'",",",":",";"," "];
    if (preg_match('/[' . preg_quote(implode(',', $non_permessi)) . ']+/',$id)){
      $errore = true;
      $msg .= "L'ID della riunione ha caratteri non permessi(spazi, punti, virgole, ecc)<br/>";
    }
    #controllo che la riunione non si sovrapponga con altre
    $sql = "SELECT data_e_ora, durata from riunione where nome_sala='$nome_sala';";
    $riunioni = $cid->query($sql);
    if (mysqli_num_rows($riunioni)!=0){
      while($row=$riunioni->fetch_row())
      {
        #trova la data e tempo in cui finisce ogni riunione che viene presa dal database
        $inizio = new DateTime($row[0]); #conversione da string a data
        $ore = date('H',strtotime($row[1]));
        $minuti = date('i',strtotime($row[1]));
        $fine = new DateTime($row[0]);
        $fine->add(new DateInterval('PT' . intval($ore) . 'H' . intval($minuti) . 'M')); //calcolo di quando finiscono le riunioni nel database
        $inizio_pianificando = new DateTime($data_e_ora);

        #trovo quando finisce la riunione che sto pianificando
        #se la riunione che sto pianificando inizia o finisce nell'arco di tempo durante il quale c'è una qualsiasi altra riunione
        #significa che c'è una sovrapposizione quindi stampo l'errore
        $b = false;
        if ($durata){
          $arr = explode(":", $durata, 2);
          $fine_pianificando = new DateTime($data_e_ora);
          $fine_pianificando->add(new DateInterval('PT' . intval($arr[0]) . 'H' . intval($arr[1]) . 'M'));
          if (($fine>=$fine_pianificando) && ($inizio<=$fine_pianificando)){
            $b = true;
          }
        }
        if ((($fine>=$inizio_pianificando) && ($inizio<=$inizio_pianificando)) || $b ){
          $errore = true;
          $msg .= "Esiste già una riunione nella stessa sala nello stesso orario";
          break;
        }
      }
    }
    #se non ci sono errori allora eseguo l'inserimento
    if (!$errore)
  	{
      $sql = "INSERT INTO riunione(id_riunione, nome_sala, nome_dipartimento, tema, data_e_ora, durata, creatore)
        VALUES ('$id', '$nome_sala', '$nome_dipartimento', '$tema', '$data_e_ora', '$durata', '$creatore')";
      $res = $cid->query($sql);
      if ($res==null)
      {
        $risultato["msg"]= "Problema nella memorizzazione del risultato sul database<br/>";
        $risultato["status"]="ko";
      }
      else
      {
        $risultato["msg"] = "L'operazione di inserimento si &egrave; conclusa con successo";
        $risultato["status"]="ok";
      }
    }
    else
    {
      $risultato["msg"] = "Non sono stati compilati correttamente dei campi del form.<br/>" . $msg;
      $risultato["status"]="ko";
    }
    return $risultato;
  }

  #lettura delle informazioni di un utente dalla tabella utente data la sua mail, viene usata nella pagina di visualizzazione del
  #profilo e in quella di modificazione di quest'ultimo
  function trovaProfilo($cid, $utente)
  {
    $info = array();
  	$risultato = array("status"=>"ok","msg"=>"", "contenuto"=>"");

  	if ($cid->connect_errno) {
  		$risultato["status"]="ko";
  		$risultato["msg"]="Errore nella connessione al db " . $cid->connect_error;
  		return $risultato;
  	}

    $sql = "SELECT  mail, nome, cognome, dipartimento, tipo, ruolo, foto, anni_servizio, data_promozione, password, data_nascita FROM utente WHERE mail='$utente';";
    $res = $cid->query($sql);
    if ($res==null)
  	{
  		$risultato["status"]="ko";
  		$risultato["msg"]="Errore nella esecuzione della interrogazione " . $cid->error;
  		return $risultato;
  	}
  	while($row=$res->fetch_row())
  	{
  			$info[$row[0]]=$row;
  	}
  	$risultato["contenuto"]=$info;
  	return $risultato;
  }

  //trova città, via e il numero di posti della sala dove si svolge la riunione
  function logisticaSala($cid, $sala){
    $sql = "SELECT citta, via, numero_posti from dipartimento join sala_riunioni on (dipartimento.nome_dipartimento=sala_riunioni.nome_dipartimento)
    where nome_sala='$sala';";
    $res = $cid->query($sql);
    $risultato=$res->fetch_row();
    return $risultato;
  }


  #modifica dei dati di un utente all'interno del database
  function riScriviUtente($cid, $utente, $tipo, $ruolo, $anniservizio, $datapromozione, $mail, $pass, $dipartimento, $foto, $datanascita)
  {
    $risultato = array("status"=>"ok","msg"=>"", "contenuto"=>"");
  	$msg="";
  	$errore=false;
    if (empty($mail) || empty($pass) || ($tipo=='Direttore' && (empty($anniservizio) && $anniservizio!=0 || empty($datapromozione)) ) )
  	{
  		$errore = true;
  		$msg = "Non hai inserito tutte le informazioni<br/>";
  	}
    #controllo che gli anni di servizio non siano superiori all'età
    if ($tipo=='Direttore') {
      $date = date("Y-m-d h:i:sa");
      $date1 = new DateTime($date);
      $date2 = new DateTime($datanascita);
      $interval = $date1->diff($date2);
      if (($anniservizio > $interval->y) || $anniservizio < 0 ){
        $errore = true;
        $msg .= "I tuoi anni di servizio non sono coerenti con la tua età<br/>";
      }
    }
    #controllo sui caratteri della password
    $numeri = ['0','1','2','3','4','5','6','7','8','9'];
    $speciali = ['!','?','£','$','%','&','(',')','[',']','{','}'];
    $non_permessi = [' ','_','.',',',';',':','-'];
    if (!preg_match('/[' . preg_quote(implode(',', $numeri)) . ']+/',$pass)){
      $errore = true;
      $msg .= "La password non ha numeri<br/>";
    }
    if (!preg_match('/[' . preg_quote(implode(',', $speciali)) . ']+/',$pass)){
      $errore = true;
      $msg .= "La password non ha caratteri speciali<br/>";
    }
    if (preg_match('/[' . preg_quote(implode(',', $non_permessi)) . ']+/',$pass)){
      $errore = true;
      $msg .= "La password ha caratteri non permessi (spazi, punti, virgole, ecc)<br/>";
    }
    if ($tipo=='Direttore' && $datapromozione < $datanascita)
    {
      $errore = true;
      $msg .= "La data di nascita deve essere minore della data di promozione<br/>";
    }
    if ($tipo=='Direttore') {
      $ruolo = NULL;
    }
    if ($tipo=='Impiegato') {
      $anniservizio = NULL;
      $datapromozione = NULL;
    }
    if (!$errore)
  	{
      #cripto la password
      $pass = md5($pass);
      if ($tipo=='Direttore') {
        #se sto modificando l'utente in un direttore allora  prima faccio diventare il direttore di quel dipartimento un impiegato
        $sql = "UPDATE utente SET tipo='Impiegato', ruolo='Impiegato Semplice', data_promozione=NULL, anni_servizio=NULL WHERE tipo = 'Direttore' AND dipartimento = '$dipartimento'";
        $res = $cid->query($sql);
      }
      #modifico l'utente
  	  $sql = "UPDATE utente SET tipo='$tipo', ruolo='$ruolo', anni_servizio='$anniservizio', data_promozione='$datapromozione',
        mail='$mail', password='$pass', dipartimento='$dipartimento', foto='$foto' WHERE mail='$utente'";
  	  $res = $cid->query($sql);
      if ($tipo=='Direttore') {
        #se sto modificando l'utente in un direttore allora aggiorno il direttore nella tabella dipartimento
        $sql = "UPDATE dipartimento SET direttore='$mail' WHERE nome_dipartimento = '$dipartimento'";
        $res = $cid->query($sql);
      }
  	  if ($res==null)
  		{
  			$risultato["msg"]= "Problema nella memorizzazione del risultato sul database.<br/>";
  			$risultato["status"]="ko";
  		}
  	  else
  	  {
  	   $risultato["msg"] = "L'operazione di modifica si &egrave; conclusa con successo.";
  	   $risultato["status"]="ok";
  	  }
  	}
  	else
  	{
  		$risultato["msg"] = "Non sono stati compilati correttamente dei campi del form.<br/>"
       		. $msg;
  	   $risultato["status"]="ko";
  	}
  	return $risultato;
  }

#prendo i dati relativi a tutti gli utenti tranne quello loggato, viene usata nella pagina autorizza per stampare tutti gli utenti appunto
#tranne il direttore autorizzante
  function leggiUtenti($cid, $autorizzatore)
  {
    $utenti = array();
  	$risultato = array("status"=>"ok","msg"=>"", "contenuto"=>"");

  	if ($cid->connect_errno) {
  		$risultato["status"]="ko";
  		$risultato["msg"]="Errore nella connessione al db " . $cid->connect_error;
  		return $risultato;
  	}
    $sql = "SELECT mail, nome, cognome, ruolo, dipartimento, tipo FROM utente WHERE mail!='$autorizzatore'";
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
  	return $risultato;
  }

  #funzione per modificare le enties nella tabella invitato_a aggiungendo responso e motivazione per ogni invito accettato o declinato
  function inserisciResponso($cid, $utente, $dati){
    $risultato = array("status"=>"","msg"=>"");
    $sql = '';
    $motivo = '';
    $res = '';
    if ($cid->connect_errno) {
      $risultato["status"]="ko";
      $risultato["msg"]="Errore nella connessione al db " . $cid->connect_error;
      return $risultato;
    }
    foreach ($dati as $nome_variabile => $valore_variabile) {
      // in dati ci stanno tutte le informazioni del post quindi in maniera alternata i text input dei motivi e il risultato del radio button
      if (substr($nome_variabile, 0, 6)=='motivo'){
        $motivo = $valore_variabile;
      }
      else {
        // se entro nell'else $nome variabile assume il valore della riunione
        if ($valore_variabile=="YES"){
          $sql = "UPDATE invitato_a SET responso='Accetto', motivo='', data_responso=CURRENT_TIMESTAMP WHERE riunione='$nome_variabile' AND utente='$utente'";
          $res = $cid->query($sql);
          if (!$res){break;}
        }
        if ($valore_variabile=="NO"){
          if ($motivo==""){
            $msg = "Non hai dato una motivazione per il declino.";
            $risultato["status"]="ko";
            $risultato["msg"]=$msg;
            return $risultato;
          }
          $motivo = $cid -> real_escape_string($motivo);
          $sql = "UPDATE invitato_a SET responso='Declino', motivo='$motivo', data_responso=CURRENT_TIMESTAMP WHERE riunione='$nome_variabile' AND utente='$utente'";
          $res = $cid->query($sql);
          if (!$res){break;}
        }
        if ($valore_variabile=="OCCUPATO"){
          $msg = "Non puoi accettare un'invito ad una riunione che si sovrappone ad una a cui hai già dato la tua partecipazione";
          $risultato["status"]="ko";
          $risultato["msg"]=$msg;
          return $risultato;
        }
      }
    }
    if (!$res) {
        $msg = "Errore nell'aggiornamento dei responsi nel database.<br/>";
        $risultato["status"]="ko";
        $risultato["msg"]=$msg;
    }
    else {
      $msg = "Aggiornamento dei responsi agli inviti avvenuto con successo.";
      $risultato["status"]="ok";
      $risultato["msg"]=$msg;
    }
    return $risultato;
  }

#inserisce nella tabella autorizza le tuple di nuovi utenti a cui è stata data l'autorizzazione
#elimina dalla tabella autorizza le tuple relativi agli utenti la cui autorizzazione è stata tolta da un direttore
#non fa nulla se i radiobutton non vengono cambiati
function registraAutorizzazioni($cid, $direttore, $utenti)
{
  $risultato = array("status"=>"","msg"=>"");
  $sql = '';

  if ($cid->connect_errno) {
    $risultato["status"]="ko";
    $risultato["msg"]="Errore nella connessione al db " . $cid->connect_error;
    return $risultato;
  }
  foreach ($utenti as $nome_campo => $valore) {
    #in $valore è registrata la mail dell'utente che funge poi da chiave nella tabella di autorizzazione assieme al direttore autorizzante
    #non si è potuta mettere la mial dell'utente come nome della variaible passata nel post perchè il punto della mail viene
    #sostituito con un underscore
    $yes = substr($valore, -4);
    $no = substr($valore, -3);
    $presenza = substr($nome_campo, 0, 8);
    #se l'utente è già autorizzato e il radiobutton con check positivo oppure se non è autorizzato e il check negativo sul radiobutton allora
    #non vienen eseguita alcuna operazione
    if (($presenza=='presente' and $yes=='.YES')||($presenza!='presente' and $no=='.NO')){
      continue;
    }
    elseif ($presenza=='presente' and $no=='.NO'){ #se l'utente è già autorizzato ma il radiobutton ha check negativo viene eliminata l'autorizzazione
      $valore = substr($valore, 0 , -3);
      $sql = "DELETE FROM autorizza WHERE impiegato = '$valore';";
      $res = $cid->query($sql);
      if (!$res){break;}
    }
    elseif ($presenza!='presente' and $yes=='.YES') {#se l'utente non è già autorizzato ma il radiobutton ha check positivo viene inserita l'autorizzazione
      $valore = substr($valore, 0 , -4);
      $sql = "INSERT INTO autorizza (direttore, impiegato, data) VALUES ('$direttore', '$valore', CURRENT_TIMESTAMP);";
      $res = $cid->query($sql);
      if (!$res){break;}
    }
  }
  if (!$res) {
      $risultato["status"]="ko";
      $risultato["msg"]="Errore nella gestione delle autorizzazioni nel database.<br>";
  }
  else {
    $msg = "Aggiornamento delle autorizzazioni avvenuto con successo.";
    $risultato["status"]="ok";
    $risultato["msg"]=$msg;
  }
  return $risultato;
}

#funzinoe per inserire nella tabella invitato_a gli inviti ad una riunione, ovviamnet esenza responso motivo e data di responso
function registraInviti($cid, $riunione, $utenti)
{
  $risultato = array("status"=>"","msg"=>"");
  $sql = '';
  if ($cid->connect_errno) {
    $risultato["status"]="ko";
    $risultato["msg"]="Errore nella connessione al db " . $cid->connect_error;
    return $risultato;
  }
  #in $utenti, essendo tutto il post del form, sono contenuti tutti gli utenti il cui checkbox è stato checkato
  #vengono ciclati ed inseriti uno alla volta
  foreach ($utenti as $id_utente => $registrato) {
    if ($registrato) {
      $sql = "INSERT INTO invitato_a (utente, riunione) VALUES ('$registrato', '$riunione');";
      $res = $cid->query($sql);
      if (!$res){break;}
    }
    else {
      continue;
    }
  }
  if (!$res) {
      $msg = "Errore nell'inserimento degli inviti nel database.<br/>";
      $risultato["status"]="ko";
      $risultato["msg"]=$msg;
  }
  else {
    $msg = "Aggiornamento delle autorizzazioni avvenuto con successo.";
    $risultato["status"]="ok";
    $risultato["msg"]=$msg;
  }
  return $risultato;
}

  #cancella dal database la riunione che ha l'id passato per parametro
  function cancellaRiunione($cid, $id){
    $risultato = array("status"=>"","msg"=>"");
    $sql = '';
    if ($cid->connect_errno) {
      $risultato["status"]="ko";
      $risultato["msg"]="Errore nella connessione al db " . $cid->connect_error;
      return $risultato;
    }
    $sql = "DELETE FROM riunione WHERE id_riunione = '$id';";
    $res = $cid->query($sql);
    if (!$res) {
        $msg = "Errore nella cancellazione della riunione dal database.<br/>";
        $risultato["status"]="ko";
        $risultato["msg"]=$msg;
    }
    else {
      $msg = "Cancellazione avvenuta con successo.";
      $risultato["status"]="ok";
      $risultato["msg"]=$msg;
    }
    return $risultato;
}

  #modifica dei dati di una riunione all'interno del database
  function riScriviRiunione($cid, $nome_sala, $nome_dipartimento, $tema, $data_e_ora, $durata, $identificativo)
  {
    $risultato = array("status"=>"ok","msg"=>"", "contenuto"=>"");
  	$msg="";
  	$errore=false;
    if (empty($nome_sala) || empty($nome_dipartimento) || empty($tema))
  	{
  		$errore = true;
  		$msg = "Non hai inserito tutte le informazioni<br/>";
  	}
    $tema = $cid -> real_escape_string($tema);
    if ($data_e_ora < date("Y-m-d h:i:sa")){
      $errore = true;
      $msg = "La data della riunione non può essere già passata<br/>";
    }
    #controllo che la riunione non si sovrapponga con altre
    $sql = "SELECT data_e_ora, durata from riunione where nome_sala='$nome_sala' and id_riunione!='$identificativo';";
    $riunioni = $cid->query($sql);
    if (mysqli_num_rows($riunioni)!=0){
      while($row=$riunioni->fetch_row())
      {
        #trova la data e tempo in cui finisce ogni riunione che viene presa dal database
        $inizio = new DateTime($row[0]); #conversione da string a data
        $ore = date('H',strtotime($row[1]));
        $minuti = date('i',strtotime($row[1]));
        $fine = new DateTime($row[0]);
        $fine->add(new DateInterval('PT' . intval($ore) . 'H' . intval($minuti) . 'M')); //calcolo di quando finiscono le riunioni nel database
        $inizio_pianificando = new DateTime($data_e_ora);

        #trovo quando finisce la riunione che sto pianificando
        #se la riunione che sto pianificando inizia o finisce nell'arco di tempo durante il quale c'è una qualsiasi altra riunione
        #significa che c'è una sovrapposizione quindi stampo l'errore
        $b = false;
        if ($durata){
          $arr = explode(":", $durata, 2);
          $fine_pianificando = new DateTime($data_e_ora);
          $fine_pianificando->add(new DateInterval('PT' . intval($arr[0]) . 'H' . intval($arr[1]) . 'M'));
          if (($fine>=$fine_pianificando) && ($inizio<=$fine_pianificando)){
            $b = true;
          }
        }
        if ((($fine>=$inizio_pianificando) && ($inizio<=$inizio_pianificando)) || $b ){
          $errore = true;
          $msg .= "Esiste già una riunione nella stessa sala nello stesso orario";
          break;
        }
      }
    }
    if (!$errore)
  	{
  	  $sql = "UPDATE riunione SET nome_sala='$nome_sala', nome_dipartimento='$nome_dipartimento', tema='$tema', data_e_ora='$data_e_ora', durata='$durata' WHERE id_riunione='$identificativo'";
  	  $res = $cid->query($sql);
  	  if ($res==null)
  		{
  			$risultato["msg"]= "Problema nella memorizzazione del risultato sul database.<br/>";
  			$risultato["status"]="ko";
  		}
  	  else
  	  {
  	   $risultato["msg"] = "L'operazione di modifica si &egrave; conclusa con successo.";
  	   $risultato["status"]="ok";
  	  }
  	}
  	else
  	{
  		$risultato["msg"] = "Non sono stati compilati correttamente dei campi del form.<br/>"	. $msg;
  	   $risultato["status"]="ko";
  	}
  	return $risultato;
  }

  #preleva le informazioni di una riunione alla quale si è stati invitati, non importa se passata o fotura, se l'invito è stato accettato o no
  #viene utilizzata nella pagina di visualizzazione degli inviti
  function leggiInviti($cid, $id_utente)
  {
  	$riunioni = array();
  	$risultato = array("status"=>"ok","msg"=>"", "contenuto"=>"");

  	if ($cid->connect_errno) {
  		$risultato["status"]="ko";
  		$risultato["msg"]="Errore nella connessione al db " . $cid->connect_error;
  		return $risultato;
  	}
  	$sql = "SELECT creatore, id_riunione, nome_dipartimento, nome_sala, data_e_ora, tema, durata, responso, motivo FROM riunione, invitato_a WHERE riunione = id_riunione and utente='$id_utente'";
  	$res=$cid->query($sql);
  	if ($res==null)
  	{
  		$risultato["status"]="ko";
  		$risultato["msg"]="Errore nella esecuzione della interrogazione " . $cid->error;
  		return $risultato;
  	}
  	while($row=$res->fetch_row())
  	{
  			$riunioni[$row[1]]=$row; #uso row[1] perchè si riferisce all'identificativo della riunione, mentre nella select il primo elemento è il creatore della riunione
  	}
  	$risultato["contenuto"]=$riunioni;
  	return $risultato;
  }
?>
