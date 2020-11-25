
/*Mise au propre BDD --TheWave--*/

DROP TABLE IF EXISTS artiste CASCADE;
DROP TABLE IF EXISTS groupe CASCADE;
DROP TABLE IF EXISTS membre CASCADE;
DROP TABLE IF EXISTS morceau CASCADE;
DROP TABLE IF EXISTS album CASCADE;
DROP TABLE IF EXISTS utilisateur CASCADE;
DROP TABLE IF EXISTS playlist CASCADE;

DROP TABLE IF EXISTS participe CASCADE;
DROP TABLE IF EXISTS albumContient CASCADE;
DROP TABLE IF EXISTS playlistContient CASCADE;
DROP TABLE IF EXISTS suitGroupe CASCADE;
DROP TABLE IF EXISTS suitUtilisateur CASCADE;
DROP TABLE IF EXISTS historique CASCADE;

/*Création de BDD --The Wave--*/

CREATE TABLE artiste (
	idA serial PRIMARY KEY,
	nomA varchar(50) NOT NULL,
	prenom varchar(50) NOT NULL,
	nationA varchar(50),
	dateNais date NOT NULL,
	dateMort date,
	CONSTRAINT uniqueNomPreNai UNIQUE (nomA, prenom, dateNais),
	CONSTRAINT dateNaisMort CHECK (dateNais < dateMort)
);

CREATE TABLE groupe (
	idG serial PRIMARY KEY,
	nomG varchar(50) NOT NULL,
	dateCrea date NOT NULL,
	nationG varchar(50),
	genre varchar(50) NOT NULL
);

CREATE TABLE membre (
	idMe serial PRIMARY KEY,
	roleM varchar(50) NOT NULL,
	dateDeb date NOT NULL,
	dateFin date,
	idA int NOT NULL REFERENCES artiste ON DELETE CASCADE ON UPDATE CASCADE,
	idG int NOT NULL REFERENCES groupe ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT dateDebFin CHECK (dateDeb < dateFin)
);

CREATE TABLE morceau (
	idMo serial PRIMARY KEY,
	titreM varchar(50) NOT NULL,
	duree interval NOT NULL,
	paroles text,
	audio varchar(200),
	idG int NOT NULL REFERENCES groupe ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE album (
	idAl serial PRIMARY KEY,
	titreA varchar(50) NOT NULL,
	dateParu date NOT NULL,
	couv varchar(200),
	descA text,
	idG int NOT NULL REFERENCES groupe ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE utilisateur (
	pseudo varchar(50) PRIMARY KEY,
	email varchar(50) UNIQUE NOT NULL,
	dateInsc date NOT NULL,
	mdp varchar(50) NOT NULL
);

CREATE TABLE playlist (
	idP serial PRIMARY KEY,
	titre varchar(50) NOT NULL,
	descP text,
	privee boolean NOT NULL,
	pseudo varchar(50) NOT NULL REFERENCES utilisateur ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE participe (
	idA int REFERENCES artiste ON DELETE CASCADE ON UPDATE CASCADE,
	idMo int REFERENCES morceau ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT participe_PK PRIMARY KEY (idA, idMo)
);

CREATE TABLE albumContient (
	idAl int REFERENCES album ON DELETE CASCADE ON UPDATE CASCADE,
	idMo int REFERENCES morceau ON DELETE CASCADE ON UPDATE CASCADE,
	num int,
	CONSTRAINT albumContient_PK PRIMARY KEY (idAl, idMo),
	CONSTRAINT numerotation_album UNIQUE (idAl, num)
);

CREATE TABLE playlistContient (
	idP int REFERENCES playlist ON DELETE CASCADE ON UPDATE CASCADE,
	idMo int REFERENCES morceau ON DELETE CASCADE ON UPDATE CASCADE,
	num int,
	CONSTRAINT playlistContient_PK PRIMARY KEY (idP, idMo),
	CONSTRAINT numerotation_playlist UNIQUE (idP, num)
);

CREATE TABLE suitGroupe (
	pseudo varchar(50) REFERENCES utilisateur ON DELETE CASCADE ON UPDATE CASCADE,
	idG int REFERENCES groupe ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT suitGroupe_PK PRIMARY KEY (pseudo, idG)
);

CREATE TABLE suitUtilisateur (
	suit varchar(50) REFERENCES utilisateur ON DELETE CASCADE ON UPDATE CASCADE,
	suivi varchar(50) REFERENCES utilisateur ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT suitUtilisateur_PK PRIMARY KEY (suit, suivi)
);

CREATE TABLE historique (
	pseudo varchar(50) REFERENCES utilisateur ON DELETE CASCADE ON UPDATE CASCADE,
	idMo int REFERENCES morceau ON DELETE CASCADE ON UPDATE CASCADE,
	dateHeure timestamp,
	CONSTRAINT historique_PK PRIMARY KEY (pseudo, idMo, dateHeure)
);

/*---------------Pour-meubler-----------------*/

/* utilisateurs */
INSERT INTO utilisateur VALUES ('admin_TheWave', 'admin.thewave@mailpro.fr', '2019-11-30', 'uZ3rFr13NdlY');
INSERT INTO utilisateur VALUES ('Galineras', 'gali.neras@mail.fr', '2020-11-25', 'topsecretmdp');
INSERT INTO utilisateur VALUES ('BananaSplit', 'banana.split@mail.fr', '2020-11-25', '42finalanswer');
INSERT INTO utilisateur VALUES ('JeanKevin69', 'jean.kevin@mail.fr', '2020-11-25', 'tupeuxpastest');
INSERT INTO utilisateur VALUES ('CpasGrave', 'david.goodenough@mail.fr', '2020-11-26', '0000');

/* groupes */
INSERT INTO groupe VALUES (1, 'The Beatles', '1960-08-15', 'britannique', 'pop rock');
INSERT INTO groupe VALUES (2, 'Santa Claws', '1982-12-25', 'americain', 'pop');

/* artistes */
INSERT INTO artiste VALUES (1, 'Lennon', 'John', 'britannique', '1940-10-09', '1980-12-08');
INSERT INTO artiste VALUES (2, 'McCartney', 'Paul', 'britannique', '1942-06-18');
INSERT INTO artiste VALUES (3, 'Harrison', 'George', 'britannique', '1943-02-25', '2001-11-29');
INSERT INTO artiste VALUES (4, 'Starkey', 'Richard', 'britannique', '1940-07-07');
INSERT INTO artiste VALUES (5, 'Claus', 'Santa', 'americain', '0001-01-01');
INSERT INTO artiste VALUES (6, 'Rudolph', 'Ren', NULL, '0001-01-01');
INSERT INTO artiste VALUES (7, 'Simon', 'Elf', 'elfeïmais', '0001-01-01');

/* membres */
INSERT INTO membre VALUES (1, 'guitariste', '1960-08-15', '1969-09-20', 1, 1);
INSERT INTO membre VALUES (2, 'chanteur', '1960-08-15', '1969-09-20', 1, 1);
INSERT INTO membre VALUES (3, 'bassiste', '1960-08-15', '1969-04-07', 2, 1);
INSERT INTO membre VALUES (4, 'guitariste', '1960-08-15', '1969-01-10', 3, 1);
INSERT INTO membre VALUES (5, 'batteur', '1962-01-01', '1968-08-22', 4, 1);
INSERT INTO membre VALUES (6, 'chanteur', '0001-01-01', '0001-12-25', 5, 2);
INSERT INTO membre VALUES (7, 'batteur', '0001-12-25', NULL, 5, 2);
INSERT INTO membre VALUES (8, 'claviériste', '0001-12-25', NULL, 6, 2);
INSERT INTO membre VALUES (9, 'chanteur', '0001-12-25', NULL, 7, 2);
INSERT INTO membre VALUES (10, 'danseur', '0001-12-25', NULL, 7, 2);

/* morceaux */
INSERT INTO morceau VALUES (1, 'We wish you a merry christmas', '00:05:31', 'www.wish-you.com/merry_christmas/lyrics', 'www.wish-you.com/merry_christmas/audio', 2);
INSERT INTO morceau VALUES (2, 'Ho ho ho', '00:02:54', 'www.wish-you.com/ho_ho_ho/lyrics', 'www.wish-you.com/ho_ho_ho/audio', 2);
INSERT INTO morceau VALUES (3, 'He is coming to town', '00:04:04', 'www.wish-you.com/coming_town/lyrics', 'www.wish-you.com/coming_town/audio', 2);
INSERT INTO morceau VALUES (4, 'Let it be', '00:03:46', 'https://www.youtube.com/watch?v=_wYyWA6ZdVU', 'https://www.youtube.com/watch?v=_wYyWA6ZdVU', 1);
INSERT INTO morceau VALUES (5, 'Hey Jude', '00:04:33', 'https://www.youtube.com/watch?v=7qMls5yxP1w', 'https://www.youtube.com/watch?v=7qMls5yxP1w', 1);
INSERT INTO morceau VALUES (6, 'Yellow submarine', '00:02:37', 'https://www.youtube.com/watch?v=j_JaDDcyIIU', 'https://www.youtube.com/watch?v=j_JaDDcyIIU', 1);
INSERT INTO morceau VALUES (7, 'Yesterday', '00:02:05', 'https://www.youtube.com/watch?v=jo505ZyaCbA', 'https://www.youtube.com/watch?v=jo505ZyaCbA', 1);
INSERT INTO morceau VALUES (8, 'Hello, goodbye', '00:03:07', 'https://www.youtube.com/watch?v=BD75RTV9P5w', 'https://www.youtube.com/watch?v=BD75RTV9P5w', 1);

/* participations */
INSERT INTO participe VALUES (5, 1);
INSERT INTO participe VALUES (6, 1);
INSERT INTO participe VALUES (7, 1);
INSERT INTO participe VALUES (5, 2);
INSERT INTO participe VALUES (5, 3);
INSERT INTO participe VALUES (6, 3);












