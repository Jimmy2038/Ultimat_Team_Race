@extends('Layout')

@section('titre')
    Generation catégorie
@endsection

@section('contenu')
    @if(isset($succes))
        <div class="alert alert-success col-3">
            {{ $succes }}
        </div>
    @endif
    <form action="{{ url('admin/generertCategorie') }}" method="get">
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Generer Tous les categories</button>
        </div>
    </form>
@endsection
