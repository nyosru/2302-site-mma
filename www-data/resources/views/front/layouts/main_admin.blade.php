<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="images/png" href="{{ url('/favicon.png') }}">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    @routes
</head>
<body class="skin-blue sidebar-mini">

<div id="app" class="wrapper">
    @yield('content')
</div>

<script>
    var appSettings = {
        'google_token': '{{env('GOOGLE_MAP_API_KEY')}}',
    };

</script>

<!-- Scripts -->
<script src="{{ asset('js/admin.js') }}"></script>

</body>
</html>
