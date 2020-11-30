<?php



namespace Jiannei\Logger\Laravel\Events;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class RequestHandledEvent extends Event
{
    public $request;
    public $response;

    public function __construct(Request $request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}
