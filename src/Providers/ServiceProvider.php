<?php


namespace Jiannei\Logger\Laravel\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Jiannei\Logger\Laravel\Events\RequestArrivedEvent;
use Jiannei\Logger\Laravel\Events\RequestHandledEvent;
use Jiannei\Logger\Laravel\Listeners\RequestArrivedListener;
use Jiannei\Logger\Laravel\Listeners\RequestHandledListener;
use Jiannei\Logger\Laravel\Repositories\Enums\LogEnum;

class ServiceProvider extends IlluminateServiceProvider
{
    public function boot()
    {
        $this->logQuery();
        $this->logRequest();
    }

    protected function logQuery()
    {
        if (!$this->app['config']->get('logging.query.enabled', false)) {
            return;
        }

        DB::listen(function (QueryExecuted $query) {
            if ($query->time < $this->app['config']->get('logging.query.slower_than', 0)) {
                return;
            }

            $sqlWithPlaceholders = str_replace(['%', '?'], ['%%', '%s'], $query->sql);

            $bindings = $query->connection->prepareBindings($query->bindings);
            $pdo = $query->connection->getPdo();
            $realSql = $sqlWithPlaceholders;
            $duration = formatDuration($query->time / 1000);

            if (count($bindings) > 0) {
                $realSql = vsprintf($sqlWithPlaceholders, array_map([$pdo, 'quote'], $bindings));
            }

            $context = [
                'database' => $query->connection->getDatabaseName(),
                'duration' => $duration,
                'sql' => $realSql,
            ];

            logger_async(LogEnum::SQL, $context);
        });
    }

    protected function logRequest()
    {
        if (!$this->app['config']->get('logging.request.enabled', false)) {
            return;
        }

        $this->app['events']->listen(RequestArrivedEvent::class, RequestArrivedListener::class);
        $this->app['events']->listen(RequestHandledEvent::class, RequestHandledListener::class);
    }
}
