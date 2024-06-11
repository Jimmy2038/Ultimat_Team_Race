@extends('Layout');

@section('titre')
     Import Etape & Resultat
@endsection

@section('contenu')


    @if(isset($validation))
        @foreach($validation as $erreur)
            <span class="text-danger">{{$erreur}}</span>
            <br>
        @endforeach
    @endif

    <h2 class="mb-2 page-title">Import etape et résultat</h2>
    @if(isset($validation)&&$validation==null)
        <div class="alert alert-success"> Importées </div>
    @endif
    <form action="{{ url('import/importEtapeResultat') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="custom-file">
            <div class="form-group mb-3">

                <label for="customFile">Import étape</label>
                <div class="custom-file">
                    <input type="file" name="etape" class="custom-file-input" id="customFile" onchange="updateFileNameLabel(this)">
                    <label class="custom-file-label" id="maison" for="customFile">Choose file</label>
                </div>

                <label for="customFile">Import résultat</label>
                <div class="custom-file">
                    <input type="file" name="resultat" class="custom-file-input" id="customFile" onchange="updateFileName(this)">
                    <label class="custom-file-label" id="devis" for="customFile">Choose file</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn mb-2 btn-primary">Importer</button>
            </div>
        </div>
    </form>
    {{--@if(isset($colonne))--}}
    {{--    @foreach($colonne as $col)--}}
    {{--    {{$col['ref_devis']}}--}}
    {{--    {{$col['ref_paiement']}}--}}
    {{--    {{$col['date_paiement']}}--}}
    {{--    {{$col['montant'] }}--}}
    {{--        <br>--}}
    {{--    @endforeach--}}
    {{--@endif--}}

    <script>
        function updateFileNameLabel(input) {
            var fileNameLabel = document.getElementById("maison");
            if (input.files.length > 0) {
                fileNameLabel.textContent = input.files[0].name;
            } else {
                fileNameLabel.textContent = "Choose file";
            }
        }
        function updateFileName(input) {
            var fileName = document.getElementById("devis");
            if (input.files.length > 0) {
                fileName.textContent = input.files[0].name;
            } else {
                fileName.textContent = "Choose file";
            }
        }
    </script>
@endsection
