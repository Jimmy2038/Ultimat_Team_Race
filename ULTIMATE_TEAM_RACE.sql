CREATE DATABASE team_race2;

\c team_race

SET CLIENT_ENCODING TO 'UTF8';

CREATE OR REPLACE FUNCTION generate_id(sequence_name VARCHAR(100), prefix VARCHAR(100))
RETURNS VARCHAR(100) AS $$
DECLARE
seq_val INT;
    id_generated VARCHAR(100);
BEGIN
EXECUTE format('SELECT nextval(''%I'')', sequence_name) INTO seq_val;

id_generated := prefix || lpad(seq_val::text, 5, '0');

RETURN id_generated;
END;
$$ LANGUAGE plpgsql;

CREATE SEQUENCE course_seq START 1;

CREATE TABLE course(
    id VARCHAR(100) primary Key default generate_id('course_seq','COURS_'),
    nom VARCHAR(100) not null ,
    nb_jour INT
);

CREATE SEQUENCE etape_seq START 1;

CREATE  TABLE  etape(
    id VARCHAR(100) primary Key default generate_id('etape_seq','ETAP_'),
    nom varchar(100) NOT NULL ,
    longueur DOUBLE PRECISION NOT NULL ,
    nb_coureur INT NOT NULL ,
    rang_etape INT NOT NULL,
    time_debut TIMESTAMP NOT NULL ,
    id_cours VARCHAR(100) ,
    FOREIGN KEY (id_cours) references course(id)
);

CREATE SEQUENCE point_seq START 1;

CREATE TABLE point(
    id VARCHAR(100) primary Key default generate_id('point_seq','Pt_'),
    pt int NOT NULL ,
    rang int NOT NULL,
    id_cours VARCHAR(100) ,
    FOREIGN KEY (id_cours) references course(id)
);

CREATE SEQUENCE equipe_seq START 1;

CREATE TABLE equipe(
    id VARCHAR(100) primary Key default generate_id('equipe_seq','EQU_'),
    nom varchar(100) NOT NULL ,
    mail varchar(100) not null ,
    pwd text not null
);

CREATE SEQUENCE coureur_seq START 1;

CREATE TABLE coureur(
    id VARCHAR(100) primary Key default generate_id('coureur_seq','Cour_'),
    nom VARCHAR(100) not null ,
    numero_dossard VARCHAR(100) NOT NULL ,
    genre varchar(20) not null ,
    date_naissance date NOT NULL ,
    id_equipe VARCHAR(100) NOT NULL ,
    FOREIGN KEY (id_equipe) references equipe(id)
);

-- màj jour 2 (nasorona)
-- CREATE SEQUENCE categorie_seq START 1;
--
-- CREATE TABLE categorie(
--     id VARCHAR(100) primary Key default generate_id('categorie_seq','CAT_'),
--     nom VARCHAR(100) NOT NULL
-- );

CREATE SEQUENCE etape_coureur_seq START 1;
-- màj jours 2
CREATE TABLE etape_coureur(
    id VARCHAR(100) primary Key default generate_id('etape_coureur_seq','ETAP_COU_'),
    id_etape varchar(100),
    id_coureur varchar(100),
    FOREIGN KEY (id_etape) references etape(id),
    FOREIGN KEY (id_coureur) references coureur(id)
);

-- jours 1
-- CREATE TABLE etape_coureur(
--     id VARCHAR(100) primary Key default generate_id('etape_coureur_seq','ETAP_COU_'),
--     id_etape varchar(100),
--     id_coureur varchar(100),
--     id_categorie varchar(100),
--     FOREIGN KEY (id_etape) references etape(id),
--     FOREIGN KEY (id_coureur) references coureur(id),
--     FOREIGN KEY (id_categorie) references categorie(id)
-- );

INSERT INTO etape_coureur (id_etape,id_coureur) VALUES ('ETAP_00002','Cour_00007'),
                                                                    ('ETAP_00002','Cour_00008');

-- jours 2 màj
CREATE SEQUENCE categorie_coureur_seq START 1;

CREATE TABLE categorie_coureur(
    id VARCHAR(100) primary Key default generate_id('categorie_coureur_seq','CAT_COU_'),
    id_coureur varchar(100),
    categorie varchar(100),
    age int ,
    FOREIGN KEY (id_coureur) references coureur(id),
    FOREIGN KEY (id_categorie) references categorie(id)
);
-- jours 1
--
-- CREATE TABLE categorie_coureur(
--     id VARCHAR(100) primary Key default generate_id('categorie_coureur_seq','CAT_COU_'),
--     id_coureur varchar(100),
--     id_categorie varchar(100),
--     FOREIGN KEY (id_coureur) references coureur(id),
--     FOREIGN KEY (id_categorie) references categorie(id)


CREATE SEQUENCE temp_coureur_etape_seq START 1;

CREATE TABLE temp_coureur_etape(
    id VARCHAR(100) primary Key default generate_id('temp_coureur_etape_seq','TEM_COU_ETA_'),
    id_etape varchar(100),
    id_coureur varchar(100),
    time_debut TIMESTAMP NOT NULL ,
    time_fin TIMESTAMP NOT NULL ,
    penalite int NOT NULL ,
    FOREIGN KEY (id_etape) references etape(id),
    FOREIGN KEY (id_coureur) references coureur(id)
);

CREATE SEQUENCE point_coureur_etape_seq START 1;

CREATE TABLE point_coureur_etape(
    id VARCHAR(100) primary Key default generate_id('point_coureur_etape_seq','Pt_COU_ETA_'),
    id_etape varchar(100),
    id_coureur varchar(100),
    pt INT NOT NULL ,
    FOREIGN KEY (id_etape) references etape(id),
    FOREIGN KEY (id_coureur) references coureur(id)
);


-- cours
INSERT INTO course (nom, nb_jour) VALUES
                                      ('Tour de France', 2),
                                        ('Paris-Nice', 2);


-- donnEe etape

INSERT INTO etape (nom, longueur, nb_coureur, rang_etape, time_debut, id_cours) VALUES
                                                                                    ('Etape 1', 120.5, 1, 1, '2024-06-02 08:00:00', 'COURS_00001'),
                                                                                    ('Etape 2', 110.3, 2, 2, '2024-06-03 09:30:00', 'COURS_00001');



-- point
INSERT INTO point (pt, rang, id_cours) VALUES
                                           (10, 1, 'COURS_00001'),
                                           (8, 2, 'COURS_00001'),
                                           (6, 3, 'COURS_00001'),
                                           (4, 4, 'COURS_00001'),
                                           (2, 5, 'COURS_00001');
-- Pour 'Paris-Nice'
INSERT INTO point (pt, rang, id_cours) VALUES
                                           (10, 1, 'COURS_00002'),
                                           (8, 2, 'COURS_00002'),
                                           (6, 3, 'COURS_00002'),
                                           (4, 4, 'COURS_00002'),
                                           (2, 5, 'COURS_00002');



-- coureur
INSERT INTO coureur (nom, numero_dossard, genre, date_naissance, id_equipe) VALUES
--                                                                                 ('Mano', 'D001', 'M', '1990-05-01', 'EQU_00001'),
--                                                                                 ('Morel', 'D002', 'F', '1992-06-15', 'EQU_00001'),
--                                                                                 ('Jim', 'D003', 'M', '1988-07-20', 'EQU_00001'),
                                                                                ('Fex', 'D007', 'M', '1988-07-20', 'EQU_00001'),
--                                                                                 ('Dior', 'D004', 'F', '1995-08-30', 'EQU_00002'),
--                                                                                 ('Sabi', 'D006', 'F', '1995-08-30', 'EQU_00002'),
                                                                                ('Tsiory', 'D008', 'F', '1995-08-30', 'EQU_00002');
--                                                                                 ('Le', 'D005', 'M', '1991-09-25', 'EQU_00002');

 -- categorie
-- INSERT INTO categorie ( nom) VALUES
--                                     ( 'Montagne'),
--                                     ( 'Vitesse'),
--                                     ( 'Endurance'),
--                                     ( 'Sprint'),
--                                     ( 'Chrono');

select c.id,c.nom,c.numero_dossard,c.genre,c.date_naissance,c.id_equipe from etape_coureur ec join coureur c on ec.id_coureur = c.id where ec.id_etape='';

select * from etape_coureur ec join coureur c on ec.id_coureur = c.id;

-- view

-- maka ny temps miaraka @ info etape
CREATE VIEW v_temp_coureur AS
SELECT
    t.id AS id_temp_coureur_etape,
    t.id_etape,
    t.id_coureur,
    t.time_debut AS time_debut_coureur,
    t.time_fin AS time_fin_coureur,
    t.penalite,
    e.nom AS nom_etape,
    e.longueur,
    e.nb_coureur,
    e.rang_etape,
    e.id_cours
FROM
    temp_coureur_etape t
        JOIN
    etape e ON t.id_etape = e.id
ORDER BY
    e.id_cours, e.rang_etape, t.id_coureur;


-- maka ny rang an'ny coureur @ etape rehetra
CREATE OR REPLACE VIEW v_temps_coureur_etape_rank AS
SELECT
    v.id_temp_coureur_etape AS id,
    v.id_etape,
    v.id_coureur,
    v.time_debut_coureur AS time_debut,
    v.time_fin_coureur AS time_fin,
    v.penalite,
    v.nom_etape,
    v.longueur,
    v.nb_coureur,
    v.rang_etape,
    v.id_cours,
    EXTRACT(EPOCH FROM (v.time_fin_coureur - v.time_debut_coureur)) / 60 AS temps_effectue,
    TO_CHAR((v.time_fin_coureur - v.time_debut_coureur), 'HH24:MI:SS') AS temps_passe,
    DENSE_RANK() OVER (PARTITION BY v.id_etape ORDER BY EXTRACT(EPOCH FROM (v.time_fin_coureur - v.time_debut_coureur)) / 60 ASC) AS rang
FROM
    v_temp_coureur v
ORDER BY
    v.id_etape,
    rang;
/*
RAHA ASINA PRESENTATION EN JOURS

SELECT
    v.id_temp_coureur_etape AS id,
    v.id_etape,
    v.id_coureur,
    v.time_debut_coureur AS time_debut,
    v.time_fin_coureur AS time_fin,
    v.penalite,
    v.nom_etape,
    v.longueur,
    v.nb_coureur,
    v.rang_etape,
    v.id_cours,
    EXTRACT(EPOCH FROM (v.time_fin_coureur - v.time_debut_coureur)) / 60 AS temps_effectue,
    TO_CHAR((v.time_fin_coureur - v.time_debut_coureur), 'DD HH24:MI:SS') AS temps_passe,
    DENSE_RANK() OVER (PARTITION BY v.id_etape ORDER BY EXTRACT(EPOCH FROM (v.time_fin_coureur - v.time_debut_coureur)) / 60 ASC) AS rang
FROM
    v_temp_coureur v
ORDER BY
    v.id_etape,
    rang;

*/


-- maka ny info an'ny coureur miaraka @ le rang
CREATE VIEW v_coureur_rang AS
SELECT
    v.id AS id_temp_coureur_etape,
    v.id_etape,
    v.id_coureur,
    v.time_debut,
    v.time_fin,
    v.penalite,
    v.nom_etape,
    v.longueur,
    v.nb_coureur,
    v.rang_etape,
    v.id_cours,
    v.temps_effectue,
    v.temps_passe,
    v.rang,
    c.nom AS nom_coureur,
    c.numero_dossard,
    c.genre,
    c.date_naissance,
    c.id_equipe
FROM
    v_temps_coureur_etape_rank v
        JOIN
    coureur c ON v.id_coureur = c.id
ORDER BY
    v.id_cours, v.rang_etape, v.rang;


-- maka ny etape sy ny course mitambatra
CREATE OR REPLACE VIEW v_etape AS
SELECT
    e.id AS id_etape,
    e.nom AS nom_etape,
    e.longueur,
    e.nb_coureur,
    e.rang_etape,
    e.time_debut,
    e.id_cours,
    c.nom AS nom_course,
    c.nb_jour
FROM
    etape e
        JOIN
    course c ON e.id_cours = c.id
ORDER BY
    c.nom, e.rang_etape;

-- maka ny rang coureur mis point
CREATE OR REPLACE VIEW v_point_coureur AS
SELECT
    COALESCE(p.pt, 0) AS pt, -- Utilise 0 si p.pt est NULL
    v.id_temp_coureur_etape,
    v.id_etape,
    v.id_coureur,
    v.time_debut,
    v.time_fin,
    v.penalite,
    v.nom_etape,
    v.longueur,
    v.nb_coureur,
    v.rang_etape,
    v.id_cours,
    v.temps_effectue,
    v.temps_passe,
    v.rang,
    v.nom_coureur,
    v.numero_dossard,
    v.genre,
    v.date_naissance,
    v.id_equipe
FROM
    v_coureur_rang v
        LEFT JOIN
    point p  ON p.rang = v.rang AND p.id_cours = v.id_cours
ORDER BY
    v.rang;


-- maka ny rang coureur mis point de misy info equipe
CREATE VIEW v_point_coureur_equipe AS
SELECT v.*, e.nom AS equipe_nom
FROM v_point_coureur v
         JOIN equipe e ON v.id_equipe = e.id;

-- maka ny classement par equipe par rapport au temps
CREATE OR REPLACE VIEW v_classement_equipe AS
SELECT
    id_equipe,
    equipe_nom,
    SUM(temps_effectue) AS total_temp_effectue,
    SUM(temps_passe::interval) AS total_temps_passe,
    SUM(pt) AS total_pt,
    DENSE_RANK() OVER (ORDER BY SUM(temps_effectue) ASC) AS rang
FROM
    v_point_coureur_equipe
GROUP BY
    id_equipe,
    equipe_nom;



WITH ranked_times AS (
    SELECT
        *,
        EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) / 60 AS temps_effectue,
        TO_CHAR((time_fin - coureur_time_debut), 'DD HH24:MI:SS') AS temps_passe,
        DENSE_RANK() OVER (PARTITION BY id_etape,categorie  ORDER BY EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) / 60 ASC) AS rang
    FROM
        v_temp_coureur
    WHERE
        id_equipe IS NOT NULL
    AND categorie='M'
    )
SELECT
    id_equipe,
    nom_equipe,
    SUM(COALESCE(p.pt, 0)) AS total_points,
    DENSE_RANK() OVER (ORDER BY SUM(COALESCE(p.pt, 0)) DESC) AS rang,
    SUM(COALESCE(temps_passe::interval, INTERVAL '0')) AS total_temps_passe
FROM
    ranked_times rt
        LEFT JOIN
    point p ON rt.rang = p.rang
GROUP BY
    id_equipe,
    nom_equipe
