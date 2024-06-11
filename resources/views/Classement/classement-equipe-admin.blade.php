@extends('Layout')

@section('titre')
    Classement Par Equipe
@endsection

@section('contenu')
    <div class="row">

    </div>
    <h2 class="mb-2 page-title">Classement par équipe</h2>
    <div class="card shadow">
        <div class="card-body">
            <div class="row">
            <div class="col-md-7">
                <table class="table table-striped">
                    <form action="{{url('classement/admin/classementParEquipe')}}" method="GET">
                        @csrf
                        <div class="form-inline">
                            <div class="form-group col-4">
                                <select class="form-control" name="categorie" id="categorieSelect">
                                    <option value="">Choisisser un categorie</option>
                                    @foreach($categories as $row)
                                        <option value="{{$row->categorie}}">{{$row->categorie}}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="form-group col-4">

                                <select class="form-control" name="genre" id="genreSelect">
                                    <option value="">Choisisser un genre</option>
                                    <option value="F">Femme</option>
                                    <option value="M">Homme</option>
                                </select>

                            </div>

                            <div class="row">

                                <div class="form-group ">
                                    <button id="valider" class="btn btn-success">Trier</button>

                                    <button class="btn btn-success ml-1">
                                        <a href="{{ url('pdf/goTocertificat') }}">Exporter en PDF</a>
                                    </button>
                                </div>


                            </div>
                        </div>
                    </form>
                    <br>
                    <thead>
                    <tr>
                        <th>Rang</th>
                        <th>Nom Equipe</th>
                        <th>Points</th>
                        <th>Voir detail</th>
                    </tr>
                    @php
                        $rangCounts = [];
                        foreach ($classement as $globals) {
                            if (!isset($rangCounts[$globals->rang])) {
                                $rangCounts[$globals->rang] = 0;
                            }
                            $rangCounts[$globals->rang]++;
                        }

                    @endphp
                    </thead>
                    <tbody>
                    @foreach($classement as $globals)
                        @php
                            $textColor = 'white';
                            if ($rangCounts[$globals->rang] > 1) {
                                $textColor = 'yellow';
                            }
                        @endphp
                        <tr>
                            <td style=" color: {{ $textColor }}">{{ $globals->rang }}</td>
                            <td style=" color: {{ $textColor }}">{{ $globals->nom_equipe }}</td>
                            <td style=" color: {{ $textColor }}">{{ $globals->total_points }}</td>
                            <td>
                                <a href="{{ url('classement/admin/getDetailClassement/'.$globals->id_equipe) }}">
                                    <button class="btn btn-primary"> Voir detail</button>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>


                </table>
            </div>
            <div class="col-md-5">

                <div id="chart"></div>
            </div>
            </div>

                {{--            <div class="d-flex justify-content-center">{{ $etapes->onEachSide(1)->links('pagination::bootstrap-4') }}--}}
        </div>

        <script src="{{ asset('apexcharts/dist/apexcharts.js') }}"></script>
            @php
                $classement = json_encode($classement);
            @endphp
        <script>
                var donnee = {!! $classement !!};

                var options = {
                    series: donnee.map(item => item.total_points),
                    chart: {
                        type: 'pie',
                        height: 350,
                        foreColor: '#adb5bd',
                    },
                    labels: donnee.map(item => item.nom_equipe),
                    stroke: {
                        show: false
                    },
                    responsive: [
                        {
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 200
                                }
                            }
                        }
                    ],
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " points";
                            }
                        }
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            </script>
    </div>


    {{--                    <tbody>--}}
    {{--                    --}}
    {{--                    @foreach($classement as $row)--}}
    {{--                        --}}
    {{--                        <tr>--}}
    {{--                            <td>{{$row->rang}}</td>--}}
    {{--                            <td>{{$row->nom_equipe}}</td>--}}
    {{--                            <td>{{$row->total_points}}</td>--}}

    {{--                        </tr>--}}
    {{--                    @endforeach--}}
@endsection
