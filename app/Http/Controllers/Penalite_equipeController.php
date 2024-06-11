<?php

namespace App\Http\Controllers;

use App\Models\Penalite_equipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class Penalite_equipeController extends Controller
{

    public function insert()
    {
        $penalite_equipe = new Penalite_equipe();
        $penalite_equipes = DB::table('penalite_equipe')->where('etat','valide')->get();
        $equipe=DB::select("SELECT * FROM equipe");
        $etape=DB::select("SELECT * FROM etape");
        return view('penalite_equipe.penalite_equipe', [
            'penalite_equipes' => $penalite_equipes,
            'equipes' => $equipe,'etapes' => $etape,
        ]);
    }

    public function create(Request $request)
    {
        $data = Validator::make($request->all(),[
            'penalite' => [
                'required',
                'regex:/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/'
            ],

        ]);


        if ($data->fails()){
            Session::flash('ajouter','ajout');
            return redirect()->route('penalite_equipe.ressource')->withErrors($data->errors());
        }

        $penalite_equipe = new Penalite_equipe();
        $penalite_equipe->insert($request);

        $id_coureur = DB::table('v_id_coureur')
            ->where('id_etape',$request->input('id_etape'))
            ->where('id_equipe', $request->input('id_equipe'))
            ->get();

        $penalite_total_info = DB::table('v_penalite_equipe')
            ->where('id_etape',$request->input('id_etape'))
            ->where('id_equipe', $request->input('id_equipe'))
            ->first();

        foreach ($id_coureur as $coureur){
            DB::update("update temp_coureur_etape set penalite='".$penalite_total_info->penalite_total."' where id_etape='".$request->input('id_etape')."' and id_coureur='".$coureur->id_coureur."'");
        }

        return redirect()->route('penalite_equipe.ressource')->with('success', 'penalite_equipe créé avec succès!');
    }

    public function modifier(Request $request)
    {
        $id = $request->input('idpenalite_equipe');// afaka asina an'io ao arinan'ny idpenalite_equipe
//        dd($id);
        DB::select("UPDATE penalite_equipe set etat = 'supprime' where id='".$id."'");
        $penalite_coureur = DB::table('penalite_equipe')->where('id',$id)->first();

        $id_coureur = DB::table('v_id_coureur')
            ->where('id_etape',$penalite_coureur->id_etape)
            ->where('id_equipe', $penalite_coureur->id_equipe)
            ->get();

        $penalite_total_info = DB::table('v_penalite_equipe')
            ->where('id_etape',$penalite_coureur->id_etape)
            ->where('id_equipe', $penalite_coureur->id_equipe)
            ->first();

        foreach ($id_coureur as $coureur){
            if ($penalite_total_info==null){
                DB::update("update temp_coureur_etape set penalite='00:00:00' where id_etape='".$penalite_coureur->id_etape."' and id_coureur='".$coureur->id_coureur."'");
            }else{
                DB::update("update temp_coureur_etape set penalite='".$penalite_total_info->penalite_total."' where id_etape='".$penalite_coureur->id_etape."' and id_coureur='".$coureur->id_coureur."'");
            }
        }


        return redirect()->route('penalite_equipe.ressource')->with('success', 'penalite_equipe supprimée avec succès!');
    }
    public function destroy($id)
    {
        try {
            DB::table('penalite_equipe')
                ->where('id', $id)// afaka asina an'io ao arinan'ny idpenalite_equipe
                ->delete();

            return redirect()->route('penalite_equipe.ressource')->with('success', 'penalite_equipe supprimé avec succès!');
        } catch (\Exception $e) {
            // Gestion de l'erreur ici
            return redirect()->route('penalite_equipe.ressource')->with('error', 'Une erreur est survenue lors de la suppression du penalite_equipe.');
        }
    }


}
