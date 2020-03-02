<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <title>Laravel Query Adviser</title>
        <link href="{{ asset(mix('/app.css', 'vendor/socialblue/laravel-query-adviser')) }}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <section id="app">
            <app></app>
        </section>
        <script src="{{asset(mix('/app.js', 'vendor/socialblue/laravel-query-adviser'))}}"></script>
        <style>
            * {
                font-family: 'Ubuntu', sans-serif;
            }

            .icon-expand::before {
                font-family: 'Material Icons';
                content: "A";
            }

            .query-group {
                font-size: 24px;
                font-weight: bold;
                padding: 10px 4px 4px 10px;
                background: rgba(128,128,128, 0.4);
                border: 1px solid rgba(128, 128, 128, 0.6);
                position: relative;
                clear: both;
            }

            .query {
                position: relative;
                clear: both;
                width: 90%;
                border: 1px solid rgba(128, 128, 128, 0.6);
                font-size: 16px;
                margin: 4px;
            }

            .query .text {
                position: relative;
                width: calc(95% - 100px);
                max-height: 100px;
                padding: 10px 4px 4px 10px;
                overflow-y: scroll;
            }

            .query .btn {
                position: relative;
                margin: 10px;
                width: 80px;
                float: right;
                left: 5px;
                right: 5px;
                font-size: 10px;
                text-decoration: underline;
                text-transform: uppercase;
            }
        </style>
    </body>
</html>