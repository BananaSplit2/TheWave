
/*Création de BDD --The Wave--*/


CREATE TABLE artiste (
	idA int PRIMARY KEY,
	nomA varchar(50) NOT NULL,
	prenom varchar(50) NOT NULL,
	nationA varchar(50),
	dateNais date NOT NULL,
	dateMort date,
	CONSTRAINT uniqueNomPreNai UNIQUE (nomA, prenom, dateNais),
	CONSTRAINT dateNaisMort CHECK (dateNais < dateMort)
);


CREATE TABLE groupe (
	idG int PRIMARY KEY,
	nomG varchar(50) NOT NULL,
	dateCrea date NOT NULL,
	nationG varchar(50),
	genre varchar(50) NOT NULL
);


CREATE TABLE membre (
	idMe int PRIMARY KEY,
	role varchar(50) NOT NULL,
	dateDeb date NOT NULL,
	dateFin date,
	idA int NOT NULL,
	idG int NOT NULL,
	CONSTRAINT dateDebFin CHECK (dateDeb < dateFin),
	FOREIGN KEY (idA) REFERENCES artiste(idA)
	ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (idG) REFERENCES groupe(idG)
	ON DELETE CASCADE ON UPDATE CASCADE
);



/*---------------------------------------------*/
