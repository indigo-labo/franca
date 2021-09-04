<?php
if (!function_exists('dbg')) {
    function dbg($out, $context = []) {
        \Log::debug($out, $context);
    }
}

if (!function_exists('debug')) {
    function debug($out, $context = []) {
        dbg($out, $context);
    }
}

if (!function_exists('debug_time')) {
    function debug_time() {
        $debug = current(debug_backtrace());

        static $debug_time_start_time = 0;
        static $debug_time_pre_debug = null;
        static $debug_time_pre_time = 0;

        $time = microtime(true);
        if (!$debug_time_start_time)
            $debug_time_start_time = $time;

        if ($debug_time_pre_time) {
            $fromPhp = basename($debug_time_pre_debug['file']);
            $toPhp = (($toPhp = basename($debug['file'])) != $fromPhp) ? $toPhp : null;
            $message = sprintf('%s(%d) - %s(%d) ::: %d ms(total: %d ms)',
                $fromPhp,
                $debug_time_pre_debug['line'],
                $toPhp,
                $debug['line'],
                ($time * 1000 - $debug_time_pre_time * 1000),
                ($time * 1000 - $debug_time_start_time * 1000)
            );
            dbg($message);
        }

        $debug_time_pre_debug = $debug;
        $debug_time_pre_time = $time;
    }
}
