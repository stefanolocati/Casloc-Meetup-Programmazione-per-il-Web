<?php
#controlla che non si stia creando una riunione che si sovrappone ad un altra, nello stesso tempo nella stessa sala
  require "connessione.php";
  $data = $_GET['data'];
  $durata_pianificando = $_GET['durata'];
  $sala = $_GET['sala'];
  $modifica = $_GET['modifica'];
  $identificativo = $_GET['identificativo'];
  $b = false;
  if ($modifica==true){
    #se sto eseguendo questo controllo mentre modifico la data di una riunione già pianifica allora nella query controllo anche l'identificativo
    #altrimenti mi darebbe l'errore che la riunione si sovrappone (con se stessa, questto perchè una riunione per essere modifica già sta nel database)
    $sql = "SELECT data_e_ora, durata from riunione where nome_sala='$sala' and id_riunione!='$identificativo';";
  }
  else {
      $sql = "SELECT data_e_ora, durata from riunione where nome_sala='$sala';";
  }
  $res = $cid->query($sql);
  if (mysqli_num_rows($res)!=0){
    while($row=$res->fetch_row())
    {
      #trova la data e tempo in cui finisce ogni riunione che viene presa dal database
      $inizio = new DateTime($row[0]); #conversione da string a data
      $ore = date('H',strtotime($row[1]));
      $minuti = date('i',strtotime($row[1]));
      $fine = new DateTime($row[0]);
      $fine->add(new DateInterval('PT' . intval($ore) . 'H' . intval($minuti) . 'M')); //calcolo di quando finiscono le riunioni nel database
      $inizio_pianificando = new DateTime($data);

      #trovo quando finisce la riunione che sto pianificando
      #se la riunione che sto pianificando inizia o finisce nell'arco di tempo durante il quale c'è una qualsiasi altra riunione
      #significa che c'è una sovrapposizione quindi stampo l'errore
      if ($durata_pianificando){
        $arr = explode(":", $durata_pianificando, 2);
        $fine_pianificando = new DateTime($data);
        $fine_pianificando->add(new DateInterval('PT' . intval($arr[0]) . 'H' . intval($arr[1]) . 'M'));
        if (($fine>=$fine_pianificando) && ($inizio<=$fine_pianificando)){
          $b = true;
        }
      }
      if ((($fine>=$inizio_pianificando) && ($inizio<=$inizio_pianificando)) || $b ){
        echo 'Esiste già una riunione nella stessa sala nello stesso orario';
        break;
      }
      else{
        echo "";
      }
    }
  }
?>
