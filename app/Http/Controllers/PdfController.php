<?php

namespace App\Http\Controllers;

use App\Models\Finition;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PdfController extends Controller
{
    public function goTocertificat_tsyalefa()
    {
          $titre ="premierPdf";
        /*
        $finition = Finition::all();
        $data = [
            'finition' => $finition
        ]; */
//        $pdf=Pdf::loadView('Template.certificat',['jim'=>'jim']);
//        return $pdf->download($titre.'.pdf');
//
        $classement_rehetra = Session::get('classement');

        $date = Carbon::now();
        $date = $date->translatedFormat('j F, Y');
        $pdf=Pdf::loadView('Template.certificat',[
            'equipe_vainceur'=>$classement_rehetra[0],
            'categorie' => Session::get('categorie'),
            'date' => $date
            ]);

        return $pdf->download($titre.'.pdf');
       /* return view('Template/certificat',[
            'equipe_vainceur'=>$classement_rehetra[0],
            'categorie' => Session::get('categorie'),
            'date' => $date
            ]);*/
    }

    public function goTocertificat()
    {
        $classement_rehetra = Session::get('classement');
        $date = Carbon::now()->translatedFormat('j F, Y');
        $zipFileName = 'pdf_files.zip';


        $zip = new ZipArchive;
        if ($zip->open(storage_path($zipFileName), ZipArchive::CREATE) === TRUE)
        {

            foreach ($classement_rehetra as $equipe)
            {
                if ($equipe->rang == 1){

                    $equipeNom = str_replace(' ', '_', $equipe->nom_equipe);
                    $pdfFileName = $equipeNom . '.pdf';

                    $pdf = PDF::loadView('Template.certificat', [
                        'equipe_vainceur' => $equipe,
                        'categorie' => Session::get('categorie'),
                        'date' => $date
                    ]);


                    $pdfPath = storage_path('app/public/temp_' . $pdfFileName);
                    Storage::put('public/temp_' . $pdfFileName, $pdf->output());


                    $zip->addFile($pdfPath, $pdfFileName);
                }

            }


            $zip->close();
        }


        return response()->download(storage_path($zipFileName))->deleteFileAfterSend(true);
    }
}
