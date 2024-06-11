@extends('Layout')

@section('titre')
  Detail Classement par equipe
@endsection

@section('contenu')
    <h2 class="mb-2 page-title">Detail classement par equipe</h2>
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>

                    <th>Nom Etape</th>
                    <th>Points</th>
{{--                    <th>Temps</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($classement as $row)
                    <tr>
                        <td>{{$row->nom_etape}}</td>
{{--                        <td>{{$row->nom_coureur}}</td>--}}
{{--                        <td>{{$row->numero_dossard}}</td>--}}
{{--                        <td>{{$row->genre}}</td>--}}
{{--                        <td>{{$row->nom_equipe}}</td>--}}
                        <td>{{$row->pt}}</td>
{{--                        <td>{{$row->temps_passe}}</td>--}}
                    </tr>
                @endforeach
                </tbody>


            </table>
            <style>
                svg{
                    width: 40px;
                }
            </style>
{{--                        <div class="d-flex justify-content-center">{{ $classement->links('pagination::bootstrap-4') }}--}}
        </div>
        <a href="{{ url('classement/admin/classementParEquipe') }}">
            <button class="btn btn-primary">Retour</button>
        </a>
    </div>
    </div>



@endsection
