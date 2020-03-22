<?php

use Socialblue\LaravelQueryAdviser\Helper\QueryBuilderHelper;
use Socialblue\LaravelQueryAdviser\Tests\TestCase;

class QueryBuilderHelperTest extends TestCase {

    /**
     * @test
     *
     * @param $query
     * @param $bindings
     * @param $result
     *
     * @dataProvider queriesAndBindings
     */
    public function should_replace_bindings_in_query($query, $bindings, $result) {

        $this->assertEquals($result, QueryBuilderHelper::combineQueryAndBindings($query, $bindings));
    }

    /**
     * @return array
     */
    public function queriesAndBindings(): array
    {
        return [
            'with_one_number' => [
                'query' => 'select * from user WHERE id = ?',
                'bindings' => [1],
                'result' => "select * from user WHERE id = '1'",
            ],

            'with_escaped_characters' => [
                'query' => 'select * from user WHERE name = ?',
                'bindings' => [QueryBuilderHelper::class],
                'result' => "select * from user WHERE name = '". addslashes(QueryBuilderHelper::class) . "'",
            ],

            'two_bindings_one_number_one_escaped_characters' => [
                'query' => 'select * from user WHERE id = ? AND name = ?',
                'bindings' => [1, QueryBuilderHelper::class],
                'result' => "select * from user WHERE id = '1' AND name = '". addslashes(QueryBuilderHelper::class) . "'",
            ],

            'with_one_date_object' => [
                'query' => 'select * from user WHERE date = ?',
                'bindings' => [now()->setDate(2019,1, 1)->setTime(0,0,0)],
                'result' => "select * from user WHERE date = '2019-01-01 00:00:00'",
            ],
        ];
    }

}