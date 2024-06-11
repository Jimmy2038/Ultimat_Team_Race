@extends('LayoutEquipe')

@section('titre')
    Accueil Equipe
@endsection

@section('contenu')
    <h2 class="mb-2 page-title">Liste des étapes</h2>

    @foreach($equipes as $nomEquipe => $etapesParId)
        <h5>Equipe {{ $nomEquipe }}</h5>
        @foreach($etapesParId as $idEtape => $etapes)
            @foreach($etapes as $nomEtape => $coureurs)

                <div class="card shadow">
                    <div class="card-body">
                        <h6> {{ $nomEtape }} :
                            ({{ $coureurs[0]['longueur'] }} Km)
                            {{ count($coureurs) }}-coureur{{ count($coureurs) > 1 ? 's' : '' }}
                        </h6>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Chrono</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($coureurs as $coureur)

                                <tr>
                                    <td>{{ $coureur['nom'] }}</td>
                                    <td>{{ $coureur['chrono'] }}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div>
                            <a href="{{ url('equipe/ajoutCoureur/'.$idEtape) }}"><button class="btn btn-primary">Ajouter des coureurs</button></a>
                        </div>

                        <style>
                            svg{
                                width: 40px;
                            }
                        </style>
                    </div>
                </div>
                <br>
                <br>
            @endforeach
        @endforeach
    @endforeach

{{--            <div class="d-flex justify-content-center">{{ $coureurs->onEachSide(1)->links('pagination::bootstrap-4') }}</div>--}}
@endsection
