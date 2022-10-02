<?php

include_once("../common/connessione.php");
include_once("../common/funzioni.php");

  $ris = riScriviRiunione($cid, $_POST["nome_sala"], $_POST["nome_dipartimento"], $_POST["tema"], $_POST["data"], $_POST["durata"], $_GET["identificativo"]);
  echo $ris["msg"];

	if ($ris["status"]=='ok'){
		header("Location:../index.php?op=riunioni&status=ok&msg=". urlencode($ris["msg"]));
	}
  else
		header("Location:../index.php?status=ko&msg=". urlencode($ris["msg"]));
?>
