<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EquipeController extends Controller
{
    //
    public function goToLogin()
    {
        return view('Auth.auth-equipe');
    }
    public function goToAcceuil(){
        $etapes = DB::table('etape')->paginate(10);
        $coureurs = DB::table('v_temp_coureur_rank_global')
            ->where('id_equipe', Auth::guard('equipe')->id())
            ->get();

        // Organiser les données par équipe et étape
        $equipes = [];
        foreach ($coureurs as $coureur) {
            $equipes[$coureur->nom_equipe][$coureur->id_etape][$coureur->nom_etape][] = [
                'nom' => $coureur->nom_coureur,
                'chrono' => $coureur->temps_passe,
                'longueur' => $coureur->longueur,
                'nb_coureur' => $coureur->nb_coureur
            ];
        }
        return view('Equipe.accueil',[
            'equipes' => $equipes,
            'coureurs' => $coureurs,
        ]);
    }

    public function goToAjoutCoureur($idEtape)
    {
        $idEquipe = Auth::guard('equipe')->id();
        $coureurs = DB::table('coureur')->where('id_equipe',$idEquipe)->get();
        $etape = DB::table('etape')->where('id', $idEtape)->first();

        $nb_coureur = $etape ? $etape->nb_coureur : 0;  // Assurez-vous que $nb_coureur est bien un entier

        return view('Equipe/ajout-coureur',[
            'idEtape' =>$idEtape,
            'coureurs' => $coureurs,
            'nb_coureur' => $nb_coureur,
        ]);
    }

    public function insertCoureurEtape(Request $request)
    {

        $idEtape = $request->input('idEtape');
        $coureurs = $request->input('coureur');

        $etape_equipe = DB::table('v_equipe')
            ->where('id_etape',$idEtape)
            ->where('id_equipe', Auth::guard('equipe')->id())
            ->get();

        $etape = DB::table('etape')
            ->where('id',$idEtape)
            ->first();
        $coureurs_inserer=[];

        for ($j = 0; $j < count($coureurs); $j++) {
            if ($coureurs[$j]!="#"){
                $coureurs_inserer[] = $coureurs[$j];
            }
        }

        $a_inserer = count($etape_equipe)+count($coureurs_inserer);


        if ($a_inserer > $etape->nb_coureur){
            return redirect()->back()->withErrors(['message' => 'Vous pouvez inserer '.($etape->nb_coureur - count($etape_equipe)).' coureur(s)']);
        }
        try {
            if (is_array($coureurs) ) {
                for ($i = 0; $i < count($coureurs_inserer); $i++) {
                    DB::table('etape_coureur')->insert([
                        'id_etape' => $idEtape,
                        'id_coureur' => $coureurs_inserer[$i]
                    ]);
                }
            } else {
                // Gérez l'erreur si ce ne sont pas des tableaux
                return redirect()->back()->withErrors(['message' => 'Les données de coureurs et de catégories ne sont pas valides.']);
            }
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['message' => ' coureur(s) déjà inserer.']);
        }

        return back()->with('succes','Coureur inserer avec succes');
    }

}
