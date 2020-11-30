<?php


namespace Jiannei\Logger\Laravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;

class LogJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    private $context;
    private $message;
    private $serverData;

    public function __construct(string $message, array $context = null, array $serverData = null)
    {
        $this->message = $message;
        $this->context = $context;
        $this->serverData = $serverData;
    }

    public function handle()
    {
        $logger = clone app('log')->getLogger();
        if ($logger instanceof Logger) {
            $logger->pushProcessor(new WebProcessor($this->serverData));
        }

        $logger->debug($this->message, $this->context);
    }
}
