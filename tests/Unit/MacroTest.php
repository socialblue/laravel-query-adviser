<?php

namespace Socialblue\LaravelQueryAdviser\Tests\Unit;

use Socialblue\LaravelQueryAdviser\Tests\TestCase;

class MacroTest extends TestCase {

    /**
     * @test
     */
    public function builder_has_dd_marco()
    {
        $this->assertTrue(\Illuminate\Database\Query\Builder::hasMacro('qadd'));
        $this->assertTrue(\Illuminate\Database\Eloquent\Builder::hasGlobalMacro('qadd'));
    }

    /**
     * @test
     */
    public function builder_has_dump_marco()
    {
        $this->assertTrue(\Illuminate\Database\Query\Builder::hasMacro('qad'));
        $this->assertTrue(\Illuminate\Database\Eloquent\Builder::hasGlobalMacro('qad'));
    }

}
