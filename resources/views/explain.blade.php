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
<h1>Query Adviser - EXPLAIN</h1>
<a href="/query-adviser/query">Back</a>

<summary>
    <dl>
        <dt>sql</dt>
        <dd>{!!
            Socialblue\LaravelQueryAdviser\Helper\SqlFormatter::format(Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper::combineQueryAndBindings($query['sql'], $query['bindings']))
        !!}</dd>

        <dt>sql</dt>
        <dd>{!!
            Socialblue\LaravelQueryAdviser\Helper\SqlFormatter::format(Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper::combineQueryAndBindings($query['sql'], $query['bindings']))
        !!}</dd>

        <dt onclick="document.querySelector('#opt').style.display='block';">show optimized query</dt>
        <dd id="opt" style="display: none">{!!
            Socialblue\LaravelQueryAdviser\Helper\SqlFormatter::format($optimized)
        !!}</dd>

        <dt>Time</dt>
        <dd>{{$query['time']}}</dd>
        <dt>Route</dt>
        <dd>{{$query['url']}}</dd>
    </dl>



</summary>


<section>
    <div>
        @foreach($queryParts as $queryPart)
            <div class="query-group">
                {{$queryPart->table}}
            </div>
            <div class="query">
                <dl>
                    <dt>Select type</dt>
                    <dd>{{Socialblue\LaravelQueryAdviser\Enum\SelectType::get($queryPart->select_type) }}</dd>

                    <dt>Type</dt>
                    <dd>{{Socialblue\LaravelQueryAdviser\Enum\JoinType::get($queryPart->type)}}</dd>

                    <dt>Possible keys</dt>
                    <dd>{{$queryPart->possible_keys}}</dd>

                    <dt>Key used</dt>
                    <dd>{{$queryPart->key}}</dd>

                    <dt>key len</dt>
                    <dd>{{$queryPart->key_len}}</dd>

                    <dt>rows</dt>
                    <dd>{{$queryPart->rows}}</dd>

                    <dt>filtered</dt>
                    <dd>{{$queryPart->filtered}}</dd>
                </dl>
            </div>
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