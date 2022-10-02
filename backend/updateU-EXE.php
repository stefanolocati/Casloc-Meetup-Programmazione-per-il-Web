<?php
session_start();

include_once("../common/connessione.php");
include_once("../common/funzioni.php");

if (isset($_SESSION["logged"]))
{
  $filename = $_FILES['foto']['name'];
  $location = "../upload/".$filename;
  move_uploaded_file($_FILES['foto']['tmp_name'], $location);
  $ris = riScriviUtente($cid, $_SESSION["utente"], $_POST["funzione"], $_POST["ruolo"], $_POST["anniservizio"], $_POST["datapromozione"],
   $_POST["mail"], $_POST["password"], $_POST["nome_dipartimento"], $filename, $_POST["data"]);
  echo $ris["msg"];


	if ($ris["status"]=='ok'){
    $_SESSION["utente"]=$_POST["mail"];
    $_SESSION["tipo"]=$_POST["funzione"];
		header("Location:../index.php?op=profilo&status=ok&msg=". urlencode($ris["msg"]));
	}
  else
		header("Location:../index.php?status=ko&msg=". urlencode($ris["msg"]));
}



?>
