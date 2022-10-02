    //funzione per mostrare campo ruolo in registra se il tipo è impiegato o gli altri campi nel caso in cui il tipo selezionato è direttore
    function mostraCampi(){
      var value = document.getElementById('tipo').value;
      if (value==="Direttore" || value===""){
        document.getElementById('mostra_1').style.visibility="hidden";
        document.getElementById('mostra_1').style.display="none";
        document.getElementById('mostra_2').style.visibility="visible";
        document.getElementById('mostra_2').style.display="block";
      }
      else {
        document.getElementById('mostra_1').style.visibility="visible";
        document.getElementById('mostra_1').style.display="block";
        document.getElementById('mostra_2').style.visibility="hidden";
        document.getElementById('mostra_2').style.display="none";
      }
    }

    //controllo per verificare se la password possieda solo caratteri accettabili
  	function checkCaratteri(){
  		var value = document.getElementById('txtPassword').value;
      var testo = "";
      const terms = ["1", "2", "3","4","5", "6", "7","8","9","0"];
      const terms_2 = ["!", "?", "$","%","£","&", "(", ")","=","{","}","[","]"];
      const terms_3 = [" ","_",".",",",";",":","-"];
      if (value.length < 5){
        testo = "Deve contenere almeno 5 caratteri totali.";
      }
      if (!terms.some(term => value.includes(term))){
        testo += " Deve contenere almeno un numero.";
      }
      if (!terms_2.some(term => value.includes(term))){
        testo += " Deve contenere almeno un carattere speciale.";
      }
      if (terms_3.some(term => value.includes(term))){
        testo += " Non può contenere spazi, punti o underscore.";
      }
      document.getElementById('errorP').innerHTML = testo;
  	}

  //controllo per verificare che la data di nascita preceda quella di promozione (per i direttori)
  //e che venga inserita una data di nascita minore della data odierna
  function checkDate(){
    //creazione della data odierna
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;
    var testo = "";
    var nascita = document.getElementById('txtDatanascita').value;
    var promozione = document.getElementById('txtDatapromozione').value;
    var funzione = document.getElementById('tipo').value;
    if (funzione === 'Direttore'){
      if(nascita > promozione){
        testo += "La data di nascita deve essere più piccola della data di promozione. ";
      }
    }
    if (String(nascita)>today){
      testo += "La data di nascita deve essere più piccola della data odierna. ";
    }
    document.getElementById('errorData').innerHTML = testo;
    document.getElementById('errorPromo').innerHTML = testo;
	}

  //controllo per determinare se gli anni di servizio inseriti siano un numero e coerenti con la data di nascita
  function checkAnni(){
    //creazione della data odierna
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;
    var funzione = document.getElementById('tipo').value;
    var nascita = document.getElementById('txtDatanascita').value;
    var anni = document.getElementById('txtAnniservizio').value;
    var objNascita = new Date(nascita);
    var objOggi = new Date(today);
    var testo = "";
    if (funzione === 'Direttore'){
      if (anni > (objOggi.getFullYear() - objNascita.getFullYear()) ){
        testo  += "I tuoi anni di servizio non sono coerenti con la tua età.";
      }
    }
    else {
      testo  = "";
    }
    document.getElementById('errorAnni').innerHTML = testo;
  }

  //controllo per verificare che tutte le informazioni obbligatorie per la creazione del profilo siano state inserite
  function checkRegistrazione(){
    var nome = document.getElementById('nome').value;
    var cognome = document.getElementById('cognome').value;
    var funzione = document.getElementById('tipo').value;
    var ruolo = document.getElementById('ruolo').value;
    var anniserv = document.getElementById('txtAnniservizio').value;
    var promozione = document.getElementById('txtDatapromozione').value;
    var nascita = document.getElementById('txtDatanascita').value;
    var mail = document.getElementById('mail').value;
    var pass = document.getElementById('txtPassword').value;
    var errorP = document.getElementById('errorP').innerHTML;
    var errorData = document.getElementById('errorData').innerHTML;
    var errorAnni = document.getElementById('errorAnni').innerHTML;
    var errorPromo = document.getElementById('errorPromo').innerHTML;
    //se sulla pagina sono stampati degli errori allora viene bloccato il submit altrimenti funziona
    if ( ((funzione==='Impiegato' && ruolo!=="") || (funzione=="Direttore" &&  anniserv!=="" && promozione!=="")) &&
      errorP==="" && errorData==="" && errorAnni==="" && errorPromo==="" && nome!=="" && cognome!=="" && nascita!=="" && mail!=="" && pass!==""){
      return true;
    }
    else {
      return false;
    }
  }

  //controllo che tutte le informazioni obbligatorie per la modifica del profilo siano state inserite
  function checkModificaUtente(){
    var funzione = document.getElementById('tipo').value;
    var ruolo = document.getElementById('ruolo').value;
    var anniserv = document.getElementById('txtAnniservizio').value;
    var promozione = document.getElementById('txtDatapromozione').value;
    var mail = document.getElementById('mail').value;
    var pass = document.getElementById('txtPassword').value;
    var errorP = document.getElementById('errorP').innerHTML;
    var errorAnni = document.getElementById('errorAnni').innerHTML;
    var errorPromo = document.getElementById('errorPromo').innerHTML;
    if ( ((funzione==='Impiegato' && ruolo!=="") || (funzione=="Direttore" &&  anniserv!=="" && promozione!=="")) &&
      errorP==="" && mail!=="" && errorAnni==="" && errorPromo==="" && pass!==""){
      return true;
    }
    else {
      return false;
    }
  }

  //funzione per mostrare la password a tasto premuto
  function mostra(){
    var x = document.getElementById("txtPassword");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }

//controllo della semantica dell'id della riunione
function checkRiunione(){
  var value = document.getElementById('identificativo').value;
  var testo = "";
  const terms = ["!", "?", "$","%","£","&", "(", ")","=","{","}","[","]",".","_","\"","\'",",",":",";"," "];

  if (terms.some(term => value.includes(term))){
    testo += "L'ID non può contenere punti, underscore, spazi o caratteri speciali.";
  }
  document.getElementById('errorID').innerHTML = testo;
}

//controllo che la data in cui si pianifica la riunione non sia già passata
function checkDataRiunione(){
    //creo la data odierna
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today =yyyy + '-' + mm + '-' + dd;
    var valore = document.getElementById('data').value;
    if (String(valore) < today){
      document.getElementById('errorData').innerHTML = 'La data inserita è già passata';
    }
    else{
      document.getElementById('errorData').innerHTML = '';
    }
}

//chiamata ajax che va a fare il controllo se la riunione che si sta pianificando avviene in un momento e luogo già occupati
function giaPianificata(){
  var modifica = false;
  data = document.getElementById('data').value;
  sala = document.getElementById('sala').value;
  durata = document.getElementById('durata').value;
  titolo = document.getElementById('titolo').innerHTML;
  identificativo = document.getElementById('identificativo').value;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("errorGiaPianificata").innerHTML = this.responseText;
    }
  };

  if (titolo!=="Pianifica"){
    modifica = true;
  }
  xmlhttp.open("GET","common/ajaxGiaPianificata.php?data="+data+"&sala="+sala+"&durata="+durata+"&modifica="+modifica+"&identificativo="+identificativo,true);
  xmlhttp.send();
}

//controllo che siano state inserite tutte le informazioni obbligatorie per la creazione di una riunione e che non siano visualizzati errori
function checkPianifica(){
  var identificativo = document.getElementById('identificativo').value;
  var sala = document.getElementById('sala').value;
  var data = document.getElementById('data').value;
  var durata = document.getElementById('durata').value;
  var tema = document.getElementById('tema').value;
  var errorID = document.getElementById('errorID').innerHTML;
  var errorData = document.getElementById('errorData').innerHTML;
  var errorGiaPianificata = document.getElementById('errorGiaPianificata').innerHTML;
  if (identificativo!=="" && sala!=="" && data!=="" && durata!=="" && tema!==""
  && errorID==="" && errorData==="" && errorGiaPianificata===""){
    return true;
  }
  else {
    return false;
  }
}

//controllo che siano state inserite tutte le informazioni obbligatorie per la modifica di una riunione e che non siano visualizzati errori
function checkModifcaRiunione(){
  var sala = document.getElementById('sala').value;
  var data = document.getElementById('data').value;
  var durata = document.getElementById('durata').value;
  var tema = document.getElementById('tema').value;
  var errorData = document.getElementById('errorData').innerHTML;
  var errorGiaPianificata = document.getElementById('errorGiaPianificata').innerHTML;
  if (sala!=="" && data!=="" && durata!=="" && tema!=="" && errorData==="" && errorGiaPianificata===""){
    return true;
  }
  else {
    return false;
  }
}

//funzione che permette di mostrare la modal della cancellazione della riunione e visualizzazione degli utenti
function showModal(){
  var modal = document.getElementById("modal");
  modal.style.display = "block";
  window.onclick = function(event) {
    if (event.target == modal) {
      //se viene schiacciato una parte dello schermo fuori dalla modal questa svanisce
      modal.style.display = "none";
    }
  }
}
//funzione che visualizza la modal del login
function showModalLog(){
  var modal = document.getElementById("modalLog");
  modal.style.display = "block";
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
}

//funzione che cambia il contenuto della modal per chiedere all'utente la conferma di cancellare la pagina
//se si preme sì viene richiamata la pagina nel backend che si occupa dell'eliminazione delle riunioni dal database
function cancellaRiunione(valore){
  //stampo del contenuto nel modal chiedendo all'utente se vuole davvero cancellare la riunione
  document.getElementById("contenuto").innerHTML = `<h1>Sei sicuro di voler cancellare la riunione?</h1><br>
  <a href="#" name="conferma" id="conferma"><button type="button" name="button" style='width: 50%; position: absolute; left: 0'>Sì</button>
  </a> <a href="#"> <button type="button" name="chiudi" id="chiudi" style='width:50%; position: absolute; right: 0;'>No</button> </a>`;
  var chiudi = document.getElementById("chiudi");
  var conferma = document.getElementById("conferma");
  chiudi.onclick = function() {
    modal.style.display = "none";
  }
  //se l'utente schiaccia conferma parte un'azione nel backend che cancella la riunione, come argomento inoltre gli passo l'id della riunione in modo
  //che possa essere usato nella query per trovare nel database la riunione da cancellare
  str = "backend/cancR-EXE.php?conferma="+valore;
  document.getElementById("conferma").setAttribute('href', str);
}

//richiesta ajax per visualizzare nella modal di riunioni i partecipante ad una certa riunione
function visualizzaUtenti(valore){
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("contenuto").innerHTML = this.responseText;
    }
  };
  //in q viene passato il valore corrispondente all'id della riunione in modo che nella query
  //del back end possa essere usato questo dato per trovare la riunione di cui si vogliono conoscere i partecipanti
  xmlhttp.open("GET","common/ajaxPartecipanti.php?id_riunione="+valore,true);
  xmlhttp.send();
}

//richiesta ajax per la pagina di pianificazione delle riunioni, quando nella compilazione del form viene cambiato il valore del Dipartimento
//parte la richiesta ajax che chiede al database i nomi delle sale del dipartimento scelto e li restituisce, questi verranno stampati
//nel menu a tendina del form per la scelta della sala in cui svolgere la riunione
function mostraSale(valore){
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("sala").innerHTML = this.responseText;
    }
  };
  xmlhttp.open("GET","common/ajaxSale.php?q="+valore,true);
  xmlhttp.send();
  mostraInfoSala(document.getElementById('sala').value);
}

//serve per stampare nella pagina di pianifica delle riunioni dove si trova il dipartimento scelto per quella riunione e gli strumenti
//messi a disposizione dalla sala scelta
function mostraInfoSala(valore){
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("infoSala").innerHTML = this.responseText;
    }
  };
  xmlhttp.open("GET","common/ajaxInfoSale.php?q="+valore,true);
  xmlhttp.send();
}

//nella pagina per invitare gli utenti questa funzione permette di stampare solo quelli della categorie o dipartimento selezionati dall'utente
//che sta invitando, nella chiamata ajax vengono passati tutti i parametri che servono alla query per selezionare correttamente gli utenti che
//rispettano le condizioni imposte dall'invitante, come parametri invece alla funzione js sono passati l'id della riunione e la mail che
//identifica l'utente invitante
function mostraUtenti(utente, id_riunione){
  var dipartimento = document.getElementById('dipartimento').value;
  var tipo = document.getElementById('tipo').value;
  var ruolo = document.getElementById('ruolo').value;
  var nposti = document.getElementById('posti_sala').value;
  if (tipo==="Direttore" || tipo==="") {
    ruolo="";
  }
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("formInvito").innerHTML = this.responseText;
    }
  };
  xmlhttp.open("GET","common/ajaxSelectUtenti.php?dipartimento="+dipartimento+"&tipo="+tipo+"&ruolo="+ruolo+"&utente="+utente+"&id_riunione="+id_riunione+"&nposti="+nposti,true);
  xmlhttp.send();
}

//funzione per azzerare i campi sulle categorie di utenti da inviare quando viene schiacciato il tasto mostra tutti
function mostraTutti(){
  document.getElementById('dipartimento').value = "";
  document.getElementById('tipo').value = "";
  document.getElementById('ruolo').value = "";
}

//se rifiuto un invito mi da l'errore di dover dare un motivo
function rifiuto(riunione){
  document.getElementById(riunione).innerHTML = 'Inserire una giustificazione per non partecipare alla riunione';
}
//controlla se ho inserito un motivo per aver rifiutato la partecipazione ad una riunione
function checkMotivo(riunione){
  var moti = 'motivo';
  var no = 'NO';
  var motivo = moti.concat(riunione); //id del textbox
  var rifiuto = no.concat(riunione); //id radio butto rifiuto
  if (document.getElementById(rifiuto).checked){
    if (document.getElementById(motivo).value===""){
      document.getElementById(riunione).innerHTML = 'Inserire una giustificazione per non partecipare alla riunione';
    }
    else {
      document.getElementById(riunione).innerHTML = '';
    }
  }
}

//codice per aggiornare il numero di posti disponibili in sala durante la fase di invito, con stampa
function postiOccupati(id){
  var rimasti = document.getElementById('valore').value;
  if (document.getElementById(id).checked){
    //se ho checkato un utente diminuisco il numero di posti disponibili
    rimasti--;
    document.getElementById('valore').value = rimasti;
  }
  else {
    //se ho tolto il check ad utente aumento di uno il numero di posti disponibili
    rimasti++;
    document.getElementById('valore').value = rimasti;
  }
  if (rimasti === 0){
    //se ho zero posti dico che è stata raggiunta la capienza
    document.getElementById('errorOccupata').innerHTML = 'Non ci sono più posti disponibili.';
  }
  else if (rimasti>0) {
    //se rimangono altri posti stampo quanti ne mancano
    document.getElementById('errorOccupata').innerHTML = 'Ci sono '+rimasti+' posti disponibili.';
  }
  else {
    //se invito oltre la capienza mando un messaggio di errore
    document.getElementById('errorOccupata').innerHTML = 'STAI ANDANDO OLTRE LA CAPIENZA DELLA SALA!';
  }
}

//se si tenta di invitare altra gente una volta che la capienza della sala è superata viene il tasto submit non funziona
function checkInviti(){
  if (document.getElementById('errorOccupata').innerHTML=='STAI ANDANDO OLTRE LA CAPIENZA DELLA SALA!'){
    return false;
  }
  else {
    return true;
  }
}

//chiamata per effettuare il controllo se l'invito a cui l'utente vuole dare responso positivo è di una riunione che si sovrappone
//ad una a cui già partecipa
//se accetta l'invito ad una riunione che si sovrappone cambio il valore del radio button in "occupato", in questo modo nel backend
//viene controllato il valore passato e se è "occupato" non effettua l'inserimento
function giaOccupato(data, identificativo, riunione, durata){
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById(riunione).innerHTML = this.responseText;
      var yes = 'YES';
      var accetto = yes.concat(riunione); //id radio button accettazione
      if (document.getElementById(riunione).innerHTML !== ""){
        document.getElementById(accetto).value = "OCCUPATO";
      }
    }
  };
  xmlhttp.open("GET","common/ajaxGiaOccupato.php?data="+data+"&identificativo="+identificativo+"&durata="+durata+"&riunione="+riunione,true);
  xmlhttp.send();
}

//chiamata per stampare solo le riunioni nel mese settimana o giorno selezionato dall'utente, oppure tutte
function mostraRiunioni(creatore){
  var mese = document.getElementById('mese').value;
  var settimana = document.getElementById('settimana').value;
  var giorno = document.getElementById('giorno').value;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById('stampaRiunioni').innerHTML = this.responseText;
    }
  };
  xmlhttp.open("GET","common/ajaxMostraRiunioni.php?mese="+mese+"&settimana="+settimana+"&giorno="+giorno+"&creatore="+creatore,true);
  xmlhttp.send();
}

//annulla quello fatto dalla funzione sopra e ristampa tutte le riunioni
function mostraTutteRiunioni(){
  document.getElementById('mese').value = "";
  document.getElementById('settimana').value = "";
  document.getElementById('giorno').value = "";
}
