<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$db = 'meetupplanner';

$cid = new mysqli($hostname,$username,$password,$db);

if($cid->connect_errno)
  { echo 'Errore connessione (' . $cid->connect_errno . ')' . $cid->connect_error; }
/*else
  { echo 'Connesso. ' . $cid->host_info . "\n";}*/

?>
