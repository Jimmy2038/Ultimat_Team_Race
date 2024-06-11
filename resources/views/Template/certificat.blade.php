<html>
<head>
    <style type='text/css'>
        @page  {
            size: A4 landscape;
        }
        body, html {
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }
        body {
            color: #000;
            text-align: center;
            margin-top: 50px;
        }
        .container {
            border: 20px solid #7ebe40;
            width: 800px;
            height: 450px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 10px;
        }
        .logo {
            color: #776c6c;
            font-size: 36px;
            margin-bottom: 20px;
        }
        .marquee {
            color: #d2b48c;
            font-size: 48px;
            margin: 20px 0;
            font-weight: bold;
        }
        .assignment {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .person {
            font-size: 28px;
            font-style: italic;
            margin-bottom: 20px;
        }
        .reason {
            font-size: 20px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="logo">
        ULTIMATE TEAM RACE
    </div>

    <div class="marquee">
        Certificat de course Catégorie <b>{{ $categorie }}</b>
    </div>

    <div class="assignment">
        Ce certificat est avec fierté présenté l'Equipe
    </div>

    <div class="person">
        {{ $equipe_vainceur->nom_equipe }}
    </div>

    <div class="reason">
        A complété la course avec le total de point de<br/>
        {{ $equipe_vainceur->total_points }}
    </div>
    <h3>Le</h3>
    <p>{{$date}}</p>
</div>
</body>
</html>

{{--<h1>Certificat</h1>--}}
{{--<h2>de ULTIMATE TEAM RACE</h2>--}}
{{--<h3>Ce certificat est avec fierté présenté l'Equipe</h3>--}}
{{--<p> {{ $equipe_vainceur->nom_equipe }} <b>Catégorie</b> {{ $categorie }}</p>--}}
{{--<h3>A complété la course avec le total de temps de</h3>--}}
{{--<p>{{ $equipe_vainceur->total_points }}</p>--}}
{{--<h3>Le</h3>--}}
{{--<p>{{$date}}</p>--}}
