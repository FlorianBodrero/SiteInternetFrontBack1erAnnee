

CREATE DATABASE IF NOT EXISTS site CHARACTER SET utf8;

USE site;

CREATE TABLE IF NOT EXISTS commande (
  id_commande INT(3) NOT NULL AUTO_INCREMENT,
  id_membre INT(3) NOT NULL,
  montant int(3) NOT NULL,
  date_enregistrement DATETIME NOT NULL,
  etat ENUM('en cours de traitement', 'enovyée', 'livrée') NOT NULL,
  PRIMARY KEY (id_commande)
) ENGINE = InnoDB DEFAULT CHARSET =utf8 AUTO_INCREMENT = 2;


CREATE TABLE IF NOT EXISTS membre (
  id_membre INT(3) NOT NULL AUTO_INCREMENT,
  pseudo VARCHAR(20) NOT NULL,
  mdp VARCHAR(32) NOT NULL,
  nom VARCHAR(20) NOT NULL,
  prenom VARCHAR(20) NOT NULL,
  email VARCHAR(50) NOT NULL,
  civilite ENUM ('m', 'f')NOT NULL,
  ville VARCHAR(20) NOT NULL,
  code_postal int(5) UNSIGNED ZEROFILL NOT NULL, #completer par des zeros devant s'il en manque
  adresse VARCHAR(50) NOT NULL,
  statut int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id_membre)
) ENGINE = InnoDB DEFAULT CHARSET =utf8 AUTO_INCREMENT = 6;

CREATE TABLE IF NOT EXISTS produit (
  id_produit INT(3) NOT NULL AUTO_INCREMENT,
  reference VARCHAR(20) NOT NULL,
  categorie VARCHAR(20) NOT NULL,
  titre VARCHAR(100) NOT NULL ,
  description TEXT NOT NULL ,
  couleur VARCHAR(20) NOT NULL ,
  taille VARCHAR(5) NOT NULL ,
  publique ENUM('h', 'f', 'mixte') NOT NULL ,
  photo VARCHAR(250) NOT NULL ,
  prix INT(3) NOT NULL ,
  stock INT(3) NOT NULL ,
  PRIMARY KEY (id_produit)
)ENGINE = InnoDB DEFAULT CHARSET =utf8 AUTO_INCREMENT = 11;

# table créant des jointures
CREATE TABLE IF NOT EXISTS details_commande (
  id_details_commande INT(3) NOT NULL AUTO_INCREMENT,
  id_commande INT(3) NOT NULL,
  id_produit INT(3) NOT NULL,
  quantite INT(3) NOT NULL,
  prix INT(3) NOT NULL,
  PRIMARY KEY (id_details_commande)
)ENGINE = InnoDB DEFAULT CHARSET =utf8 AUTO_INCREMENT = 4;


ALTER TABLE details_commande ADD CONSTRAINT fk_details_produit FOREIGN KEY (id_produit) REFERENCES produit(id_produit);
ALTER TABLE details_commande ADD CONSTRAINT fk_details_commande FOREIGN KEY (id_commande) REFERENCES commande(id_commande);
ALTER TABLE commande ADD CONSTRAINT fk_commande_membre FOREIGN KEY (id_membre) REFERENCES membre(id_membre);

# --------------------------------------------------------------------------
SELECT * FROM membre;
INSERT INTO membre (pseudo, mdp,  nom, prenom, email, civilite,ville,  code_postal, adresse, statut) VALUES ('ynov', 'admin', 'ynov', 'ynov','romain.lacube@gmail.com', 'm', 'Aix', '13100', '2 rue des mes fesses', '1');












