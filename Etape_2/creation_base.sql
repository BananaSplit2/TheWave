/*Création de BDD --The Wave--*/
CREATE TABLE artiste (
	idA serial PRIMARY KEY,
	nomA varchar(50) NOT NULL,
	prenom varchar(50) NOT NULL,
	nationA varchar(50),
	dateNais date NOT NULL,
	dateMort date CHECK (dateNais < dateMort),
	CONSTRAINT uniqueNomPreNai UNIQUE (nomA, prenom, dateNais)
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
	idA int REFERENCES artiste ON UPDATE CASCADE,
	idG int REFERENCES groupe,
	CONSTRAINT dateDebFin CHECK (dateDeb < dateFin)
);

CREATE TABLE morceau (
	idMo serial PRIMARY KEY,
	titreM varchar(50) NOT NULL,
	duree time NOT NULL,
	paroles text NOT NULL,
	audio text UNIQUE NOT NULL,
	idG int NOT NULL,
	FOREIGN KEY (idG) REFERENCES groupe(idG)
	ON DELETE CASCADE ON UPDATE CASCADE
);
/*---------------------------------------------*/
