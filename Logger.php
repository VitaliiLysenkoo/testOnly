<?php

declare(strict_types=1);

namespace TestOnly;

use stdClass;

class Logger
{
    /**
     * @param stdClass|null $response
     * @param string $requestType
     * @return void
     */
    public function log(?stdClass $response, string $requestType): void
    {
         $message = 'request_type:'.$requestType.' | success:';
         $message .= !empty($response) && !empty($response->success) && $response->success ? 'true | ' : 'false | ';
         if(!empty($response->message)) $message .= 'error_message:'.$response->message.' | ';
         if(!empty($response->action_id)) $message .= 'action_id:'.$response->action_id.' | ';
         $message .= PHP_EOL;
         echo $message;
    }
}
