CREATE DATABASE team_race2;

\c team_race2

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

CREATE SEQUENCE etape_seq START 1;

CREATE  TABLE  etape(
                        id VARCHAR(100) primary Key default generate_id('etape_seq','ETAP_'),
                        nom varchar(100) NOT NULL ,
                        longueur DOUBLE PRECISION NOT NULL ,
                        nb_coureur INT NOT NULL ,
                        rang_etape INT NOT NULL,
                        time_debut TIMESTAMP NOT NULL,
                        UNIQUE(nom,time_debut)
);

CREATE SEQUENCE point_seq START 1;

CREATE TABLE point(
                      id VARCHAR(100) primary Key default generate_id('point_seq','Pt_'),
                      pt int NOT NULL UNIQUE ,
                      rang int NOT NULL
);

CREATE SEQUENCE equipe_seq START 1;

CREATE TABLE equipe(
                       id VARCHAR(100) primary Key default generate_id('equipe_seq','EQU_'),
                       nom varchar(100) NOT NULL UNIQUE ,
                       mail varchar(100) not null ,
                       pwd text not null
);

CREATE SEQUENCE coureur_seq START 1;

CREATE TABLE coureur(
                        id VARCHAR(100) primary Key default generate_id('coureur_seq','Cour_'),
                        nom VARCHAR(100) not null ,
                        numero_dossard VARCHAR(100) NOT NULL UNIQUE ,
                        genre varchar(20) not null ,
                        date_naissance date NOT NULL ,
                        id_equipe VARCHAR(100) NOT NULL ,
                        FOREIGN KEY (id_equipe) references equipe(id)
);



CREATE SEQUENCE etape_coureur_seq START 1;
-- màj jours 2
CREATE TABLE etape_coureur(
                              id VARCHAR(100) primary Key default generate_id('etape_coureur_seq','ETAP_COU_'),
                              id_etape varchar(100),
                              id_coureur varchar(100),
                              FOREIGN KEY (id_etape) references etape(id),
                              FOREIGN KEY (id_coureur) references coureur(id),
                              UNIQUE (id_etape,id_coureur)
);


-- jours 2 màj
CREATE SEQUENCE categorie_coureur_seq START 1;

CREATE TABLE categorie_coureur(
                                  id VARCHAR(100) primary Key default generate_id('categorie_coureur_seq','CAT_COU_'),
                                  id_coureur varchar(100),
                                  categorie varchar(100),
                                  age int ,
                                  FOREIGN KEY (id_coureur) references coureur(id)
);


CREATE SEQUENCE temp_coureur_etape_seq START 1;

CREATE TABLE temp_coureur_etape (
                                        id VARCHAR(100) PRIMARY KEY DEFAULT generate_id('temp_coureur_etape_seq','TEM_COU_ETA_'),
                                        id_etape VARCHAR(100),
                                        id_coureur VARCHAR(100),
                                        time_debut TIMESTAMP NOT NULL,
                                        time_fin TIMESTAMP NOT NULL,
                                        penalite INTERVAL Default '00:00:00' NOT NULL,
                                        FOREIGN KEY (id_etape) REFERENCES etape(id),
                                        FOREIGN KEY (id_coureur) REFERENCES coureur(id),
                                        UNIQUE (id_etape, id_coureur)
);
-- CREATE TABLE temp_coureur_etape(
--     id VARCHAR(100) primary Key default generate_id('temp_coureur_etape_seq','TEM_COU_ETA_'),
--     id_etape varchar(100),
--     id_coureur varchar(100),
--     time_debut TIMESTAMP NOT NULL ,
--     time_fin TIMESTAMP NOT NULL ,
--     penalite int NOT NULL ,
--     FOREIGN KEY (id_etape) references etape(id),
--     FOREIGN KEY (id_coureur) references coureur(id),
--     UNIQUE (id_etape,id_coureur)
-- );

ALTER TABLE temp_coureur_etape
ALTER COLUMN penalite TYPE INTERVAL USING penalite::INTERVAL CASCADE;

-- -- Supprimer la colonne existante
-- ALTER TABLE temp_coureur_etape DROP COLUMN penalite CASCADE ;
--
-- -- Ajouter la nouvelle colonne avec le type et la valeur par défaut souhaités
-- ALTER TABLE temp_coureur_etape ADD COLUMN penalite time DEFAULT '00:00:00';

-- penalite equipe
CREATE SEQUENCE penalite_equipe_seq START 1;

CREATE TABLE penalite_equipe(
    id VARCHAR(100) primary Key default generate_id('penalite_equipe_seq','PEN_EQU_'),
    id_equipe VARCHAR(100),
    nom_equipe VARCHAR(100),
    id_etape VARCHAR(100),
    nom_etape VARCHAR(100),
    penalite TIME DEFAULT '00:00:00',
    etat VARCHAR(100), --(delet,valide)
    FOREIGN KEY (id_etape) REFERENCES etape(id),
    FOREIGN KEY (id_equipe) REFERENCES equipe(id)
);
--
-- INSERT INTO penalite_equipe (id_equipe, nom_equipe, id_etape, nom_etape, penalite, etat)
-- VALUES ('EQU_00001', 'B', 'ETAP_00001', 'Betsirazaina', '00:20:10', 'appliquée');

-- table temp

CREATE TABLE etape_temp(
    etape VARCHAR(100) NOT NULL ,
    longueur DOUBLE PRECISION NOT NULL ,
    nb_coureur INT NOT NULL ,
    rang INT NOT NULL ,
    dateheure_depart TIMESTAMP not null
);



-- resultat temp
CREATE TABLE resultat(
    etape_rang INT NOT NULL ,
    numero_dossard VARCHAR(100) NOT NULL ,
    nom VARCHAR(100) NOT NULL ,
    genre VARCHAR(100) NOT NULL ,
    date_naissance date NOT NULL ,
    equipe VARCHAR(100) NOT NULL ,
    arrivee timestamp NOT NULL,
    UNIQUE (etape_rang,numero_dossard,equipe)
);

-- view
-- get penalite total par etape par equipe ka vlide ny etat
CREATE OR REPLACE VIEW v_penalite_equipe AS
select id_equipe,nom_equipe,id_etape,nom_etape,sum(penalite) penalite_total
from penalite_equipe pe
         join equipe e on pe.id_equipe = e.id
         join etape e2 on e2.id = pe.id_etape
where etat='valide'
GROUP BY id_equipe,nom_equipe,id_etape,nom_etape;

CREATE VIEW v_id_coureur AS
SELECT tce.id_etape,c2.id id_coureur,e.id id_equipe from temp_coureur_etape tce
                                                             JOIN coureur c2 on tce.id_coureur = c2.id
                                                             JOIN equipe e on c2.id_equipe = e.id;
-- get age coureur
CREATE VIEW v_age_coureur AS
SELECT
    id as id_coureur,
    EXTRACT(YEAR FROM CURRENT_DATE) - EXTRACT(YEAR FROM date_naissance) AS age
FROM
    coureur;

-- SELECT c.id id_coureur,c.date_naissance, v. time_debut
-- FROM
--     Coureur c
-- Join v_equipe v on c.id= v.id_coureur;

CREATE VIEW v_equipe AS
SELECT
    e.id AS id_etape,
    e.nom AS nom_etape,
    e.longueur,
    e.nb_coureur,
    e.rang_etape,
    e.time_debut,
    eq.id AS id_equipe,
    eq.nom AS nom_equipe,
    c.id AS id_coureur,
    c.nom AS nom_coureur,
    c.numero_dossard,
    c.genre,
    c.date_naissance,
    cc.categorie,
    cc.age
FROM
    etape e
        LEFT JOIN
    etape_coureur ec ON e.id = ec.id_etape
        LEFT JOIN
    coureur c ON ec.id_coureur = c.id
        LEFT JOIN
    equipe eq ON c.id_equipe = eq.id
        LEFT JOIN
    categorie_coureur cc ON c.id = cc.id_coureur;


-- maka ny temps equipe
--     Jours 2
CREATE OR REPLACE VIEW v_temp_coureur AS
SELECT
    ve.id_etape,
    ve.nom_etape,
    ve.longueur,
    ve.nb_coureur,
    ve.rang_etape,
    ve.time_debut AS etape_time_debut,
    ve.id_equipe,
    ve.nom_equipe,
    ve.id_coureur,
    ve.nom_coureur,
    ve.numero_dossard,
    ve.genre,
    ve.date_naissance,
    ve.categorie,
    ve.age,
    tce.time_debut AS coureur_time_debut,
    tce.time_fin,
    tce.penalite
FROM
    v_equipe ve
      LEFT JOIN
    temp_coureur_etape tce ON ve.id_etape = tce.id_etape AND ve.id_coureur = tce.id_coureur;

-- rang classement par coureur global
CREATE VIEW v_temp_coureur_rank_global AS
WITH ranked_times AS (
    SELECT
        *,
        (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 AS temps_effectue,
        TO_CHAR((time_fin - coureur_time_debut +penalite), 'DD HH24:MI:SS') AS temps_passe,
        DENSE_RANK() OVER (PARTITION BY id_etape ORDER BY (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 ASC) AS rang
    FROM
        v_temp_coureur
    WHERE
        id_equipe IS NOT NULL
)
SELECT
    rt.*,
   COALESCE(p.pt,0)
FROM
    ranked_times rt
        LEFT JOIN
    point p ON rt.rang = p.rang;

-- rang par coureur par categorie
CREATE VIEW v_temp_coureur_rank_categorie AS
WITH ranked_times AS (
    SELECT
        *,
        EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) / 60 AS temps_effectue,
        TO_CHAR((time_fin - coureur_time_debut), 'DD HH24:MI:SS') AS temps_passe,
        DENSE_RANK() OVER (PARTITION BY id_etape,categorie ORDER BY EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) / 60 ASC) AS rang
    FROM
        v_temp_coureur
    WHERE
        id_equipe IS NOT NULL
)
SELECT
    rt.*,
    p.pt
FROM
    ranked_times rt
        LEFT JOIN
    point p ON rt.rang = p.rang;

-- rang par coureur par genre
CREATE VIEW v_temp_coureur_rank_genre AS
WITH ranked_times AS (
    SELECT
        *,
        EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) / 60 AS temps_effectue,
        TO_CHAR((time_fin - coureur_time_debut), 'DD HH24:MI:SS') AS temps_passe,
        DENSE_RANK() OVER (PARTITION BY id_etape,genre ORDER BY EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) / 60 ASC) AS rang
    FROM
        v_temp_coureur
    WHERE
        id_equipe IS NOT NULL
)
SELECT
    rt.*,
    p.pt
FROM
    ranked_times rt
        LEFT JOIN
    point p ON rt.rang = p.rang;

-- rang par coureur par genre par categorie
CREATE VIEW v_temp_coureur_rank_genre_categorie AS
WITH ranked_times AS (
    SELECT
        *,
        EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) / 60 AS temps_effectue,
        TO_CHAR((time_fin - coureur_time_debut), 'DD HH24:MI:SS') AS temps_passe,
        DENSE_RANK() OVER (PARTITION BY id_etape,genre,categorie ORDER BY EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) / 60 ASC) AS rang
    FROM
        v_temp_coureur
    WHERE
        id_equipe IS NOT NULL
)
SELECT
    rt.*,
    p.pt
FROM
    ranked_times rt
        LEFT JOIN
    point p ON rt.rang = p.rang;


-- maka ny classement par equipe par rapport au point de solosoloina ny v_temp_coureur_rank_genre_categorie any @metier
-- CREATE OR REPLACE VIEW v_classement_equipe AS
-- SELECT
--     id_equipe,
--     nom_equipe,
--     SUM(COALESCE(temps_effectue, 0)) AS total_temp_effectue,
--     SUM(COALESCE(temps_passe::interval, INTERVAL '0')) AS total_temps_passe,
--     SUM(pt) AS total_pt,
--     DENSE_RANK() OVER (ORDER BY SUM(pt) DESC) AS rang
-- FROM
--     v_temp_coureur_rank_genre_categorie
-- GROUP BY
--     id_equipe,
--     nom_equipe;



--     prototype
-- SELECT
--     id_etape,id_equipe,id_coureur,coureur_time_debut,time_fin,
--     EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) / 60 AS temps_effectue,
--     TO_CHAR((time_fin - coureur_time_debut), 'DD HH24:MI:SS') AS temps_passe,
--     DENSE_RANK() OVER (PARTITION BY id_etape ORDER BY EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) / 60 ASC) AS rang
-- FROM
--         v_temp_coureur
--     ORDER BY
--         temps_effectue;

--    Jours 1 V.1
/*CREATE VIEW v_temp_coureur AS
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
    e.id_cours, e.rang_etape, t.id_coureur;*/


-- maka ny rang an'ny coureur @ etape rehetra
/*CREATE OR REPLACE VIEW v_temps_coureur_etape_rank AS
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
    rang;*/
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
/*CREATE VIEW v_coureur_rang AS
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

*/
-- maka ny rang coureur mis point de misy info equipe
/*CREATE VIEW v_point_coureur_equipe AS
SELECT v.*, e.nom AS equipe_nom
FROM v_point_coureur v
         JOIN equipe e ON v.id_equipe = e.id;*/



WITH ranked_times AS (
    SELECT
        *,
        EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) / 60 AS temps_effectue,
        TO_CHAR((time_fin - coureur_time_debut), 'DD HH24:MI:SS') AS temps_passe,
        DENSE_RANK() OVER (PARTITION BY id_etape ORDER BY EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) / 60 ASC) AS rang
    FROM
        v_temp_coureur
    WHERE
        id_equipe IS NOT NULL
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
    nom_equipe;






--
WITH ranked_times AS (
    SELECT
        *,
        (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 AS temps_effectue,
        TO_CHAR((time_fin - coureur_time_debut + penalite), 'DD HH24:MI:SS') AS temps_passe,
        DENSE_RANK() OVER (PARTITION BY id_etape ORDER BY (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 ASC) AS rang
    FROM
        v_temp_coureur
    WHERE
        id_equipe IS NOT NULL
)
SELECT
    rt.*,
    p.pt
FROM
    ranked_times rt
        LEFT JOIN point p ON rt.rang = p.rang;


SELECT *
FROM
    temp_coureur_etape pe
JOIN coureur c2 on pe.id_coureur = c2.id
join penalite_equipe vpe on pe.id_etape = vpe.id_etape and c2.id_equipe = vpe.id_equipe;



-- update temp_coureur_etape set penalite='00:02:59' where id_etape='ETAP_00002' and id_coureur in(select * from v_id_coureur where id_etape='ETAP_00001' and id_equipe='EQU_00001');


-- procedure stocker

CREATE OR REPLACE FUNCTION generer_requete(partition TEXT, idEquipe TEXT, AND_condition TEXT)
RETURNS TABLE (
    id_etape VARCHAR(100),
    nom_etape VARCHAR(100),
    longueur DOUBLE PRECISION,
    nb_coureur INT,
    rang_etape INT,
    etape_time_debut TIMESTAMP,
    id_equipe VARCHAR(100),
    nom_equipe VARCHAR(100),
    id_coureur VARCHAR(100),
    nom_coureur VARCHAR(100),
    numero_dossard VARCHAR(100),
    genre VARCHAR(100),
    date_naissance DATE,
    categorie VARCHAR(100),
    age INT,
    coureur_time_debut TIMESTAMP,
    time_fin TIMESTAMP,
    penalite INTERVAL,
    temps_effectue DOUBLE PRECISION,
    temps_passe INTERVAL,
    rang INT,
    pt INT
) AS $$
BEGIN
RETURN QUERY EXECUTE '
    WITH ranked_times AS (
        SELECT
            id_etape,
            nom_etape,
            longueur,
            nb_coureur,
            rang_etape,
            etape_time_debut,
            id_equipe,
            nom_equipe,
            id_coureur,
            nom_coureur,
            numero_dossard,
            genre,
            date_naissance,
            categorie,
            age,
            coureur_time_debut,
            time_fin,
            penalite,
            ((EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60)::double precision AS temps_effectue,
            TO_CHAR((time_fin - coureur_time_debut + penalite), ''DD HH24:MI:SS'') AS temps_passe,
            DENSE_RANK() OVER (PARTITION BY id_etape ' || COALESCE(partition, '') || ' ORDER BY ((EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60) ASC) AS rang
        FROM
            v_temp_coureur
        WHERE
            id_equipe IS NOT NULL
    )
    SELECT
        rt.id_etape,
        rt.nom_etape,
        rt.longueur,
        rt.nb_coureur,
        rt.rang_etape,
        rt.etape_time_debut,
        rt.id_equipe,
        rt.nom_equipe,
        rt.id_coureur,
        rt.nom_coureur,
        rt.numero_dossard,
        rt.genre,
        rt.date_naissance,
        rt.categorie,
        rt.age,
        rt.coureur_time_debut,
        rt.time_fin,
        rt.penalite,
        rt.temps_effectue,
        rt.temps_passe,
        rt.rang,
        p.pt
    FROM
        ranked_times rt
        LEFT JOIN point p ON rt.rang = p.rang
    WHERE
        rt.id_equipe = ' || quote_literal(idEquipe) || ' ' || COALESCE('AND ' || AND_condition, '') || '
    ORDER BY rt.rang;
    ';
END;
$$ LANGUAGE plpgsql;

--
-- modele
   WITH ranked_times AS (
    SELECT
        *,
        (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 AS temps_effectue,
        TO_CHAR((time_fin - coureur_time_debut + penalite), 'DD HH24:MI:SS') AS temps_passe,
        DENSE_RANK() OVER (PARTITION BY id_etape ORDER BY (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 ASC) AS rang
    FROM
        v_temp_coureur
    WHERE
        id_equipe IS NOT NULL
)
SELECT
    rt.rang,
    rt.nom_coureur,
    rt.numero_dossard,
    rt.genre,
    rt.nom_equipe,
    sum(p.pt) pt
FROM
    ranked_times rt
        LEFT JOIN point p ON rt.rang = p.rang
GROUP BY
    rt.nom_coureur,
    rt.numero_dossard,
    rt.genre,
    rt.nom_equipe,rt.rang;

-- test
WITH ranked_times AS (
    SELECT
        *,
        (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 AS temps_effectue,
        TO_CHAR((time_fin - coureur_time_debut +penalite), 'DD HH24:MI:SS') AS temps_passe
    FROM
        v_temp_coureur
    WHERE
        id_equipe IS NOT NULL
)
SELECT
    rt.nom_coureur,
    rt.numero_dossard,
    rt.genre,
    rt.nom_equipe,
    COALESCE(sum(p.pt)) pt
FROM
    ranked_times rt
        LEFT JOIN
    point p ON rt.rang = p.rang

GROUP BY
    rt.nom_coureur,
    rt.numero_dossard,
    rt.genre,
    rt.nom_equipe;



-- ampiasaina
WITH ranked_times AS (
    SELECT
        *,
        (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 AS temps_effectue,
        TO_CHAR((time_fin - coureur_time_debut +penalite), 'DD HH24:MI:SS') AS temps_passe,
        DENSE_RANK() OVER ( ORDER BY (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 ASC) AS rang
    FROM
        v_temp_coureur
    WHERE
        id_equipe IS NOT NULL
)
SELECT
    rt.nom_coureur,
    rt.numero_dossard,
    rt.genre,
    rt.nom_equipe,
    COALESCE(p.pt,0) as pt
FROM
    ranked_times rt
        LEFT JOIN
    point p ON rt.rang = p.rang
GROUP BY
    rt.nom_coureur,
    rt.numero_dossard,
    rt.genre,
    rt.nom_equipe;
WHERE id_equipe = '".$idEquipe."'

     ORDER BY rang"

<td>{{$row->rang}}</td>
                        <td>{{$row->nom_coureur}}</td>
                        <td>{{$row->numero_dossard}}</td>
                        <td>{{$row->genre}}</td>
                        <td>{{$row->nom_equipe}}</td>
                        <td>{{$row->pt}}</td>


drop function generer_requete(text,text,text);

SELECT * FROM generer_requete(null, 'EQU_00001', null);








select info.id_etape,info.nom_etape,sum(pt) pt from(
WITH ranked_times AS (
                                SELECT
                                    *,
                                    (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 AS temps_effectue,
                                    TO_CHAR((time_fin - coureur_time_debut +penalite), 'DD HH24:MI:SS') AS temps_passe,
                                    DENSE_RANK() OVER (PARTITION BY id_etape  ORDER BY (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 ASC) AS rang
                                FROM
                                    v_temp_coureur
                                WHERE
                                    id_equipe IS NOT NULL
                            )
                            SELECT
                                rt.*,
                                COALESCE(p.pt,0) as pt
                            FROM
                                ranked_times rt
                                    LEFT JOIN
                                point p ON rt.rang = p.rang
                                ORDER BY rang) info group by info.id_etape,info.nom_etape;
