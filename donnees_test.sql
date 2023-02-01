-- on se positionne sur la base exemple 
USE memorycards; 
-- insertion d'un utilisateur et 3 article associés 
-- on créé des variable de session utilisateur
SET @user_email='myriam@gmail.com'; 
SET @user_password = '$2y$10$jfxAhN9YVKTF8lJc2LtN4.fpWi4oEZ7imz6UP2P9qdIUh9717gOw2'; -- hash de password
SET @pseudo = 'Myriam';

INSERT INTO utilisateur(email, password, pseudo) VALUES (@user_email, @user_password, @pseudo);  --password
SET @user_id = LAST_INSERT_ID(); -- LAST_INSERT_ID() renvoi le dernier id inséré dans la base de données : 
-- https://dev.mysql.com/doc/refman/8.0/en/information-functions.html#function_last-insert-id

-- Les articles de l'utilisateur nico@exemple.com
INSERT INTO categorie(nom) VALUES ('programmation'); 

INSERT INTO theme(id_utilisateur, id_categorie, nom_theme, description, public, date_creation) VALUES 
(@user_id, (SELECT id_categorie FROM categorie WHERE nom = 'programmation'), 'HTML', "C'est cool", true, '2022-10-26'),
(@user_id, (SELECT id_categorie FROM categorie WHERE nom = 'programmation'), 'Javascript', "C'est cool", false, '2022-10-26');

INSERT INTO carte(id_theme, recto, verso, date_creation, date_modification) VALUES 
((SELECT id_theme FROM theme WHERE nom_theme = 'HTML'), 'A quoi sert le HTML ?', "Construire la structure d'une page web", '2022-10-26', '2022-10-27');
SET @id_carte = LAST_INSERT_ID();

INSERT INTO revision (id_utilisateur, id_theme, nb_niveau, nb_carte, date_creation) VALUES 
(@user_id, (SELECT id_theme FROM theme WHERE nom_theme = 'HTML'), 7, 2, "2022-10-26");
SET @id_revision = LAST_INSERT_ID();

INSERT INTO revu (id_revision, id_carte, derniere_vu, niveau) VALUES 
(@id_revision, @id_carte, "2022-10-27", 4);


