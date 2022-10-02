<header role="banner">
  <nav class="navbar navbar-expand-md navbar-dark bg-dark" id='menuorizzontale'>
    <div class="container">

      <a class="navbar-brand" href="index.php"><img src='images/logo1.png' style='height: 50px;'>Casloc</a>
      <!--stampa del bottone con menu a tendina per dispositivi mobile che non riscono a visualizzare
      tutte le opzione della navbar assieme-->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!--bottoni della navbar-->
      <div class="collapse navbar-collapse" id='navbarsExample05'>
        <ul class="navbar-nav ml-auto pl-lg-5 pl-0" id="menucentrato">

          <!-- link per tornare alla home -->
          <li class="nav-item">
            <a class="nav-link active" href="index.php">Home</a>
          </li>

          <!--if che controlla se si è loggati, se lo si è vengono allora stampati tutti i polsanti per eseguire per visualizzare
          le relative pagine e svolgere operazioni, altrimenti viene stampato solamente il tasto di log in / registrazione-->
          <?php
            if (isset($_SESSION["logged"]))
            {
          ?>
          <!--menu a tendina per le pagine relative alle funzioni sulle riunioni-->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Riunioni</a>
            <div class="dropdown-menu" aria-labelledby="dropdown04">
              <a class="dropdown-item" href="index.php?op=riunioni">Visualizza Riunioni</a>
              <a class="dropdown-item" href="index.php?op=riunioniPassate">Riunioni Trascorse</a>

              <!--if che stampa l'opzione per raggiungere la pagina di pianifica delle riunioni solo se si è utenti direttori o autorizzati-->
              <?php if($_SESSION["tipo"]=='Direttore' or $_SESSION["autorizzazione"]==$_SESSION["utente"])
                {
              ?>
              <a class="dropdown-item" href="index.php?op=pianifica">Pianifica</a>
              <?php } ?>

            </div>
          </li>

          <!--menu a tendina per le funzioni relative alla gestione del profilo utente-->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Profilo</a>
            <div class="dropdown-menu" aria-labelledby="dropdown04">
              <a class="dropdown-item" href="index.php?op=profilo">Visualizza Profilo</a>
              <a class="dropdown-item" href="index.php?op=inviti">Visualizza Inviti</a>
              <a class="dropdown-item" href="index.php?op=modificaProfilo">Modifica Profilo</a>

              <!--if per stampare l'opzione per raggiunre la pagina di autorizzazione di altri utenti solo se si è direttori-->
              <?php if($_SESSION["tipo"]=='Direttore')
                {
              ?>
              <a class="dropdown-item" href="index.php?op=autorizza">Autorizza</a>
              <?php } ?>

              <a class="dropdown-item" href="index.php?op=infoVarie">Info Varie</a>
            </div>
          </li>

          <!-- link alla pagina dei contatti-->
          <li class="nav-item">
            <a class="nav-link" href="index.php?op=contatti">Contatti</a>
          </li>

        <?php } #fine controllo sessione ?>

        </ul>

        <!-- tasto di registrazione / log in-->
        <ul class="navbar-nav ml-auto" id='pulsantelogin'>
          <?php if (isset($_SESSION["logged"]))
            {
          ?>
          <!-- se si è loggati il tasto mostra la scritta log out per uscire dal profilo -->
          <li class="nav-item cta-btn">
            <a class="nav-link" href="backend/logout-EXE.php">Log Out</a>
          </li>
          <?php
            }
            else
            {
          ?>
          <!-- se non si è loggati il tasto mostra la scritta log in registrati-->
          <li class="nav-item cta-btn" onclick="showModalLog()">
            <a class="nav-link" href="" data-toggle="modal">Log In/Registrati</a>
          </li>
        <?php } #fine else di controllo sessione ?>
        </ul>

      </div>

    </div>

  </nav>

</header>

<!--modal che viene aperto quando si schiaccia il tasto di log in o registrazione per inserire i propri dati-->
<div class="modal" id="modalLog" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-admin">
    <div class="modal-content">
      <h1>Accedi al tuo account</h1><br>
      <form method="POST" action="backend/login-EXE.php">
        <input type="text" name="mail" placeholder="Mail di login"  value=""><br>
        <input type="password" name="pass" placeholder="Password"><br>Ricordami:
        <input type="checkbox" name="ricordami"><br>
        <input type="submit" name="login" value="Login">
      </form>
      <div class="login-help">
        <br><a href="index.php?op=registrati" id='linkRegistrazione'>Registrazione</a>
      </div>
    </div>
  </div>
</div>
