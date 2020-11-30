<?php



namespace Jiannei\Logger\Laravel\Events;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class RequestArrivedEvent extends Event
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
