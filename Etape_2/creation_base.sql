
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






/*---------------------------------------------*/
