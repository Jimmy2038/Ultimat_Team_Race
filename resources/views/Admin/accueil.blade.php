@extends('Layout')

@section('titre')
    Accueil admin
@endsection

@section('contenu')
    <h2 class="mb-2 page-title">Liste des étapes</h2>
    <div class="card shadow">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        {{--                    id | nom | longueur | nb_coureur | rang_etape--}}
                        <th>Nom</th>
                        <th>Longueur</th>
                        <th>Nombres de coureur</th>
                        <th>Rang d' étape</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($etapes as $row)
                        <tr>
                            <td>{{$row->nom}}</td>
                            <td>{{$row->longueur}}</td>
                            <td>{{$row->nb_coureur}}</td>
                            <td>{{$row->rang_etape}}</td>
                            <td>
                                <a href="{{ url('admin/ajoutTempCoureur/'.$row->id) }}"><button class="btn btn-primary">Ajouter temps des coureurs</button></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>


                </table>
                <style>
                    svg{
                        width: 40px;
                    }
                </style>
                <div class="d-flex justify-content-center">{{ $etapes->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            </div>


    </div>

@endsection
