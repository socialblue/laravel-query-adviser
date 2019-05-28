<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel Query Adviser</title>
</head>
<body>
<h1>Query Adviser</h1>
<section>
    <div>
        @foreach($queries as $time => $querys)
            <div class="query-group">
                {{date("Y-m-d H:i:s")}}
            </div>

            @if(is_array($querys[0]))
                @foreach($querys as $query)
                    <div class="query">
                        <div class="text">
                            {{Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper::combineQueryAndBindings($query['sql'] ?? $query[0], $query['bindings'] ?? $query[1])}}
                        </div>
                        <div class="btn">
                            EXEC | EXPLAIN
                        </div>
                    </div>
                @endforeach
            @endif

        @endforeach

    </div>
</section>
<style>
    .query-group {
        font-family: Consolas;
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
        font-family: Consolas;
        font-size: 16px;
        margin: 4px;
    }

    .query .text {
        position: relative;
        width: calc(95% - 100px);
        max-height: 40px;
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

