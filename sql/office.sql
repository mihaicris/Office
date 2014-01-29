-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Gazda: localhost
-- Timp de generare: 28 Ian 2014 la 23:04
-- Versiune server: 5.6.12-log
-- Versiune PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Bază de date: `office`
--
CREATE DATABASE IF NOT EXISTS `office`
  DEFAULT CHARACTER SET utf8
  COLLATE utf8_general_ci;
USE `office`;

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `companii`
--

CREATE TABLE IF NOT EXISTS `companii` (
  `id_companie`     SMALLINT(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `nume_companie`   VARCHAR(100)                  NOT NULL,
  `adresa_companie` VARCHAR(150)                  NOT NULL,
  `oras_companie`   VARCHAR(30)                   NOT NULL,
  `tara_companie`   VARCHAR(50)                   NOT NULL,
  PRIMARY KEY (`id_companie`),
  UNIQUE KEY `nume_client` (`nume_companie`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  AUTO_INCREMENT =51;

--
-- Salvarea datelor din tabel `companii`
--

INSERT INTO `companii` (`id_companie`, `nume_companie`, `adresa_companie`, `oras_companie`, `tara_companie`) VALUES
(00001, 'Siemens S.R.L.', 'Bulevardul Preciziei Nr. 24, West Gate Park, Clădirea H4', 'București', 'România'),
(00002, 'Transelectrica S.A.', 'Strada Olteni Nr. 2-4, sector 3', 'București', 'România'),
(00004, 'Tempos Serv S.R.L.', 'Șoseaua Colentina Nr. 85', 'București', 'România'),
(00005, 'Automatica S.A.', 'Șoseaua Afumați Nr. 108', 'Voluntari', 'România'),
(00006, 'Electrotel S.A.', 'Strada Dunării Nr. 279', 'Alexandria', 'România'),
(00007, 'Electroutilaj S.A.', 'Strada Bobâlna Nr. 44', 'Câmpina', 'România'),
(00008, 'EnergoBIT S.R.L.', 'Strada Tăietura Turcului Nr. 47', 'Cluj-Napoca', 'România'),
(00009, 'Romelectro S.A.', 'Bulevardul Lacul Tei Nr. 1-3', 'București', 'România'),
(00010, 'Electrogrup S.A.', 'Strada Calea Turzii Nr. 217', 'Cluj-Napoca', 'România'),
(00011, 'Electro-Alfa Internațional S.R.L.', 'Strada Manolești Deal Nr. 33', 'Botoșani', 'România'),
(00012, 'Energotech S.A.', 'Bd. Timişoara Nr. 104E', 'București', 'România'),
(00013, 'Albaco Exim S.R.L.', 'Strada Biruinței Nr. 46A', 'Alba Iulia', 'România'),
(00014, 'Alex Rom S.R.L.', 'Strada Aurel Vlaicu Nr. 3A', 'Mediaș', 'România'),
(00015, 'Almet S.R.L.', 'Strada Industriei Nr. 10', 'Bistrița Năsăud', 'România'),
(00016, 'Alpha Internațional S.R.L.', 'Bulevardul 9 Mai Nr. 6', 'Piatra Neamț', 'România'),
(00017, 'Alro S.R.L.', 'Strada Pitești Nr. 116', 'Slatina', 'România'),
(00018, 'Amurco S.R.L.', 'Strada Chimiei Nr. 1', 'Bacău', 'România'),
(00019, 'Apex Electric VD S.R.L.', 'Strada Izvorul Rece Nr. 3, Bl. 14, Ap. 3', 'Brașov', 'România'),
(00020, 'BIT Invest S.R.L.', 'Bulevardul Libertății Nr. 3-5, Et. 2', 'Târgoviște', 'România'),
(00021, 'Electrotehno S.R.L.', 'Bulevardul Republicii Nr. 166', 'Bacău', 'România'),
(00022, 'Electroconstrucția ELECON S.A. Brașov', 'Strada Zizinului Nr. 100A', 'Brașov', 'România'),
(00024, 'Emon Electric S.A.', 'Str. Balcescu Nicolae, Nr. 1A', 'Câmpina', 'România'),
(00043, 'Electroutil Alister S.R.L.', 'Str. Costeni, Nr. 311, RO-335100, Jud. Hunedoara', 'Aninoasa', 'România'),
(00044, 'Ritz Instrument Transformers GmbH', 'Wandsbeker Zollstrasse 92-98', 'Hamburg', 'Germania'),
(00045, 'General Management S.R.L.', 'Fratii Grachi 5, RO-550282', 'Sibiu', 'România'),
(00046, 'Elektrotechnische Werke Fritz Driescher & Söhne GmbH', 'Driescherstraße 3, DE-85368', 'Moosburg', 'Germania'),
(00047, 'Eximprod Grup S.R.L.', 'Str. Obor, Bloc 1C, Etaj 1', 'Buzău', 'România'),
(00048, 'ETI România S.R.L.', 'Str. Doina, Nr. 17A, Sector 5', 'București', 'România'),
(00049, 'Electroechipament Industrial S.R.L.', 'Str. Tmișoarei, Nr. 2A', 'Reșița', 'România'),
(00050, 'Bacme S.A.', 'Str. Gherghiței, Nr. 23 C, Sector 2', 'București', 'România');

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `oferte`
--

CREATE TABLE IF NOT EXISTS `oferte` (
  `id_oferta`     SMALLINT(6)              NOT NULL AUTO_INCREMENT,
  `id_client`     SMALLINT(6)              NOT NULL,
  `id_vanzator`   SMALLINT(6)              NOT NULL,
  `data_creare`   DATE                     NOT NULL,
  `data_expirare` DATE                     NOT NULL,
  `stadiu`        VARCHAR(10)
                  COLLATE utf8_romanian_ci NOT NULL,
  PRIMARY KEY (`id_oferta`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_romanian_ci
  AUTO_INCREMENT =3;

--
-- Salvarea datelor din tabel `oferte`
--

INSERT INTO `oferte` (`id_oferta`, `id_client`, `id_vanzator`, `data_creare`, `data_expirare`, `stadiu`) VALUES
(1, 5, 1, '2013-11-19', '2013-12-19', 'open'),
(2, 6, 2, '2013-11-18', '2013-12-18', 'open');

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `persoane`
--

CREATE TABLE IF NOT EXISTS `persoane` (
  `id_persoana`          SMALLINT(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `nume_persoana`        VARCHAR(50)
                         COLLATE utf8_romanian_ci      NOT NULL,
  `prenume_persoana`     VARCHAR(50)
                         COLLATE utf8_romanian_ci      NOT NULL,
  `gen_persoana`         TINYINT(1)                    NOT NULL,
  `functie_persoana`     VARCHAR(50)
                         COLLATE utf8_romanian_ci      NOT NULL,
  `departament_persoana` VARCHAR(50)
                         COLLATE utf8_romanian_ci      NOT NULL,
  `id_companie_persoana` SMALLINT(5) UNSIGNED ZEROFILL NOT NULL,
  `tel_persoana`         VARCHAR(50)
                         COLLATE utf8_romanian_ci      NOT NULL,
  `fax_persoana`         VARCHAR(50)
                         COLLATE utf8_romanian_ci      NOT NULL,
  `mobil_persoana`       VARCHAR(50)
                         COLLATE utf8_romanian_ci      NOT NULL,
  `email_persoana`       VARCHAR(100)
                         COLLATE utf8_romanian_ci      NOT NULL,
  PRIMARY KEY (`id_persoana`),
  KEY `id_companie_persoana` (`id_companie_persoana`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_romanian_ci
  AUTO_INCREMENT =15;

--
-- Salvarea datelor din tabel `persoane`
--

INSERT INTO `persoane` (`id_persoana`, `nume_persoana`, `prenume_persoana`, `gen_persoana`, `functie_persoana`, `departament_persoana`, `id_companie_persoana`, `tel_persoana`, `fax_persoana`, `mobil_persoana`, `email_persoana`)
  VALUES
  (00001, 'Pană', 'Dochin', 0, 'Director Tehnic', 'Management', 00043, '+40 (254) 513.325', '+40 (254) 513.464',
   '+40 (769) 252.265', 'electroutil@yahoo.com'),
  (00002, 'Cristescu', 'Mihai', 0, 'Suport Vânzări', 'Vânzări', 00001, '+40 (21) 6296 484', '+40 (21) 6296 480', '+40 (724) 38 38 28', 'mihai.cristescu@siemens.com'),
  (00003, 'Pigulea', 'Petre', 0, 'Inginer Proiectant Medie Tensiune', 'Birou Proiectare', 00005, '+40 (372) 058 100  Int. 207', '+40 (372) 058 101', '+40 (722) 501 617 ', 'petre.pigulea@automatica.ro'),
  (00004, 'Mirea', 'Ioana', 1, 'Şef Birou Aprovizionare', 'Birou Achiziţii', 00005, '+40 (372) 058 100*107', '+40 (372) 058 101', '+40 (729) 035 164', 'ioana.mirea@automatica.ro'),
  (00005, 'Ionescu', 'Octavian', 0, 'Responsabil Medie Tensiune', 'Departament Tehnic', 00006, '+40 (247) 306 200', '+40 (247) 306 213', '+40 (744) 538 062', 'office@electrotel.ro'),
  (00006, 'Stoinea', 'Dumitru', 0, 'Responsabil Ofertă', 'Departament Ofertare', 00006, '+40 (21) 231 89 90', '+40 (247) 306 213', '+40 (723) 361157', 'office@electrotel.ro'),
  (00007, 'Terec', 'Adrian', 0, 'Director Executiv', 'Management', 00008, '+40 (264) 207 544', '+40 (264) 207 555', '+40 (723) 658 773', 'adrian.terec@energobit.com'),
  (00008, 'Putineanu', 'Sorin', 0, 'Inginer Ofertare', 'Medie Tensiune', 00011, '+40 (21) 311 83 36', '+40 (21) 315 41 77 ', '+40 (730) 066 955', 'sorin.putineanu@electroalfa.ro'),
  (00009, 'Chirileasa', 'Costel', 0, 'Inginer Proiectant Electric', 'Directia Comerciala', 00011, '+40 (231) 53 21 86', '+40 (231) 53 21 85 ', '+40 (231) 31 99 73 ', 'costel.chirileasa@electroalfa.ro'),
  (00010, 'Costin', 'Ana Maria', 1, 'Director Executiv', 'Management', 00010, '+40 (264) 415 120', '+40 (264) 415 121', '+40 (721) 367 234', 'anamaria.costin@electrogrup.ro'),
  (00011, 'Stoica', 'Neculai', 0, 'Ing. Electric APE', 'Birou Proiectare', 00021, '+40 (234) 574 479', '+40 (234) 573 548', '+40 (234) 574 479', 'stoica.n@electrotehno.ro'),
  (00012, 'Chivu', 'Niculina', 1, 'Responsabil achiziții', 'Departament Achiziții', 00024, '+40 (244) 306 076',
   '+40 (244) 375 220', '+40 (729) 990 856', 'niculina.chivu@emon.ro'),
  (00014, 'Cristescu', 'Mihai', 0, 'Director', 'Home', 00001, '021 6296 484', '021 6296 690', '0724383828',
   'mihai.cristescu@gmail.com');

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `pozitii_oferte`
--

CREATE TABLE IF NOT EXISTS `pozitii_oferte` (
  `id_pozitie` TINYINT(4)               NOT NULL AUTO_INCREMENT,
  `id_oferta`  TINYINT(4)               NOT NULL,
  `id_produs`  TINYINT(4)               NOT NULL,
  `pozitie`    VARCHAR(10)
               COLLATE utf8_romanian_ci NOT NULL,
  `pret`       FLOAT                    NOT NULL,
  `cantitate`  INT(11)                  NOT NULL,
  PRIMARY KEY (`id_pozitie`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_romanian_ci
  AUTO_INCREMENT =3;

--
-- Salvarea datelor din tabel `pozitii_oferte`
--

INSERT INTO `pozitii_oferte` (`id_pozitie`, `id_oferta`, `id_produs`, `pozitie`, `pret`, `cantitate`) VALUES
(1, 1, 1, '10', 100, 2),
(2, 1, 2, '20', 150, 3);

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `produse`
--

CREATE TABLE IF NOT EXISTS `produse` (
  `id_produs`        SMALLINT(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `id_companie`      SMALLINT(5) UNSIGNED ZEROFILL NOT NULL,
  `cod_produs`       VARCHAR(50)
                     COLLATE utf8_romanian_ci      NOT NULL,
  `titlu_produs`     VARCHAR(50)
                     COLLATE utf8_romanian_ci      NOT NULL,
  `descriere_produs` TEXT
                     COLLATE utf8_romanian_ci      NOT NULL,
  `unitate_produs`   VARCHAR(10)
                     COLLATE utf8_romanian_ci      NOT NULL,
  `pret_produs`      FLOAT(10, 2) DEFAULT NULL,
  PRIMARY KEY (`id_produs`),
  UNIQUE KEY `cod_produs` (`cod_produs`),
  KEY `companie` (`id_companie`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_romanian_ci
  DELAY_KEY_WRITE =1
  AUTO_INCREMENT =6;

--
-- Salvarea datelor din tabel `produse`
--

INSERT INTO `produse` (`id_produs`, `id_companie`, `cod_produs`, `titlu_produs`, `descriere_produs`, `unitate_produs`, `pret_produs`)
  VALUES
  (00001, 00001, '3AX1325', 'COUNTER DESCARCATOR', 'SURGE COUNTER\r\nWITH MA-METER', 'buc', 110.00),
  (00002, 00002, 'AUT:34434', 'CELULE MEDIE TENSIUNE',
   'CELULE DE MEDIE TENSIUNE\r\n24 kV, 630A, 16 kA\r\nCONFORM ANEXE TEHNICE', 'ans', 65235.65),
  (00005, 00001, '3AE1184-2BF55-0FR2-Z, F20 F28 F30', 'INTRERUPATOR IN VID TIP SION',
   '"VACUUM CIRCUIT-BREAKER SION\r\n12KV 25KA 1250A\r\nPOLE-CENTER DISTANCE 210MM\r\nWIDTH ACROSS FLATS 310MM\r\nSECONDARY EQUIPMENT :\r\nOPERATING CYCLE COUNTER\r\n2 POSITION SWITCHES FOR SIGNAL\r\nCLOSING SPRING CHARGED',
   'buc', 5000.00);

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `vanzatori`
--

CREATE TABLE IF NOT EXISTS `vanzatori` (
  `id_vanzator`      TINYINT(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  `nume_vanzator`    VARCHAR(50)
                     COLLATE utf8_romanian_ci     NOT NULL,
  `prenume_vanzator` VARCHAR(50)
                     COLLATE utf8_romanian_ci     NOT NULL,
  PRIMARY KEY (`id_vanzator`)
)
  ENGINE =InnoDB
  DEFAULT CHARSET =utf8
  COLLATE =utf8_romanian_ci
  AUTO_INCREMENT =21;

--
-- Salvarea datelor din tabel `vanzatori`
--

INSERT INTO `vanzatori` (`id_vanzator`, `nume_vanzator`, `prenume_vanzator`) VALUES
(00009, 'Cristescu', 'Mihai'),
(00011, 'Medinceanu', 'Costinel'),
(00012, 'Manta', 'Elena'),
(00013, 'Luca', 'Adrian'),
(00014, 'Stoian', 'Oana'),
(00015, 'Cristea', 'Vasile'),
(00016, 'Ionescu', 'Mihai'),
(00019, 'Cristescu', 'Andrei'),
(00020, 'Coman', 'Gabriel');

--
-- Restrictii pentru tabele sterse
--

--
-- Restrictii pentru tabele `persoane`
--
ALTER TABLE `persoane`
ADD CONSTRAINT `persoane_ibfk_1` FOREIGN KEY (`id_companie_persoana`) REFERENCES `companii` (`id_companie`);

--
-- Restrictii pentru tabele `produse`
--
ALTER TABLE `produse`
ADD CONSTRAINT `produse_ibfk_1` FOREIGN KEY (`id_companie`) REFERENCES `companii` (`id_companie`);

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
