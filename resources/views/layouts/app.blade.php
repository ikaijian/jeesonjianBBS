<!doctype html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="crsf-token" content="{{csrf_token()}}">
    <title>@yield('title','jeesonBBS')-{{ setting('site_name', 'jianBBS论坛') }}</title>
    <meta name="description" content="@yield('description', setting('seo_description', 'jianBBS论坛 爱好者社区。'))" />
    <meta name="keyword" content="@yield('keyword', setting('seo_keyword', 'jian论坛,社区,论坛,开发者论坛'))" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    @yield('styles')

</head>
<body>
<div id="app" class="{{ route_class() }}-page">
    @include('layouts._header')

    <div class="container">
        @include('layouts._message')
        @yield('content')

    </div>

    @include('layouts._footer')
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
</body>
</html>