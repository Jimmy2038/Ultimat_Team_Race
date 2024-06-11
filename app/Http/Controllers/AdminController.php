<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    //

    public function goToCategorie()
    {
        return view('Admin/generate-categorie');
    }

    public function genererCategorie()
    {
        $age_coureur = DB::table('v_age_coureur')->get();
        foreach ($age_coureur as $row){

            $coureur = DB::table('categorie_coureur')
                    ->where('id_coureur', $row->id_coureur)
                    ->first();
            $categorie = 'junior';

            if($row->age > 18){
                $categorie = 'senior';
            }

            if ($coureur!=null){
                DB::table('categorie_coureur')
                    ->where('id_coureur',$row->id_coureur)
                    ->update([
                        'categorie' =>$categorie
                    ]);
            }else{
                DB::table('categorie_coureur')->insert([
                    'id_coureur' => $row->id_coureur,
                    'categorie' => $categorie,
                    'age' => $row->age
                ]);
            }
        }
        return view('Admin/generate-categorie',[
            'succes'=> 'Catégorie generer avec succès'
        ]);
    }

    public function insertTempCoureur(Request $request)
    {
         $request->validate([
            'coureur' => 'required',
//            'penalite'  => 'required|numeric',
             'daty' => 'required',
             'heure'=> 'required|numeric|between:0,23',
             'minute' => 'required|numeric|between:0,59',
             'seconde' => 'required|numeric|between:0,59',
        ]);

        $idEtape = $request->input('idEtape');
        $coureur = $request->input('coureur');
//        $penalite = $request->input('penalite');
        $daty_arrive = $request->input('daty');
        $heure = $request->input('heure');
        $min = $request->input('minute');
        $sec = $request->input('seconde');

        $etape = DB::table('etape')
            ->where('id',$idEtape)
            ->first();

        $date_depart = $etape->time_debut;
        $date_arrive = Carbon::createFromFormat('Y-m-d', $daty_arrive);

        $date_arrive->setTime($heure, $min, $sec);


        try {
             DB::table('temp_coureur_etape')->insert([
                'id_etape'=> $idEtape,
                'id_coureur'=> $coureur,
//                'penalite'=> $penalite,
                'time_debut'=> $date_depart,
                'time_fin'=>$date_arrive,
            ]);

            return redirect('/admin/ajoutTempCoureur/'.$idEtape)->with('message','Temps_coureur inserer avec succes');
        } catch (\Exception $e) {
            return back()->with('err',$e->getMessage());
        }
    }

    public function goToAjoutTempCoureur($idEtape)
    {
        $coureurs = DB::select("select c.id,c.nom,c.numero_dossard,c.genre,c.date_naissance,c.id_equipe from etape_coureur ec join coureur c on ec.id_coureur = c.id where ec.id_etape='".$idEtape."'");

        return view('Admin/ajout-temp-coureur',[
            'idEtape' =>$idEtape,
            'coureurs' => $coureurs,
        ]);
    }

    public function goToAcceuil()
    {
        $etapes = DB::table('etape')->orderBy('rang_etape')->paginate(10);
        return view('Admin.accueil',[
            'etapes' => $etapes,
        ]);
    }
    public function truncate() {
        $tables = Schema::getAllTables();
        DB::statement('SET session_replication_role = replica');

        DB::beginTransaction();
        try {
            foreach ($tables as $table) {
                $tableName = $table->tablename;
                DB::table($tableName)->truncate();

                // Assuming the sequence name follows the pattern 'table_name_id_seq'
                $sequenceName = $tableName . '_seq';

                // Check if the sequence exists
                $sequenceExists = DB::select("SELECT 1 FROM pg_class WHERE relkind = 'S' AND relname = ?", [$sequenceName]);

                if (!empty($sequenceExists)) {
                    DB::statement('ALTER SEQUENCE ' . $sequenceName . ' RESTART WITH 1');
                }
            }

            DB::commit();

            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        } finally {
            DB::statement('SET session_replication_role = DEFAULT');

            // Assuming you need to reset the sequences for the user table as well
            $user = User::create([
                'pseudo' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin'),
                'role' => 'admin'
            ]);
        }
    }

}
