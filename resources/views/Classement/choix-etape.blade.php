@extends('LayoutEquipe')

@section('titre')
    Liste Etape
@endsection

@section('contenu')
    <h2 class="mb-2 page-title">Liste des etapes</h2>
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-striped">

                <thead>
                <tr>
                    <th>Nom Etape</th>
                    <th>Longueur</th>
                    <th>Nombre de coureur</th>
                    <th>Numero etape</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($etape as $row)
                    {{--                         id     |     nom      | longueur | nb_coureur | rang_etape |     time_debut--}}
                    <tr>
                        <td>{{$row->nom}}</td>
                        <td>{{$row->longueur}}</td>
                        <td>{{$row->nb_coureur}}</td>
                        <td>{{$row->rang_etape}}</td>
                        <td>
                            <form action="{{ url('classement/equipe/coureurEtape') }}" method="get">

                                <input type="hidden" name="etape" value="{{$row->id}}">
                                <input type="hidden" name="categorie">
                                <input type="hidden" name="genre">

                                <button type="submit" class="btn btn-primary">
                                    Voir classement
                                </button>

                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>


            </table>

            {{--            <div class="d-flex justify-content-center">{{ $etapes->onEachSide(1)->links('pagination::bootstrap-4') }}--}}
        </div>
    </div>
    </div>

@endsection
