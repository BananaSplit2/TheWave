
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
	dateNais date,
	dateMort date,
	CONSTRAINT uniqueNomPre UNIQUE (nomA, prenom),
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
INSERT INTO groupe VALUES (3, 'Nightwish', '1996-12-01', 'finlandais', 'heavy metal');
INSERT INTO groupe VALUES (4, 'Within Temptation', '1996-01-01', 'néerlandais', 'metal symphonique');

/* artistes */
INSERT INTO artiste VALUES (1, 'Lennon', 'John', 'britannique', '1940-10-09', '1980-12-08');
INSERT INTO artiste VALUES (2, 'McCartney', 'Paul', 'britannique', '1942-06-18');
INSERT INTO artiste VALUES (3, 'Harrison', 'George', 'britannique', '1943-02-25', '2001-11-29');
INSERT INTO artiste VALUES (4, 'Starkey', 'Richard', 'britannique', '1940-07-07');
INSERT INTO artiste VALUES (5, 'Claus', 'Santa', 'americain', '0001-01-01');
INSERT INTO artiste VALUES (6, 'Rudolph', 'Ren', NULL, '0001-01-01');
INSERT INTO artiste VALUES (7, 'Simon', 'Elf', 'elfeïmais', '0001-01-01');

INSERT INTO artiste VALUES (8, 'Holopainen', 'Tuomas', 'finlandais', '1976-12-25');
INSERT INTO artiste VALUES (9, 'Vuorinen', 'Emppu', 'finlandais', '1978-06-24');
INSERT INTO artiste VALUES (10, 'Hietala', 'Marco', 'finlandais', '1966-01-14');
INSERT INTO artiste VALUES (11, 'Donockley', 'Troy', 'anglais', '1964-05-30');
INSERT INTO artiste VALUES (12, 'Jansen', 'Floor', 'néérlandaise', '1981-02-21');
INSERT INTO artiste VALUES (13, 'Hahto', 'Kai', 'finlandais', '1973-12-31');
INSERT INTO artiste VALUES (14, 'Turunen', 'Tarja', 'finlandaise', '1977-08-17');
INSERT INTO artiste VALUES (15, 'Nevalainen', 'Jukka', 'finlandais', '1978-04-21');
INSERT INTO artiste VALUES (16, 'Vänskä', 'Sami', 'finlandais', '1976-09-26');
INSERT INTO artiste VALUES (17, 'Olzon', 'Anette', 'suédoise', '1971-06-21');

INSERT INTO artiste VALUES (18, 'Den Adel', 'Sharon', 'néerlandaise', '1974-07-12');
INSERT INTO artiste VALUES (19, 'Westerholt', 'Robert', 'néerlandais', '1975-01-02');
INSERT INTO artiste VALUES (20, 'Van Veen', 'Jeroen', 'néerlandais', '1974-10-26');
INSERT INTO artiste VALUES (21, 'Jolie', 'Ruud', 'néerlandais', '1976-04-19');
INSERT INTO artiste VALUES (22, 'Spierenburg', 'Martijn', 'néerlandais', '1975-01-30');
INSERT INTO artiste VALUES (23, 'Coolen', 'Mike', NULL, NULL);
INSERT INTO artiste VALUES (24, 'Helleblad', 'Stefan', NULL, NULL;
INSERT INTO artiste VALUES (25, 'Papenhove', 'Michiel', NULL, NULL);
INSERT INTO artiste VALUES (26, 'Westerholt', 'Martijn', 'néerlandais', '1979-03-30');
INSERT INTO artiste VALUES (27, 'Leeflang', 'Dennis', 'néerlandais', '1979-05-22');
INSERT INTO artiste VALUES (28, 'Willemse', 'Richard', NULL, NULL);
INSERT INTO artiste VALUES (29, 'De Graaf', 'Ivar', 'néerlandais', '1973-08-20');
INSERT INTO artiste VALUES (30, 'Von Pyreen', 'Marius', NULL, NULL);
INSERT INTO artiste VALUES (31, 'Palma', 'Ciro', NULL, NULL);
INSERT INTO artiste VALUES (32, 'Bakker', 'Jelle', NULL, NULL);
INSERT INTO artiste VALUES (33, 'Van Haestregt', 'Stephen', 'néerlandais', '1972-09-12');
INSERT INTO artiste VALUES (34, 'Hellenberg', 'Nicka', NULL, NULL);

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

INSERT INTO membre VALUES (11, 'claviériste', '1996-12-01', NULL, 8, 3);
INSERT INTO membre VALUES (12, 'guitariste', '1996-12-01', NULL, 9, 3);
INSERT INTO membre VALUES (13, 'bassiste', '2001-11-01', NULL, 10, 3);
INSERT INTO membre VALUES (14, 'chanteur', '2001-11-01', NULL, 10, 3);
INSERT INTO membre VALUES (15, 'guitariste', '2007-01-01', NULL, 10, 3);
INSERT INTO membre VALUES (16, 'guitariste', '2013-06-01', NULL, 11, 3);
INSERT INTO membre VALUES (17, 'chanteur', '2013-06-01', NULL, 11, 3);
INSERT INTO membre VALUES (18, 'flûtiste', '2013-06-01', NULL, 11, 3);
INSERT INTO membre VALUES (19, 'chanteuse', '2013-10-09', NULL, 12, 3);
INSERT INTO membre VALUES (20, 'batteur', '2019-07-01', NULL, 13, 3);
INSERT INTO membre VALUES (21, 'chanteuse', '1997-12-01', '2005-10-22', 14, 3);
INSERT INTO membre VALUES (22, 'batteur', '1997-01-01', '2019-07-01', 15, 3);
INSERT INTO membre VALUES (23, 'bassiste', '1998-01-01', '2001-11-01', 16, 3);
INSERT INTO membre VALUES (24, 'chanteuse', '2007-01-01', '2012-10-01', 17, 3);

INSERT INTO membre VALUES (25, 'chanteuse', '1996-01-01', NULL, 18, 4);
INSERT INTO membre VALUES (26, 'guitariste', '1996-01-01', NULL, 19, 4);
INSERT INTO membre VALUES (27, 'chanteur', '1996-01-01', NULL, 19, 4);
INSERT INTO membre VALUES (28, 'bassiste', '1996-01-01', NULL, 20, 4);
INSERT INTO membre VALUES (29, 'guitariste', '2001-06-01', NULL, 21, 4);
INSERT INTO membre VALUES (29, 'claviériste', '2001-06-01', NULL, 22, 4);
INSERT INTO membre VALUES (30, 'batteur', '2011-02-01', NULL, 23, 4);
INSERT INTO membre VALUES (31, 'guitariste', '2011-06-01', NULL, 24, 4);
INSERT INTO membre VALUES (32, 'guitariste', '1996-01-01', '2001-05-01', 25, 4);
INSERT INTO membre VALUES (33, 'claviériste', '1996-01-01', '2001-05-01', 26, 4);
INSERT INTO membre VALUES (34, 'batteur', '1996-01-01', '1996-06-01', 27, 4);
INSERT INTO membre VALUES (35, 'batteur', '1996-06-01', '1996-10-01', 28, 4);
INSERT INTO membre VALUES (36, 'batteur', '1996-10-01', '1998-03-01', 29, 4);
INSERT INTO membre VALUES (37, 'batteur', '1999-02-01', '2001-06-01', 29, 4);
INSERT INTO membre VALUES (38, 'batteur', '1998-03-01', '1998-06-01', 30, 4);
INSERT INTO membre VALUES (39, 'batteur', '1998-06-01', '1999-02-01', 31, 4);
INSERT INTO membre VALUES (40, 'batteur', '1998-06-01', '1999-02-01', 32, 4);
INSERT INTO membre VALUES (41, 'batteur', '2001-06-01', '2010-01-01', 33, 4);
INSERT INTO membre VALUES (42, 'batteur', '2010-01-01', '2011-02-01', 34, 4);

/* morceaux */
INSERT INTO morceau VALUES (1, 'We wish you a merry christmas', '00:05:31', 'www.wish-you.com/merry_christmas/lyrics', 'www.wish-you.com/merry_christmas/audio', 2);
INSERT INTO morceau VALUES (2, 'Ho ho ho', '00:02:54', 'www.wish-you.com/ho_ho_ho/lyrics', 'www.wish-you.com/ho_ho_ho/audio', 2);
INSERT INTO morceau VALUES (3, 'He is coming to town', '00:04:04', 'www.wish-you.com/coming_town/lyrics', 'www.wish-you.com/coming_town/audio', 2);
INSERT INTO morceau VALUES (4, 'Let it be', '00:03:46', NULL, 'https://www.youtube.com/watch?v=_wYyWA6ZdVU', 1);
INSERT INTO morceau VALUES (5, 'Hey Jude', '00:04:33', NULL, 'https://www.youtube.com/watch?v=7qMls5yxP1w', 1);
INSERT INTO morceau VALUES (6, 'Yellow submarine', '00:02:37', NULL, NULL, 1);
INSERT INTO morceau VALUES (7, 'Yesterday', '00:02:05', NULL, 'https://www.youtube.com/watch?v=jo505ZyaCbA', 1);
INSERT INTO morceau VALUES (8, 'Hello, goodbye', '00:03:07', NULL, 'https://www.youtube.com/watch?v=BD75RTV9P5w', 1);

INSERT INTO morceau VALUES (9, 'The Poet and the Pendulum', '00:13:53', NULL, NULL, 3);
INSERT INTO morceau VALUES (10, 'Bye Bye Beautiful', '00:04:15', NULL, NULL, 3);
INSERT INTO morceau VALUES (11, 'Amaranth', '00:03:51', NULL, NULL, 3);
INSERT INTO morceau VALUES (12, 'Cadence of Her Last Breath', '00:04:15', NULL, NULL, 3);
INSERT INTO morceau VALUES (13, 'Master Passion Greed', '00:05:58', NULL, NULL, 3);
INSERT INTO morceau VALUES (14, 'Eva', '00:04:26', NULL, NULL, 3);
INSERT INTO morceau VALUES (15, 'Sahara', '00:05:46', NULL, NULL, 3);
INSERT INTO morceau VALUES (16, 'Whoever Brings the Night', '00:04:16', NULL, NULL, 3);
INSERT INTO morceau VALUES (17, 'For the Heart I Once Had', '00:03:56', NULL, NULL, 3);
INSERT INTO morceau VALUES (18, 'The Islander', '00:05:06', NULL, NULL, 3);
INSERT INTO morceau VALUES (19, 'Last of the Wilds', '00:05:41', '', NULL, 3);
INSERT INTO morceau VALUES (20, '7 Days to the Wolves', '00:07:03', NULL, NULL, 3);
INSERT INTO morceau VALUES (21, 'Meadows of Heaven', '00:07:10', NULL, NULL, 3);
INSERT INTO morceau VALUES (22, 'Escapist', '00:04:57', NULL, NULL, 3);

/* albums */
INSERT INTO album VALUES (1, 'Dark Passion Play', '2007-09-26', NULL, 'Dark Passion Play est le sixième album du groupe Nightwish, sorti le 28 septembre 2007 en Europe, excepté en France où il est paru le premier octobre. C''est le premier album avec la chanteuse Anette Olzon.', 3);

/* albumContient */

INSERT INTO albumContient VALUES (1, 9, 1);
INSERT INTO albumContient VALUES (1, 10, 2);
INSERT INTO albumContient VALUES (1, 11, 3);
INSERT INTO albumContient VALUES (1, 12, 4);
INSERT INTO albumContient VALUES (1, 13, 5);
INSERT INTO albumContient VALUES (1, 14, 6);
INSERT INTO albumContient VALUES (1, 15, 7);
INSERT INTO albumContient VALUES (1, 16, 8);
INSERT INTO albumContient VALUES (1, 17, 9);
INSERT INTO albumContient VALUES (1, 18, 10);
INSERT INTO albumContient VALUES (1, 19, 11);
INSERT INTO albumContient VALUES (1, 20, 12);
INSERT INTO albumContient VALUES (1, 21, 13);
INSERT INTO albumContient VALUES (1, 22, 14);

/* participations */
INSERT INTO participe VALUES (5, 1);
INSERT INTO participe VALUES (6, 1);
INSERT INTO participe VALUES (7, 1);
INSERT INTO participe VALUES (5, 2);
INSERT INTO participe VALUES (5, 3);
INSERT INTO participe VALUES (6, 3);

INSERT INTO participe VALUES (17, 9);
INSERT INTO participe VALUES (8, 9);
INSERT INTO participe VALUES (10, 9);
INSERT INTO participe VALUES (9, 9);
INSERT INTO participe VALUES (15, 9);

INSERT INTO participe VALUES (17, 10);
INSERT INTO participe VALUES (8, 10);
INSERT INTO participe VALUES (10, 10);
INSERT INTO participe VALUES (9, 10);
INSERT INTO participe VALUES (15, 10);

INSERT INTO participe VALUES (17, 11);
INSERT INTO participe VALUES (8, 11);
INSERT INTO participe VALUES (10, 11);
INSERT INTO participe VALUES (9, 11);
INSERT INTO participe VALUES (15, 11);

INSERT INTO participe VALUES (17, 12);
INSERT INTO participe VALUES (8, 12);
INSERT INTO participe VALUES (10, 12);
INSERT INTO participe VALUES (9, 12);
INSERT INTO participe VALUES (15, 12);

INSERT INTO participe VALUES (8, 13);
INSERT INTO participe VALUES (10, 13);
INSERT INTO participe VALUES (9, 13);
INSERT INTO participe VALUES (15, 13);

INSERT INTO participe VALUES (17, 14);
INSERT INTO participe VALUES (8, 14);
INSERT INTO participe VALUES (10, 14);
INSERT INTO participe VALUES (9, 14);
INSERT INTO participe VALUES (15, 14);

INSERT INTO participe VALUES (17, 15);
INSERT INTO participe VALUES (8, 15);
INSERT INTO participe VALUES (10, 15);
INSERT INTO participe VALUES (9, 15);
INSERT INTO participe VALUES (15, 15);

INSERT INTO participe VALUES (17, 16);
INSERT INTO participe VALUES (8, 16);
INSERT INTO participe VALUES (10, 16);
INSERT INTO participe VALUES (9, 16);
INSERT INTO participe VALUES (15, 16);

INSERT INTO participe VALUES (17, 17);
INSERT INTO participe VALUES (8, 17);
INSERT INTO participe VALUES (10, 17);
INSERT INTO participe VALUES (9, 17);
INSERT INTO participe VALUES (15, 17);

INSERT INTO participe VALUES (17, 18);
INSERT INTO participe VALUES (8, 18);
INSERT INTO participe VALUES (10, 18);
INSERT INTO participe VALUES (9, 18);
INSERT INTO participe VALUES (15, 18);
INSERT INTO participe VALUES (11, 18);

INSERT INTO participe VALUES (8, 19);
INSERT INTO participe VALUES (10, 19);
INSERT INTO participe VALUES (9, 19);
INSERT INTO participe VALUES (15, 19);
INSERT INTO participe VALUES (11, 19);

INSERT INTO participe VALUES (17, 20);
INSERT INTO participe VALUES (8, 20);
INSERT INTO participe VALUES (10, 20);
INSERT INTO participe VALUES (9, 20);
INSERT INTO participe VALUES (15, 20);

INSERT INTO participe VALUES (17, 21);
INSERT INTO participe VALUES (8, 21);
INSERT INTO participe VALUES (10, 21);
INSERT INTO participe VALUES (9, 21);
INSERT INTO participe VALUES (15, 21);
INSERT INTO participe VALUES (11, 21);

INSERT INTO participe VALUES (17, 22);
INSERT INTO participe VALUES (8, 22);
INSERT INTO participe VALUES (10, 22);
INSERT INTO participe VALUES (9, 22);
INSERT INTO participe VALUES (15, 22);