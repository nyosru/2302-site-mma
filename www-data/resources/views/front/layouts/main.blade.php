<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

    <link rel="icon" type="images/png" href="{{ url('/images/favicon.png') }}">
    <meta itemprop="image" content="{{ url('/images/favicon.png') }}" />
    <meta property="og:image" content="{{ url('/images/favicon.png') }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ url('/images/favicon.png') }}">
    <meta name="twitter:image" content="{{ url('/images/favicon.png') }}" />

    <meta name="twitter:card" content="website" />
    <meta name="twitter:site" content="Monte Kristcorporate" />

    <meta itemprop="isFamilyFriendly" content="True" />

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Monte Kristcorporate">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <link rel="stylesheet" href="{{ mix('/css/front.css') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    @yield('meta')

</head>
<body>
    <div class="main-wrapper">
        @include('front.layouts.parts.header')

        @yield('crumbs')

        @yield('content')

        @include('front.layouts.parts.footer')
    </div>

    <script src="{{ mix('/js/front.js') }}"></script>

    @yield('scripts')
    </body>
</html>
