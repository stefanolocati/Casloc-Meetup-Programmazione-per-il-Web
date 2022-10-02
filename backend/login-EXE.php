<?php

$login= $_POST["mail"];
$pwd = $_POST["pass"];
$pwdmd5 = md5($pwd);

include_once("../common/connessione.php");
include_once("../common/funzioni.php");

if ($cid)
{
  $result= isUser($cid,$login,$pwdmd5);
	if ($result["status"]=="ok")
	{
	  if (isset($_POST["ricordami"]))
		  // se l'utente richiede di essere ricordato, allora setto il cookie
		  // questo approccio è MOLTO insicuro (per cui non consento di salvare anche la password
		  // vedremo più avanti come renderlo più sicuro e salvare anche la password
		 setcookie ("mail",$login,time()+43200,"/");
	   elseif (isset($_COOKIE["mail"])) {
		 unset($_COOKIE['mail']);
		 setcookie('mail', null, -1, '/');
	   }
    $tipo = trovaTipo($cid, $login);
    $autorizzazione = trovaAut($cid, $login);
	  $cid->close();
	  session_start();
    #setto alcune informazioni della sessione, quindi la mail con dell'utente con cui la sessione viene identifiacta univocamente
    #il tipo di utente (se direttore o impiegato in modo da visualizzare le giuste operazioni e compiti che ognuo dei due può svolgere)
    #l'auterizzazione, in modo da permettere ad utenti autorizzati di svolgere ulteriori operazioni
	  $_SESSION["utente"]=$login;
    $_SESSION["tipo"]=$tipo[0];
	  $_SESSION["logged"]=true;
    $_SESSION["autorizzazione"]=$autorizzazione[0];
    #se l'utente è autorizzato allora in $_SESSION["autorizzazione"] metto la mail dell'utente, altrimenti rimane vuoto

	  header("Location:../index.php?status=ok&msg=". urlencode($result["msg"]));
	}
	else
	{
	  header("Location:../index.php?status=ko&msg=" . urlencode($result["msg"]));
	}
}
else
	header("Location:../index.php?status=ko&msg=". urlencode("errore di connessione al db"));


?>
