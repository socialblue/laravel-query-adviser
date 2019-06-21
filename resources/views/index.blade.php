<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
    <title>Laravel Query Adviser</title>
    <link href="{{ asset(mix('/app.css', 'vendor/socialblue/laravel-query-adviser')) }}" rel="stylesheet" type="text/css">
</head>
<body>
    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Laravel Query Adviser
                </h1>
                <h2 class="subtitle">
                    Queries logged by application
                </h2>
            </div>
        </div>
    </section>
    <nav class="level">
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Queries</p>
                <p class="title">3,456</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Routes</p>
                <p class="title">123</p>
            </div>
        </div>
    </nav>


    <section id="app">
        <div>
            @foreach($queries as $time => $querys)
                <div class="query-group">
                    {{date("Y-m-d H:i:s", $time)}} ({{count($querys)}})
                </div>

                @if(is_array($querys[0]))
                    @foreach($querys as $key => $query)
                        <query-block
                                sql="{{Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper::combineQueryAndBindings($query['sql'] ?? $query[0], $query['bindings'] ?? $query[1])}}"
                                :time-key="{{(int)$key}}"
                                :time="{{(int)$time}}"
                                route="{{$query['url']}}"
                        >
                        </query-block>

                        <query-explain v-if="true" :time-key="{{(int)$key}}" :time="{{(int)$time}}"></query-explain>
                    @endforeach
                @endif

            @endforeach

        </div>
    </section>
    <footer class="footer">
        <div class="content has-text-centered">
            <p>
                <strong>Laravel Query Adviser</strong> by <a href="https://socialblue.com">Social Blue</a>.
            </p>
        </div>
    </footer>


<script src="{{asset(mix('/app.js', 'vendor/socialblue/laravel-query-adviser'))}}"></script>
<style>
    * {
        font-family: 'Ubuntu', sans-serif;
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

<script>
    import QueryBlock from "../js/view/query-block";
    export default {
        components: {QueryBlock}
    }
</script>
<script>
    import QueryExplain from "../assets/js/view/query-explain";
    export default {
        components: {QueryExplain}
    }
</script>