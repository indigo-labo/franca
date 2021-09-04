<?php

namespace IndigoLabo\Franca\Services;

use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\Events\TransactionRolledBack;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class DatabaseLogService
{
    public static function listen()
    {
        if (config('logging.channels.sql.enable') === false) {
            return;
        }
        DB::listen(function ($query): void {
            $sql = $query->sql;
            foreach ($query->bindings as $binding) {
                if (is_string($binding)) {
                    $binding = "'{$binding}'";
                } elseif ($binding === null) {
                    $binding = 'NULL';
                } elseif (is_bool($binding)) {
                    $binding = $binding ? '1' : '0';
                }
                $sql = preg_replace('/\?/', $binding, $sql, 1);
            }
            Log::channel('sql')->debug('SQL', ['sql' => $sql, 'time' => "{$query->time} ms"]);
        });
        Event::listen(TransactionBeginning::class, function (TransactionBeginning $event): void {
            Log::channel('sql')->debug('START TRANSACTION');
        });
        Event::listen(TransactionCommitted::class, function (TransactionCommitted $event): void {
            Log::channel('sql')->debug('COMMIT');
        });
        Event::listen(TransactionRolledBack::class, function (TransactionRolledBack $event): void {
            Log::channel('sql')->debug('ROLLBACK');
        });
    }
}
