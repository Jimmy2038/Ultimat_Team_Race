@extends('LayoutEquipe')

@section('titre')
    Ajout coureur
@endsection

@section('contenu')
    <h2 class="mb-2 page-title">Ajout Coureur(s)</h2>
    <div class="card shadow col-9 m-auto" >
        <div class="card-body">
            @if(session('succes'))
                <div class="alert alert-success">
                    {{ session('succes') }}
                </div>
            @endif
            @error('message')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror
            <form action="{{ url('equipe/insertCoureurEtape') }}" method="GET">
                @csrf

                <input type="hidden" name="idEtape" value="{{ $idEtape }}">
                @for($i = 0; $i< $nb_coureur ; $i++)
                    <div class="form-group">
                        <label for="designation">Choix coureur</label>
                        <select class="form-control" name="coureur[]">
                            <option value="#" >Choisisser un coureur</option>
                            @foreach($coureurs as $row)
                                <option value="{{$row->id}}">{{$row->nom}}</option>
                            @endforeach
                        </select>
                        @error('coureur')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endfor
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
@endsection
