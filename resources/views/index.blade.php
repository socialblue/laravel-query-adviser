@php
    function vite_vendor($asset, $vendor)
    {
        $path = public_path($vendor . '/build/manifest.json');
        if (!file_exists($path)) {
            return $vendor . '/build/' . $asset;
        }

        $manifest = json_decode(file_get_contents($path), true);
        return $vendor . '/build/' . $manifest[$asset]['file'];
    }
@endphp
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="icon" href="/vendor/socialblue/laravel-query-adviser/images/favicon.svg" type="image/svg+xml">
        <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet'>
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
        <title>Laravel Query Adviser</title>
        <link href="{{ vite_vendor('resources/app/app.css', '/vendor/socialblue/laravel-query-adviser') }}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <section id="app">
            <view-layout />
        </section>
        <script src="{{ vite_vendor('resources/app/app.js', '/vendor/socialblue/laravel-query-adviser') }}"></script>
        <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    </body>
</html>
