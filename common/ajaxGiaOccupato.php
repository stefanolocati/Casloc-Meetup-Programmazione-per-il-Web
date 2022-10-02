<?php
#controlla che l'utente non stia accettando di partecipare ad una riunione che si sovrappone ad una a cui ha già dato disponibilità
  require "connessione.php";
  $data = $_GET['data'];
  $identificativo = $_GET['identificativo'];
  $durata = $_GET['durata'];
  $id_riunione = $_GET['riunione'];

  $b = false;

  $sql = "SELECT DISTINCT data_e_ora, durata FROM riunione JOIN invitato_a ON (id_riunione=riunione)
  WHERE utente='$identificativo' AND responso='Accetto' AND id_riunione!='$id_riunione';";
  //con l'ultimo and evito che per una riunione il cui invito è già stato accettato e il responso inserito nel database, se si schiaccia su rifiuto e poi
  //di nuovo su accetto non vienen dato errore
  $res = $cid->query($sql);
  if (mysqli_num_rows($res)!=0){
    while($row=$res->fetch_row())
  	{
        //in row[0] c'è la data in ror[1] c'è la durata
        $inizio = new DateTime($row[0]);
        $ore = date('H',strtotime($row[1]));
        $minuti = date('i',strtotime($row[1])); //prende ore e minuti di durata delle lezioni selezionate dal database
        $fine = new DateTime($row[0]);
        $fine->add(new DateInterval('PT' . intval($ore) . 'H' . intval($minuti) . 'M')); //trovo il datetime di fine delle riunioni prese dal database
        $inizio_pianificando = new DateTime($data);


        $arr = explode(":", $durata, 3);
        $fine_pianificando = new DateTime($data);
        $fine_pianificando->add(new DateInterval('PT' . intval($arr[0]) . 'H' . intval($arr[1]) . 'M'));
        //trovo il datatime di fine della riunione selezionata dall'utente
        //se fine od inizio della riunione dell'utente vanno a sovrapporsi nel tempo di un'altra riunione che ha accettato allora stampo
        //un errore altrimento non stampo nulla
        if (($fine>=$fine_pianificando) && ($inizio<=$fine_pianificando)){
          $b = true;
        }
        if ((($fine>=$inizio_pianificando) && ($inizio<=$inizio_pianificando)) || $b ){
          echo 'Partecipi già ad un\'altra riunione nello stesso momento';
          break;
        }
        else{
          echo '';
        }
  	}
  }



?>
