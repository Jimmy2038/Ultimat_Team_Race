@extends('LayoutEquipe')

@section('titre')
    Classement Par Equipe
@endsection

@section('contenu')
    <h2 class="mb-2 page-title">Classement par équipe</h2>
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-striped">

                <form action="{{url('classement/equipe/classementParEquipe')}}" method="GET">
                    @csrf
                    <div class="form-inline">
                        <div class="form-group col-3">

                        </div>
                        <div class="form-group col-3">

                            <select class="form-control" name="categorie" id="categorieSelect">
                                <option value="">Choisisser un categorie</option>
                                @foreach($categories as $row)
                                    <option value="{{$row->categorie}}">{{$row->categorie}}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group col-3">

                            <select class="form-control" name="genre" id="genreSelect">
                                <option value="">Choisisser un genre</option>
                                <option value="F">Femme</option>
                                <option value="M">Homme</option>
                            </select>

                        </div>

                        <div class="form-group col-3">
                            <button id="valider" class="btn btn-success">Trier</button>
                        </div>
                    </div>
                </form>
                <br>
                <thead>
                <tr>
                    <th>Rang</th>
                    <th>Nom Equipe</th>
{{--                    <th>Temps</th>--}}
                    <th>Points</th>
                </tr>
                </thead>
                <tbody>
                @foreach($classement as $row)
                    <tr>
                        <td>{{$row->rang}}</td>
                        <td>{{$row->nom_equipe}}</td>
                        <td>{{$row-> total_points}}</td>
                    </tr>
                @endforeach
                </tbody>


            </table>
            <style>
                svg{
                    width: 40px;
                }
            </style>
            {{--            <div class="d-flex justify-content-center">{{ $etapes->onEachSide(1)->links('pagination::bootstrap-4') }}--}}
        </div>
    </div>
    </div>



@endsection
