<?php

namespace Socialblue\LaravelQueryAdviser\DataListener\Services;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class BindingsMapper
{
    const EXPRESSION = "{EXPRESSION:} ";

    public function toCache(array $bindings): array
    {
        foreach ($bindings as &$binding) {
            if ($binding instanceof Expression) {
                $binding = self::EXPRESSION . $binding->getValue();
            }
        }
        return $bindings;
    }

    public function fromCache(array $bindings): array
    {
        foreach ($bindings as &$binding) {
            if (str_starts_with($binding, self::EXPRESSION)) {
                $binding = DB::raw(substr($binding, 14));
            }
        }
        return $bindings;
    }
}
