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
        <title>Laravel Query Adviser</title>
        <link href="{{ asset(mix('/css/app.css', 'vendor/socialblue/laravel-query-adviser')) }}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <section id="app">
            <view-layout />
        </section>
        <script src="{{ asset(mix('/js/app.js', 'vendor/socialblue/laravel-query-adviser')) }}"></script>
        <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    </body>
</html>
