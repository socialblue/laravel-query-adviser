<?php

namespace Socialblue\LaravelQueryAdviser\DataListener\Services;

class TraceMapper
{
    public function get()
    {
        return $this->format(
            $this->possibleTraces()
        );
    }

    private function possibleTraces(): array
    {
        $traces = debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, 25);
        krsort($traces);

        $possibleTraces = array_filter(
            $traces,
            static function ($trace) {
                return isset($trace['file']) &&
                    isset($trace['object']) &&
                    strpos($trace['file'], base_path('vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php')) !== false;
            }
        );

        $currentPossibleTrace = current($possibleTraces);

        if (! empty($currentPossibleTrace)) {
            $calledBy = $traces[key($possibleTraces) + 1];
            $currentPossibleTrace['file'] = $calledBy['file'];
            $currentPossibleTrace['line'] = $calledBy['line'];
            $currentPossibleTrace['function'] = $calledBy['function'];
            return [$currentPossibleTrace];
        }

        return $possibleTraces;
    }

    private function format(array $possibleTraces): array
    {
        array_walk($possibleTraces, static function (&$trace) {
            if (method_exists($trace['object'], 'getModel')) {
                $a = $trace['object']->getModel();
                if (is_string($a)) {
                    $trace['model'] = $a;
                } else {
                    $trace['model'] = get_class($a);
                }
            }
            unset($trace['object']);
            unset($trace['args']);
        });
        return $possibleTraces;
    }
}
