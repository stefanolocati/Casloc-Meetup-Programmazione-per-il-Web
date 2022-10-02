<?php
session_start();

include_once("../common/connessione.php");
include_once("../common/funzioni.php");

if (isset($_SESSION["logged"]))
{
  $ris = cancellaRiunione($cid, $_GET["conferma"]);
	if ($ris["status"]=='ok'){
		header("Location:../index.php?op=riunioni&status=ok&msg=". urlencode($ris["msg"]));
  }
	else
		header("Location:../index.php?status=ko&msg=". urlencode($ris["msg"]));
}


?>
