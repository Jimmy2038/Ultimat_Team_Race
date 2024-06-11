
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('Bika/Running-icon_48x.ico') }}">
    <title>Login</title>
    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="{{ asset('Bika/css/simplebar.css') }}">
    <!-- Fonts CSS -->
    <link
        href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{ asset('Bika/css/feather.css') }}">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{ asset('Bika/css/daterangepicker.css') }}">
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('Bika/css/app-light.css') }}" id="lightTheme" disabled>
    <link rel="stylesheet" href="{{ asset('Bika/css/app-dark.css') }}" id="darkTheme">
</head>

<body class="dark ">
<div class="wrapper vh-100">
    <div class="row align-items-center h-100">
        <form class="col-lg-3 col-md-4 col-10 mx-auto text-center" method="post" action="{{url('Auth/LoginEquipe')}}">
            @csrf
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="#">

                <img src="{{ asset('Bika/Running-icon_64x.png') }}" />

            </a>
            <h1 class="h6 mb-3">Sign in</h1>
            @if (session('error'))
                <span class="fe fe-alert-triangle fe-16 mr-2"></span>
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="form-group">
                <label for="inputEmail" class="sr-only">Email address</label>
                <input type="text" {{--id="inputEmail"--}} name="email" class="form-control form-control-lg"
                       placeholder="Email address" value="A" required="" autofocus="">
                @if ($errors->has('email'))
                    <span style="color: red;">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="inputPassword" name="password" class="form-control form-control-lg"
                       placeholder="Password" value="A" required="">
                @if ($errors->has('password'))
                    <span style="color: red;">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" name="resterConnecter"> Stay logged in </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Let me in</button>
            <br />
            <!-- <a href="/Auth/LoginClient"> Client </a>
            <a href="Auth/Inscription"> S'inscrir </a> -->

            <p class="mt-5 mb-3 text-muted">© 2024</p>
        </form>
    </div>
</div>
<script src="{{ asset('Bika/js/jquery.min.js') }}"></script>
<script src="{{ asset('Bika/js/popper.min.js') }}"></script>
<script src="{{ asset('Bika/js/moment.min.js') }}"></script>
<script src="{{ asset('Bika/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('Bika/js/simplebar.min.js') }}"></script>
<script src='{{ asset('Bika/js/daterangepicker.js') }}'></script>
<script src='{{ asset('Bika/js/jquery.stickOnScroll.js') }}'></script>
<script src="{{ asset('Bika/js/tinycolor-min.js') }}"></script>
<script src="{{ asset('Bika/js/config.js') }}"></script>
<script src="{{ asset('Bika/js/apps.js') }}"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-56159088-1');
</script>
</body>

</html>
