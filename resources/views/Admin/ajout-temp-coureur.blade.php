@extends('Layout')

@section('titre')
    Accueil admin
@endsection

@section('contenu')
    <style>
        .w-60px {
            width: 60px !important;
        }
    </style>
    <h2 class="mb-2 page-title">Ajout Temps Coureur(s)</h2>
    <div class="card shadow col-9 m-auto" >
        <div class="card-body">
            @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            <form action="{{ url('admin/insertTempCoureur') }}" method="POST">
                @csrf

                <input type="hidden" name="idEtape" value="{{ $idEtape }}">
                @for($i = 0; $i< 1 ; $i++)
                    <div class="form-group">
                        <label for="designation">Choix coureur</label>

                        <select class="form-control @error('coureur') is-invalid @enderror" name="coureur">
                            <option value="">Choisier un coureur</option>
                            @foreach($coureurs as $row)
                                <option value="{{$row->id}}">{{$row->nom}}</option>
                            @endforeach
                        </select>
                        @error('coureur')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>
                    <div class="form-group">
                        <label for="description">Temps d'arivée</label>
                        <div class="form-inline">
                            <div class="form-group col-3">
                                <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Date</label>
                                <input type="date" class="form-control mb-2 mr-sm-2 @error('daty') is-invalid @enderror"  name="daty">
                                @error('daty')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-2">
                                <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Heure</label>
                                <input type="text" class="form-control mb-2 mr-sm-2  w-60px @error('heure') is-invalid @enderror"   name="heure">
                                @error('heure')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-2">
                                <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Minute</label>
                                <input type="text" class="form-control mb-2 mr-sm-2 w-60px @error('minute') is-invalid @enderror"  name="minute">
                                @error('minute')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group col-2">
                                <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Seconde</label>
                                <input type="text" name="seconde" class="form-control mb-2 mr-sm-2 w-60px @error('seconde') is-invalid @enderror"  >
                                @error('seconde')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if(session('err'))
                            <div class="invalid-feedback">{{ session('err') }}</div>
                        @endif
                    </div>
{{--                    <div class="form-group">--}}
{{--                        <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Pénalité</label>--}}
{{--                        <input type="text" class="form-control mb-2 mr-sm-2 @error('penalite') is-invalid @enderror" name="penalite">--}}
{{--                        @error('penalite')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
                @endfor
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
@endsection
