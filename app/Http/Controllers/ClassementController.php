<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ClassementController extends Controller
{

    public function ClassementcoureurEtape(Request $request)
    {
        $idEtape = $request->input('etape');
        $categorie = $request->input('categorie');
        $genre =$request->input('genre');

            $Etape = DB::table( 'etape' )
                ->get();
        if ($idEtape==null){
            $idEtape = $Etape[0]->id;
        }
        $cate = DB::select("select distinct categorie from categorie_coureur");

        $partition = "";
        $AND = "";
        if ($categorie!=null && $genre!=null){
            $partition = ",categorie,genre";
            $AND = "AND categorie = '".$categorie."' AND genre = '".$genre."'";
        }elseif ($categorie==null && $genre!=null){
            $partition = ",genre";
            $AND = " AND genre = '".$genre."'";
        }elseif ($categorie!=null && $genre==null){
            $partition = ",categorie";
            $AND = "AND categorie = '".$categorie."'";
        }
        $classement = DB::select("WITH ranked_times AS (
                                SELECT
                                    *,
                                    (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 AS temps_effectue,
                                    TO_CHAR((time_fin - coureur_time_debut +penalite), 'DD HH24:MI:SS') AS temps_passe_penalite,
                                    TO_CHAR((time_fin - coureur_time_debut ), 'DD HH24:MI:SS') AS temps_passe,
                                    DENSE_RANK() OVER (PARTITION BY id_etape ".$partition." ORDER BY (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 ASC) AS rang
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
                                WHERE id_etape = '".$idEtape."'
                                ".$AND."
                                ORDER BY rang
                                ");

        return view('Classement/coureur-etape-admin',[
            'classement' => $classement,
            'etape' => $Etape,
            'categories' => $cate,
        ]);
    }

    public function ClassementParEquipe(Request $request)
    {
        $categorie = $request->input('categorie');
        $genre =$request->input('genre');
        $cate = DB::select("select distinct categorie from categorie_coureur");

        $categorie_choisie ="Toute categorie";
        if ($categorie!=null || $genre!=null){
            $type_genre = "";
            if ($genre=='F'){
                $type_genre ="Femme";
            }elseif ($genre=='M'){
                $type_genre = "Homme";
            }
            $categorie_choisie = $type_genre ." ". $categorie;
        }
        $partition = "";
        $AND = "";
        if ($categorie!=null && $genre!=null){
            $partition = ",categorie,genre";
            $AND = "AND categorie = '".$categorie."' AND genre = '".$genre."'";
        }elseif ($categorie==null && $genre!=null){
            $partition = ",genre";
            $AND = " AND genre = '".$genre."'";
        }elseif ($categorie!=null && $genre==null){
            $partition = ",categorie";
            $AND = "AND categorie = '".$categorie."'";
        }
        $classement = DB::select("WITH ranked_times AS (
                                     SELECT
                                        *,
                                        (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 AS temps_effectue,
                                        TO_CHAR((time_fin - coureur_time_debut +penalite), 'DD HH24:MI:SS') AS temps_passe,
                                        DENSE_RANK() OVER (PARTITION BY id_etape ".$partition." ORDER BY (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 ASC) AS rang
                                    FROM
                                        v_temp_coureur
                                    WHERE
                                        id_equipe IS NOT NULL
                                        ".$AND."
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
                                    ORDER BY rang");


        Session::put('categorie_choisie',$categorie);
        Session::put('genre_choisi',$genre);
        Session::put('categorie',$categorie_choisie);
        Session::put('classement',$classement);
        return view('Classement/classement-equipe-admin',[
            'classement' => $classement,
            'categories' =>$cate,
        ]);
    }

    public function ClassementcoureurEtapeClient(Request $request)
    {
        $idEtape = $request->input('etape');
        $categorie = $request->input('categorie');
        $genre =$request->input('genre');

        $Etape = DB::table( 'etape' )
            ->get();
        if ($idEtape==null){
            $idEtape = $Etape[0]->id;
        }

        $cate = DB::select("select distinct categorie from categorie_coureur");

        $partition = "";
        $AND = "";
        if ($categorie!=null && $genre!=null){
            $partition = ",categorie,genre";
            $AND = "AND categorie = '".$categorie."' AND genre = '".$genre."'";
        }elseif ($categorie==null && $genre!=null){
            $partition = ",genre";
            $AND = " AND genre = '".$genre."'";
        }elseif ($categorie!=null && $genre==null){
            $partition = ",categorie";
            $AND = "AND categorie = '".$categorie."'";
        }
        $classement = DB::select("WITH ranked_times AS (
                                SELECT
                                    *,
                                    (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 AS temps_effectue,
                                    TO_CHAR((time_fin - coureur_time_debut +penalite), 'DD HH24:MI:SS') AS temps_passe_penalite,
                                    TO_CHAR((time_fin - coureur_time_debut ), 'DD HH24:MI:SS') AS temps_passe,                                    DENSE_RANK() OVER (PARTITION BY id_etape ".$partition." ORDER BY (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 ASC) AS rang
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
                                WHERE id_etape = '".$idEtape."'
                                ".$AND."
                                ORDER BY rang
                                ");
        return view('Classement/coureur-etape',[
            'classement' => $classement,
            'etape' => $Etape,
            'categories' => $cate,
        ]);
    }

    public function ClassementParEquipeClient(Request $request)
    {
        $categorie = $request->input('categorie');
        $genre =$request->input('genre');
        $cate = DB::select("select distinct categorie from categorie_coureur");

        $partition = "";
        $AND = "";
        if ($categorie!=null && $genre!=null){
            $partition = ",categorie,genre";
            $AND = "AND categorie = '".$categorie."' AND genre = '".$genre."'";
        }elseif ($categorie==null && $genre!=null){
            $partition = ",genre";
            $AND = " AND genre = '".$genre."'";
        }elseif ($categorie!=null && $genre==null){
            $partition = ",categorie";
            $AND = "AND categorie = '".$categorie."'";
        }
        $classement = DB::select("WITH ranked_times AS (
                                     SELECT
                                        *,
                                        (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 AS temps_effectue,
                                        TO_CHAR((time_fin - coureur_time_debut + penalite), 'DD HH24:MI:SS') AS temps_passe,
                                        DENSE_RANK() OVER (PARTITION BY id_etape ".$partition." ORDER BY (EXTRACT(EPOCH FROM (time_fin - coureur_time_debut)) + EXTRACT(EPOCH FROM penalite)) / 60 ASC) AS rang
                                    FROM
                                        v_temp_coureur
                                    WHERE
                                        id_equipe IS NOT NULL
                                        ".$AND."
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
                                    ORDER BY rang");
        return view('Classement/classement-equipe',[
            'classement' => $classement,
            'categories' => $cate,
        ]);
    }

    public function getDetailClassement($idEquipe)
    {

        $categorie =Session::get('categorie_choisie');

        $genre =Session::get('genre_choisi');

        $Etape = DB::table( 'etape' )
            ->get();

        $cate = DB::select("select distinct categorie from categorie_coureur");

        $partition = "";
        $AND = "";
        if ($categorie!=null && $genre!=null){
            $partition = ",categorie,genre";
            $AND = "AND categorie = '".$categorie."' AND genre = '".$genre."'";
        }elseif ($categorie==null && $genre!=null){
            $partition = ",genre";
            $AND = " AND genre = '".$genre."'";
        }elseif ($categorie!=null && $genre==null){
            $partition = ",categorie";
            $AND = "AND categorie = '".$categorie."'";
        }
        $classement = DB::select("select info.id_etape,info.nom_etape,sum(pt) pt from(
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
                            WHERE id_equipe = '".$idEquipe."'
                                ORDER BY rang) info group by info.id_etape,info.nom_etape


                                ");

        return view('Classement/detail-classement-admin',[
            'classement' => $classement,
            'etape' => $Etape,
            'categories' => $cate,
        ]);
    }


    public function getEtapeAdmin()
    {
        $etape = DB::table('etape')
            ->orderBy('rang_etape')
            ->get();

        return view('Classement/choix-etape-admin',[
            'etape' =>$etape,
        ]);
    }
    public function getEtapeClient()
    {
        $etape = DB::table('etape')
            ->orderBy('rang_etape')
            ->get();

        return view('Classement/choix-etape',[
            'etape' =>$etape,
        ]);
    }

}
