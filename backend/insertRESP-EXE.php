<?php
session_start();

include_once("../common/connessione.php");
include_once("../common/funzioni.php");

if (isset($_SESSION["logged"]))
{


  $ris = inserisciResponso($cid, $_SESSION["utente"], $_POST);
	if ($ris["status"]=='ok'){
		header("Location:../index.php?op=inviti&status=ok&msg=". urlencode($ris["msg"]));
	}
  else
		header("Location:../index.php?op=inviti&status=ko&msg=". urlencode($ris["msg"]));
}

?>
