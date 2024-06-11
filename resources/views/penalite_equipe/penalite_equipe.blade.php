@extends('Layout')

@section('titre')
    Gestion penalite_equipe
@endsection

@section('contenu')
<h2 class="mb-2 page-title">Gestion penalite_equipe</h2>
{{--     Modale ajout     --}}
<div class="modal fade ajouter"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter penalite_equipe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <form action="{{ url('penalite_equipe/insert') }}" method="POST">
                @csrf
                <div class="modal-body">
                                  <div class="form-group ">
                    <label for="equipe">Choix équipe</label>
                    <select class="form-control" name="id_equipe">
                        @foreach($equipes as $row)
                            <option value="{{$row->id}}">{{$row->nom}}</option>
                        @endforeach
                    </select>
                    @error('id_equipe')
                    <div class="invalid-feedbpack">{{ $message }}</div>
                    @enderror
                </div>
              <div class="form-group ">
                    <label for="etape">Choix étape</label>
                    <select class="form-control" name="id_etape">
                        @foreach($etapes as $row)
                            <option value="{{$row->id}}">{{$row->nom}}</option>
                        @endforeach
                    </select>
                    @error('id_etape')
                    <div class="invalid-feedbpack">{{ $message }}</div>
                    @enderror
                </div>
<div class="form-group">
    <label for="penalite">Penalite</label>
    <input type="text" class="form-control @error('penalite') is-invalid @enderror" id="penalite" name="penalite" placeholder="hh:mm:ss">
    @error('penalite')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                </div>

                <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save </button>
                </div>
            </form>
        </div>
    </div>
</div>
    <hr>

    {{--     Modale modification     --}}

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="editForm" action="{{ url('penalite_equipe/modifier') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="idpenalite_equipe" id="idpenalite_equipe">
                        <b>Vouler vous vraiment effacer cette pénalité?</b>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Oui</button>
                        <button type="button" class="btn btn-success" data-dismiss="modal">Non</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-body">
            <table class="table table-striped">
                <a href="./#" data-toggle="modal" data-target=".ajouter">
                    <button type="button" class="btn mb-2 btn-success" style="float: right;">
                        New <span class="fe fe-16 fe-plus"></span>
                    </button>
                </a>
                <thead>
    <tr>
        <th>idpenalite_equipe</th>
        <th>Equipe</th>
        <th>Etape</th>
        <th>Penalite</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
@foreach($penalite_equipes as $row)
    <tr>
        <td>{{$row->id}}</td>
        <td>{{$row->nom_equipe}}</td>
        <td>{{$row->nom_etape}}</td>
        <td>{{$row->penalite}}</td>
        <td>
            <button class="btn btn-danger"
                    onclick="openModal('{{$row->id}}')"><i class="fe fe-trash-2"></i></button>
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
{{--            <div class="d-flex justify-content-center">{{ $penalite_equipes->onEachSide(1)->links('pagination::bootstrap-4') }}--}}
            </div>
        </div>
    </div>

        <script>
function openModal(idpenalite_equipe) {
    document.getElementById('idpenalite_equipe').value = idpenalite_equipe;
    $('#exampleModal').modal('show');
}
</script>


    @if(session('modifier'))
        <script>
            $(document).ready(function (){
                $('#exampleModal').modal('show');
            });
        </script>
    @endif

    @if(session('ajouter'))
        <script>
            $(document).ready(function (){
                $('.ajouter').modal('show');
            });
        </script>
    @endif
@endsection
