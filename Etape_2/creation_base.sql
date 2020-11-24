
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

/*---------------------------------------------*/
