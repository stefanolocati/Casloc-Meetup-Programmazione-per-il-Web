<?php
session_start();

include_once("../common/connessione.php");
include_once("../common/funzioni.php");

$id_riunione = $_GET["id_riunione"];

if (isset($_SESSION["logged"]))
{
  $ris = registraInviti($cid, $id_riunione, $_POST);
	if ($ris["status"]=='ok'){

		header("Location:../index.php?op=invitaUtenti&valore=$id_riunione&status=ok&msg=". urlencode($ris["msg"]));
	}
  else
		header("Location:../index.php?op=invitaUtenti&valore=$id_riunione&status=ko&msg=". urlencode($ris["msg"]));
}
?>
