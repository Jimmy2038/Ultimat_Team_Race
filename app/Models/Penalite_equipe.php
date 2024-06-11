<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Penalite_equipe extends Model
{
    use HasFactory;
    protected $table = 'penalite_equipe';
    protected $keyType = 'string';

    public function getList(){
        $result = DB::select("SELECT * FROM penalite_equipe");
        return $result;
    }

    public function insert($data){
        $equipe = DB::table('equipe')->where('id',$data['id_equipe'])->first();
        $etape = DB::table('etape')->where('id',$data['id_etape'])->first();
        DB::table('penalite_equipe')->insert([
            'id_equipe' => $data['id_equipe'],
            'nom_equipe' => $equipe->nom,
            'id_etape' => $data['id_etape'],
            'nom_etape' => $etape->nom,
            'penalite' => $data['penalite'],
            'etat' => 'valide',

        ]);
    }

    public function modifier($id, $data){
        DB::table('penalite_equipe')
            ->where('id', $id)// asina penalite_equipe ao arinan'ny id ra misy nomVariable ao arinan'le id @ base
            ->update([
                'equipe' => $data['equipemodal'],
'etape' => $data['etapemodal'],
'penalite' => $data['penalitemodal'],

            ]);
    }


}
