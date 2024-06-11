<?php

namespace App\Http\Controllers;

use App\Imports\Import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class ImportController extends Controller
{
    //
    public function goToImportResultat(){
        return view('Import/Import-resultat');
    }

    public function goToImportPoint()
    {
        return view('Import/Import-point');
    }
    public function importEtapeResultat(Request $request)
    {
         $data= Excel::toArray(new Import(),$request->file('etape'))[0];

        $donnee = [];
        foreach ($data as $row) {
            $donnee[] = [
                'etape' => $row['etape'],
                'longueur' => str_replace(',', '.', $row['longueur']),
                'nb_coureur' => $row['nb_coureur'],
                'rang' => $row['rang'],
                'date_depart' => $row['date_depart'],
                'heure_depart' => $row['heure_depart'],
            ];
        }
        $regle = [
            'etape' => 'required',
            'longueur' => 'required|numeric|min:0',
            'nb_coureur' => 'required|numeric',
            'rang' => 'required|numeric',
            'date_depart' => ['required',
                'regex:/^(?:(\d{4}-\d{2}-\d{2})|(\d{8})|(\d{4}\/\d{2}\/\d{2})|(\d{2}-\d{2}-\d{4})|(\d{2}\/\d{2}\/\d{4}))$/',
                function ($attribute, $value, $fail) {
                    // Définir les formats à vérifier
                    $formats = [
                        'Y-m-d', 'Ymd', 'Y/m/d', 'm-d-Y', 'm/d/Y', 'd-m-Y', 'd/m/Y'
                    ];
                    $isValid = false;
                    foreach ($formats as $format) {
                        $parsedDate = \DateTime::createFromFormat($format, $value);
                        if ($parsedDate && $parsedDate->format($format) === $value) {
                            // Vérification de la validité de la date
                            $isValid = true;
                            // Vérification des jours dans le mois
                            $year = (int)$parsedDate->format('Y');
                            $month = (int)$parsedDate->format('m');
                            $day = (int)$parsedDate->format('d');
                            if (!checkdate($month, $day, $year)) {
                                $isValid = false;
                            }
                            break;
                        }
                    }
                    if (!$isValid) {
                        $fail('Le format de la date n\'est pas valide ou la date est incorrecte.');
                    }
                },
            ],
            'heure_depart' => 'required',
        ];

        $validation = [];
        $olana = [];
        $i =1;
        foreach ($donnee as $ligne)
        {
            $validateur = Validator::make($ligne,$regle);
            if($validateur->fails())
            {
                $erreur =  $validateur->errors()->all();
                foreach ($erreur as $error) {
                    $validation[]=$error .' (Ligne '.$i.')';
                }
            }
            $i++;
        }

        if (count($validation)>0){
            return view('Import/Import-resultat',[
                'colonne' => $donnee,
                'validation' => $validation
            ]);
        }else{
            try {
//                id     |      |  |  |  |
                foreach ($donnee as $ligne) {
                    DB::table('etape')->insert([
                        'nom' => $ligne['etape'],
                        'longueur' =>str_replace(',', '.', $ligne['longueur']),
                        'nb_coureur' => $ligne['nb_coureur'],
                        'rang_etape' => $ligne['rang'],
                        'time_debut' => $ligne['date_depart'].' '.$ligne['heure_depart']
                    ]);
                }
//
            }catch (Exception $e){
                $olana[] = $e->getMessage();
            }
        }

        $donnee_resultat = Excel::toArray(new Import(),$request->file('resultat'))[0];

        $regle2 = [
            'etape_rang' => 'required|integer',
            'numero_dossard' => 'required|integer',
            'nom' => 'required|string|max:255',
            'genre' => 'required', //|in:M,F
            'date_naissance' => 'required|date_format:d/m/Y',
            'equipe' => 'required|string|max:1',
            'arrivee' => 'required|date_format:d/m/Y H:i:s',
        ];

        $i=1;

        foreach ($donnee_resultat as $ligne)
        {
            $validateur = Validator::make($ligne,$regle2);
            if($validateur->fails())
            {
                $erreur =  $validateur->errors()->all();
                foreach ($erreur as $error) {
                    $validation[]=$error .' (Ligne '.$i.')';
                }
            }else{
                try {
                    DB::table('resultat')->insert([
                        'etape_rang' => $ligne['etape_rang'],
                        'numero_dossard' => $ligne['numero_dossard'],
                        'nom' => $ligne['nom'],
                        'genre' => $ligne['genre'],
                        'date_naissance' => $ligne['date_naissance'],
                        'equipe' => $ligne['equipe'],
                        'arrivee' => $ligne['arrivee'],

                    ]);
                }catch (Exception $e){
                    $olana[] = $e->getMessage();
                }

            }
            $i++;
        }

//   insert equipe
        try {
            $equipe =DB::select('select distinct equipe from resultat');
            foreach ($equipe as $nom){
                DB::table('equipe')->insert([
                    'nom' => $nom->equipe,
                    'mail' => $nom->equipe,
                    'pwd' => Hash::make($nom->equipe),
                ]);
            }
        }catch (Exception $e){
            $olana[] = $e->getMessage();
        }

//        insert coureur
        try {
            DB::select ('INSERT INTO coureur (nom, numero_dossard, genre, date_naissance, id_equipe)
                                    select distinct r.nom,numero_dossard, genre,date_naissance,e.id
                                    from resultat r
                                    join equipe e on r.equipe = e.nom');
        }catch (Exception $e){

            $olana[] =$e->getMessage();
        }

//        insert etape_coureur
        try {
            DB::insert("INSERT INTO etape_coureur (id_coureur,id_etape)
                        SELECT c.id id_coureur,e.id id_etape
                        FROM resultat r
                            JOIN coureur c on r.nom = c.nom
                            JOIN etape e on r.etape_rang = e.rang_etape");
        }catch (Exception $e){
            $olana[] =$e->getMessage();
        }

//        insert temps arriver
        try {
            DB::insert("INSERT INTO temp_coureur_etape(id_etape,id_coureur,time_debut,time_fin)
                        select e.id id_etape,c.id id_coureur,e.time_debut,r.arrivee
                        FROM resultat r
                                 JOIN coureur c on r.nom = c.nom
                                 JOIN etape e on r.etape_rang = e.rang_etape");
        }catch (Exception $e){
            $olana[] =$e->getMessage();
        }

        return view('Import/Import-resultat',[
            'olana' => $olana,
            'validation' => $validation
        ])->with('succes_etape','Etape inserer avec succès');
    }

    public function importPoint(Request $request)
    {
        $donnee= Excel::toArray(new Import(),$request->file('point'))[0];

        $regle =[
            'classement' => 'required|numeric',
            'points' =>'required|numeric'
        ];

        $validation = [];
        $olana = [];
        $i =1;
        foreach ($donnee as $ligne)
        {
            $validateur = Validator::make($ligne,$regle);
            if($validateur->fails())
            {
                $erreur =  $validateur->errors()->all();
                foreach ($erreur as $error) {
                    $validation[]=$error .' (Ligne '.$i.')';
                }
            }else{
                try {
                    DB::table('point')->insert([
                        'pt' => $ligne['points'],
                        'rang' =>$ligne['classement'],
                    ]);
                }catch (Exception $e){
                    $olana[] = $e->getMessage();
                }
            }
            $i++;
        }

        return view('Import/Import-point',[
            'olana' => $olana,
            'validation' => $validation
        ])->with('succes_point','Etape inserer avec succès');
    }
}
