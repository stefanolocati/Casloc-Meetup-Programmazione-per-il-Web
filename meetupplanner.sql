-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 16, 2022 alle 14:01
-- Versione del server: 10.4.22-MariaDB
-- Versione PHP: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meetupplanner`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `autorizza`
--

CREATE TABLE `autorizza` (
  `direttore` varchar(30) NOT NULL,
  `impiegato` varchar(30) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `autorizza`
--

INSERT INTO `autorizza` (`direttore`, `impiegato`, `data`) VALUES
('matcas@mail.com', 'micpal@mail.com', '2022-02-16 12:58:53'),
('yelpra@mail.com', 'nino@mail.com', '2022-02-15 16:31:04');

-- --------------------------------------------------------

--
-- Struttura della tabella `dipartimento`
--

CREATE TABLE `dipartimento` (
  `nome_dipartimento` varchar(30) NOT NULL,
  `numero_impiegati` int(3) DEFAULT NULL,
  `citta` varchar(20) NOT NULL,
  `via` varchar(20) NOT NULL,
  `civico` int(3) NOT NULL,
  `direttore` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `dipartimento`
--

INSERT INTO `dipartimento` (`nome_dipartimento`, `numero_impiegati`, `citta`, `via`, `civico`, `direttore`) VALUES
('Artistico', 8, 'Lecco', 'Bonfanti', 6, 'yelpra@mail.com'),
('Consegne', 4, 'Lecco', 'Turati', 13, 'andmarz@mail.com'),
('Informatico', 15, 'Milano', 'Balotti', 13, 'matcas@mail.com'),
('Marketing', 10, 'Milano', 'Siberio', 25, 'steloc@mail.com');

-- --------------------------------------------------------

--
-- Struttura della tabella `invitato_a`
--

CREATE TABLE `invitato_a` (
  `utente` varchar(30) NOT NULL,
  `riunione` varchar(10) NOT NULL,
  `responso` varchar(10) NOT NULL,
  `data_responso` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `motivo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `invitato_a`
--

INSERT INTO `invitato_a` (`utente`, `riunione`, `responso`, `data_responso`, `motivo`) VALUES
('andmarz@mail.com', 'cinque5', 'Declino', '2022-02-10 13:32:32', 'Visita medica'),
('andmarz@mail.com', 'due2', 'Accetto', '2022-02-10 13:32:33', ''),
('andmarz@mail.com', 'quattro4', '', '2022-02-10 13:26:37', NULL),
('andmarz@mail.com', 'tre3', 'Accetto', '2022-02-10 13:32:33', ''),
('gincam@mail.com', 'cinque5', 'Declino', '2022-02-15 16:03:58', 'Impegno in famiglia'),
('gincam@mail.com', 'due2', 'Accetto', '2022-02-10 14:02:40', ''),
('gincam@mail.com', 'quattro4', 'Accetto', '2022-02-10 14:02:39', ''),
('gincam@mail.com', 'sei6', 'Accetto', '2022-02-15 16:03:58', ''),
('gincam@mail.com', 'sette7', '', '2022-02-10 13:56:34', NULL),
('gincam@mail.com', 'uno1', 'Accetto', '2022-02-15 16:03:57', ''),
('lorfer@mail.com', 'cinque5', 'Accetto', '2022-02-15 16:04:22', ''),
('lorfer@mail.com', 'tre3', 'Accetto', '2022-02-15 16:04:22', ''),
('lorfer@mail.com', 'uno1', 'Declino', '2022-02-15 16:04:21', 'Dottore'),
('luclas@mail.com', 'cinque5', 'Declino', '2022-02-15 16:04:44', 'Matrimonio'),
('luclas@mail.com', 'quattro4', 'Declino', '2022-02-10 14:01:10', 'Dottore'),
('luclas@mail.com', 'sei6', 'Accetto', '2022-02-15 16:04:44', ''),
('luclas@mail.com', 'sette7', 'Declino', '2022-02-15 16:04:44', 'Partecipo già ad un altra riunione'),
('luclas@mail.com', 'uno1', 'Accetto', '2022-02-15 16:04:44', ''),
('matcas@mail.com', 'due2', 'Accetto', '2022-02-10 13:29:17', ''),
('micpal@mail.com', 'cinque5', 'Accetto', '2022-02-15 16:06:56', ''),
('micpal@mail.com', 'sette7', 'Accetto', '2022-02-15 16:06:56', ''),
('nino@mail.com', 'cinque5', 'Declino', '2022-02-10 14:03:17', 'Matrimonio'),
('nino@mail.com', 'quattro4', 'Accetto', '2022-02-10 14:03:15', ''),
('nino@mail.com', 'sei6', 'Declino', '2022-02-10 14:03:15', 'Visita'),
('nino@mail.com', 'sette7', '', '2022-02-10 13:56:34', NULL),
('sernin@mail.com', 'cinque5', 'Accetto', '2022-02-10 13:31:40', ''),
('sernin@mail.com', 'quattro4', '', '2022-02-10 13:26:32', NULL),
('steloc@mail.com', 'cinque5', 'Accetto', '2022-02-10 13:30:40', ''),
('steloc@mail.com', 'tre3', 'Declino', '2022-02-10 13:30:40', 'Dottore'),
('tertod@mail.com', 'cinque5', 'Declino', '2022-02-10 13:34:01', 'Visita'),
('tertod@mail.com', 'due2', 'Accetto', '2022-02-10 13:34:01', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `riunione`
--

CREATE TABLE `riunione` (
  `id_riunione` varchar(10) NOT NULL,
  `nome_sala` varchar(30) NOT NULL,
  `nome_dipartimento` varchar(30) NOT NULL,
  `tema` text NOT NULL,
  `data_e_ora` datetime NOT NULL,
  `durata` time NOT NULL,
  `creatore` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `riunione`
--

INSERT INTO `riunione` (`id_riunione`, `nome_sala`, `nome_dipartimento`, `tema`, `data_e_ora`, `durata`, `creatore`) VALUES
('cinque5', 'Ripostiglio', 'Consegne', 'Fondi', '2022-04-08 14:28:00', '01:00:00', 'matcas@mail.com'),
('due2', 'Seconda', 'Artistico', 'Progettazioni', '2022-02-12 16:30:00', '01:00:00', 'micpal@mail.com'),
('quattro4', 'Seconda', 'Artistico', 'Nuovi strumenti', '2022-02-11 16:31:00', '01:00:00', 'steloc@mail.com'),
('sei6', 'Progettazione', 'Informatico', 'Marketing', '2022-06-07 16:00:00', '01:00:00', 'andmarz@mail.com'),
('sette7', 'Magna', 'Marketing', 'discussione libera', '2022-06-07 16:00:00', '01:00:00', 'matcas@mail.com'),
('tre3', 'Prima', 'Artistico', 'Aumenti', '2022-04-15 14:30:00', '01:00:00', 'micpal@mail.com'),
('uno1', 'Magna', 'Marketing', 'Strategie marketing', '2022-07-15 13:01:00', '01:00:00', 'steloc@mail.com');

-- --------------------------------------------------------

--
-- Struttura della tabella `sala_riunioni`
--

CREATE TABLE `sala_riunioni` (
  `nome_sala` varchar(30) NOT NULL,
  `nome_dipartimento` varchar(30) NOT NULL,
  `numero_posti` int(3) NOT NULL DEFAULT 20,
  `numero_tavoli` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `sala_riunioni`
--

INSERT INTO `sala_riunioni` (`nome_sala`, `nome_dipartimento`, `numero_posti`, `numero_tavoli`) VALUES
('Beve', 'Marketing', 20, 3),
('Magna', 'Marketing', 100, 12),
('Prima', 'Artistico', 40, 5),
('Progettazione', 'Informatico', 15, 3),
('Ripostiglio', 'Consegne', 10, NULL),
('Seconda', 'Artistico', 15, 2),
('Soqquadro', 'Informatico', 20, 6);

-- --------------------------------------------------------

--
-- Struttura della tabella `strumento`
--

CREATE TABLE `strumento` (
  `id_strumento` varchar(10) NOT NULL,
  `tipo` enum('Lavagna','Proiettore','Computer') NOT NULL,
  `quantità` int(2) NOT NULL,
  `nome_sala` varchar(30) NOT NULL,
  `nome_dipartimento` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `strumento`
--

INSERT INTO `strumento` (`id_strumento`, `tipo`, `quantità`, `nome_sala`, `nome_dipartimento`) VALUES
('1', 'Computer', 10, 'Progettazione', 'Informatico'),
('10', 'Lavagna', 1, 'Seconda', 'Artistico'),
('11', 'Computer', 7, 'Soqquadro', 'Informatico'),
('12', 'Proiettore', 1, 'Soqquadro', 'Informatico'),
('13', 'Computer', 1, 'Ripostiglio', 'Consegne'),
('2', 'Lavagna', 1, 'Progettazione', 'Informatico'),
('3', 'Computer', 7, 'Beve', 'Marketing'),
('4', 'Proiettore', 1, 'Beve', 'Marketing'),
('5', 'Proiettore', 2, 'Magna', 'Marketing'),
('6', 'Lavagna', 2, 'Magna', 'Marketing'),
('7', 'Computer', 20, 'Prima', 'Artistico'),
('8', 'Lavagna', 2, 'Prima', 'Artistico'),
('9', 'Computer', 5, 'Seconda', 'Artistico');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `password` varchar(100) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `cognome` varchar(20) NOT NULL,
  `ruolo` text DEFAULT NULL,
  `tipo` varchar(10) NOT NULL,
  `data_nascita` date NOT NULL,
  `data_promozione` date DEFAULT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `anni_servizio` int(11) DEFAULT NULL,
  `dipartimento` varchar(30) NOT NULL,
  `mail` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`password`, `nome`, `cognome`, `ruolo`, `tipo`, `data_nascita`, `data_promozione`, `foto`, `anni_servizio`, `dipartimento`, `mail`) VALUES
('dfc4471748a5caf92797674197cb13fe', 'Andrea', 'Marzese', '', 'Direttore', '1990-09-12', '2018-08-12', '6.png', 7, 'Consegne', 'andmarz@mail.com'),
('b2fc813aad1c030679a646fa98649cd1', 'Gina', 'Cammina', 'Funzionario', 'Impiegato', '1999-05-12', '0000-00-00', '4.png', 0, 'Consegne', 'gincam@mail.com'),
('4ba923ddeee30327f2c6902fc8ef7fe6', 'Lorenzo', 'Ferri', 'Impiegato Semplice', 'Impiegato', '1986-03-12', '0000-00-00', '8.png', 0, 'Marketing', 'lorfer@mail.com'),
('e23769a388227987753fdfc6a4f9bf3e', 'Lucilla', 'Lassari', 'Impiegato Semplice', 'Impiegato', '1987-04-12', NULL, '2.png', NULL, 'Artistico', 'luclas@mail.com'),
('2047790750d56bdef8769534c3f29b06', 'Matteo', 'Castagna', '', 'Direttore', '1991-08-22', '2022-02-10', '', 2, 'Informatico', 'matcas@mail.com'),
('e0e9db95129e21f9263cb0aacbe02f78', 'Michele', 'Palazzo', 'Impiegato Semplice', 'Impiegato', '1990-04-12', NULL, '10.png', NULL, 'Informatico', 'micpal@mail.com'),
('4ed94a45cd40cf4da459e6411ea18ea4', 'Nina', 'Noni', 'Impiegato Semplice', 'Impiegato', '1992-05-18', NULL, '', NULL, 'Artistico', 'nino@mail.com'),
('ea964ef19f310133a33a45c6bb7fd265', 'Sergio', 'Ninotti', 'Impiegato Semplice', 'Impiegato', '1982-09-12', NULL, '5.png', NULL, 'Artistico', 'sernin@mail.com'),
('f890432bf9f910b5f42e3abe4d3ef83a', 'Stefano', 'Locati', '', 'Direttore', '1989-12-03', '2018-08-12', '', 4, 'Marketing', 'steloc@mail.com'),
('c47d40bcb64b7a9855206c67213ca339', 'Teresa', 'Todeschini', 'Funzionario', 'Impiegato', '1994-02-21', '0000-00-00', '9.png', 0, 'Consegne', 'tertod@mail.com'),
('92382798e0bc2f2626a2104aace458c8', 'Yelena', 'Prada', '', 'Direttore', '1996-07-12', '2022-02-03', '', 2, 'Artistico', 'yelpra@mail.com');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `autorizza`
--
ALTER TABLE `autorizza`
  ADD PRIMARY KEY (`impiegato`),
  ADD KEY `auto_1` (`direttore`);

--
-- Indici per le tabelle `dipartimento`
--
ALTER TABLE `dipartimento`
  ADD PRIMARY KEY (`nome_dipartimento`),
  ADD KEY `direttore` (`direttore`);

--
-- Indici per le tabelle `invitato_a`
--
ALTER TABLE `invitato_a`
  ADD PRIMARY KEY (`utente`,`riunione`),
  ADD KEY `dove` (`riunione`);

--
-- Indici per le tabelle `riunione`
--
ALTER TABLE `riunione`
  ADD PRIMARY KEY (`id_riunione`),
  ADD KEY `lugo_riunione` (`nome_dipartimento`,`nome_sala`),
  ADD KEY `creatore` (`creatore`);

--
-- Indici per le tabelle `sala_riunioni`
--
ALTER TABLE `sala_riunioni`
  ADD PRIMARY KEY (`nome_sala`),
  ADD KEY `dipartimento` (`nome_dipartimento`);

--
-- Indici per le tabelle `strumento`
--
ALTER TABLE `strumento`
  ADD PRIMARY KEY (`id_strumento`),
  ADD KEY `sala_riunioni` (`nome_dipartimento`,`nome_sala`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`mail`),
  ADD KEY `dove_lavora` (`dipartimento`);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `autorizza`
--
ALTER TABLE `autorizza`
  ADD CONSTRAINT `auto_1` FOREIGN KEY (`direttore`) REFERENCES `utente` (`mail`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auto_2` FOREIGN KEY (`impiegato`) REFERENCES `utente` (`mail`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `dipartimento`
--
ALTER TABLE `dipartimento`
  ADD CONSTRAINT `direttore` FOREIGN KEY (`direttore`) REFERENCES `utente` (`mail`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Limiti per la tabella `invitato_a`
--
ALTER TABLE `invitato_a`
  ADD CONSTRAINT `chi` FOREIGN KEY (`utente`) REFERENCES `utente` (`mail`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dove` FOREIGN KEY (`riunione`) REFERENCES `riunione` (`id_riunione`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `riunione`
--
ALTER TABLE `riunione`
  ADD CONSTRAINT `creatore` FOREIGN KEY (`creatore`) REFERENCES `utente` (`mail`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lugo_riunione` FOREIGN KEY (`nome_dipartimento`,`nome_sala`) REFERENCES `sala_riunioni` (`nome_dipartimento`, `nome_sala`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `sala_riunioni`
--
ALTER TABLE `sala_riunioni`
  ADD CONSTRAINT `dipartimento` FOREIGN KEY (`nome_dipartimento`) REFERENCES `dipartimento` (`nome_dipartimento`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Limiti per la tabella `strumento`
--
ALTER TABLE `strumento`
  ADD CONSTRAINT `sala_riunioni` FOREIGN KEY (`nome_dipartimento`,`nome_sala`) REFERENCES `sala_riunioni` (`nome_dipartimento`, `nome_sala`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `utente`
--
ALTER TABLE `utente`
  ADD CONSTRAINT `dove_lavora` FOREIGN KEY (`dipartimento`) REFERENCES `dipartimento` (`nome_dipartimento`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
