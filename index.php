<!DOCTYPE html>
<html lang="it">
  <?php
    session_start(); #creazione di una nuova sessione
    #inclusione della connessione al database, inclusione delle funzion che permettono di raccogliere dati dal database ed inserirli
    #inclusione del file html che contiene l'head e i metadati, inclusione del file con le funzioni javascript
    require "common/connessione.php";
    include "common/funzioni.php";
    require "common/head.html";
  ?>

  <body>
    <script type="text/javascript" src="js/funzionijs.js"></script>
    <?php
      require "common/nav.php"; //inclusione della navbar inclusa nel bootstrap
    ?>

    <div class="objectcontainer">
    <!--div che contiene tutto quello che viene stampato nella pagina-->
      <?php
      if (!isset($_SESSION["logged"])) //codice da eseguire nel caso non si è loggati
      {
        if (!isset($_GET['op'])){
          #se non si è loggati e non si compiono operazioni nella pagina viene mostrato un messaggio di benvenuto
          echo "<div class='benvenuto'><h1>Benvenuto nel sistema di gestione delle riunioni</h1></div>";
        }
        else
        {
          #eventualmente se qualcuno nella barra di ricerca digita il codice di una qualche operazione ma
          #non è comunque loggato viene stampato un messaggio di errore
          if ($_GET["op"]=='registrati')
          {
            #eccezione nel caso in cui si esegue l'operazione di registrazione o di log in
            echo "<div>";
            #vienen richiamato il codice nel frontend relativo all'operazione indicata, in questo caso registrati.php
            include "frontend/" . $_GET["op"] . ".php";
            echo "</div>";
          }
          else {
            echo "<div class=\"alert alert-danger\"><strong>Non sei loggato. Non puoi accedere a questo servizio</strong></div>";
          }
        }
      }
      else //codice da eseguire nel caso si è loggati
      {
        if (isset($_GET["op"]))
        {
          #se si è loggati e si schiaccia il pulsante relativo ad una funzione, visualizzazione delle riunioni, del profilo o altro
          #viene caricata la rispettiva pagina del frontend
          echo "<div>";
          include "frontend/" . $_GET["op"] . ".php";
          echo "</div>";
        }
        elseif (!isset($_GET["status"])) {
          #messaggio di benvenuto non appena effettutato il log in quando lo status non è ancora settato (in quanto relativo al successo di una qualsiasi
          #altra operazione oltre al log in)
          echo "<div class='benvenuto'><h1>";
          echo "Ciao " . $_SESSION["utente"] . ". Sei connesso";
          echo "</h1></div>";
        }
      }
      ?>


    <div class="messagebox">
      <?php
        if (isset($_GET["status"]))
        {
          #vengono stampati i messaggi relativi al fallimento o successo di un'operazione che vengono identificati rispettivamente da
          #uno status ko o status ok
          if ($_GET["status"]=='ok')
            echo "<div class='benvenuto'><div class=\"alert alert-success\"><strong>" . urldecode($_GET["msg"]) .
            "</strong></div></div>";
          else
            echo "<div class=\"alert alert-danger\"><strong>Errore: " . urldecode($_GET["msg"]) .
            "</strong></div>";

         }
      ?>
    </div>
    </div>

    <!-- richiamo il footer a fine body -->
    <?php
      require "common/footer.html"
    ?>
  </body>
</html>
