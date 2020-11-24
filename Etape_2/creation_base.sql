
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
	role varchar(50) NOT NULL,
	dateDeb date NOT NULL,
	dateFin date,
	idA int NOT NULL REFERENCES artiste ON DELETE CASCADE ON UPDATE CASCADE,
	idG int NOT NULL REFERENCES groupe ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT dateDebFin CHECK (dateDeb < dateFin)
);


CREATE TABLE morceau (
	idMo serial PRIMARY KEY,
	titreM varchar(50) NOT NULL,
	duree time NOT NULL,
	paroles text NOT NULL,
	audio text NOT NULL,
	idG int NOT NULL REFERENCES groupe ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE album (
	idAl serial PRIMARY KEY,
	titreA varchar(50) NOT NULL,
	dateParu date NOT NULL,
	couv text NOT NULL,
	descA text NOT NULL,
	idG int NOT NULL REFERENCES groupe ON DELETE CASCADE ON UPDATE CASCADE
);


/*---------------------------------------------*/
