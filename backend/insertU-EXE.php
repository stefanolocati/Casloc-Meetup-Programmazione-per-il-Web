<?php
session_start();

include_once("../common/connessione.php");
include_once("../common/funzioni.php");
  $filename = $_FILES['foto']['name'];
  $location = "../upload/".$filename;
  move_uploaded_file($_FILES['foto']['tmp_name'], $location);
  $ris = scriviUtente($cid, $_POST["funzione"], $_POST["ruolo"], $_POST["anniservizio"], $_POST["datapromozione"],
  $_POST["nome"], $_POST["cognome"], $_POST["mail"], $_POST["password"], $_POST["nome_dipartimento"], $filename, $_POST["data"]);


	if ($ris["status"]=='ok'){
    //imposto i dati della sessione in modo che dopo la registrazione l'utente sia automaticamente loggato
    $_SESSION["utente"]=$_POST["mail"];
    $_SESSION["tipo"]=$_POST["funzione"];
    $_SESSION["logged"]=true;
    $_SESSION["autorizzazione"]="";
		header("Location:../index.php?op=profilo&status=ok&msg=". urlencode($ris["msg"]));
  }
	else
		header("Location:../index.php?status=ko&msg=". urlencode($ris["msg"]));



?>
